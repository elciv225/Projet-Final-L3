<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory as LoopFactory;
use React\Socket\Server as ReactorServer;

// Load .env variables
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

class Chat implements MessageComponentInterface {
    protected $clients;
    private $db;
    private $discussionRooms;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->discussionRooms = [];
        echo "Chat server started...\n";

        try {
            $this->db = \System\Database\Database::getInstance();
            echo "Database connection established.\n";
        } catch (\PDOException $e) {
            echo "Database connection failed: " . $e->getMessage() . "\n";
            // Exit or handle error appropriately if DB is essential for server to start
            // For now, we'll let it run but log the error.
            $this->db = null;
        }
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        echo "Message from {$from->resourceId}: {$msg}\n";
        $data = json_decode($msg, true);

        if (!$data || !isset($data['discussion_id']) || !isset($data['auteur_id']) || !isset($data['message'])) {
            echo "Invalid message format: {$msg}\n";
            $from->send(json_encode(['error' => 'Invalid message format.']));
            return;
        }

        $discussionId = $data['discussion_id'];
        $auteurId = $data['auteur_id'];
        $messageText = $data['message'];
        $dateMessage = date('Y-m-d H:i:s');

        if ($this->db) {
            try {
                $stmt = $this->db->prepare("INSERT INTO messagerie (discussion_id, auteur_id, message, date_message) VALUES (?, ?, ?, ?)");
                $stmt->execute([$discussionId, $auteurId, $messageText, $dateMessage]);
                $messageId = $this->db->lastInsertId();
                echo "Message saved to DB with ID: {$messageId}\n";

                // Fetch auteur_nom for broadcasting
                $userStmt = $this->db->prepare("SELECT nom, prenoms FROM utilisateur WHERE id = ?");
                $userStmt->execute([$auteurId]);
                $user = $userStmt->fetch(\PDO::FETCH_ASSOC);
                $auteurNom = $user ? ($user['prenoms'] . ' ' . $user['nom']) : 'Utilisateur inconnu';

                $broadcastMsg = json_encode([
                    'discussion_id' => $discussionId,
                    'auteur_id' => $auteurId,
                    'auteur_nom' => $auteurNom,
                    'message' => $messageText,
                    'date_message' => $dateMessage,
                    'id' => $messageId
                ]);

                // Add connection to a "room" if not already there
                if (!isset($this->discussionRooms[$discussionId])) {
                    $this->discussionRooms[$discussionId] = new \SplObjectStorage;
                }
                $this->discussionRooms[$discussionId]->attach($from);

                // Broadcast to clients in the same discussion room
                foreach ($this->discussionRooms[$discussionId] as $client) {
                    echo "Broadcasting to client {$client->resourceId} in room {$discussionId}\n";
                    $client->send($broadcastMsg);
                }

            } catch (\PDOException $e) {
                echo "DB Error: " . $e->getMessage() . "\n";
                $from->send(json_encode(['error' => 'Failed to save or broadcast message.']));
            }
        } else {
            echo "No database connection. Cannot save or broadcast message.\n";
            $from->send(json_encode(['error' => 'Server database error.']));
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        // Remove from all rooms
        foreach ($this->discussionRooms as $roomId => $room) {
            if ($room->contains($conn)) {
                $room->detach($conn);
                echo "Client {$conn->resourceId} removed from room {$roomId}\n";
                if ($room->count() === 0) {
                    unset($this->discussionRooms[$roomId]);
                    echo "Room {$roomId} is now empty and closed.\n";
                }
            }
        }
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}

// Setup ReactPHP event loop and socket server
$loop   = LoopFactory::create();
$socket = new ReactorServer('0.0.0.0:' . ($_ENV['WEBSOCKET_PORT'] ?? '8080'), $loop);

// Setup Ratchet server
$server = new IoServer(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    $socket,
    $loop
);

echo "WebSocket server listening on port " . ($_ENV['WEBSOCKET_PORT'] ?? '8080') . "\n";
$server->run();

?>

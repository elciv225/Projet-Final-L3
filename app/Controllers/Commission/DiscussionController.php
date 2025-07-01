<?php

namespace App\Controllers\Commission;

use App\Controllers\Controller;
use App\Dao\DiscussionDAO;
use System\Http\Response;

class DiscussionController extends Controller
{
    private DiscussionDAO $discussionDAO;

    public function __construct()
    {
        parent::__construct();
        $this->discussionDAO = new DiscussionDAO($this->pdo);
    }

    /**
     * Affiche la page principale des discussions (messagerie de la commission).
     * @return Response
     */
    public function index(): Response
    {
        // Exemple: Récupérer les discussions pour un rapport spécifique (à adapter)
        // $rapportId = $this->request->getQueryParam('rapport_id', 1); // Exemple
        // $discussions = $this->discussionDAO->recupererDiscussionsParRapport($rapportId);

        $data = [
            'title' => 'Messagerie de la Commission',
            'heading' => 'Discussions',
            'content' => 'Interface de messagerie pour la commission.',
            // 'discussions' => $discussions // Passer les données à la vue
        ];

        // La vue 'commission/discussions' sera chargée dans le contenu principal
        // via la méthode gestionMenuModules du Controller parent si appelée par le menu
        return Response::view('commission/discussions', $data);
    }

    /**
     * Exemple: Ajoute un nouveau message à une discussion.
     * Devrait être appelé via une requête POST.
     * @return Response
     */
    public function ajouterMessage(): Response
    {
        // Exemple de récupération des données POST
        // $rapportId = $this->request->getPostParams('rapport_id');
        // $utilisateurId = $this->request->getPostParams('utilisateur_id'); // ou session utilisateur
        // $message = $this->request->getPostParams('message');
        // $pieceJointe = $this->request->getFiles('piece_jointe'); // Optionnel

        // if (!$rapportId || !$utilisateurId || !$message) {
        //     return $this->error("Données manquantes pour ajouter le message.");
        // }

        // $dataToInsert = [
        //     'id_rapport_etudiant' => $rapportId,
        //     'id_utilisateur' => $utilisateurId,
        //     'message' => $message,
        //     'date_message' => date('Y-m-d H:i:s'),
        //     // Gérer le chemin de la pièce jointe si uploadée
        // ];

        // if ($this->discussionDAO->creer($dataToInsert)) {
        //     return $this->succes("Message ajouté avec succès.");
        // } else {
        //     return $this->error("Erreur lors de l'ajout du message.");
        // }
        return $this->info("Fonctionnalité d'ajout de message à implémenter.");
    }

    // D'autres méthodes pourraient inclure:
    // - recupererMessagesPourDiscussion(int $discussionId)
    // - marquerMessageCommeLu(int $messageId)
}
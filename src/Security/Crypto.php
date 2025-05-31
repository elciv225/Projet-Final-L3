<?php

namespace System\Security;

use Dotenv\Dotenv;

class Crypto
{
    private static ?self $instance = null;
    private string $key;
    private string $cipher = 'aes-256-gcm';

    private function __construct()
    {
        // Constructeur privé pour empêcher l'instanciation directe
        $this->loadEnvironmentAndSetKey();
    }

    private function loadEnvironmentAndSetKey(): void
    {
        // Charger les variables d'environnement
        $dotenv = Dotenv::createImmutable(BASE_PATH);
        $dotenv->load();

        $base64Key = $_ENV['APP_KEY'] ?? null;

        if (empty($base64Key)) {
            error_log("Erreur Crypto : APP_KEY n'est pas définie dans le fichier .env");
            die("Crypto.php => Erreur de configuration. Veuillez vérifier votre fichier .env");
        }

        // Vérifier le format base64:
        if (!str_starts_with($base64Key, 'base64:')) {
            error_log("Erreur Crypto : APP_KEY doit commencer par 'base64:'");
            die("Crypto.php => Format de clé invalide. APP_KEY doit commencer par 'base64:'");
        }

        // Retirer le préfixe 'base64:' et décoder
        $keyWithoutPrefix = substr($base64Key, 7);
        $this->key = base64_decode($keyWithoutPrefix, true);

        if ($this->key === false) {
            error_log("Erreur Crypto : APP_KEY n'est pas un base64 valide");
            die("Crypto.php => APP_KEY n'est pas un base64 valide");
        }

        if (strlen($this->key) !== 32) {
            error_log("Erreur Crypto : APP_KEY doit faire exactement 32 bytes. Taille actuelle: " . strlen($this->key));
            die("Crypto.php => APP_KEY doit faire exactement 32 bytes après décodage");
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function encrypt(string $data): string
    {
        try {
            $iv = random_bytes(openssl_cipher_iv_length($this->cipher));
            $tag = '';

            $encrypted = openssl_encrypt(
                $data,
                $this->cipher,
                $this->key,
                0,
                $iv,
                $tag
            );

            if ($encrypted === false) {
                throw new \Exception("Échec du chiffrement");
            }

            return base64_encode($iv . $tag . $encrypted);
        } catch (\Exception $e) {
            error_log("Erreur de chiffrement : " . $e->getMessage());
            throw new \Exception("Erreur lors du chiffrement des données");
        }
    }

    public function decrypt(string $data): string
    {
        try {
            $data = base64_decode($data, true);

            if ($data === false) {
                throw new \Exception("Données base64 invalides");
            }

            $ivLength = openssl_cipher_iv_length($this->cipher);

            if (strlen($data) < $ivLength + 16) {
                throw new \Exception("Données chiffrées trop courtes");
            }

            $iv = substr($data, 0, $ivLength);
            $tag = substr($data, $ivLength, 16);
            $encrypted = substr($data, $ivLength + 16);

            $decrypted = openssl_decrypt(
                $encrypted,
                $this->cipher,
                $this->key,
                0,
                $iv,
                $tag
            );

            if ($decrypted === false) {
                throw new \Exception("Échec du déchiffrement");
            }

            return $decrypted;
        } catch (\Exception $e) {
            error_log("Erreur de déchiffrement : " . $e->getMessage());
            throw new \Exception("Erreur lors du déchiffrement des données");
        }
    }

    // Méthodes statiques pour faciliter l'utilisation
    public static function encryptData(string $data): string
    {
        return self::getInstance()->encrypt($data);
    }

    public static function decryptData(string $data): string
    {
        return self::getInstance()->decrypt($data);
    }
}
<?php

namespace App\Models;

class Audit
{
    private int $id;
    private string $description;
    private string $date_traitement;
    private string $traitement_id;
    private string $utilisateur_id;

    /**
     * Enregistre une entrée d'audit dans la base de données
     * 
     * @param string $description Description de l'action effectuée
     * @param string $traitement_id Identifiant du traitement
     * @param string|null $utilisateur_id Identifiant de l'utilisateur (null si système)
     * @return bool True si l'enregistrement a réussi, False sinon
     */
    public static function enregistrer(string $description, string $traitement_id, ?string $utilisateur_id = null): bool
    {
        // Récupérer l'ID de l'utilisateur connecté si non fourni
        if ($utilisateur_id === null && isset($_SESSION['utilisateur_connecte']['id'])) {
            $utilisateur_id = $_SESSION['utilisateur_connecte']['id'];
        }

        // Si toujours null, utiliser un ID par défaut pour le système
        if ($utilisateur_id === null) {
            $utilisateur_id = '0'; // ID pour le système
        }

        // Créer un nouvel objet Audit
        $audit = new self();
        $audit->setDescription($description);
        $audit->setDateTraitement(date('Y-m-d H:i:s'));
        $audit->setTraitementId($traitement_id);
        $audit->setUtilisateurId($utilisateur_id);

        // Enregistrer dans la base de données
        $pdo = \System\Database\Database::getConnection();
        $auditDAO = new \App\Dao\AuditDAO($pdo);

        return $auditDAO->creer($audit);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDateTraitement(): string
    {
        return $this->date_traitement;
    }

    /**
     * @param string $date_traitement
     */
    public function setDateTraitement(string $date_traitement): void
    {
        $this->date_traitement = $date_traitement;
    }

    /**
     * @return string
     */
    public function getTraitementId(): string
    {
        return $this->traitement_id;
    }

    /**
     * @param string $traitement_id
     */
    public function setTraitementId(string $traitement_id): void
    {
        $this->traitement_id = $traitement_id;
    }

    /**
     * @return string
     */
    public function getUtilisateurId(): string
    {
        return $this->utilisateur_id;
    }

    /**
     * @param string $utilisateur_id
     */
    public function setUtilisateurId(string $utilisateur_id): void
    {
        $this->utilisateur_id = $utilisateur_id;
    }
}

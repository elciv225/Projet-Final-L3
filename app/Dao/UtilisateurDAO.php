<?php

namespace App\Dao;

use PDO;
use App\Models\Utilisateur; // Assuming you have a User model

class UtilisateurDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'utilisateur', Utilisateur::class, 'id');
    }

    /**
     * Trouve un utilisateur par son login.
     *
     * @param string $login Le login de l'utilisateur.
     * @return Utilisateur|null L'objet Utilisateur ou null si non trouvé.
     */
    public function findByLogin(string $login): ?Utilisateur
    {
        $sql = "SELECT * FROM {$this->table} WHERE login = :login";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':login', $login);
        $stmt->setFetchMode(PDO::FETCH_CLASS, $this->model);
        if ($stmt->execute()) {
            $result = $stmt->fetch();
            return $result ?: null;
        }
        return null;
    }

    /**
     * Crée un nouvel utilisateur avec hachage du mot de passe.
     *
     * @param array $data Données de l'utilisateur. 'mot_de_passe' sera haché.
     * @return bool True si la création a réussi, sinon false.
     */
    public function creer(array $data): bool
    {
        if (isset($data['mot_de_passe'])) {
            $data['mot_de_passe'] = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);
        }
        return parent::creer($data);
    }

    /**
     * Met à jour un utilisateur avec hachage optionnel du mot de passe.
     *
     * @param string $id Identifiant de l'utilisateur.
     * @param array $data Données à mettre à jour. Si 'mot_de_passe' est présent et non vide, il sera haché.
     * @return bool True si la mise à jour a réussi, sinon false.
     */
    public function mettreAJour(string $id, array $data): bool
    {
        // Si un nouveau mot de passe est fourni et n'est pas vide, le hacher
        if (isset($data['mot_de_passe']) && !empty($data['mot_de_passe'])) {
            $data['mot_de_passe'] = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);
        } elseif (isset($data['mot_de_passe']) && empty($data['mot_de_passe'])) {
            // Si le mot de passe est fourni mais vide, ne pas le mettre à jour (le retirer du tableau $data)
            unset($data['mot_de_passe']);
        }

        // Si après avoir retiré un mot de passe vide, $data est vide, ne rien faire.
        if (empty($data)) {
            return true; // Considéré comme un succès car aucune mise à jour n'était nécessaire.
        }

        return parent::mettreAJour($id, $data);
    }
}
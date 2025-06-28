<?php

namespace App\Dao;

use PDO;

abstract class DAO
{
    protected PDO $pdo;
    protected string $table;
    protected string $model;
    protected string $primaryKey;

    /**
     * Constructeur de la classe DAO
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données
     * @param string $table Nom de la table
     * @param string $model Nom du modèle associé
     */
    public function __construct(PDO $pdo, string $table, string $model, string $primaryKey = 'id')
    {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->model = $model;
        $this->primaryKey = $primaryKey;
    }

    /**
     * Trouver un enregistremet avec son Identifiant
     * @param string $id Identitifiant de l'enregistrement
     * @return object|null Le Model de l'enregistrement
     */
    public function recupererParId(string $id): ?object
    {
        $query = "SELECT * FROM $this->table WHERE $this->primaryKey = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->setFetchMode(PDO::FETCH_CLASS, $this->model);
        return $stmt->execute() ? $stmt->fetch() : null;
    }

    /**
     * Récupérer tous les enregistrements de la table
     * @param string|null $orderBy Order par une colonne spécifique
     * @param string|null $orderType Type de tri (ASC ou DESC)
     * @return array Un tableau d'objets du modèle
     */
    public function recupererTous(?string $orderBy = null, ?string $orderType = null): array
    {
        $query = "SELECT * FROM $this->table";

        if ($orderBy && $orderType) {
            $query .= " ORDER BY $orderBy $orderType";
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, $this->model);
        return $stmt->execute() ? $stmt->fetchAll() : [];
    }

    /**
     * Créer un nouvel enregistrement
     * @param array $data Données à insérer
     * @return bool True si l'insertion a réussi, sinon false
     */
    public function creer(array $data): bool
    {
        $colonnes = implode(", ", array_keys($data));
        $valeurs = ":" . implode(", :", array_keys($data));

        $sql = "INSERT INTO $this->table ($colonnes) VALUES ($valeurs)";
        $stmt = $this->pdo->prepare($sql);
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        return $stmt->execute();
    }

    /**
     * Mettre à jour un enregistrement
     * @param string $id Identifiant de l'enregistrement
     * @param array $data Données à mettre à jour
     * @return bool True si la mise à jour a réussi, sinon false
     */
    public function mettreAJour(string $id, array $data): bool
    {
        $set = "";
        foreach ($data as $key => $value) {
            $set .= "$key = :$key, ";
        }
        $set = rtrim($set, ", ");

        $sql = "UPDATE $this->table SET $set WHERE $this->primaryKey = :id";
        $stmt = $this->pdo->prepare($sql);
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    /**
     * Supprimer un enregistrement
     * @param string $id Identifiant de l'enregistrement
     * @return bool True si la suppression a réussi, sinon false
     */
    public function supprimer(string $id): bool
    {
        $sql = "DELETE FROM $this->table WHERE $this->primaryKey = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    /**
     * Rechercher des enregistrements
     * @param array $criteres Critères de recherche
     * @param string|null $orderBy Order par une colonne spécifique
     * @param string|null $orderType Type de tri (ASC ou DESC)
     * @return array Un tableau d'objets du modèle
     */
    public function rechercher(array $criteres, ?string $orderBy = null, ?string $orderType = null): array
    {
        $whereClauses = [];
        $params = [];

        foreach ($criteres as $colonne => $valeur) {
            $paramName = str_replace('.', '_', $colonne); // Eviter les points dans les noms de paramètres

            if ($valeur === null) {
                $whereClauses[] = "$colonne IS NULL";
            } elseif (is_array($valeur)) {
                if (empty($valeur)) { // Gérer le cas d'un tableau vide pour IN() pour éviter une erreur SQL
                    $whereClauses[] = "1=0"; // Condition toujours fausse
                    continue;
                }
                // Clause IN pour les tableaux de valeurs
                $inParams = [];
                foreach ($valeur as $key => $v) {
                    $pName = "{$paramName}_{$key}";
                    $inParams[] = ":{$pName}";
                    $params[$pName] = $v;
                }
                $whereClauses[] = "$colonne IN (" . implode(", ", $inParams) . ")";
            } else {
                // Clause égale pour les valeurs simples
                $whereClauses[] = "$colonne = :$paramName";
                $params[$paramName] = $valeur;
            }
        }

        $sql = "SELECT * FROM $this->table";

        if (!empty($whereClauses)) {
            $sql .= " WHERE " . implode(" AND ", $whereClauses);
        }

        if ($orderBy) {
            $sql .= " ORDER BY $orderBy $orderType";
        }

        $stmt = $this->pdo->prepare($sql);
        // PDOStatement::bindValue attend une référence pour le 3ème argument (type),
        // il est plus sûr de binder dans la boucle ou directement dans execute() si les types sont simples.
        // Ici, comme on a déjà construit $params, on peut les passer à execute().

        $stmt->setFetchMode(PDO::FETCH_CLASS, $this->model);
        return $stmt->execute($params) ? $stmt->fetchAll() : [];
    }

    /**
     * Compter le nombre d'enregistrements
     * @param array $criteres Critères de recherche
     * @return int Nombre d'enregistrements
     */
    public function compter(array $criteres = []): int
    {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        $params = [];

        if (!empty($criteres)) {
            $whereParts = [];
            foreach ($criteres as $colonne => $valeur) {
                // Sanitize column name if necessary, though binding protects value
                $paramName = str_replace('.', '_', $colonne); // Basic sanitization for param name
                if ($valeur === null) {
                    $whereParts[] = "$colonne IS NULL";
                } else {
                    $whereParts[] = "$colonne = :$paramName";
                    $params[$paramName] = $valeur;
                }
            }
            $sql .= " WHERE " . implode(" AND ", $whereParts);
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Exécute une requête SQL personnalisée
     * @param string $sql Requête SQL avec placeholders
     * @param array $params Paramètres pour la requête
     * @param bool $retournerEntites Si vrai, convertit les résultats en entités
     * @return array Résultats de la requête
     */
    public function executerRequete(string $sql, array $params = [], bool $retournerEntites = true): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        if ($retournerEntites) {
            return $stmt->fetchAll(PDO::FETCH_CLASS, $this->model);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
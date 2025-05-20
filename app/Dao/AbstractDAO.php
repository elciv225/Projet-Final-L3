<?php

namespace App\Dao;

use PDO;

abstract class AbstractDAO
{
    protected PDO $pdo;
    protected string $table;
    protected string $model;

    /**
     * Constructeur de la classe DAO
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données
     * @param string $table Nom de la table
     * @param string $model Nom du modèle associé
     */
    public function __construct(PDO $pdo, string $table, string $model)
    {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->model = $model;
    }

    /**
     * Trouver un enregistremet avec son identifiant
     * @param string $id Identitifiant de l'enregistrement
     * @return object|null Le Model de l'enregistrement
     */
    public function recupererParId(string $id): ?object
    {
        $query = "SELECT * FROM $this->table WHERE identifiant = :id";
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

        $sql = "UPDATE $this->table SET $set WHERE identifiant = :id";
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
        $sql = "DELETE FROM $this->table WHERE identifiant = :id";
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
        $where = "";
        $params = [];

        foreach ($criteres as $colonne => $valeur) {
            if (!empty($where)) {
                $where .= ' AND ';
            }

            // Gestion des valeurs NULL
            if ($valeur === null) {
                $where .= "$colonne IS NULL";
            } else {
                $paramName = str_replace('.', '_', $colonne);
                $where .= "$colonne = :$paramName";
                $params[$paramName] = $valeur;
            }
        }

        $sql = "SELECT * FROM $this->table";

        if (!empty($whereClause)) {
            $sql .= " WHERE $whereClause";
        }

        if ($orderBy) {
            $sql .= " ORDER BY $orderBy $orderType";
        }

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->setFetchMode(PDO::FETCH_CLASS, $this->model);
        return $stmt->execute() ? $stmt->fetchAll() : [];
    }

    /**
     * Compter le nombre d'enregistrements
     * @param array $criteres Critères de recherche
     * @return int Nombre d'enregistrements
     */
    public function compter(array $criteres = []): int
    {
       $date = $this->rechercher(array());
       return count($date);
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
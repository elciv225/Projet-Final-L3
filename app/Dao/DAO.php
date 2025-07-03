<?php

namespace App\Dao;

use PDO;
use ReflectionObject;
use ReflectionProperty;

abstract class DAO
{
    protected PDO $pdo;
    protected string $table;
    protected string $model;
    protected string $primaryKey;

    public function __construct(PDO $pdo, string $table, string $model, string $primaryKey = 'id')
    {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->model = $model;
        $this->primaryKey = $primaryKey;
    }

    protected function hydrater(array $data): object
    {
        $object = new $this->model();
        foreach ($data as $column => $value) {
            $setterMethod = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $column)));
            if (method_exists($object, $setterMethod)) {
                $object->$setterMethod($value);
            }
        }
        return $object;
    }

    public function recupererParId(string $id): ?object
    {
        $query = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrater($row) : null;
    }

    public function recupererTous(?string $orderBy = null, ?string $orderType = 'ASC'): array
    {
        $query = "SELECT * FROM {$this->table}";
        if ($orderBy) {
            $query .= " ORDER BY $orderBy $orderType";
        }
        $stmt = $this->pdo->query($query);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $objects = [];
        foreach ($rows as $row) {
            $objects[] = $this->hydrater($row);
        }
        return $objects;
    }

    public function creer(object $model): bool
    {
        $data = $this->extractDataFromModel($model);
        $colonnes = implode(", ", array_keys($data));
        $valeurs = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO {$this->table} ($colonnes) VALUES ($valeurs)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function mettreAJour(string $id, object $model): bool
    {
        $data = $this->extractDataFromModel($model);
        $set = [];
        foreach ($data as $key => $value) {
            if ($key === $this->primaryKey) continue;
            $set[] = "$key = :$key";
        }
        $setClause = implode(", ", $set);
        $sql = "UPDATE {$this->table} SET $setClause WHERE {$this->primaryKey} = :id";
        $stmt = $this->pdo->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function persister(object $model): bool
    {
        $getter = 'get' . ucfirst($this->primaryKey);
        if (method_exists($model, $getter) && !empty($model->$getter())) {
            return $this->mettreAJour($model->$getter(), $model);
        }
        return $this->creer($model);
    }

    public function supprimer(string $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    public function rechercher(array $criteres, ?string $orderBy = null, ?string $orderType = 'ASC'): array
    {
        $whereClauses = [];
        $params = [];
        foreach ($criteres as $colonne => $valeur) {
            $paramName = str_replace('.', '_', $colonne);
            if ($valeur === null) {
                $whereClauses[] = "$colonne IS NULL";
            } elseif (is_array($valeur)) {
                if (empty($valeur)) { $whereClauses[] = "1=0"; continue; }
                $inParams = [];
                foreach ($valeur as $key => $v) {
                    $pName = "{$paramName}_{$key}";
                    $inParams[] = ":{$pName}";
                    $params[$pName] = $v;
                }
                $whereClauses[] = "$colonne IN (" . implode(", ", $inParams) . ")";
            } else {
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
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $objects = [];
        foreach ($rows as $row) {
            $objects[] = $this->hydrater($row);
        }
        return $objects;
    }

    public function executerRequeteAction(string $sql, array $params = []): int
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    /**
     * Exécute une requête SELECT et retourne les résultats bruts.
     * @param string $sql La requête SQL SELECT à exécuter.
     * @param array $params Les paramètres de la requête.
     * @return array Un tableau de tableaux associatifs.
     */
    public function executerSelect(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function extractDataFromModel(object $model): array
    {
        $reflection = new ReflectionObject($model);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PRIVATE);
        $data = [];

        foreach ($properties as $property) {
            $propertyName = $property->getName();
            // Utiliser directement le nom de la propriété comme nom de colonne
            // au lieu de faire une conversion camelCase -> snake_case
            $columnName = $propertyName;
        
            // Construire le nom du getter en convertissant snake_case -> camelCase
            $getterName = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $propertyName)));
        
            if ($reflection->hasMethod($getterName)) {
                $value = $model->$getterName();
                if (!is_object($value) && !is_array($value) && $value !== null) {
                    $data[$columnName] = $value;
                }
            }
        }
        return $data;
    }
}
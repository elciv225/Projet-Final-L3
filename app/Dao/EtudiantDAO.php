<?php

namespace App\Dao;

use PDO;
use App\Models\Etudiant;

class EtudiantDAO extends DAO {
    public function __construct(PDO $pdo) { parent::__construct($pdo, 'etudiant', Etudiant::class, 'utilisateur_id'); }

    public function recupererTousAvecDetails(): array {
        $sql = "
            SELECT u.id, u.nom, u.prenoms, u.email, e.numero_carte, ne.libelle as niveau_etude, ie.annee_academique_id
            FROM utilisateur u
            JOIN etudiant e ON u.id = e.utilisateur_id
            LEFT JOIN (
                SELECT utilisateur_id, MAX(annee_academique_id) as max_annee
                FROM inscription_etudiant
                GROUP BY utilisateur_id
            ) ie_max ON u.id = ie_max.utilisateur_id
            LEFT JOIN inscription_etudiant ie ON ie_max.utilisateur_id = ie.utilisateur_id AND ie_max.max_annee = ie.annee_academique_id
            LEFT JOIN niveau_etude ne ON ie.niveau_etude_id = ne.id
            ORDER BY u.nom, u.prenoms;
        ";
        return $this->executerSelect($sql);
    }
}
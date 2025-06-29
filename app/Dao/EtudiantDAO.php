<?php

namespace App\Dao;

use PDO;
use App\Models\Etudiant;

class EtudiantDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'etudiant', Etudiant::class, 'utilisateur_id');
    }

    // Le modèle InscriptionEtudiant attend un objet Etudiant.
    // La méthode `recupererParId` du DAO parent pour EtudiantDAO retournera un objet Etudiant
    // construit avec le constructeur `Etudiant::__construct(string $utilisateurId)`.
    // Cela est suffisant pour les besoins d'InscriptionEtudiantDAO si ce dernier
    // utilise $etudiant->getUtilisateurId() pour ses opérations de base de données
    // et si l'objet Etudiant lui-même n'a pas besoin d'être plus riche pour le modèle InscriptionEtudiant.
}
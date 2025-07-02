<?php

namespace App\Dao;

use App\Models\NiveauAccesDonnees; // Assurez-vous que le modèle NiveauAccesDonnees existe
use PDO;

class NiveauAccesDonneesDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        // Le nom de la table est 'niveau_acces_donnees'
        parent::__construct($pdo, 'niveau_acces_donnees', NiveauAccesDonnees::class, 'id');
    }
}

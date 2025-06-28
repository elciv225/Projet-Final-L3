<?php

namespace App\Dao;

use PDO;
use App\Models\EtatSauvegarde;

class EtatSauvegardeDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'etat_sauvegarde', EtatSauvegarde::class, 'id');
    }

}
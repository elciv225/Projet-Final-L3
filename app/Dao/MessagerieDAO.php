<?php

namespace App\Dao;

use PDO;
use App\Models\Messagerie;

class MessagerieDAO extends DAO
{
    public function __construct(PDO $pdo) {
        parent::__construct($pdo, 'messagerie', Messagerie::class, ''); // Pas de clé primaire simple
    }
}
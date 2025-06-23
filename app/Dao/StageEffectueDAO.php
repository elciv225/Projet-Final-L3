<?php

namespace App\Dao;

use PDO;
use App\Models\StageEffectue;

class StageEffectueDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'stage_effectue', StageEffectue::class, 'id');
    }
}
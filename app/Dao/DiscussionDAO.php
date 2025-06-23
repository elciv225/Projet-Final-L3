<?php

namespace App\Dao;

use PDO;
use App\Models\Discussion;

class DiscussionDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'discussion', Discussion::class, 'id');
    }
}
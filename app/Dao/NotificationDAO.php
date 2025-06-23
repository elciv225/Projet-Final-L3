<?php

namespace App\Dao;

use PDO;
use App\Models\Notification;

class NotificationDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'notification', Notification::class, 'id');
    }
}
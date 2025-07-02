<?php

namespace App\Dao;

use App\Models\SessionExamen; // Assurez-vous que ce modèle existe ou créez-le
use PDO;

class SessionExamenDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        // Adapter le nom de la table et le nom de la classe du modèle si nécessaire
        parent::__construct($pdo, 'session_examen', SessionExamen::class, 'id');
    }

    // Vous pouvez ajouter ici des méthodes spécifiques pour les sessions d'examen si besoin.
    // Par exemple, récupérer les sessions pour une année académique donnée, etc.
}

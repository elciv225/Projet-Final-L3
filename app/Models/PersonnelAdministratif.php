<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\Utilisateur;
use App\Models\ApprobationRapport; // Added import
use PDO;

/**
 * Class PersonnelAdministratif
 *
 * Represents the personnel_administratif table.
 *
 * @package App\Models
 */
class PersonnelAdministratif extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'personnel_administratif';

    /**
     * @var string The ID of the administrative staff. This is also a foreign key to utilisateur.id.
     */
    public string $id_utilisateur;

    // Removed nom, prenom, contact, id_fonction properties

    /**
     * PersonnelAdministratif constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

}

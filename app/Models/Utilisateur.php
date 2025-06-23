<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\GroupeUtilisateur;
use App\Models\TypeUtilisateur;
use App\Models\NiveauAccesDonnees;
use App\Models\Audit;
use App\Models\PersonnelAdministratif;
use App\Models\Enseignant;
use App\Models\Etudiant;
use App\Models\AccesTraitement;
use App\Models\Notification; // Added import
use PDO;

/**
 * Class Utilisateur
 *
 * Represents the utilisateur table.
 *
 * @package App\Models
 */
class Utilisateur extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'utilisateur';

    /**
     * @var string The ID of the user.
     */
    public string $id;

    /**
     * @var string The username.
     */
    public string $nom_utilisateur;

    /**
     * @var string The password hash.
     */
    public string $mot_de_passe;

    /**
     * @var string|null The email address.
     */
    public ?string $email;

    /**
     * @var string|null The creation date of the user account.
     */
    public ?string $date_creation_compte; // Assuming DATETIME or TIMESTAMP

    /**
     * @var string|null The last login date.
     */
    public ?string $date_derniere_connexion; // Assuming DATETIME or TIMESTAMP

    /**
     * @var string|null The ID of the user type this user belongs to.
     */
    public ?string $type_utilisateur_id;

    /**
     * @var string|null The ID of the user group this user belongs to.
     */
    public ?string $groupe_utilisateur_id;

    /**
     * @var string|null The ID of the data access level for this user.
     */
    public ?string $niveau_acces_donnees_id;

    /**
     * Utilisateur constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

}

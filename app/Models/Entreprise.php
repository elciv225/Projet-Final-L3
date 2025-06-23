<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\StageEffectue; // Added import
use PDO;

/**
 * Class Entreprise
 *
 * Represents the entreprise table.
 *
 * @package App\Models
 */
class Entreprise extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'entreprise';

    /**
     * @var string The ID of the company.
     */
    public string $id;

    /**
     * @var string The name of the company.
     */
    public string $nom_entreprise;

    /**
     * @var string|null The address of the company.
     */
    public ?string $adresse_entreprise;

    /**
     * @var string|null The phone number of the company.
     */
    public ?string $telephone_entreprise;

    /**
     * @var string|null The email of the company.
     */
    public ?string $email_entreprise;

    /**
     * @var string|null The sector of activity of the company.
     */
    public ?string $secteur_activite;

    /**
     * Entreprise constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

}

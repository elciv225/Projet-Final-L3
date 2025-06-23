<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\HistoriqueFonction; // Added import
use PDO;

/**
 * Class Fonction
 *
 * Represents the fonction table.
 *
 * @package App\Models
 */
class Fonction extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'fonction';

    /**
     * @var string The ID of the function.
     */
    public string $id;

    /**
     * @var string The label of the function.
     */
    public string $libelle;

    /**
     * Fonction constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

}

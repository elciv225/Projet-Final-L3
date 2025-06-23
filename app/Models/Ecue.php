<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\Ue;
use App\Models\Evaluation; // Added import
use PDO;

/**
 * Class Ecue
 *
 * Represents the ecue table.
 *
 * @package App\Models
 */
class Ecue extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'ecue';

    /**
     * @var string The ID of the ECUE.
     */
    public string $id;

    /**
     * @var string The label of the ECUE.
     */
    public string $libelle;

    /**
     * @var int The number of credits for the ECUE (TINYINT UNSIGNED).
     */
    public int $credit;

    /**
     * Ecue constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

}

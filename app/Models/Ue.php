<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\Ecue; // Ensure Ecue is imported
use PDO;

/**
 * Class Ue
 *
 * Represents the ue table.
 *
 * @package App\Models
 */
class Ue extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'ue';

    /**
     * @var string The ID of the UE.
     */
    public string $id;

    /**
     * @var string The label of the UE.
     */
    public string $libelle;

    /**
     * @var int The number of credits for the UE (TINYINT UNSIGNED).
     */
    public int $credit;

    /**
     * @var string|null The ID of the ECUE this UE belongs to (FK to ecue.id).
     */
    public ?string $ecue_id;

    /**
     * Ue constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

}

<?php

namespace App\Models;

use App\Models\RemiseCompteRendu; // For existing getRemisesCompteRendu
use App\Models\HistoriqueApprobation; // For upcoming getHistoriqueApprobations
use PDO;

/**
 * Class CompteRendu
 *
 * Represents the compte_rendu table.
 * DDL: id VARCHAR(15), titre VARCHAR(255), date_rapport DATE.
 *
 * @package App\Models
 */
class CompteRendu extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'compte_rendu';

    /**
     * @var string The ID of the report.
     */
    public string $id;

    /**
     * @var string|null The title of the report.
     */
    public ?string $titre;

    /**
     * @var string|null The date of the report. (DDL: DATE)
     */
    public ?string $date_rapport;

    // Removed: contenu, date_creation, id_etudiant

    /**
     * CompteRendu constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

    }

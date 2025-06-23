<?php

namespace App\Models;

/**
 * Class Action
 *
 * Represents the action table.
 *
 * @package App\Models
 */
class Action
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'action';

    /**
     * @var string The ID of the action.
     */
    public string $id;

    /**
     * @var string The label of the action.
     */
    public string $libelle;

    /**
     * @var string|null A description of the action.
     */
    public ?string $description;

    /**
     * @param string $table
     * @param string $id
     * @param string $libelle
     * @param string|null $description
     */
    public function __construct(string $table, string $id, string $libelle, ?string $description)
    {
        $this->table = $table;
        $this->id = $id;
        $this->libelle = $libelle;
        $this->description = $description;
    }


}

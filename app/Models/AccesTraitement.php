<?php

namespace App\Models;


/**
 * Class AccesTraitement
 *
 * @package App\Models
 */
class AccesTraitement
{
    /**
     * @var string
     */
    protected string $table = 'acces_traitement';

    /**
     * @var string
     */
    protected string $traitementId;

    /**
     * @var string
     */
    protected string $utilisateurId;

    /**
     * @var string|null
     */
    protected ?string $dateAccesion; // DDL specifies DATE

    /**
     * @var string|null
     */
    protected ?string $heureAccesion; // DDL specifies TIME

    /**
     * @param string $table
     * @param string $traitementId
     * @param string $utilisateurId
     * @param string|null $dateAccesion
     * @param string|null $heureAccesion
     */
    public function __construct(string $table, string $traitementId, string $utilisateurId, ?string $dateAccesion, ?string $heureAccesion)
    {
        $this->table = $table;
        $this->traitementId = $traitementId;
        $this->utilisateurId = $utilisateurId;
        $this->dateAccesion = $dateAccesion;
        $this->heureAccesion = $heureAccesion;
    }

}

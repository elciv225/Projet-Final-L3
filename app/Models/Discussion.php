<?php

namespace App\Models;

/**
 * Class Discussion
 *
 * @package App\Models
 */
class Discussion
{
    /**
     * @var string
     */
    protected string $table = 'discussion';

    /**
     * @var string L'ID de la discussion.
     */
    private string $id;

    /**
     * @var string|null La date de la discussion.
     */
    private ?string $dateDiscussion; // DDL spécifie DATETIME

    /**
     * @param string $id
     * @param string|null $dateDiscussion
     */
    public function __construct(string $id, ?string $dateDiscussion)
    {
        $this->id = $id;
        $this->dateDiscussion = $dateDiscussion;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getDateDiscussion(): ?string
    {
        return $this->dateDiscussion;
    }

    /**
     * @param string|null $dateDiscussion
     */
    public function setDateDiscussion(?string $dateDiscussion): void
    {
        $this->dateDiscussion = $dateDiscussion;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }
}

<?php

namespace App\Models;
class Discussion {
    private string $id;
    private ?string $date_discussion = null;
    public function __construct() {}
    public function getId(): string { return $this->id; }
    public function setId(string $id): void { $this->id = $id; }
    public function getDateDiscussion(): ?string { return $this->date_discussion; }
    public function setDateDiscussion(?string $date): void { $this->date_discussion = $date; }
}

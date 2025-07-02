<?php

namespace App\Models;

class HistoriqueGrade {
    private string $utilisateur_id;
    private string $grade_id;
    private ?string $date_grade = null;
    public function __construct() {}
    public function getUtilisateurId(): string { return $this->utilisateur_id; }
    public function setUtilisateurId(string $id): void { $this->utilisateur_id = $id; }
    public function getGradeId(): string { return $this->grade_id; }
    public function setGradeId(string $id): void { $this->grade_id = $id; }
    public function getDateGrade(): ?string { return $this->date_grade; }
    public function setDateGrade(?string $date): void { $this->date_grade = $date; }
}
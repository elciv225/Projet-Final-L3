<?php

namespace App\Models;

/**
 * Représente une spécialité pour un enseignant (ex: 'Réseaux et Systèmes', 'Génie Logiciel').
 */
class Specialite {
    private $id_specialite;
    private $libelle_specialite;

    /**
     * Constructeur de la classe Specialite.
     * @param array $data Données pour initialiser l'objet.
     */
    public function __construct(array $data = []) {
        if (!empty($data)) {
            $this->id_specialite = $data['id_specialite'] ?? null;
            $this->libelle_specialite = $data['libelle_specialite'] ?? null;
        }
    }

    // --- Getters ---

    public function getIdSpecialite() {
        return $this->id_specialite;
    }

    public function getLibelleSpecialite() {
        return $this->libelle_specialite;
    }

    // --- Setters ---

    public function setIdSpecialite($id_specialite) {
        $this->id_specialite = $id_specialite;
    }

    public function setLibelleSpecialite($libelle_specialite) {
        $this->libelle_specialite = $libelle_specialite;
    }
}

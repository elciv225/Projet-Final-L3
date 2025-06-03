<?php

namespace App\Controllers;

use System\Http\Response;

class AdministrateurController
{
    /**
     * Page d'accueil de l'espace administrateur
     */
    public function index(): Response
    {
        $data = [
            'title' => 'Espace Administrateur',
            'heading' => 'Bienvenue dans l\'Espace Administrateur',
            'content' => 'Tableau de bord principal de l\'espace administrateur.'
        ];

        // Si c'est une requête AJAX, retourner seulement le contenu du dashboard
        if ($this->isAjaxRequest()) {
            return Response::view('admin/main-content', $data);
        }

        // Sinon, retourner la page complète avec le menu intégré
        return Response::view('espace-administrateur', $data);
    }

    /**
     * Gestion du personnel administratif
     */
    public function gestionPersonnelAdministratif(): Response
    {
        $data = [
            'title' => 'Gestion du Personnel Administratif',
            'heading' => 'Personnel Administratif',
            'content' => 'Gestion du personnel administratif de l\'établissement.'
        ];

        // Si c'est une requête AJAX, retourner seulement le contenu de la section
        if ($this->isAjaxRequest()) {
            return Response::view('gestion/personnel-administratif', $data);
        }

        // Pour une requête normale (rechargement de page), retourner la page complète
        // mais avec le contenu de personnel-administratif affiché
        return Response::view('espace-administrateur', array_merge($data, [
            'currentSection' => 'personnel-administratif',
            'sectionContent' => 'gestion/personnel-administratif'
        ]));
    }

    /**
     * Gestion des enseignants
     */
    public function gestionEnseignants(): Response
    {
        $data = [
            'title' => 'Gestion des Enseignants',
            'heading' => 'Enseignants',
            'content' => 'Gestion du corps enseignant de l\'établissement.'
        ];

        if ($this->isAjaxRequest()) {
            return Response::view('gestion/enseignants', $data);
        }

        return Response::view('espace-administrateur', array_merge($data, [
            'currentSection' => 'enseignants',
            'sectionContent' => 'gestion/enseignants'
        ]));
    }

    /**
     * Gestion des étudiants
     */
    public function gestionEtudiants(): Response
    {
        $data = [
            'title' => 'Gestion des Étudiants',
            'heading' => 'Étudiants',
            'content' => 'Gestion des étudiants de l\'établissement.'
        ];

        if ($this->isAjaxRequest()) {
            return Response::view('gestions/etudiants', $data);
        }

        return Response::view('espace-administrateur', array_merge($data, [
            'currentSection' => 'etudiants',
            'sectionContent' => 'gestions/etudiants'
        ]));
    }

    /**
     * Détecte si la requête est une requête AJAX
     */
    private function isAjaxRequest(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
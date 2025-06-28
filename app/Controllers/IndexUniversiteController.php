<?php

namespace App\Controllers;

use System\Http\Response;

class IndexUniversiteController extends Controller
{
    /**
     * Page d'accueil de l'espace administrateur
     */
    public function index(): Response
    {
        $data = [
            'title' => 'Espace Administrateur',
            'heading' => 'Bienvenue dans l\'Espace Administrateur',
            'content' => 'Tableau de bord principal de l\'espace administrateur.',
            'modules' => $this->getAvailableModules()
        ];

        // Si c'est une requête AJAX, retourner seulement le contenu du dashboard
        if (Response::isAjaxRequest()) {
            return Response::view('admin/main-content', $data);
        }

        // Sinon, retourner la page complète avec le menu intégré
        return Response::view('index-universite', $data);
    }

    /**
     * Gestionnaire dynamique pour tous les modules
     */
    public function gestionMenuModules(string $view = 'index-universite'): Response
    {
        return parent::gestionMenuModules($view);
    }

    /**
     * Gère les traitements spécifiques des modules (ajouter, modifier, supprimer, etc.).
     * @param string $category
     * @param string $moduleName
     * @param string $traitementName
     * @param string $endpoint L'endpoint de la requête (e.g., 'index-universite/gestion/etudiants/ajouter').
     * @return Response
     */
    public function gererTraitementModule( string $category, string $moduleName, string $traitementName, string $endpoint = 'index-universite'): Response
    {
       return parent::gererTraitementModule($endpoint,$category, $moduleName, $traitementName);
    }

}

<?php

namespace App\Controllers;

use System\Http\Response;

class IndexController extends Controller
{
    /**
     * Page d'accueil de l'espace administrateur
     */
    public function index(): Response
    {
        // Charger les données dynamiques pour le tableau de bord
        $dashboardDAO = new \App\Dao\DashboardDAO($this->pdo);

        // Récupérer les statistiques
        $stats = $dashboardDAO->getStatistics();

        // Récupérer les activités récentes
        $recentActivities = $dashboardDAO->getRecentActivities(3);

        // Récupérer les informations système
        $systemInfo = $dashboardDAO->getSystemInfo();

        // Préparer les données pour la vue
        $data = [
            'title' => 'Espace Administrateur',
            'heading' => 'Bienvenue dans l\'Espace Administrateur',
            'content' => 'Tableau de bord principal de l\'espace administrateur.',
            'modules' => $this->getAvailableModules(),
            'stats' => $stats,
            'recentActivities' => $recentActivities,
            'systemInfo' => $systemInfo,
            'currentUser' => $_SESSION['utilisateur_connecte'] ?? null
        ];

        // Si c'est une requête AJAX, retourner seulement le contenu du dashboard
        if (Response::isAjaxRequest()) {
            return Response::view('admin/main-content', $data);
        }

        // Sinon, retourner la page complète avec le menu intégré
        return Response::view('index', $data);
    }

    /**
     * Gestionnaire dynamique pour tous les modules
     */
    public function gestionMenuModules(string $view = 'index'): Response
    {
        return parent::gestionMenuModules($view);
    }

    /**
     * Gère les menu_views spécifiques des modules (ajouter, modifier, supprimer, etc.).
     * @param string $category
     * @param string $moduleName
     * @param string $traitementName
     * @param string $endpoint L'endpoint de la requête (e.g., 'index/menu_views/etudiants/ajouter').
     * @return Response
     */
    public function gererTraitementModule( string $category, string $moduleName, string $traitementName, string $endpoint = 'index'): Response
    {
       return parent::gererTraitementModule($endpoint,$category, $moduleName, $traitementName);
    }

}

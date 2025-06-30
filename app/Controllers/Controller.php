<?php

namespace App\Controllers;

use Exception;
use System\Database\Database;
use System\Http\Request;
use System\Http\Response;
use PDO;

class Controller
{
    protected Request $request;
    protected pdo $pdo;

    public function __construct()
    {
        $this->request = Request::create();
        $this->pdo = Database::getConnection();
    }


    /**
     * Fonction de pop up erreur
     * @param string $message
     * @return Response JSON pour la pop up
     */
    public function succes(string $message):Response{
        return Response::json([
            'statut' => 'succes',
            'message' => $message
        ]);
    }

    /**
     * Fonction de pop up erreur
     * @param string $message
     * @return Response JSON pour la pop up
     */
    public function error(string $message):Response{
        return Response::json([
            'statut' => 'error',
            'message' => $message
        ]);
    }

    /**
     * Fonction de pop up info
     * @param string $message
     * @return Response JSON pour la pop up
     */
    public function info(string $message):Response{
        return Response::json([
            'statut' => 'info',
            'message' => $message
        ]);
    }

    /**
     * Récupère tous les modules disponibles pour le menu
     * @return array
     */
    public function getAvailableModules(): array
    {
        if (!defined('MODULES_CONFIG')) {
            return [];
        }
        return MODULES_CONFIG;
    }

    /**
     * Gestionnaire dynamique pour tous les modules
     * @param string $view La vue ex (espace-administrateur)
     */
    public function gestionMenuModules(string $view): Response
    {
        $uri = $_SERVER['REQUEST_URI'];
        // Supprime le slash de début/fin et découpe l'URI en parties
        $pathParts = explode('/', trim($uri, '/'));

        if (count($pathParts) < 3) {
            return $this->error("Chemin d'accès au module invalide.");
        }

        // Les parties pertinentes sont à l'index 1 (catégorie) et 2 (nom du module)
        $category = $pathParts[1];
        $moduleName = $pathParts[2];

        // Récupère la configuration du module en utilisant la nouvelle méthode francisée
        $moduleConfig = $this->getModuleConfig($category, $moduleName);

        if (!$moduleConfig) {
            return $this->error("Configuration du module '$moduleName' dans la catégorie '$category' non trouvée.");
        }

        // Vérifier si le contrôleur existe en utilisant la clé francisée 'controleur'
        if (!class_exists($moduleConfig['controleur'])) {
            return $this->error("Le contrôleur {$moduleConfig['controleur']} n'existe pas pour le module '$moduleName'.");
        }

        try {
            // Instancier le contrôleur et appeler la méthode en utilisant les clés francisées
            $controller = new $moduleConfig['controleur']();
            $method = $moduleConfig['methodePrincipale']; // Utilisation de 'methodePrincipale'

            if (!method_exists($controller, $method)) {
                return $this->error("La méthode '$method' n'existe pas dans le contrôleur {$moduleConfig['controleur']} pour le module '$moduleName'.");
            }

            // Appeler la méthode du contrôleur
            $moduleResponse = $controller->$method();

            // Si c'est une requête AJAX, retourner directement la réponse du module
            if (Response::isAjaxRequest()) {
                return $moduleResponse;
            }

            // Pour une requête normale, wrapper dans l'espace administrateur
            $data = [
                'title' => $moduleConfig['label'], // Utilisation de 'titre'
                'heading' => $moduleConfig['label'], // Utilisation de 'titre'
                'content' => $moduleConfig['description'],
                'currentSection' => $moduleName,
                'currentCategory' => $category,
                'moduleContent' => $moduleResponse,
                'modules' => $this->getAvailableModules()
            ];

            return Response::view($view, $data);

        } catch (Exception $e) {
            error_log("Erreur dans gestionMenuModules: " . $e->getMessage());
            return $this->error("Erreur interne lors du chargement du module: " . $e->getMessage());
        }
    }

    /**
     * Récupère la configuration d'un module
     * @param string $category
     * @param string $moduleName
     * @return array|null
     */
    private function getModuleConfig(string $category, string $moduleName): ?array
    {
        if (!defined('MODULES_CONFIG')) {
            return null;
        }

        // Utilisation de la constante globale pour accéder à la configuration
        $modules = MODULES_CONFIG;
        return $modules[$category][$moduleName] ?? null;
    }

    /**
     * Gère les menu_views spécifiques des modules (ajouter, modifier, supprimer, etc.).
     * @param string $endpoint
     * @param string $category La catégorie du module (e.g., 'menu_views').
     * @param string $moduleName Le nom du module (e.g., 'etudiants').
     * @param string $traitementName Le nom du traitement (e.g., 'ajouter').
     * @return Response
     */
    public function gererTraitementModule( string $category, string $moduleName, string $traitementName,string $endpoint): Response
    {
        $moduleConfig = $this->getModuleConfig($category, $moduleName);

        if (!$moduleConfig) {
            return $this->error("Module '$moduleName' dans la catégorie '$category' non trouvé pour le traitement '$traitementName'.");
        }

        $traitementsConfig = $moduleConfig['menu_views'][$traitementName] ?? null;

        if (!$traitementsConfig) {
            return $this->error("Traitement '$traitementName' non configuré pour le module '$moduleName'.");
        }

        // Instancier le contrôleur spécifique du module
        if (!class_exists($moduleConfig['controleur'])) {
            return $this->error("Le contrôleur {$moduleConfig['controleur']} n'existe pas pour le traitement '$traitementName'.");
        }

        try {
            $controller = new $moduleConfig['controleur']();
            if (!method_exists($controller, $traitementName)) {
                return $this->error("La méthode de traitement '$traitementName' n'existe pas dans le contrôleur {$moduleConfig['controleur']}.");
            }

            $response = $controller->$traitementName();

            if (Response::isAjaxRequest()) {
                return $response;
            } else {
                return Response::redirect("/$endpoint/$category/$moduleName");
            }

        } catch (Exception $e) {
            error_log("Erreur lors du traitement du module '$moduleName' (traitement: '$traitementName'): " . $e->getMessage());
            return $this->error("Erreur lors de l'exécution du traitement '$traitementName': " . $e->getMessage());
        }
    }
}

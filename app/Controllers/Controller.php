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
     * Retourne une réponse JSON pour un succès.
     * Peut inclure des données supplémentaires pour la mise à jour de la vue.
     * @param string $message
     * @param array $data Optionnel, données à inclure dans la réponse.
     * @return Response
     */
    public function succes(string $message, array $data = []): Response
    {
        return Response::json(array_merge([
            'statut' => 'succes',
            'message' => $message
        ], $data));
    }

    /**
     * Retourne une réponse JSON pour une erreur.
     * @param string $message
     * @param int $statusCode Code HTTP pour l'erreur (défaut 400 Bad Request).
     * @return Response
     */
    public function error(string $message, int $statusCode = 400): Response
    {
        // Assurez-vous que le statut HTTP est également défini.
        // La méthode Response::json devrait idéalement permettre de passer un code de statut.
        // Si ce n'est pas le cas, il faudrait modifier Response::json ou utiliser http_response_code() avant.
        // http_response_code($statusCode); // Exemple
        return Response::json([
            'statut' => 'error',
            'message' => $message
        ]); // Potentiellement Response::json([...], $statusCode)
    }

    /**
     * Retourne une réponse JSON pour une information.
     * @param string $message
     * @param array $data Optionnel, données à inclure.
     * @return Response
     */
    public function info(string $message, array $data = []): Response
    {
        return Response::json(array_merge([
            'statut' => 'info',
            'message' => $message
        ], $data));
    }

    /**
     * Gère la réponse commune pour les opérations CRUD dans les contrôleurs de MenuViews.
     * Recharge la vue principale du module avec un message et les données mises à jour.
     *
     * @param string $viewPath Le chemin vers la vue principale du module (ex: 'menu_views/etudiants').
     * @param array $viewData Les données nécessaires pour rendre la vue (ex: liste des étudiants, formulaires).
     * @param string $message Le message de succès/erreur/info.
     * @param string $status Le statut ('succes', 'error', 'info').
     * @param array $additionalJsonData Données JSON supplémentaires à fusionner.
     * @return Response
     */
    protected function reponseVueAvecMessage(
        string $viewPath,
        array $viewData,
        string $message,
        string $status = 'info',
        ?string $partialViewPathForAjax = null, // Chemin optionnel vers une vue partielle pour AJAX
        array $additionalJsonData = []
    ): Response {
        $baseJsonData = [
            'statut' => $status,
            'message' => $message,
        ];

        // Fusionner les données additionnelles JSON en premier pour qu'elles puissent être écrasées par $viewData si conflit.
        $finalJsonData = array_merge($baseJsonData, $additionalJsonData, $viewData);

        if (Response::isAjaxRequest()) {
            if ($partialViewPathForAjax) {
                // Si un chemin de vue partielle est fourni pour AJAX, rendre cette vue.
                // Le JS (ajax.js) doit être capable de gérer une réponse HTML pour son data-target.
                // On peut aussi inclure le JSON de statut/message dans un en-tête ou un wrapper si besoin.
                // Pour la simplicité, on va juste rendre la vue partielle.
                // Le JS peut faire un autre appel pour le message si nécessaire, ou le message peut être ignoré.
                // Ou, mieux, la vue partielle peut elle-même inclure une zone pour le message si $message et $status sont passés.
                $viewData['messagePopup'] = ['texte' => $message, 'type' => $status]; // Rendre le message dispo à la vue partielle
                return Response::view($partialViewPathForAjax, $viewData); // Ne pas passer $finalJsonData comme JSON ici.
            } else {
                // Comportement par défaut : renvoyer toutes les données en JSON.
                return Response::json($finalJsonData);
            }
        } else {
            // Pour une requête non-AJAX, recharger la page entière avec les données.
            $viewData['messagePopup'] = ['texte' => $message, 'type' => $status];
            return Response::view($viewPath, $viewData);
        }
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

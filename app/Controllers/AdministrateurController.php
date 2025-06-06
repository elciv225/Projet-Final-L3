<?php

namespace App\Controllers;

use Exception;
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
            'content' => 'Tableau de bord principal de l\'espace administrateur.',
            'modules' => $this->getAvailableModules()
        ];

        // Si c'est une requête AJAX, retourner seulement le contenu du dashboard
        if (Response::isAjaxRequest()) {
            return Response::view('admin/main-content', $data);
        }

        // Sinon, retourner la page complète avec le menu intégré
        return Response::view('espace-administrateur', $data);
    }

    /**
     * Récupère tous les modules disponibles pour le menu
     * @return array
     */
    private function getAvailableModules(): array
    {
        if (!defined('MODULES_CONFIG')) {
            return [];
        }

        return MODULES_CONFIG;
    }

    /**
     * Gestionnaire dynamique pour tous les modules
     */
    public function gestionMenuModules(): Response
    {
        $uri = $_SERVER['REQUEST_URI'];
        // Supprime le slash de début/fin et découpe l'URI en parties
        $pathParts = explode('/', trim($uri, '/'));

        // Format : espace-administrateur/categorie/nom-module
        // On vérifie qu'il y a au moins 3 parties après le trim (espace-administrateur, categorie, nom-module)
        if (count($pathParts) < 3 || $pathParts[0] !== 'espace-administrateur') {
            return $this->moduleNotFound("Chemin d'accès au module invalide.");
        }

        // Les parties pertinentes sont à l'index 1 (catégorie) et 2 (nom du module)
        $category = $pathParts[1];
        $moduleName = $pathParts[2];

        // Récupère la configuration du module en utilisant la nouvelle méthode francisée
        $moduleConfig = $this->getModuleConfig($category, $moduleName);

        if (!$moduleConfig) {
            return $this->moduleNotFound("Configuration du module '$moduleName' dans la catégorie '$category' non trouvée.");
        }

        // Vérifier si le contrôleur existe en utilisant la clé francisée 'controleur'
        if (!class_exists($moduleConfig['controleur'])) {
            return $this->moduleNotFound("Le contrôleur {$moduleConfig['controleur']} n'existe pas pour le module '$moduleName'.");
        }

        try {
            // Instancier le contrôleur et appeler la méthode en utilisant les clés francisées
            $controller = new $moduleConfig['controleur']();
            $method = $moduleConfig['methodePrincipale']; // Utilisation de 'methodePrincipale'

            if (!method_exists($controller, $method)) {
                return $this->moduleNotFound("La méthode '$method' n'existe pas dans le contrôleur {$moduleConfig['controleur']} pour le module '$moduleName'.");
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

            return Response::view('espace-administrateur', $data);

        } catch (Exception $e) {
            error_log("Erreur dans gestionMenuModules: " . $e->getMessage());
            return $this->moduleNotFound("Erreur interne lors du chargement du module: " . $e->getMessage());
        }
    }

    /**
     * Gestion du cas où un module n'est pas trouvé
     * @param string $message
     * @return Response
     */
    private function moduleNotFound(string $message = "Module non trouvé"): Response
    {
        if (Response::isAjaxRequest()) {
            return Response::error($message);
        }

        $data = [
            'title' => 'Module non trouvé',
            'heading' => 'Erreur 404',
            'content' => $message,
            'modules' => $this->getAvailableModules()
        ];

        return Response::view('espace-administrateur', $data);
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
     * Gère les traitements spécifiques des modules (ajouter, modifier, supprimer, etc.).
     * @param string $category La catégorie du module (e.g., 'gestion').
     * @param string $moduleName Le nom du module (e.g., 'etudiants').
     * @param string $traitementName Le nom du traitement (e.g., 'ajouter').
     * @return Response
     */
    public function gererTraitementModule(string $category, string $moduleName, string $traitementName): Response
    {
        $moduleConfig = $this->getModuleConfig($category, $moduleName);

        if (!$moduleConfig) {
            return $this->moduleNotFound("Module '$moduleName' dans la catégorie '$category' non trouvé pour le traitement '$traitementName'.");
        }

        $traitementsConfig = $moduleConfig['traitements'][$traitementName] ?? null;

        if (!$traitementsConfig) {
            return $this->moduleNotFound("Traitement '$traitementName' non configuré pour le module '$moduleName'.");
        }

        // Instancier le contrôleur spécifique du module
        if (!class_exists($moduleConfig['controleur'])) {
            return $this->moduleNotFound("Le contrôleur {$moduleConfig['controleur']} n'existe pas pour le traitement '$traitementName'.");
        }

        try {
            $controller = new $moduleConfig['controleur']();

            // La méthode à appeler dans le contrôleur du module est le nom du traitement lui-même.
            // Ex: EtudiantsController::ajouter(), EnseignantsController::modifier()
            if (!method_exists($controller, $traitementName)) {
                return $this->moduleNotFound("La méthode de traitement '$traitementName' n'existe pas dans le contrôleur {$moduleConfig['controleur']}.");
            }

            // Exécuter la méthode de traitement.
            // Le contrôleur du module sera responsable de la validation des données POST,
            // de l'interaction avec la base de données, et du retour d'une réponse.
            $response = $controller->$traitementName();

            // S'il s'agit d'une requête AJAX, retournez la réponse du contrôleur directement.
            // Sinon, vous pourriez vouloir rediriger l'utilisateur ou afficher un message de succès.
            if (Response::isAjaxRequest()) {
                return $response;
            } else {
                // Exemple de redirection après un traitement réussi
                // Vous pouvez ajuster cela en fonction de votre logique d'application
                return Response::redirect("/espace-administrateur/$category/$moduleName");
            }

        } catch (Exception $e) {
            error_log("Erreur lors du traitement du module '$moduleName' (traitement: '$traitementName'): " . $e->getMessage());
            return $this->moduleNotFound("Erreur lors de l'exécution du traitement '$traitementName': " . $e->getMessage());
        }
    }

    /**
     * API pour récupérer la liste des modules (utile pour le JavaScript)
     * @return Response
     */
    public function getModulesApi(): Response
    {
        return Response::json([
            'modules' => $this->getAvailableModules()
        ]);
    }
}

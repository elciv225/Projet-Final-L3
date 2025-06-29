<?php

namespace App\Services;

use App\Dao\UtilisateurDAO;
use App\Models\Utilisateur; // Assuming your DAO returns this type or you cast to it.
use System\Database\Database; // To get PDO instance for DAO

class AuthService
{
    private UtilisateurDAO $utilisateurDAO;

    public function __construct()
    {
        // It's generally better to inject dependencies (like PDO or UtilisateurDAO instance)
        // For simplicity here, creating DAO instance directly.
        // Consider a Dependency Injection Container for larger applications.
        $pdo = Database::getInstance(); // Assuming Database::getInstance() returns PDO
        $this->utilisateurDAO = new UtilisateurDAO($pdo);
    }

    /**
     * Attempts to log in a user.
     *
     * @param string $login The user's login identifier.
     * @param string $password The user's plain text password.
     * @return bool True on successful login, false otherwise.
     */
    public function login(string $login, string $password): bool
    {
        $user = $this->utilisateurDAO->findByLogin($login);

        if ($user && password_verify($password, $user->mot_de_passe)) {
            // Password matches, store user info in session
            $_SESSION['user'] = [
                'id' => $user->id,
                'login' => $user->login,
                'nom' => $user->nom,
                'prenoms' => $user->prenoms,
                'groupe_utilisateur_id' => $user->groupe_utilisateur_id,
                'type_utilisateur_id' => $user->type_utilisateur_id
                // Add any other frequently accessed, non-sensitive user data
            ];
            $_SESSION['authenticated_at'] = time(); // Optional: for session timeout logic
            session_regenerate_id(true); // Regenerate session ID for security
            return true;
        }
        return false;
    }

    /**
     * Logs out the current user.
     */
    public function logout(): void
    {
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
        }
        if (isset($_SESSION['authenticated_at'])) {
            unset($_SESSION['authenticated_at']);
        }

        // Could also clear other session variables if needed
        // session_unset(); // Clears all session variables

        session_destroy();

        // Ensure a new session is started for subsequent requests if needed,
        // or that a guest session is cleanly handled.
        // Kernel.php will call session_start() on next request.
        // For immediate effect if redirecting after logout to a page that needs session:
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_regenerate_id(true); // Good practice after invalidating a session
    }

    /**
     * Checks if a user is currently authenticated.
     *
     * @return bool True if authenticated, false otherwise.
     */
    public function isAuthenticated(): bool
    {
        return isset($_SESSION['user']['id']);
    }

    /**
     * Gets the ID of the currently authenticated user.
     *
     * @return string|null The user ID or null if not authenticated.
     */
    public function getCurrentUserId(): ?string
    {
        return $_SESSION['user']['id'] ?? null;
    }

    /**
     * Gets the currently authenticated user's data from the session.
     *
     * @return array|null The user data array or null if not authenticated.
     */
    public function getCurrentUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Gets the groupe_utilisateur_id of the currently authenticated user.
     *
     * @return string|null The groupe_utilisateur_id or null if not authenticated.
     */
    public function getUserGroupId(): ?string
    {
        return $_SESSION['user']['groupe_utilisateur_id'] ?? null;
    }

    /**
     * Gets the type_utilisateur_id of the currently authenticated user.
     *
     * @return string|null The type_utilisateur_id or null if not authenticated.
     */
    public function getUserTypeId(): ?string
    {
        return $_SESSION['user']['type_utilisateur_id'] ?? null;
    }

    // The hasPermission method would be more complex and might involve another DAO (AutorisationActionDAO)
    // and a clear definition of how traitements/actions are identified (e.g., by string IDs or constants).
    // For now, focusing on core authentication.
    // public function hasPermission(string $traitementId, ?string $actionId = null): bool
    // {
    //     if (!$this->isAuthenticated()) {
    //         return false;
    //     }
    //     $groupeId = $this->getUserGroupId();
    //     if (!$groupeId) {
    //         return false;
    //     }
    //     // Logic to query AutorisationActionDAO based on $groupeId, $traitementId, $actionId
    //     // This requires AutorisationActionDAO to be available.
    //     // Example:
    //     // $autorisationActionDAO = new \App\Dao\AutorisationActionDAO(Database::getInstance());
    //     // $criteria = ['groupe_utilisateur_id' => $groupeId, 'traitement_id' => $traitementId];
    //     // if ($actionId) $criteria['action_id'] = $actionId;
    //     // $permissions = $autorisationActionDAO->rechercher($criteria);
    //     // return !empty($permissions);
    //     return true; // Placeholder
    // }
}

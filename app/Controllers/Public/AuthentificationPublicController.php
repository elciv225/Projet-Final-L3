<?php

namespace App\Controllers\Public;

use App\Controllers\Controller;
use System\Http\Response;
use System\Mail\Mail;

class AuthentificationPublicController extends Controller
{

    private Mail $mail;
    private AuthService $authService; // Added AuthService
    /**
     * Configuration de l'email pour l'envoi de code de vérification
     */
    private $mailConfig = [
        'host' => 'mailhog',        // Service Docker
        'port' => 1025,             // Port SMTP MailHog
        'username' => '',
        'password' => '',
        'encryption' => '',
        'from_email' => 'noreply@example.com',
        'from_name' => 'Projet XXX (Test)'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->mail = Mail::make($this->mailConfig);
        $this->authService = new \App\Services\AuthService(); // Initialize AuthService
    }

    /**
     * Affiche le formulaire de connexion standard.
     */
    public function loginForm(): Response
    {
        // Assumes 'public/login.php' view exists or 'public/authentification.php' can be adapted
        // For now, let's reuse 'public/authentification.php' and pass a specific 'mode' or 'etape'
        return Response::view('public/authentification', [
            'mode' => 'login', // Differentiate from multi-step registration
            'etape' => 'login_credentials', // A new step for the view to render login/password fields
            'txtButton' => 'Se connecter'
        ]);
    }

    /**
     * Traite la soumission du formulaire de connexion standard.
     */
    public function handleLogin(): Response
    {
        $login = $this->request->getPostParams('login');
        $password = $this->request->getPostParams('mot_de_passe'); // Ensure form field name matches

        if (empty($login) || empty($password)) {
            // Return to login form with error
            return Response::view('public/authentification', [
                'mode' => 'login',
                'etape' => 'login_credentials',
                'txtButton' => 'Se connecter',
                'error' => 'Login et mot de passe requis.'
            ], status: 400); // Bad request
        }

        if ($this->authService->login($login, $password)) {
            // Successful login, redirect to a dashboard or home page
            // The specific redirect URL should be configurable or determined by user role
            return Response::redirect('/espace-administrateur'); // Example redirect
        } else {
            // Failed login
            return Response::view('public/authentification', [
                'mode' => 'login',
                'etape' => 'login_credentials',
                'txtButton' => 'Se connecter',
                'error' => 'Login ou mot de passe incorrect.'
            ], status: 401); // Unauthorized
        }
    }

    /**
     * Gère la déconnexion de l'utilisateur.
     */
    public function logout(): Response
    {
        $this->authService->logout();
        return Response::redirect('/login'); // Redirect to login page after logout
    }


    /**
     * Point d'entrée principal - traite toutes les étapes d'authentification/enregistrement
     */
    public function authentification(): Response
    {
        // Récupérer l'étape actuelle du processus
        $etapeActuelle = $this->request->getPostParams('etape');

        // Traiter selon l'étape demandée
        switch ($etapeActuelle) {
            case 'verification': // Start of multi-step registration
                return $this->verifierIP();
            case 'envoi_email':
                return $this->envoyerCodeEmail();
            case 'verification_code':
                return $this->verifierCode();
            case 'enregistrement': // Final step of multi-step registration
                return $this->creerCompte();
        }
        // Default to showing the initial step of registration or an error
        return $this->index();
        // return $this->error("Étape d'authentification inconnue ou non spécifiée : $etapeActuelle");
    }

    /**
     * ÉTAPE 1 : Vérifier l'identifiant permanent (IP) - Part of multi-step registration
     */
    private function verifierIP(): Response
    {
        // Récupérer l'IP saisie par l'utilisateur
        $identifiantPermanent = $this->request->getPostParams('ip');

        // Lancement de la validation de l'IP
        $erreur = $this->estIPValide($identifiantPermanent);
        if ($erreur !== null) {
            return $erreur; // Retourne l'erreur si IP invalide
        }

        $_SESSION['registration_utilisateur']['ip'] = $identifiantPermanent; // Store registration IP in a specific session key

        return Response::view('public/authentification', data: [
            'ip' => $identifiantPermanent,
            'etape' => 'envoi_email', // Next step in registration
            'txtButton' => 'Envoyer le code'
        ], json: [
            'statut' => 'succes',
            'message' => "Identifiant permanent valide : $identifiantPermanent"
        ]);
    }

    /**
     * Valider un identifiant permanent
     */
    private function estIPValide(string $ip): null|Response
    {
        // TODO: Implémenter votre logique de validation
        if (empty($ip)) {
            return $this->error("L'identifiant permanent ne peut pas être vide");
        } elseif (strlen($ip) < 14) { // Example validation
            return $this->error("L'identifiant permanent doit faire au moins 14 caractères");
        } elseif (strlen($ip) > 14) { // Example validation
            return $this->error("L'identifiant permanent ne doit pas dépasser 14 caractères");
        } elseif (!preg_match('/^[A-Z0-9]{14}$/', $ip)) { // Example validation
            return $this->error("L'identifiant permanent doit contenir uniquement des lettres majuscules et des chiffres");
        }
        return null; // IP valide
    }

    /**
     * ÉTAPE 2 : Envoyer le code de vérification par email - Part of multi-step registration
     */
    private function envoyerCodeEmail(): Response
    {
        $emailUtilisateur = $this.request->getPostParams('email');
        if (empty($_SESSION['registration_utilisateur']['ip'])) {
            return $this->error("Session invalide ou expirée. Veuillez recommencer le processus.");
        }

        $_SESSION['registration_code_verification'] = $this->genererCodeVerification();
        $_SESSION['registration_code_expiry'] = time() + (10 * 60); // Code expires in 10 minutes

        try {
            $this->mail->to($emailUtilisateur, $_SESSION['registration_utilisateur']['ip'])
                ->subject('Code de vérification')
                ->view('emails/verification_code', [
                    'verificationCode' => $_SESSION['registration_code_verification'],
                    'expirationTime' => date('H:i:s', $_SESSION['registration_code_expiry']),
                ])
                ->send();
        } catch (\Exception $e) {
            return $this->error("Erreur lors de l'envoi de l'email : " . $e->getMessage());
        }
        $_SESSION['registration_utilisateur']['email'] = $emailUtilisateur;

        return Response::view('public/authentification', data: [
            'ip' => $_SESSION['registration_utilisateur']['ip'],
            'email' => $emailUtilisateur,
            'etape' => 'verification_code', // Next step in registration
            'txtButton' => 'Vérifier le code'
        ], json: [
            'statut' => 'succes',
            'message' => "Un code de vérification a été envoyé à l'adresse : $emailUtilisateur"
        ]);
    }

    /**
     * Générer un code de vérification à 6 chiffres
     */
    private function genererCodeVerification(): string
    {
        return (string)rand(100000, 999999);
    }

    /**
     * ÉTAPE 3 : Vérifier le code saisi par l'utilisateur - Part of multi-step registration
     */
    private function verifierCode(): Response
    {
        $codeSaisiParUtilisateur = $this->request->getPostParams('code');
        $codeVerification = $_SESSION['registration_code_verification'] ?? null;
        $codeExpiry = $_SESSION['registration_code_expiry'] ?? 0;

        if (time() > $codeExpiry) {
            unset($_SESSION['registration_code_verification']);
            unset($_SESSION['registration_code_expiry']);
            return $this->error("Le code de vérification a expiré. Veuillez demander un nouveau code.");
        }

        $erreur = $this->verifierCodeSaisi($codeSaisiParUtilisateur);
        if ($erreur !== null) {
            return $erreur;
        }

        if ($codeSaisiParUtilisateur !== $codeVerification) {
            return $this->error("Le code de vérification saisi ne correspond pas au code envoyé");
        }

        // Code verified, clear it from session
        unset($_SESSION['registration_code_verification']);
        unset($_SESSION['registration_code_expiry']);
        $_SESSION['registration_utilisateur']['code_verified'] = true;

        return Response::view('public/authentification', data: [
            'ip' => $_SESSION['registration_utilisateur']['ip'],
            'email' => $_SESSION['registration_utilisateur']['email'],
            'etape' => 'enregistrement', // Next step in registration
            'txtButton' => 'Créer le compte'
        ], json: [
            'statut' => 'succes',
            'message' => "Code de vérification validé avec succès"
        ]);
    }

    /**
     * Vérifier le code de vérification saisi par l'utilisateur
     */
    private function verifierCodeSaisi(string $codeSaisi): Response|null
    {
        if (empty($codeSaisi)) {
            return $this->error("Le code de vérification ne peut pas être vide");
        }
        if (!preg_match('/^\d{6}$/', $codeSaisi)) {
            return $this->error("Le code de vérification doit être un nombre à 6 chiffres");
        }
        return null;
    }

    /**
     * ÉTAPE 4 : Créer le compte utilisateur - Final step of multi-step registration
     */
    private function creerCompte(): Response
    {
        if (empty($_SESSION['registration_utilisateur']['code_verified'])) {
             return $this->error("Veuillez d'abord vérifier votre code email.");
        }

        $motDePasse = $this->request->getPostParams('password');
        $confirmationMotDePasse = $this->request->getPostParams('password_confirm');
        // It's assumed that the registration form for this step also includes nom, prenoms, etc.
        // or these are collected in earlier steps and stored in $_SESSION['registration_utilisateur']
        $nom = $this->request->getPostParams('nom', $_SESSION['registration_utilisateur']['nom'] ?? ''); // Example
        $prenoms = $this->request->getPostParams('prenoms', $_SESSION['registration_utilisateur']['prenoms'] ?? ''); // Example
        $dateNaissance = $this->request->getPostParams('date_naissance', $_SESSION['registration_utilisateur']['date_naissance'] ?? null); // Example

        if (empty($nom) || empty($prenoms) || empty($motDePasse)) {
             return $this->error("Nom, prénoms et mot de passe sont requis.");
        }

        if ($motDePasse !== $confirmationMotDePasse) {
            return $this->error("Les mots de passe ne correspondent pas");
        }

        // Data for new user.
        // The 'id' (matricule) is ip from registration.
        // 'login' could be email or ip or another chosen field. For now, let's use email.
        $userData = [
            'id' => $_SESSION['registration_utilisateur']['ip'], // Assuming IP is the matricule/user ID
            'nom' => $nom,
            'prenoms' => $prenoms,
            'email' => $_SESSION['registration_utilisateur']['email'],
            'login' => $_SESSION['registration_utilisateur']['email'], // Using email as login
            'mot_de_passe' => $motDePasse,
            'date_naissance' => $dateNaissance,
            // Default group, type, access level for new users - these should be configurable
            'groupe_utilisateur_id' => 'GRP_ETUDIANT', // Example default
            'type_utilisateur_id' => 'TYPE_ETUDIANT_DEFAULT', // Example default
            'niveau_acces_donnees_id' => 'ACCES_PUBLIC', // Example default
        ];

        // The UtilisateurDAO->creer method will hash the password.
        if ($this->authService->getUtilisateurDAO()->creer($userData)) { // Accessing DAO via AuthService for now
            // Clear registration session data
            unset($_SESSION['registration_utilisateur']);
            unset($_SESSION['registration_code_verification']); // Just in case
            unset($_SESSION['registration_code_expiry']); // Just in case

            // Optionally log the user in directly
            // $this->authService->login($userData['login'], $motDePasse); // Use the plain password before it's only hashed in DB

            return $this->succes("Compte créé avec succès. Vous pouvez maintenant vous connecter.", '/login'); // Redirect to login
        } else {
            return $this->error("Erreur lors de la création du compte. L'identifiant ou l'email existe peut-être déjà.");
        }
    }

    /*****************************************************************
     * MÉTHODES UTILITAIRES (error, succes - assuming they exist in parent Controller)
     ****************************************************************/
    // protected function error(string $message, int $status = 400, ?string $redirectUrl = null) { ... }
    // protected function succes(string $message, ?string $redirectUrl = null, int $status = 200) { ... }


    /**
     * Page d'accueil pour l'authentification/enregistrement.
     * Par défaut, commence le processus d'enregistrement en plusieurs étapes.
     * Pour afficher le formulaire de connexion, il faut appeler /login (nouvelle route à créer).
     */
    public function index(): Response
    {
        // This is the start of the multi-step registration process
        return Response::view('public/authentification', [
            'mode' => 'register', // Differentiate from login mode
            'etape' => 'verification', // First step of registration
            'txtButton' => 'Vérifier l\'identifiant'
        ]);
    }
}
<?php

namespace App\Controllers\Public;

use App\Controllers\Controller;
use App\Dao\CategorieUtilisateurDAO;
use App\Dao\EtudiantDAO;
use App\Dao\GroupeUtilisateurDAO;
use App\Dao\UtilisateurDAO;
use System\Http\Response;
use System\Mail\Mail;

class AuthentificationPublicController extends Controller
{

    private Mail $mail;
    private UtilisateurDAO $utilisateurDAO;
    private EtudiantDAO $etudiantDAO;
    private CategorieUtilisateurDAO $categorieUtilisateurDAO;
    private GroupeUtilisateurDAO $groupeUtilisateurDAO;

    /**
     * Configuration de l'email pour l'envoi de code de vérification
     */
    private array $mailConfig = [ // Added type hint for array
        'host' => 'mailhog',        // Service Docker
        'port' => 1025,             // Port SMTP MailHog
        'username' => '',
        'password' => '',
        'encryption' => '',
        'from_email' => 'noreply@example.com',
        'from_name' => 'Projet Suivi Stage (Publique)' // Updated name
    ];

    public function __construct()
    {
        parent::__construct();
        $this->mail = Mail::make($this->mailConfig);
        $this->utilisateurDAO = new UtilisateurDAO($this->pdo);
        $this->etudiantDAO = new EtudiantDAO($this->pdo);
        $this->categorieUtilisateurDAO = new CategorieUtilisateurDAO($this->pdo);
        $this->groupeUtilisateurDAO = new GroupeUtilisateurDAO($this->pdo);
    }


    /**
     * Point d'entrée principal - traite toutes les étapes d'authentification
     */
    public function authentification(): Response
    {
        // Récupérer l'étape actuelle du processus
        $etapeActuelle = $this->request->getPostParams('etape');

        // Traiter selon l'étape demandée
        switch ($etapeActuelle) {
            case 'verification_ip': // Renamed for clarity
                return $this->verifierIdentifiantPermanent();
            case 'envoi_email':
                return $this->envoyerCodeEmail();
            case 'verification_code':
                return $this->verifierCode();
            case 'enregistrement_compte': // Renamed for clarity
                return $this->creerCompteEtudiant(); // Changed to a more specific method
            default: // Added default case
                return $this->error("Étape d'authentification non reconnue: $etapeActuelle");
        }
    }

    /**
     * ÉTAPE 1 : Vérifier l'identifiant permanent (num_matricule ou code unique)
     */
    private function verifierIdentifiantPermanent(): Response
    {
        $identifiantPermanent = strtoupper($this->request->getPostParams('ip')); // 'ip' is a misnomer, should be 'identifiant_permanent'

        $erreurValidation = $this->validerFormatIdentifiantPermanent($identifiantPermanent);
        if ($erreurValidation !== null) {
            return $erreurValidation;
        }

        // Vérifier si l'identifiant existe déjà pour un utilisateur actif
        $utilisateurExistant = $this->etudiantDAO->recupererParNumMatricule($identifiantPermanent); // Assuming 'ip' is num_matricule
        if ($utilisateurExistant) {
            // Potentially check if user account is already fully registered or active.
            // For now, let's assume if it exists, it's an issue for new registration.
             // Further check: Does this etudiant already have an associated utilisateur record?
            $baseUser = $this->utilisateurDAO->recupererParId($utilisateurExistant->getIdUtilisateur());
            if ($baseUser && !empty($baseUser->getLogin())) { // getLogin or another field indicating completion
                 return $this->error("Un compte est déjà associé à cet identifiant ($identifiantPermanent). Veuillez vous connecter.");
            }
             // If etudiant exists but no full user, maybe allow to continue to link/create user part.
             // This logic depends on how pre-existing etudiant records without user accounts are handled.
             // For this flow (new registration), we might assume etudiant record itself shouldn't pre-exist or should be in a specific state.
        }


        // TODO: Add logic to check if this identifiant permanent is known in the system
        // (e.g., corresponds to a pre-registered student who needs to create their online account).
        // For now, we assume it's valid if format is okay and not actively registered.

        $_SESSION['nouvel_utilisateur_ip'] = $identifiantPermanent; // Stocker l'identifiant en session
        return Response::view('public/authentification', data: [
            'ip' => $identifiantPermanent, // Keep 'ip' for view compatibility for now
            'etape' => 'envoi_email',
            'txtButton' => 'Envoyer le code de vérification'
        ], json: [
            'statut' => 'succes',
            'message' => "Identifiant permanent ($identifiantPermanent) valide. Veuillez fournir votre email."
        ]);
    }

    /**
     * Valider le format d'un identifiant permanent
     */
    private function validerFormatIdentifiantPermanent(string $identifiant): ?Response // Return type hint
    {
        if (empty($identifiant)) {
            return $this->error("L'identifiant permanent ne peut pas être vide.");
        }
        // Assumed format: 14 chars, alphanumeric. Adjust as per actual requirements.
        // Example: MIAGE2023S1001 (adjust regex if needed)
        if (!preg_match('/^[A-Z0-9]{ मिनिमम, अधिकतम}$/', $identifiant)) { // Example: 10 to 20 chars
             // return $this->error("Format de l'identifiant permanent invalide. Il doit comporter entre 10 et 20 caractères alphanumériques.");
        }
        // For the existing 14 char rule:
        if (strlen($identifiant) !== 14) {
            return $this->error("L'identifiant permanent doit comporter exactement 14 caractères.");
        }
        if (!preg_match('/^[A-Z0-9]{14}$/', $identifiant)) {
            return $this->error("L'identifiant permanent ne doit contenir que des lettres majuscules et des chiffres.");
        }
        return null; // Identifiant valide
    }

    /**
     * ÉTAPE 2 : Envoyer le code de vérification par email
     */
    private function envoyerCodeEmail(): Response
    {
        $emailUtilisateur = $this->request->getPostParams('email');
        $identifiantPermanent = $_SESSION['nouvel_utilisateur_ip'] ?? null;

        if (!$identifiantPermanent) {
            return $this->error("Session invalide ou identifiant permanent manquant. Veuillez recommencer.", redirect: '/authentification');
        }
        if (!filter_var($emailUtilisateur, FILTER_VALIDATE_EMAIL)) {
            return $this->error("Format d'email invalide.");
        }

        // Check if email is already in use by another active account
        $utilisateurParEmail = $this->utilisateurDAO->rechercher(['email' => $emailUtilisateur]);
        if (!empty($utilisateurParEmail)) {
            return $this->error("Cette adresse email est déjà utilisée par un autre compte.");
        }


        $_SESSION['nouvel_utilisateur_code_verification'] = $this->genererCodeVerification();
        $_SESSION['nouvel_utilisateur_email'] = $emailUtilisateur;
        $_SESSION['nouvel_utilisateur_code_timestamp'] = time(); // Store timestamp for expiration check

        try {
            $this->mail->to($emailUtilisateur, $identifiantPermanent) // Name can be identifiant
                ->subject('Votre code de vérification')
                ->view('emails/verification_code', [ // Ensure this view exists
                    'verificationCode' => $_SESSION['nouvel_utilisateur_code_verification'],
                    'expirationTime' => date('H:i', strtotime('+10 minutes')), // Display format
                ])
                ->send();
        } catch (\Exception $e) {
            // Log the actual error $e->getMessage() for server admin
            return $this->error("Erreur lors de l'envoi de l'email de vérification. Veuillez réessayer plus tard.");
        }

        return Response::view('public/authentification', data: [
            'ip' => $identifiantPermanent,
            'email' => $emailUtilisateur,
            'etape' => 'verification_code',
            'txtButton' => 'Vérifier le code'
        ], json: [
            'statut' => 'succes',
            'message' => "Un code de vérification a été envoyé à : $emailUtilisateur"
        ]);
    }

    private function genererCodeVerification(): string
    {
        return (string)random_int(100000, 999999); // Use cryptographically secure generator
    }

    /**
     * ÉTAPE 3 : Vérifier le code saisi par l'utilisateur
     */
    private function verifierCode(): Response
    {
        $codeSaisi = $this->request->getPostParams('code');
        $codeStocke = $_SESSION['nouvel_utilisateur_code_verification'] ?? null;
        $timestampCode = $_SESSION['nouvel_utilisateur_code_timestamp'] ?? 0;
        $identifiantPermanent = $_SESSION['nouvel_utilisateur_ip'] ?? null;
        $emailUtilisateur = $_SESSION['nouvel_utilisateur_email'] ?? null;

        if (!$identifiantPermanent || !$emailUtilisateur || !$codeStocke) {
             return $this->error("Session invalide. Veuillez recommencer le processus.", redirect: '/authentification');
        }

        $erreurFormat = $this->validerFormatCodeSaisi($codeSaisi);
        if ($erreurFormat !== null) {
            return $erreurFormat;
        }

        // Check code expiration (e.g., 10 minutes)
        if (time() - $timestampCode > (10 * 60)) {
            // Clear expired code session variables
            unset($_SESSION['nouvel_utilisateur_code_verification'], $_SESSION['nouvel_utilisateur_code_timestamp']);
            return $this->error("Le code de vérification a expiré. Veuillez demander un nouveau code.");
        }

        if ($codeSaisi !== $codeStocke) {
            return $this->error("Le code de vérification saisi est incorrect.");
        }

        // Code is correct, clear it from session to prevent reuse
        unset($_SESSION['nouvel_utilisateur_code_verification'], $_SESSION['nouvel_utilisateur_code_timestamp']);
        $_SESSION['nouvel_utilisateur_code_verifie'] = true; // Mark as verified

        return Response::view('public/authentification', data: [
            'ip' => $identifiantPermanent,
            'email' => $emailUtilisateur,
            'etape' => 'enregistrement_compte',
            'txtButton' => 'Créer mon compte'
        ], json: [
            'statut' => 'succes',
            'message' => "Code de vérification validé."
        ]);
    }

    private function validerFormatCodeSaisi(string $codeSaisi): ?Response
    {
        if (!preg_match('/^\d{6}$/', $codeSaisi)) {
            return $this->error("Le code de vérification doit comporter 6 chiffres.");
        }
        return null;
    }

    /**
     * ÉTAPE 4 : Créer le compte étudiant (Utilisateur + Etudiant)
     */
    private function creerCompteEtudiant(): Response
    {
        $identifiantPermanent = $_SESSION['nouvel_utilisateur_ip'] ?? null;
        $email = $_SESSION['nouvel_utilisateur_email'] ?? null;
        $codeVerifie = $_SESSION['nouvel_utilisateur_code_verifie'] ?? false;

        if (!$identifiantPermanent || !$email || !$codeVerifie) {
            return $this->error("Session invalide ou processus de vérification non complété. Veuillez recommencer.", redirect: '/authentification');
        }

        $nom = trim($this->request->getPostParams('nom')); // Add form fields for nom, prenom etc.
        $prenom = trim($this->request->getPostParams('prenom'));
        $motDePasse = $this->request->getPostParams('password');
        $confirmationMotDePasse = $this->request->getPostParams('password_confirm');

        if (empty($nom) || empty($prenom) || empty($motDePasse)) {
            return $this->error("Veuillez remplir tous les champs : nom, prénom et mot de passe.");
        }
        if ($motDePasse !== $confirmationMotDePasse) {
            return $this->error("Les mots de passe ne correspondent pas.");
        }
        if (strlen($motDePasse) < 8) { // Example password policy
            return $this->error("Le mot de passe doit comporter au moins 8 caractères.");
        }

        // Get default category and group for new students
        $categorieEtudiant = $this->categorieUtilisateurDAO->recupererParSlug('etudiant'); // Assumes slug 'etudiant' exists
        $groupeEtudiant = $this->groupeUtilisateurDAO->recupererParNom('Étudiants'); // Assumes group 'Étudiants' exists

        if (!$categorieEtudiant || !$groupeEtudiant) {
            // Log this critical error for admin
            return $this->error("Erreur de configuration système. Impossible de créer le compte.");
        }

        $donneesUtilisateur = [
            'nom' => $nom,
            'prenom' => $prenom,
            'login' => $identifiantPermanent, // Using identifiant_permanent as login
            'email' => $email,
            'mot_de_passe' => password_hash($motDePasse, PASSWORD_DEFAULT),
            'id_categorie_utilisateur' => $categorieEtudiant->getId(),
            'id_groupe_utilisateur' => $groupeEtudiant->getId(),
            // 'telephone', 'adresse' can be added later or made optional
        ];

        $this->pdo->beginTransaction();
        try {
            // Check again if login (identifiantPermanent) or email already exists to prevent race conditions
            if (!empty($this->utilisateurDAO->rechercher(['login' => $donneesUtilisateur['login']]))) {
                 throw new \Exception("Cet identifiant est déjà utilisé comme login.");
            }
            if (!empty($this->utilisateurDAO->rechercher(['email' => $donneesUtilisateur['email']]))) {
                 throw new \Exception("Cette adresse email est déjà enregistrée.");
            }

            if (!$this->utilisateurDAO->creer($donneesUtilisateur)) {
                throw new \Exception("Erreur lors de la création de l'utilisateur.");
            }
            $idUtilisateur = $this->pdo->lastInsertId();

            // Create the Etudiant specific record
            // num_matricule is the identifiantPermanent
            // Other Etudiant fields like date_naissance, lieu_naissance, id_niveau_etude
            // would need to be collected or set to defaults if not available at this stage.
            $donneesEtudiant = [
                'id_utilisateur' => $idUtilisateur,
                'num_matricule' => $identifiantPermanent,
                // 'date_naissance' => $this->request->getPostParams('date_naissance'), // Collect if needed
                // 'lieu_naissance' => $this->request->getPostParams('lieu_naissance'), // Collect if needed
                // 'id_niveau_etude' => $this->request->getPostParams('id_niveau_etude'), // Collect if needed
            ];
            if (!$this->etudiantDAO->creer($donneesEtudiant)) {
                throw new \Exception("Erreur lors de la création des détails de l'étudiant.");
            }

            $this->pdo->commit();

            // Clear session variables related to registration
            unset(
                $_SESSION['nouvel_utilisateur_ip'],
                $_SESSION['nouvel_utilisateur_email'],
                $_SESSION['nouvel_utilisateur_code_verifie']
            );

            // TODO: Log in the user automatically by setting session variables for logged-in state
            // $_SESSION['user_id'] = $idUtilisateur;
            // $_SESSION['user_login'] = $donneesUtilisateur['login'];
            // etc.

            return $this->succes("Compte étudiant créé avec succès pour $nom $prenom. Vous pouvez maintenant vous connecter.", redirect: '/'); // Redirect to login or dashboard
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return $this->error("Erreur lors de la création du compte : " . $e->getMessage());
        }
    }

    /**
     * Page d'accueil - première visite
     */
    public function index(): Response
    {
        // Clear any stale registration session data if user revisits the initial page
        unset(
            $_SESSION['nouvel_utilisateur_ip'],
            $_SESSION['nouvel_utilisateur_email'],
            $_SESSION['nouvel_utilisateur_code_verification'],
            $_SESSION['nouvel_utilisateur_code_timestamp'],
            $_SESSION['nouvel_utilisateur_code_verifie']
        );

        return Response::view('public/authentification', [
            'etape' => 'verification_ip', // Start with IP verification
            'txtButton' => 'Vérifier mon identifiant'
        ]);
    }
}
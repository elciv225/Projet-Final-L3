<?php

namespace App\Controllers\Public;

use App\Controllers\Controller;
use System\Http\Response;
use System\Mail\Mail;

class InscriptionController extends Controller
{

    private Mail $mail;
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
            case 'verification':
                return $this->verifierIP();
            case 'envoi_email':
                return $this->envoyerCodeEmail();
            case 'verification_code':
                return $this->verifierCode();
            case 'enregistrement':
                return $this->creerCompte();
        }

        return $this->error("Étape non reconnue: $etapeActuelle");
    }

    /**
     * ÉTAPE 1 : Vérifier l'identifiant permanent (IP)
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

        $_SESSION['utilisateur']['ip'] = $identifiantPermanent; // Stocker l'IP en session
        // Passer à l'étape suivante
        return Response::view('public/inscription', data: [
            'ip' => $identifiantPermanent,
            'etape' => 'envoi_email',
            'txtButton' => 'Envoyer le code'
        ], json: [
            'statut' => 'succes',
            'message' => "Identifiant permanent valide",
            'etape' => 'envoi_email'
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
        } elseif (strlen($ip) < 14) {
            return $this->error("L'identifiant permanent doit faire au moins 14 caractères");
        } elseif (strlen($ip) > 14) {
            return $this->error("L'identifiant permanent ne doit pas dépasser 14 caractères");
        } elseif (!preg_match('/^[A-Z0-9]{14}$/', $ip)) {
            return $this->error("L'identifiant permanent doit contenir uniquement des lettres majuscules et des chiffres");
        }
        return null; // IP valide
    }

    /**
     * ÉTAPE 2 : Envoyer le code de vérification par email
     */
    private function envoyerCodeEmail(): Response
    {
        $emailUtilisateur = $this->request->getPostParams('email');

        // Validation de l'email
        if (empty($emailUtilisateur)) {
            return $this->error("L'adresse email ne peut pas être vide");
        }

        if (!filter_var($emailUtilisateur, FILTER_VALIDATE_EMAIL)) {
            return $this->error("L'adresse email n'est pas valide");
        }

        // Génération d'un code de vérification à 6 chiffres
        $_SESSION['code_verification'] = $this->genererCodeVerification();

        // Configuration de l'expiration du code (10 minutes)
        $_SESSION['code_expiration'] = time() + (10 * 60);

        // Envoyer le code de vérification par email
        try {
            $this->mail->to($emailUtilisateur, $_SESSION['utilisateur']['ip'])
                ->subject('Code de vérification')
                ->view('emails/verification_code', [
                    'verificationCode' => $_SESSION['code_verification'],
                    'expirationTime' => date('H:i:s', strtotime('+10 minutes')),
                ])
                ->send();
        } catch (\Exception $e) {
            return $this->error("Erreur lors de l'envoi de l'email : " . $e->getMessage());
        }

        // Stocker l'email en session
        $_SESSION['utilisateur']['email'] = $emailUtilisateur;

        // Passer à l'étape suivante
        return Response::view('public/inscription', data: [
            'ip' => $_SESSION['utilisateur']['ip'],
            'email' => $emailUtilisateur,
            'etape' => 'verification_code',
            'txtButton' => 'Vérifier le code'
        ], json: [
            'statut' => 'succes',
            'message' => "Un code de vérification a été envoyé à l'adresse : $emailUtilisateur",
            'etape' => 'verification_code'
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
     * ÉTAPE 3 : Vérifier le code saisi par l'utilisateur
     */
    private function verifierCode(): Response
    {
        // Code saisi par l'utilisateur
        $codeSaisiParUtilisateur = $this->request->getPostParams('code');
        // Récupérer le code de vérification envoyé par email
        $codeVerification = $_SESSION['code_verification'] ?? null;
        $codeExpiration = $_SESSION['code_expiration'] ?? null;

        // Vérifier si le code a expiré
        if ($codeExpiration && time() > $codeExpiration) {
            return $this->error("Le code de vérification a expiré. Veuillez recommencer le processus.");
        }

        $erreur = $this->verifierCodeSaisi($codeSaisiParUtilisateur);
        if ($erreur !== null) {
            return $erreur; // Retourne l'erreur si le code saisi est invalide
        }

        // Vérifier que le code saisi correspond au code envoyé
        if ($codeSaisiParUtilisateur !== $codeVerification) {
            return $this->error("Le code de vérification saisi ne correspond pas au code envoyé");
        }

        // Passer à l'étape suivante
        return Response::view('public/inscription', data: [
            'ip' => $_SESSION['utilisateur']['ip'],
            'email' => $_SESSION['utilisateur']['email'],
            'etape' => 'enregistrement',
            'txtButton' => 'Créer le compte'
        ], json: [
            'statut' => 'succes',
            'message' => "Code de vérification validé avec succès",
            'etape' => 'enregistrement'
        ]);
    }

    /**
     * Vérifier le code de vérification saisi par l'utilisateur
     */
    private function verifierCodeSaisi(string $codeSaisi): Response|null
    {
        // Vérifier que le code ne contient que des chiffres
        if (!preg_match('/^\d{6}$/', $codeSaisi)) {
            return $this->error("Le code de vérification doit être un nombre à 6 chiffres");
        }
        if ($codeSaisi === '') {
            return $this->error("Le code de vérification ne peut pas être vide");
        }
        return null;
    }

    /*****************************************************************
     * MÉTHODES UTILITAIRES
     ****************************************************************/

    /**
     * ÉTAPE 4 : Créer le compte utilisateur
     */
    private function creerCompte(): Response
    {
        // Récupérer les mots de passe
        $motDePasse = $this->request->getPostParams('password');
        $confirmationMotDePasse = $this->request->getPostParams('password_confirm');

        // Vérifier que les mots de passe correspondent
        if ($motDePasse !== $confirmationMotDePasse) {
            return $this->error("Les mots de passe ne correspondent pas");
        }

        // Vérifier la complexité du mot de passe
        if (strlen($motDePasse) < 8) {
            return $this->error("Le mot de passe doit contenir au moins 8 caractères");
        }

        if (!preg_match('/[A-Z]/', $motDePasse) || !preg_match('/[a-z]/', $motDePasse) || !preg_match('/[0-9]/', $motDePasse)) {
            return $this->error("Le mot de passe doit contenir au moins une majuscule, une minuscule et un chiffre");
        }


        // On modifie juste le mot de passe qui lui ai assigné et l'email aussi.
        // Et on créer la variable de sa session

        // Rediriger vers l'espace utilisateur
        return Response::success("Compte créé avec succès. Bienvenue dans votre espace personnel.", redirect: '/espace-utilisateur');
    }

    /**
     * Page d'accueil - première visite
     */
    public function index(): Response
    {
        // Par défaut, on affiche la vue par étapes
        if ($this->request->getPostParams('mode') === 'standard') {
            // Si le mode standard est demandé, on montre le formulaire classique
            return Response::view('public/inscription');
        }

        return Response::view('public/inscription', [
            'etape' => 'verification'
        ]);
    }

}
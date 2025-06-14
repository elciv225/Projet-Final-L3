<?php

namespace App\Controllers;

use System\Http\Response;
use System\Mail\Mail;

class AuthentificationPublicController extends Controller
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

        return $this->error("A faire : $etapeActuelle");
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
        return Response::view('public/authentification', data: [
            'ip' => $identifiantPermanent,
            'etape' => 'envoi_email',
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
        // Génération d'un code de vérification
        $_SESSION['code_verification'] = $this->genererCodeVerification();
        // envoyer le code de vérification par email
        try {
            $this->mail->to($emailUtilisateur, $_SESSION['utilisateur']['ip'])
                ->subject('Code de vérification')
                ->view('emails/verification_code', [
                    'verificationCode' => $_SESSION['code_verification'],
                    'expirationTime' => date('H:i:s', strtotime('+10 minutes')),
                ])
                ->send();
        } catch (\Exception $e) {
            $this->error("Erreur lors de l'envoi de l'email : " . $e->getMessage());
        }
        $_SESSION['utilisateur']['email'] = $emailUtilisateur; // Stocker l'email en session
        return Response::view('public/authentification', data: [
            'ip' => $_SESSION['utilisateur']['ip'],
            'email' => $emailUtilisateur,
            'etape' => 'verification_code',
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
     * ÉTAPE 3 : Vérifier le code saisi par l'utilisateur
     * @throws \Exception
     */
    private function verifierCode(): Response
    {
        // Code saisi par l'utilisateur
        $codeSaisiParUtilisateur = $this->request->getPostParams('code');
        // Récupérer le code de vérification envoyé par email
        $codeVerification = $_SESSION['code_verification'] ?? null;

        $erreur = $this->verifierCodeSaisi($codeSaisiParUtilisateur);
        if ($erreur !== null) {
            return $erreur; // Retourne l'erreur si le code saisi est invalide
        }
        // Vérifier que le code saisi correspond au code envoyé

        if ($codeSaisiParUtilisateur !== $codeVerification) {
            return $this->error("Le code de vérification saisi ne correspond pas au code envoyé");
        }

        return Response::view('public/authentification', data: [
            'ip' => $_SESSION['utilisateur']['ip'],
            'email' => $_SESSION['utilisateur']['email'],
            'etape' => 'enregistrement',
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

        // TODO: Enregistrer l'utilisateur en base de données

        return $this->succes("Compte créé avec succès");
    }

    /**
     * Page d'accueil - première visite
     */
    public
    function index(): Response
    {
        return Response::view('public/authentification', [
            'etape' => 'verification',
            'txtButton' => 'Vérifier le statut'
        ]);
    }

}
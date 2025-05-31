<?php

namespace App\Controllers;

use System\Http\Response;

class AuthentificationController extends Controller
{
    public function __construct()
    {
        parent::__construct();
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
    private
    function verifierIP(): Response
    {
        // Récupérer l'IP saisie par l'utilisateur
        $identifiantPermanent = $this->request->getPostParams('ip');

        // Valider l'IP
        if (!$this->estIPValide($identifiantPermanent)) {
            return $this->error("Identifiant permanent invalide");
        }
        // Passer à l'étape suivante
        return Response::view('authentification', [
            'ip' => $identifiantPermanent,
            'etape' => 'envoi_email',
            'txtButton' => 'Envoyer le code'
        ]);
    }

    /**
     * Valider un identifiant permanent
     */
    private
    function estIPValide(string $ip): bool
    {
        // TODO: Implémenter votre logique de validation
        return !empty($ip) && strlen($ip) >= 3;
    }

    /**
     * ÉTAPE 2 : Envoyer le code de vérification par email
     */
    private
    function envoyerCodeEmail(): Response
    {
        // Récupérer l'email saisi
        $emailUtilisateur = $this->request->getPostParams('email');

        return $this->info("A faire : Envoyer un email à $emailUtilisateur avec le code de vérification");
    }

    /**
     * ÉTAPE 3 : Vérifier le code saisi par l'utilisateur
     */
    private
    function verifierCode(): Response
    {
        // Code saisi par l'utilisateur
        $codeSaisiParUtilisateur = $this->request->getPostParams('code');
        // Générer un code de vérification (pour l'exemple, on utilise un code fixe)
        $codeVerification = $this->genererCodeVerification(); // Nope on doit conserver le code envoyé par email
        return $this->info("A faire : Vérifier le code saisi ($codeSaisiParUtilisateur) avec le code envoyé ($codeVerification)");
    }

    /**
     * ÉTAPE 4 : Créer le compte utilisateur
     */
    private
    function creerCompte(): Response
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

    /*****************************************************************
     * MÉTHODES UTILITAIRES
     ****************************************************************/

    /**
     * Page d'accueil - première visite
     */
    public
    function index(): Response
    {
        return Response::view('authentification', [
            'etape' => 'verification',
            'txtButton' => 'Vérifier le statut'
        ]);
    }

    /**
     * Générer un code de vérification à 6 chiffres
     */
    private
    function genererCodeVerification(): string
    {
        return (string)rand(100000, 999999);
    }

}
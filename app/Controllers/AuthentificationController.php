<?php

namespace App\Controllers;

use System\Http\Request;
use System\Http\Response;

class AuthentificationController extends Controller
{

    /**
     * Fonction Principale de traitement de l'authentification
     */
    public function authentification(): Response
    {
        if ($this->request->getPostParams('verification')) {
            // Vérification de l'IP
            return $this->verificationIP();
        } elseif ($this->request->getPostParams('connexion')) {
            // Connexion de l'utilisateur
            return $this->connexion();
        } elseif ($this->request->getPostParams('envoieMail')) {
            // Envoi du mail de vérification
            return $this->envoieMail();
        } elseif ($this->request->getPostParams('inscription')) {
            // Inscription de l'utilisateur
            return $this->inscription();
        }
        // Si aucune action n'est effectuée, on retourne une erreur
        return $this->error("Aucune action n'a été effectuée.");
    }

    /**
     * Fonction de vérification du statut de l'utilisateur
     * @return Response
     */
    private function verificationIP(): Response
    {
        // Un if pour vérifier si l'IP est valide et autorisé à Soumettre un formulaire
        // Cas valide : L'IP est valide et autorisé à Soumettre un formulaire
        $_SESSION['auth_etudiant'] = [
            'nouvelleConnexion' => true,
            'messageEnvoye' => false,
            'etapeAuthentification' => 'envoieMail',
            'txtButton' => "Envoyer le code de vérification",
            'ip' => $this->request->getPostParams('ip'), // Récupération de l'IP depuis le formulaire
            'email' => '', // Récupération de l'email si présent
            'code_verification' => '', // Initialisation du code de vérification
            'password' => '', // Initialisation du mot de passe
            'password_confirm' => '' // Initialisation de la confirmation du mot de passe
        ];

        return Response::json(
            [
                'statut' => 'succes',
                'message' => "Le statut de l'étudiant est vérifié. Vous pouvez maintenant envoyer le code de vérification.",
                'redirect' => '/authentification', // Redirection vers la page d'authentification
                'redirectDelay' => 0 // Délai de redirection en millisecondes
            ]
        );

        //return $this->info("Le statut de l'étudiant est vérifié. Vous pouvez maintenant envoyer le code de vérification.");
    }

    /**
     * Fonction de connexion
     * @return Response
     */
    private function connexion(): Response
    {
        // Simuler une connexion réussie
        return Response::json(
            [
                'statut' => 'succes',
                'message' => 'Connexion réussie. Un e-mail de confirmation a été envoyé.',
                'redirect' => '/', // Redirection vers la page d'authentification
                'redirectDelay' => 1500 // Délai de redirection en millisecondes
            ]
        );
    }

    /**
     * Fonction d'envoi du mail de vérification
     * @return Response
     */
    private function envoieMail(): Response
    {
        // Ici, vous pouvez ajouter la logique d'envoi de l'e-mail de vérification
        // Par exemple, envoyer un e-mail avec un lien de confirmation.

        // Simuler un envoi d'e-mail réussi
        return Response::view('authentification', [
            'nouvelleConnexion' => false, // Indique que c'est une nouvelle connexion
            'etapeAuthentification' => 'connexion', // Indique l'étape suivante de l'authentification
            'txtButton' => "S'inscrire" // Texte du bouton
        ]);
    }

    /**
     * Fonction d'inscription de l'utilisateur
     * @return Response
     */
    private function inscription(): Response
    {
        // Ici, vous pouvez ajouter la logique d'inscription de l'utilisateur
        // Par exemple, enregistrer les informations dans la base de données
        // et envoyer un e-mail de confirmation.

        // Simuler une inscription réussie
        return Response::json(
            [
                'statut' => 'succes',
                'message' => 'Inscription réussie. Un e-mail de confirmation a été envoyé.',
                'redirect' => '/authentification', // Redirection vers la page d'authentification
                'redirectDelay' => 1500 // Délai de redirection en millisecondes
            ]
        );
    }

    public function index(): Response
    {
        return Response::view('authentification', [
            'etapeAuthentification' => 'verification',
            'txtButton' => "Vérifié le statut" // Texte du bouton
        ]);
    }

}
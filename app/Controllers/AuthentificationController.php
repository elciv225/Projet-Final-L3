<?php

namespace App\Controllers;


use System\Http\Response;
use System\Mail\Mail;
use App\Dao\UtilisateurDAO;
class AuthentificationController extends  Controller
{

    protected Mail $mail;
    protected UtilisateurDAO $dao;

    /**
     * Configuration de l'email pour l'envoi de code de vérification
     */
    private array $mailConfig = [
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
        $this->dao = new UtilisateurDAO($this->pdo);
    }

    public function index(): Response
    {
        // Vérifier s'il y a une action spécifique demandée
        $action = $this->request->getGetParams('action', '');
        $token = $this->request->getGetParams('token', '');

        if ($action === 'reinitialiser' && !empty($token)) {
            return $this->afficherFormulaireReinitialisation();
        }

        return Response::view('authentification', [
            'title' => 'Authentification',
        ]);
    }

    public function authentification(): Response
    {
        $login = $this->request->getPostParams('login');
        $password = $this->request->getPostParams('password');

        // Vérifier si les champs sont remplis
        if (empty($login) || empty($password)) {
            return $this->error("Login et mot de passe sont obligatoires");
        }

        // Rechercher l'utilisateur par login
        $utilisateur = $this->dao->rechercher(['login' => $login]);

        if (empty($utilisateur)) {
            return $this->error("Login incorrect");
        }

        // Récupérer le premier utilisateur trouvé
        $user = $utilisateur[0];
        
        // Vérifier le mot de passe hashé
        $motDePasseHash = $user->getMotDePasse();
        
        // Vérifier le mot de passe (support pour SHA256 et password_hash)
        $passwordCorrect = false;
        
        // Vérifier d'abord avec password_verify (pour les nouveaux comptes)
        if (password_verify($password, $motDePasseHash)) {
            $passwordCorrect = true;
        }
        // Sinon vérifier avec SHA256 (pour les comptes existants)
        elseif (hash('sha256', $password) === $motDePasseHash) {
            $passwordCorrect = true;
        }
        
        if (!$passwordCorrect) {
            return $this->error("Mot de passe incorrect");
        }

        // Stocker les informations utilisateur en session
        $_SESSION['utilisateur_connecte'] = [
            'id' => $user->getId(),
            'nom' => $user->getNom(),
            'prenoms' => $user->getPrenoms(),
            'email' => $user->getEmail(),
            'login' => $user->getLogin(),
            'groupe_utilisateur_id' => $user->getGroupeUtilisateurId(),
            'type_utilisateur_id' => $user->getTypeUtilisateurId()
        ];

        // Redirection selon le groupe utilisateur
        $redirectUrl = '/index';
        if ($user->getGroupeUtilisateurId() === 'GRP_ETUDIANTS') {
            $redirectUrl = '/espace-utilisateur';
        }

        return Response::success("Connexion réussie", redirect: $redirectUrl);
    }

    /**
     * Traite la demande de réinitialisation de mot de passe
     */
    public function motDePasseOublie(): Response
    {
        $email = $this->request->getPostParams('email');

        // Vérifier si l'email existe dans la base de données
        $utilisateur = $this->dao->rechercher(['email' => $email]);

        if (empty($utilisateur)) {
            return $this->error("Aucun compte n'est associé à cette adresse email");
        }

        // Générer un token unique pour la réinitialisation
        $token = bin2hex(random_bytes(32));

        // Stocker le token en session (dans un système réel, on le stockerait en BDD avec une date d'expiration)
        $_SESSION['reset_token'] = [
            'token' => $token,
            'email' => $email,
            'expiration' => time() + 3600 // 1 heure
        ];

        // Envoyer un email avec le lien de réinitialisation
        try {
            $lienReinitialisation = $_SERVER['HTTP_ORIGIN'] . "/authentification?action=reinitialiser&token=" . $token;

            $this->mail->to($email)
                ->subject('Réinitialisation de votre mot de passe')
                ->view('emails/reinitialisation_mot_de_passe', [
                    'lienReinitialisation' => $lienReinitialisation,
                    'expirationTime' => date('H:i', time() + 3600),
                ])
                ->send();

            return Response::success("Un email de réinitialisation a été envoyé à l'adresse indiquée.");
        } catch (\Exception $e) {
            return $this->error("Erreur lors de l'envoi de l'email : " . $e->getMessage());
        }
    }

    /**
     * Affiche le formulaire de réinitialisation de mot de passe
     */
    public function afficherFormulaireReinitialisation(): Response
    {
        $token = $this->request->getGetParams('token', '');

        // Vérifier la validité du token
        if (empty($_SESSION['reset_token']) || $_SESSION['reset_token']['token'] !== $token || $_SESSION['reset_token']['expiration'] < time()) {
            return Response::view('authentification', [
                'error' => 'Le lien de réinitialisation est invalide ou a expiré.'
            ]);
        }

        return Response::view('reinitialisation_mot_de_passe', [
            'token' => $token
        ]);
    }

    /**
     * Traite la réinitialisation du mot de passe
     */
    public function reinitialiserMotDePasse(): Response
    {
        $token = $this->request->getPostParams('token');
        $nouveauMotDePasse = $this->request->getPostParams('nouveau_mot_de_passe');
        $confirmation = $this->request->getPostParams('confirmation_mot_de_passe');

        // Vérifier la validité du token
        if (empty($_SESSION['reset_token']) || $_SESSION['reset_token']['token'] !== $token || $_SESSION['reset_token']['expiration'] < time()) {
            return $this->error('Le lien de réinitialisation est invalide ou a expiré.');
        }

        // Vérifier que les mots de passe correspondent
        if ($nouveauMotDePasse !== $confirmation) {
            return $this->error('Les mots de passe ne correspondent pas.');
        }

        // Récupérer l'email associé au token
        $email = $_SESSION['reset_token']['email'];

        // Récupérer l'utilisateur correspondant
        $utilisateurs = $this->dao->rechercher(['email' => $email]);
        if (empty($utilisateurs)) {
            return $this->error("Utilisateur introuvable.");
        }

        $utilisateur = $utilisateurs[0];

        // Hasher le nouveau mot de passe avec SHA256 (pour correspondre au schéma DB)
        $motDePasseHash = hash('sha256', $nouveauMotDePasse);
        $utilisateur->setMotDePasse($motDePasseHash);

        // Enregistrer les modifications
        if ($this->dao->mettreAJour($utilisateur->getId(), $utilisateur)) {
            // Supprimer le token de la session
            unset($_SESSION['reset_token']);

            return Response::success('Votre mot de passe a été réinitialisé avec succès.', redirect: '/authentification');
        } else {
            return $this->error('Une erreur est survenue lors de la réinitialisation du mot de passe.');
        }
    }
}
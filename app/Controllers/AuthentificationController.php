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
        return Response::view('authentification', [
            'title' => 'Authentification',
        ]);
    }

    public function authentification(): Response
    {
        $login = $this->request->getPostParams('login');
        $password = $this->request->getPostParams('password');

        $data = [
            'login' => $login,
            'password' => $password
        ];

        $loginExiste = $this->dao->rechercher(['login' => $data["login"]]);

        if (!empty($loginExiste)) {
            $passwordCorrecte = $this->dao->rechercher($data);
            if (!empty($passwordCorrecte)) {
                return Response::success("Mot de passe correcte", redirect: '/index');
            } else {
                return $this->error("Mot de passe incorrect");
            }
        } else {
            return $this->error("Login incorrect");
        }

    }


}
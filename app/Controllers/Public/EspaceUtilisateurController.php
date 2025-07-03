<?php

namespace App\Controllers\Public;

use App\Controllers\Controller;
use System\Http\Response;

class EspaceUtilisateurController extends Controller
{
    public function index(): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['utilisateur_connecte'])) {
            return Response::redirect('/authentification');
        }

        // Récupérer les données de l'utilisateur connecté
        $utilisateur = $_SESSION['utilisateur_connecte'];
        
        // Préparer les données pour la vue
        $donneesUtilisateur = [
            'id' => $utilisateur['id'],
            'nom' => $utilisateur['nom'],
            'prenoms' => $utilisateur['prenoms'],
            'email' => $utilisateur['email'],
            'login' => $utilisateur['login'],
            'groupe_utilisateur_id' => $utilisateur['groupe_utilisateur_id'],
            'type_utilisateur_id' => $utilisateur['type_utilisateur_id'],
            'nom_complet' => $utilisateur['prenoms'] . ' ' . $utilisateur['nom'],
            'initiales' => strtoupper(substr($utilisateur['prenoms'], 0, 1) . substr($utilisateur['nom'], 0, 1))
        ];

        return Response::view('public/espace-utilisateur', [
            'utilisateur' => $donneesUtilisateur,
            'title' => 'Espace Utilisateur - ' . $donneesUtilisateur['nom_complet']
        ]);
    }
}
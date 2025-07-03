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

    /**
     * Met à jour les informations de l'utilisateur connecté
     * 
     * @return Response
     */
    public function mettreAJour(): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['utilisateur_connecte'])) {
            return Response::json([
                'success' => false,
                'message' => 'Vous devez être connecté pour effectuer cette action'
            ]);
        }

        // Récupérer les données du formulaire
        $nom = $_POST['nom'] ?? '';
        $prenoms = $_POST['prenoms'] ?? '';
        $email = $_POST['email'] ?? '';

        // Validation basique
        if (empty($nom) || empty($prenoms) || empty($email)) {
            return Response::json([
                'success' => false,
                'message' => 'Tous les champs sont obligatoires'
            ]);
        }

        // Mettre à jour les données de l'utilisateur en session
        $_SESSION['utilisateur_connecte']['nom'] = $nom;
        $_SESSION['utilisateur_connecte']['prenoms'] = $prenoms;
        $_SESSION['utilisateur_connecte']['email'] = $email;
        $_SESSION['utilisateur_connecte']['nom_complet'] = $prenoms . ' ' . $nom;
        $_SESSION['utilisateur_connecte']['initiales'] = strtoupper(substr($prenoms, 0, 1) . substr($nom, 0, 1));

        // Dans une application réelle, il faudrait également mettre à jour la base de données
        // $this->utilisateurModel->update($id, ['nom' => $nom, 'prenoms' => $prenoms, 'email' => $email]);

        return Response::json([
            'success' => true,
            'message' => 'Vos informations ont été mises à jour avec succès',
            'utilisateur' => [
                'nom' => $nom,
                'prenoms' => $prenoms,
                'email' => $email,
                'nom_complet' => $prenoms . ' ' . $nom,
                'initiales' => strtoupper(substr($prenoms, 0, 1) . substr($nom, 0, 1))
            ]
        ]);
    }

    /**
     * Déconnecte l'utilisateur en détruisant la session
     * 
     * @return Response
     */
    public function deconnexion(): Response
    {
        // Détruire la session
        session_unset();
        session_destroy();

        // Rediriger vers la page d'authentification
        return Response::redirect('/authentification');
    }
}

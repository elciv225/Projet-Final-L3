<?php

namespace App\Controllers\MenuViews;

use App\Controllers\Controller;
use System\Http\Response;

class HistoriquePersonnelController extends Controller
{
    public function index(): Response
    {
        $data = [
            'title' => 'Gestion des Historiques',
            'heading' => 'Enseignants',
            'content' => 'Gestion du corps enseignant de l\'établissement.'
        ];

        // Toujours retourner la vue de menu_views, jamais la page complète
        return Response::view('menu_views/historique-personnel', $data);
    }

    public function chargerPersonnelPourDonneeHistorique(): Response
    {
        $typeUtilisateur = $this->request->getPostParams('type-utilisateur');

        switch ($typeUtilisateur) {
            case 'enseignant':
                $listeUtilisateur = [
                    [
                        'utilisateur_id' => 1,
                        'nom-prenom' => 'Enseignant 1'
                    ],
                    [
                        'utilisateur_id' => 2,
                        'nom-prenom' => 'Enseignant 2'
                    ]
                ];
                break;
            case 'personnel_administratif':
                $listeUtilisateur = [
                    [
                        'utilisateur_id' => 1,
                        'nom-prenom' => 'Personnel 1'
                    ],
                    [
                        'utilisateur_id' => 2,
                        'nom-prenom' => 'Personnel 2'
                    ]
                ];
                break;
            default:
                $listeUtilisateur = [];
                $this->error("Aucun type d'utilisateur");
        }

        $data = [
            'title' => 'Gestion des Historiques',
            'heading' => 'Enseignants',
            'content' => 'Gestion du corps enseignant de l\'établissement.',
            'listeUtilisateur' => $listeUtilisateur,
            'selectActive' => true,
            'recherche' => true
        ];

        // Toujours retourner la vue de menu_views, jamais la page complète
        return Response::view(
            'menu_views/historique-personnel',
            $data,
            json: [
                'statut' => 'succes',
                'message' => 'Données chargé'
            ]
        );
    }

    public function chargerDonneeHistoriquePersonnel(): Response
    {
        $typeHistorique = $this->request->getPostParams('type-historique');
        $utilisateurId = $this->request->getPostParams('utilisateur');

        // Valider les entrées
        if (empty($typeHistorique) || empty($utilisateurId)) {
            return Response::json([
                'statut' => 'error',
                'message' => 'Veuillez remplir tous les champs'
            ]);
        }

        $entete = [];
        $corps = [];
        $donneesChargees = true; // Indicateur que des données ont été recherchées

        // Simuler la récupération des données en fonction du type d'historique
        switch ($typeHistorique) {
            case 'fonction':
                $entete = ['Fonction', 'Date de début', 'Date de fin'];
                // Données d'exemple pour l'historique des fonctions de l'utilisateur $utilisateurId
                if ($utilisateurId == 1) { // Utiliser == au lieu de === pour la comparaison
                    $corps = [
                        ['Enseignant de Mathématiques', '01/09/2020', '31/08/2023'],
                        ['Responsable de niveau', '01/09/2023', 'Actuel']
                    ];
                }
                break;

            case 'grade':
                $entete = ['Grade', 'Date de début', 'Date de fin'];
                // Données d'exemple pour l'historique des grades de l'utilisateur $utilisateurId
                if ($utilisateurId == 1) {
                    $corps = [
                        ['Master en Éducation', '15/06/2020', 'diplome_master.pdf'],
                        ['Agrégation', '10/07/2023', 'agregation_2023.pdf']
                    ];
                } else if ($utilisateurId == 2) {
                    $corps = [
                        ['Licence en Histoire', '20/06/2018', 'diplome_licence.pdf']
                    ];
                }
                break;
        }

        $data = [
            'title' => 'Gestion des Historiques',
            'heading' => 'Enseignants',
            'content' => 'Gestion du corps enseignant de l\'établissement.',
            'entete' => $entete,
            'corps' => $corps,
            'donneesChargees' => $donneesChargees // Ajouter cet indicateur
        ];

        return Response::view('menu_views/historique-personnel', $data, json: [
            'statut' => 'succes',
            'message' => 'Données chargées'
        ]);
    }
}
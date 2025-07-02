<?php

namespace App\Controllers\MenuViews;

use App\Controllers\Controller;
use App\Dao\AnneeAcademiqueDAO;
use App\Dao\EtudiantDAO;
use App\Dao\InscriptionEtudiantDAO;
use App\Dao\NiveauEtudeDAO;
use System\Database\Database;
use System\Http\Response;
use PDO;

class ReglementInscriptionController extends Controller
{
    protected PDO $pdo;
    private InscriptionEtudiantDAO $inscriptionDAO;
    private EtudiantDAO $etudiantDAO;
    private AnneeAcademiqueDAO $anneeAcademiqueDAO;
    private NiveauEtudeDAO $niveauEtudeDAO;

    public function __construct()
    {
        parent::__construct();
        $this->pdo = Database::getConnection();
        $this->inscriptionDAO = new InscriptionEtudiantDAO($this->pdo);
        $this->etudiantDAO = new EtudiantDAO($this->pdo);
        $this->anneeAcademiqueDAO = new AnneeAcademiqueDAO($this->pdo);
        $this->niveauEtudeDAO = new NiveauEtudeDAO($this->pdo);
    }

    public function index(): Response
    {
        // Récupérer les données nécessaires pour la vue (années académiques, etc.)
        $anneesAcademiques = $this->anneeAcademiqueDAO->recupererTousTriesParIdDesc();


        $data = [
            'title' => 'Règlement des Frais d\'Inscription',
            'heading' => 'Règlement Inscription',
            'anneesAcademiques' => $anneesAcademiques,
            // Les inscriptions et infos étudiant seront chargées via AJAX
        ];
        return Response::view('menu_views/reglement-inscription', $data);
    }

    /**
     * Recherche un étudiant par son ID (matricule) et retourne ses informations
     * ainsi que son inscription pour l'année académique sélectionnée.
     */
    public function rechercherEtudiantPourReglement(): Response
    {
        $etudiantId = $this->request->getGetParams('etudiant_id');
        $anneeAcademiqueId = $this->request->getGetParams('annee_academique_id');

        if (!$etudiantId || !$anneeAcademiqueId) {
            return Response::json(['error' => 'ID étudiant ou année académique manquant.'], 400);
        }

        $etudiant = $this->etudiantDAO->recupererParIdAvecDetailsInscription($etudiantId, $anneeAcademiqueId);

        if (!$etudiant) {
            return Response::json(['error' => 'Étudiant non trouvé ou non inscrit pour cette année.'], 404);
        }

        // Récupérer l'historique des paiements pour cette inscription spécifique
        // Supposons que InscriptionEtudiantDAO a une méthode pour cela, sinon il faut la créer
        // ou que les infos de paiement sont déjà dans $etudiant['inscription_details']
        // Pour l'instant, on se base sur ce qui est dans $etudiant

        return Response::json(['etudiant' => $etudiant]);
    }

    /**
     * Enregistre un nouveau paiement pour une inscription.
     */
    public function enregistrerPaiement(): Response
    {
        $etudiantId = $this->request->getPostParams('etudiant_id');
        $anneeAcademiqueId = $this->request->getPostParams('annee_academique_id');
        $montantPaye = (float)($this->request->getPostParams('montant_a_payer') ?? 0);
        $datePaiement = $this->request->getPostParams('date_paiement') ?? date('Y-m-d'); // Date actuelle par défaut

        if (!$etudiantId || !$anneeAcademiqueId || $montantPaye <= 0) {
            return Response::json(['error' => 'Données de paiement invalides.'], 400);
        }

        try {
            // La procédure stockée sp_enregistrer_paiement_inscription gère la logique
            $success = $this->inscriptionDAO->enregistrerPaiementViaProcedure($etudiantId, $anneeAcademiqueId, $montantPaye, $datePaiement);

            if ($success) {
                 // Recharger les infos de l'étudiant pour mettre à jour la vue
                $etudiantMaj = $this->etudiantDAO->recupererParIdAvecDetailsInscription($etudiantId, $anneeAcademiqueId);
                return Response::json([
                    'success' => true,
                    'message' => 'Paiement enregistré avec succès.',
                    'etudiant' => $etudiantMaj
                ]);
            } else {
                // Ce cas peut arriver si la procédure retourne false sans lever d'exception PDO
                // (par exemple, si l'étudiant ou l'inscription n'est pas trouvé par la procédure)
                return Response::json(['error' => 'Échec de l\'enregistrement du paiement. Vérifiez les informations.'], 500);
            }
        } catch (PDOException $e) {
            error_log("Erreur PDO lors de l'enregistrement du paiement: " . $e->getMessage());
             if ($e->getCode() == '45000') { // Erreur métier de la procédure (ex: montant dépasse le reste à payer)
                $message = $e->getMessage();
                if (preg_match('/1644 (.*)/', $message, $matches)) {
                    $customMessage = $matches[1];
                     return Response::json(['error' => "Erreur: " . $customMessage], 400);
                }
                 return Response::json(['error' => "Erreur lors du paiement: " . $message],400);
            }
            return Response::json(['error' => 'Erreur de base de données lors de l\'enregistrement du paiement.'], 500);
        } catch (\Exception $e) {
            error_log("Erreur système lors de l'enregistrement du paiement: " . $e->getMessage());
            return Response::json(['error' => 'Erreur système.'], 500);
        }
    }

    /**
     * Récupère l'historique des paiements pour une inscription donnée.
     */
    public function recupererHistoriquePaiements(): Response
    {
        $etudiantId = $this->request->getGetParams('etudiant_id');
        $anneeAcademiqueId = $this->request->getGetParams('annee_academique_id');

        if (!$etudiantId || !$anneeAcademiqueId) {
            return Response::json(['error' => 'ID étudiant ou année académique manquant pour l\'historique.'], 400);
        }

        $historique = $this->inscriptionDAO->recupererHistoriquePaiementsPourInscription($etudiantId, $anneeAcademiqueId);

        return Response::json(['historique' => $historique]);
    }

}
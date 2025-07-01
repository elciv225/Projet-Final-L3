<?php

namespace App\Controllers\MenuViews;

use App\Controllers\Controller;
use App\Dao\EnseignantDAO;
use App\Dao\HistoriqueFonctionDAO;
use App\Dao\HistoriqueGradeDAO;
use App\Dao\PersonnelAdministratifDAO;
use App\Dao\UtilisateurDAO; // To link Enseignant/Personnel to Utilisateur if needed
use System\Http\Response;

class HistoriquePersonnelController extends Controller
{
    private EnseignantDAO $enseignantDAO;
    private PersonnelAdministratifDAO $personnelAdministratifDAO;
    private HistoriqueFonctionDAO $historiqueFonctionDAO;
    private HistoriqueGradeDAO $historiqueGradeDAO;
    private UtilisateurDAO $utilisateurDAO;

    public function __construct()
    {
        parent::__construct();
        $this->enseignantDAO = new EnseignantDAO($this->pdo);
        $this->personnelAdministratifDAO = new PersonnelAdministratifDAO($this->pdo);
        $this->historiqueFonctionDAO = new HistoriqueFonctionDAO($this->pdo);
        $this->historiqueGradeDAO = new HistoriqueGradeDAO($this->pdo);
        $this->utilisateurDAO = new UtilisateurDAO($this->pdo); // If names come from Utilisateur table
    }

    public function index(): Response
    {
        $data = [
            'title' => 'Historique du Personnel',
            'heading' => 'Historique du Personnel',
            'content' => 'Consultation de l\'historique des fonctions et grades du personnel.'
        ];
        return Response::view('menu_views/historique-personnel', $data);
    }

    public function chargerPersonnelPourDonneeHistorique(): Response
    {
        $typeUtilisateur = $this->request->getPostParams('type-utilisateur');
        $listeUtilisateur = [];

        switch ($typeUtilisateur) {
            case 'enseignant':
                // Assuming EnseignantDAO has a method to get basic info (id, nom, prenom)
                // And UtilisateurDAO links this to the main user ID if personnel_id is different from utilisateur_id
                $enseignants = $this->enseignantDAO->recupererTousAvecNomPrenom(); // Needs specific method in DAO
                foreach ($enseignants as $ens) {
                    // Assuming $ens is an object or array with 'id_utilisateur' or 'id_enseignant' and 'nom', 'prenom'
                    // The key for 'utilisateur_id' must match what the view expects for the select dropdown
                    $listeUtilisateur[] = [
                        'utilisateur_id' => $ens->getIdUtilisateur(), // or $ens->getIdEnseignant() if that's the primary link
                        'nom-prenom' => $ens->getPrenom() . ' ' . $ens->getNom()
                    ];
                }
                break;
            case 'personnel_administratif':
                $personnels = $this->personnelAdministratifDAO->recupererTousAvecNomPrenom(); // Needs specific method in DAO
                foreach ($personnels as $pers) {
                    $listeUtilisateur[] = [
                        'utilisateur_id' => $pers->getIdUtilisateur(), // or $pers->getIdPersonnel()
                        'nom-prenom' => $pers->getPrenom() . ' ' . $pers->getNom()
                    ];
                }
                break;
            default:
                return $this->error("Type d'utilisateur non valide.");
        }

        $data = [
            'title' => 'Historique du Personnel',
            'heading' => 'Historique du Personnel',
            'content' => 'Consultation de l\'historique des fonctions et grades du personnel.',
            'listeUtilisateur' => $listeUtilisateur,
            'selectActive' => true,
            'recherche' => true,
            'selectedTypeUtilisateur' => $typeUtilisateur // Pass back the selected type
        ];

        return Response::view(
            'menu_views/historique-personnel',
            $data,
            json: [
                'statut' => 'succes',
                'message' => 'Personnel chargé.',
                'listeUtilisateurHtml' => $this->genererOptionsSelectPersonnel($listeUtilisateur) // Helper to generate HTML for select
            ]
        );
    }

    private function genererOptionsSelectPersonnel(array $personnelListe): string
    {
        $html = '<option value="">Sélectionnez un personnel</option>';
        foreach ($personnelListe as $p) {
            $html .= "<option value=\"{$p['utilisateur_id']}\">{$p['nom-prenom']}</option>";
        }
        return $html;
    }


    public function chargerDonneeHistoriquePersonnel(): Response
    {
        $typeHistorique = $this->request->getPostParams('type-historique');
        $utilisateurId = $this->request->getPostParams('utilisateur_id'); // Ensure frontend sends 'utilisateur_id'
        $typeUtilisateur = $this->request->getPostParams('type_utilisateur_initial'); // Need this to know which table to query for personnel ID

        if (empty($typeHistorique) || empty($utilisateurId) || empty($typeUtilisateur)) {
            return $this->error('Veuillez sélectionner le type d\'utilisateur, le personnel et le type d\'historique.');
        }

        // Determine the actual personnel ID (enseignant_id or personnel_administratif_id) from utilisateur_id
        // This step is crucial if HistoriqueFonction/Grade tables use enseignant_id/personnel_id directly.
        // For simplicity, this example assumes HistoriqueFonction/Grade can be queried by utilisateur_id
        // or that a join can be made. A more robust solution might involve getting the specific personnel ID first.
        // E.g., if $typeUtilisateur == 'enseignant', $personnel = $this->enseignantDAO->recupererParUtilisateurId($utilisateurId); $personnelId = $personnel->getId();

        $entete = [];
        $corps = [];
        $donneesChargees = true;

        switch ($typeHistorique) {
            case 'fonction':
                $entete = ['Fonction', 'Date de début', 'Date de fin', 'Actions']; // Added Actions
                // We need to join with the 'fonction' table to get the function name.
                // And ensure that HistoriqueFonctionDAO can fetch by utilisateur_id (or specific personnel_id after lookup)
                $historiqueFonctions = $this->historiqueFonctionDAO->recupererParUtilisateurAvecDetails($utilisateurId); // Method to be created in DAO
                foreach ($historiqueFonctions as $hf) {
                    $corps[] = [
                        $hf->getFonctionNom(), // Assuming a method that returns the joined function name
                        $hf->getDateDebut(),
                        $hf->getDateFin() ?: 'Actuel',
                        '<button onclick="modifierHistoriqueFonction('.$hf->getId().')">Modifier</button>' // Example action
                    ];
                }
                break;

            case 'grade':
                $entete = ['Grade', 'Date d\'obtention', 'Justificatif', 'Actions']; // Added Actions
                // Similar to functions, join with 'grade' table for grade name.
                $historiqueGrades = $this->historiqueGradeDAO->recupererParUtilisateurAvecDetails($utilisateurId); // Method to be created in DAO
                foreach ($historiqueGrades as $hg) {
                    $corps[] = [
                        $hg->getGradeNom(), // Assuming a method that returns the joined grade name
                        $hg->getDateObtention(),
                        $hg->getJustificatif() ? '<a href="/path/to/justificatifs/'.$hg->getJustificatif().'" target="_blank">Voir</a>' : 'N/A',
                        '<button onclick="modifierHistoriqueGrade('.$hg->getId().')">Modifier</button>' // Example action
                    ];
                }
                break;
            default:
                return $this->error("Type d'historique non reconnu.");
        }

        // Re-fetch personnel list for the view state if needed, or assume it's already populated by a previous call
        // For this AJAX response, we primarily care about the table data.
        // The main view (historique-personnel.php) should handle maintaining the selected user.

        $data = [
            // 'title' => 'Historique du Personnel', // Not needed for AJAX partial update
            // 'heading' => 'Historique du Personnel',
            // 'content' => 'Consultation de l\'historique...',
            'enteteTableau' => $entete, // Use a different key to avoid conflicts if view uses 'entete'
            'corpsTableau' => $corps,   // Use a different key
            'donneesChargees' => $donneesChargees,
            // 'selectedTypeUtilisateur' => $typeUtilisateur,
            // 'selectedUtilisateurId' => $utilisateurId,
            // 'selectedTypeHistorique' => $typeHistorique,
        ];

        // For AJAX, we might want to return JSON that the client-side script uses to build the table,
        // or return a partial HTML view of the table.
        // The current setup returns the whole view, which might be fine if it's designed for that.
        // Let's assume the view `menu_views/historique-personnel` can render just the table part if these variables are set.
        // A more typical AJAX response for table data would be pure JSON.

        return Response::view('menu_views/historique-personnel', $data, json: [
            'statut' => 'succes',
            'message' => 'Données d\'historique chargées.',
            'htmlTable' => $this->genererTableauHistorique($entete, $corps) // Helper to generate HTML for table
        ]);
    }

    private function genererTableauHistorique(array $entete, array $corps): string
    {
        if (empty($corps) && empty($entete)) return "<p>Aucune donnée d'historique à afficher pour cette sélection.</p>";

        $html = '<table class="table table-striped table-bordered">';
        $html .= '<thead><tr>';
        foreach ($entete as $th) {
            $html .= "<th>{$th}</th>";
        }
        $html .= '</tr></thead>';
        $html .= '<tbody>';
        if (empty($corps)) {
            $html .= '<tr><td colspan="' . count($entete) . '">Aucun historique trouvé pour cette sélection.</td></tr>';
        } else {
            foreach ($corps as $row) {
                $html .= '<tr>';
                foreach ($row as $td) {
                    $html .= "<td>{$td}</td>";
                }
                $html .= '</tr>';
            }
        }
        $html .= '</tbody></table>';
        return $html;
    }

    // Placeholder methods for adding/editing history entries - would need forms and more logic
    public function ajouterHistoriqueFonction(): Response
    {
        // $idUtilisateur = $this->request->getPostParams('id_utilisateur');
        // $idFonction = $this->request->getPostParams('id_fonction');
        // $dateDebut = $this->request->getPostParams('date_debut');
        // $dateFin = $this->request->getPostParams('date_fin'); // optional
        // Logic to add to historique_fonction table using HistoriqueFonctionDAO
        return $this->info("Ajout d'historique de fonction à implémenter.");
    }

    public function ajouterHistoriqueGrade(): Response
    {
        // $idUtilisateur = $this->request->getPostParams('id_utilisateur');
        // $idGrade = $this->request->getPostParams('id_grade');
        // $dateObtention = $this->request->getPostParams('date_obtention');
        // $justificatif = $this->request->getFiles('justificatif'); // Handle file upload
        // Logic to add to historique_grade table using HistoriqueGradeDAO
        return $this->info("Ajout d'historique de grade à implémenter.");
    }
}
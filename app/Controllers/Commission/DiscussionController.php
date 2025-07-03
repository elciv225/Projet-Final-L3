<?php

namespace App\Controllers\Commission;

use App\Controllers\Controller;
use App\Dao\AffectationEncadrantDAO;
use App\Dao\ApprobationRapportDAO;
use App\Dao\DepotRapportDAO;
use App\Dao\MessagerieDAO;
use App\Dao\RapportEtudiantDAO;
use App\Dao\ValidationRapportDAO;
use System\Http\Response;

class DiscussionController extends Controller
{
    private RapportEtudiantDAO $rapportDAO;
    private DepotRapportDAO $depotDAO;
    private ValidationRapportDAO $validationDAO;
    private ApprobationRapportDAO $approbationDAO;
    private MessagerieDAO $messagerieDAO;
    private AffectationEncadrantDAO $affectationDAO;

    public function __construct()
    {
        parent::__construct();
        $this->rapportDAO = new RapportEtudiantDAO($this->pdo);
        $this->depotDAO = new DepotRapportDAO($this->pdo);
        $this->validationDAO = new ValidationRapportDAO($this->pdo);
        $this->approbationDAO = new ApprobationRapportDAO($this->pdo);
        $this->messagerieDAO = new MessagerieDAO($this->pdo);
        $this->affectationDAO = new AffectationEncadrantDAO($this->pdo);
    }

    /**
     * Affiche la page de discussions de la commission
     * @return Response
     */
    public function index(): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['utilisateur_connecte'])) {
            return Response::redirect('/authentification');
        }

        $utilisateur = $_SESSION['utilisateur_connecte'];

        // Vérifier si l'utilisateur est un membre de la commission
        if ($utilisateur['groupe_utilisateur_id'] !== 'GRP_COMMISSION') {
            return Response::redirect('/espace-utilisateur');
        }

        // Récupérer les rapports en attente de validation
        $rapportsEnAttente = $this->rapportDAO->getRapportsEnAttente();

        // Récupérer les rapports validés mais pas encore approuvés
        $rapportsValides = $this->rapportDAO->getRapportsValides();

        // Récupérer les discussions en cours
        $discussions = $this->messagerieDAO->getAllDiscussions();

        $data = [
            'title' => 'Discussions de la Commission',
            'heading' => 'Discussions',
            'content' => 'Gestion des discussions sur les rapports des étudiants.',
            'utilisateur' => $utilisateur,
            'rapports_en_attente' => $rapportsEnAttente,
            'rapports_valides' => $rapportsValides,
            'discussions' => $discussions,
            'depotDAO' => $this->depotDAO,
            'validationDAO' => $this->validationDAO
        ];

        // Toujours retourner la vue de discussions, jamais la page complète
        return Response::view('commission/discussions', $data);
    }

    /**
     * Affiche la discussion pour un rapport spécifique
     * @return Response
     */
    public function afficherDiscussion(): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['utilisateur_connecte'])) {
            return Response::redirect('/authentification');
        }

        $utilisateur = $_SESSION['utilisateur_connecte'];

        // Vérifier si l'utilisateur est un membre de la commission
        if ($utilisateur['groupe_utilisateur_id'] !== 'GRP_COMMISSION') {
            return Response::redirect('/espace-utilisateur');
        }

        // Récupérer l'ID du rapport
        $rapportId = $this->request->getGetParams('rapport_id');
        $etudiantId = $this->request->getGetParams('etudiant_id');

        if (empty($rapportId) || empty($etudiantId)) {
            return $this->error("Paramètres manquants");
        }

        // Récupérer les informations du rapport
        $rapport = $this->rapportDAO->recupererParId($rapportId);

        if (!$rapport) {
            return $this->error("Rapport introuvable");
        }

        // Récupérer les informations de dépôt
        $infoDepot = $this->depotDAO->getInfosDepot($rapportId);

        // Vérifier si une discussion existe déjà pour ce rapport
        $discussionId = 'DISC_' . $rapportId;

        if (!$this->messagerieDAO->discussionExiste($discussionId)) {
            // Créer une nouvelle discussion
            $this->messagerieDAO->creerDiscussion($discussionId, date('Y-m-d H:i:s'));
        }

        // Récupérer les messages de la discussion
        $messages = $this->messagerieDAO->getMessagesDiscussion($discussionId);

        $data = [
            'title' => 'Discussion - ' . $rapport->getTitre(),
            'rapport' => $rapport,
            'info_depot' => $infoDepot,
            'discussion_id' => $discussionId,
            'etudiant_id' => $etudiantId,
            'messages' => $messages,
            'utilisateur' => $utilisateur
        ];

        return Response::view('commission/discussion_rapport', $data);
    }

    /**
     * Ajoute un message à une discussion
     * @return Response
     */
    public function ajouterMessage(): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['utilisateur_connecte'])) {
            return Response::redirect('/authentification');
        }

        $utilisateur = $_SESSION['utilisateur_connecte'];

        // Vérifier si l'utilisateur est un membre de la commission
        if ($utilisateur['groupe_utilisateur_id'] !== 'GRP_COMMISSION') {
            return Response::redirect('/espace-utilisateur');
        }

        // Récupérer les données du formulaire
        $discussionId = $this->request->getPostParams('discussion_id');
        $etudiantId = $this->request->getPostParams('etudiant_id');
        $message = $this->request->getPostParams('message');

        if (empty($discussionId) || empty($etudiantId) || empty($message)) {
            return $this->error("Tous les champs sont obligatoires");
        }

        // Enregistrer le message
        $this->messagerieDAO->enregistrerMessage(
            $utilisateur['id'],
            $etudiantId,
            $discussionId,
            $message,
            date('Y-m-d H:i:s')
        );

        // Rediriger vers la discussion
        return Response::redirect('/commission/discussion?rapport_id=' . substr($discussionId, 5) . '&etudiant_id=' . $etudiantId);
    }

    /**
     * Enregistre un vote sur un rapport
     * @return Response
     */
    public function voter(): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['utilisateur_connecte'])) {
            return Response::redirect('/authentification');
        }

        $utilisateur = $_SESSION['utilisateur_connecte'];

        // Vérifier si l'utilisateur est un membre de la commission
        if ($utilisateur['groupe_utilisateur_id'] !== 'GRP_COMMISSION') {
            return Response::redirect('/espace-utilisateur');
        }

        // Récupérer les données du formulaire
        $rapportId = $this->request->getPostParams('rapport_id');
        $vote = $this->request->getPostParams('vote');
        $commentaire = $this->request->getPostParams('commentaire') ?? '';

        if (empty($rapportId) || empty($vote)) {
            return $this->error("Paramètres manquants");
        }

        // Si le vote est positif et que tous les membres ont voté positivement
        if ($vote === 'approuve') {
            // Valider le rapport
            $this->validationDAO->validerRapport(
                $utilisateur['id'],
                $rapportId,
                $commentaire,
                date('Y-m-d')
            );

            // Approuver le rapport (par un administrateur fictif pour l'instant)
            $this->approbationDAO->approuverRapport(
                $utilisateur['id'], // Normalement, ce serait un administrateur
                $rapportId,
                date('Y-m-d')
            );

            // Assigner aléatoirement des encadrants
            $this->affectationDAO->assignerEncadrantsAleatoires($rapportId);

            return Response::success("Le rapport a été approuvé et des encadrants ont été assignés");
        } else {
            // Enregistrer le vote négatif comme un message dans la discussion
            $discussionId = 'DISC_' . $rapportId;
            $etudiantId = $this->depotDAO->getInfosDepot($rapportId)['utilisateur_id'];

            $message = "J'ai voté CONTRE l'approbation de ce rapport. Raison: " . $commentaire;

            $this->messagerieDAO->enregistrerMessage(
                $utilisateur['id'],
                $etudiantId,
                $discussionId,
                $message,
                date('Y-m-d H:i:s')
            );

            return Response::success("Votre vote a été enregistré");
        }
    }
}

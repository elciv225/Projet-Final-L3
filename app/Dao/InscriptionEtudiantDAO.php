<?php

namespace App\Dao;

use PDO;
use App\Models\InscriptionEtudiant;

class InscriptionEtudiantDAO extends DAO
{
    private EtudiantDAO $etudiantDAO;
    private NiveauEtudeDAO $niveauEtudeDAO;
    private AnneeAcademiqueDAO $anneeAcademiqueDAO;

    // Le constructeur de InscriptionEtudiant est:
    // public function __construct(Etudiant $etudiant, NiveauEtude $niveauEtude, AnneeAcademique $anneeAcademique, ?string $dateInscription, ?int $montant)
    // La clé primaire de la table inscription_etudiant est (utilisateur_id, niveau_etude_id, annee_academique_id)

    public function __construct(
        PDO $pdo,
        EtudiantDAO $etudiantDAO,
        NiveauEtudeDAO $niveauEtudeDAO,
        AnneeAcademiqueDAO $anneeAcademiqueDAO
    ) {
        parent::__construct($pdo, 'inscription_etudiant', InscriptionEtudiant::class, ''); // Pas de clé primaire simple
        $this->etudiantDAO = $etudiantDAO;
        $this->niveauEtudeDAO = $niveauEtudeDAO;
        $this->anneeAcademiqueDAO = $anneeAcademiqueDAO;
    }

    private function hydraterInscriptionEtudiant(array $data): ?InscriptionEtudiant
    {
        $etudiant = $this->etudiantDAO->recupererParId($data['utilisateur_id']);
        $niveauEtude = $this->niveauEtudeDAO->recupererParId($data['niveau_etude_id']);
        $anneeAcademique = $this->anneeAcademiqueDAO->recupererParId($data['annee_academique_id']);

        if ($etudiant && $niveauEtude && $anneeAcademique) {
            return new InscriptionEtudiant(
                $etudiant,
                $niveauEtude,
                $anneeAcademique,
                $data['date_inscription'] ?? null,
                isset($data['montant']) ? (int)$data['montant'] : null
            );
        }
        return null;
    }

    public function recupererParIdsComposites(string $utilisateurId, string $niveauEtudeId, string $anneeAcademiqueId): ?InscriptionEtudiant
    {
        $query = "SELECT * FROM $this->table
                  WHERE utilisateur_id = :utilisateur_id
                    AND niveau_etude_id = :niveau_etude_id
                    AND annee_academique_id = :annee_academique_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':utilisateur_id', $utilisateurId);
        $stmt->bindParam(':niveau_etude_id', $niveauEtudeId);
        $stmt->bindParam(':annee_academique_id', $anneeAcademiqueId);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? $this->hydraterInscriptionEtudiant($data) : null;
    }

    public function creer(InscriptionEtudiant $inscription): bool
    {
        $data = [
            'utilisateur_id' => $inscription->getEtudiant()->getUtilisateurId(),
            'niveau_etude_id' => $inscription->getNiveauEtude()->getId(),
            'annee_academique_id' => $inscription->getAnneeAcademique()->getId(),
            'date_inscription' => $inscription->getDateInscription(),
            'montant' => $inscription->getMontant()
        ];
        return parent::creer($data);
    }

    public function mettreAJour(InscriptionEtudiant $inscription): bool
    {
        // Seuls date_inscription et montant peuvent être mis à jour. La CPK ne change pas.
        $sql = "UPDATE $this->table
                SET date_inscription = :date_inscription, montant = :montant
                WHERE utilisateur_id = :utilisateur_id
                  AND niveau_etude_id = :niveau_etude_id
                  AND annee_academique_id = :annee_academique_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':date_inscription', $inscription->getDateInscription());
        $stmt->bindValue(':montant', $inscription->getMontant());
        $stmt->bindValue(':utilisateur_id', $inscription->getEtudiant()->getUtilisateurId());
        $stmt->bindValue(':niveau_etude_id', $inscription->getNiveauEtude()->getId());
        $stmt->bindValue(':annee_academique_id', $inscription->getAnneeAcademique()->getId());
        return $stmt->execute();
    }

    public function supprimerParIdsComposites(string $utilisateurId, string $niveauEtudeId, string $anneeAcademiqueId): bool
    {
        $sql = "DELETE FROM $this->table
                WHERE utilisateur_id = :utilisateur_id
                  AND niveau_etude_id = :niveau_etude_id
                  AND annee_academique_id = :annee_academique_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':utilisateur_id', $utilisateurId);
        $stmt->bindParam(':niveau_etude_id', $niveauEtudeId);
        $stmt->bindParam(':annee_academique_id', $anneeAcademiqueId);
        return $stmt->execute();
    }

    /**
     * Récupère toutes les inscriptions avec leurs objets liés.
     */
    public function recupererTousAvecObjets(?string $orderBy = 'annee_academique_id', ?string $orderType = 'DESC'): array
    {
        // Construction de la requête de base
        // Il est important de récupérer toutes les colonnes de la table inscription_etudiant
        $query = "SELECT utilisateur_id, niveau_etude_id, annee_academique_id, date_inscription, montant
                  FROM $this->table";

        if ($orderBy && $orderType) {
            // Valider orderBy pour éviter injection SQL si nécessaire, ou utiliser une liste blanche de colonnes.
            // Pour cet exemple, on fait confiance à l'entrée.
            $query .= " ORDER BY $orderBy $orderType";
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $resultsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($resultsData)) {
            return [];
        }

        // Collecter tous les IDs nécessaires
        $etudiantIds = array_unique(array_column($resultsData, 'utilisateur_id'));
        $niveauEtudeIds = array_unique(array_column($resultsData, 'niveau_etude_id'));
        $anneeAcademiqueIds = array_unique(array_column($resultsData, 'annee_academique_id'));

        // Récupérer les objets liés en batch
        $etudiants = !empty($etudiantIds) ? $this->etudiantDAO->rechercher(['utilisateur_id' => $etudiantIds]) : [];
        $niveauxEtudes = !empty($niveauEtudeIds) ? $this->niveauEtudeDAO->rechercher(['id' => $niveauEtudeIds]) : [];
        $anneesAcademiques = !empty($anneeAcademiqueIds) ? $this->anneeAcademiqueDAO->rechercher(['id' => $anneeAcademiqueIds]) : [];

        // Mapper les objets par ID pour un accès facile
        $etudiantsMap = [];
        foreach ($etudiants as $e) { $etudiantsMap[$e->getUtilisateurId()] = $e; }

        $niveauxEtudesMap = [];
        foreach ($niveauxEtudes as $n) { $niveauxEtudesMap[$n->getId()] = $n; }

        $anneesAcademiquesMap = [];
        foreach ($anneesAcademiques as $a) { $anneesAcademiquesMap[$a->getId()] = $a; }

        $inscriptions = [];
        foreach ($resultsData as $data) {
            $etudiant = $etudiantsMap[$data['utilisateur_id']] ?? null;
            $niveauEtude = $niveauxEtudesMap[$data['niveau_etude_id']] ?? null;
            $anneeAcademique = $anneesAcademiquesMap[$data['annee_academique_id']] ?? null;

            if ($etudiant && $niveauEtude && $anneeAcademique) {
                $inscriptions[] = new InscriptionEtudiant(
                    $etudiant,
                    $niveauEtude,
                    $anneeAcademique,
                    $data['date_inscription'] ?? null,
                    isset($data['montant']) ? (int)$data['montant'] : null
                );
            }
        }
        return $inscriptions;
    }
}
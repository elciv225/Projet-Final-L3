<?php

namespace App\Dao;

use App\Models\Evaluation; // Assurez-vous que le modèle Evaluation existe
use PDO;

class EvaluationDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        // La table 'evaluation' a une clé primaire composite (enseignant_id, etudiant_id, ecue_id, annee_academique_id, session_id)
        // Le DAO générique n'est pas idéal pour les clés composites complexes.
        // Nous spécifions 'id' comme placeholder, mais les méthodes CRUD génériques ne fonctionneront pas correctement.
        // Il faudra implémenter des méthodes spécifiques pour 'creer', 'mettreAJour', 'supprimer'.
        parent::__construct($pdo, 'evaluation', Evaluation::class, 'id'); // 'id' est un placeholder.
    }

    /**
     * Récupère toutes les évaluations avec les détails (noms, libellés).
     * @return array
     */
    public function recupererTousAvecDetails(): array
    {
        $sql = "
            SELECT
                e.enseignant_id, ens.nom as enseignant_nom, ens.prenoms as enseignant_prenoms,
                e.etudiant_id, etu.nom as etudiant_nom, etu.prenoms as etudiant_prenoms,
                e.ecue_id, ec.libelle as ecue_libelle,
                e.annee_academique_id,
                e.session_id, -- Supposant une table 'session_examen' ou un libellé direct
                e.date_evaluation,
                e.note
            FROM evaluation e
            JOIN utilisateur ens ON e.enseignant_id = ens.id
            JOIN utilisateur etu ON e.etudiant_id = etu.id
            JOIN ecue ec ON e.ecue_id = ec.id
            -- JOIN session_examen se ON e.session_id = se.id (si session_id est une FK)
            ORDER BY e.date_evaluation DESC, etu.nom, ec.libelle;
        ";
        // Note: Si session_id n'est pas une clé étrangère mais juste une chaîne (ex: 'SESSION_NORMALE'),
        // la jointure sur session_examen n'est pas nécessaire.

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crée une nouvelle évaluation.
     * @param array $data Les données de l'évaluation.
     * @return bool True si succès, false sinon.
     */
    public function creerEvaluation(array $data): bool
    {
        $sql = "INSERT INTO evaluation (enseignant_id, etudiant_id, ecue_id, annee_academique_id, session_id, date_evaluation, note)
                VALUES (:enseignant_id, :etudiant_id, :ecue_id, :annee_academique_id, :session_id, :date_evaluation, :note)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':enseignant_id' => $data['enseignant_id'],
            ':etudiant_id' => $data['etudiant_id'],
            ':ecue_id' => $data['ecue_id'],
            ':annee_academique_id' => $data['annee_academique_id'],
            ':session_id' => $data['session_id'],
            ':date_evaluation' => $data['date_evaluation'],
            ':note' => $data['note']
        ]);
    }

    /**
     * Met à jour la note d'une évaluation existante.
     * @param array $data Les données pour identifier l'évaluation et la nouvelle note.
     * @return bool True si succès, false sinon.
     */
    public function mettreAJourNote(array $data): bool
    {
        $sql = "UPDATE evaluation SET note = :note, date_evaluation = :date_evaluation
                WHERE enseignant_id = :enseignant_id
                  AND etudiant_id = :etudiant_id
                  AND ecue_id = :ecue_id
                  AND annee_academique_id = :annee_academique_id
                  AND session_id = :session_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':note' => $data['note'],
            ':date_evaluation' => $data['date_evaluation'],
            ':enseignant_id' => $data['enseignant_id'],
            ':etudiant_id' => $data['etudiant_id'],
            ':ecue_id' => $data['ecue_id'],
            ':annee_academique_id' => $data['annee_academique_id'],
            ':session_id' => $data['session_id']
        ]);
    }

    /**
     * Supprime une évaluation spécifique.
     * @param array $keys Les clés composites pour identifier l'évaluation.
     * @return bool True si succès, false sinon.
     */
    public function supprimerEvaluation(array $keys): bool
    {
        $sql = "DELETE FROM evaluation
                WHERE enseignant_id = :enseignant_id
                  AND etudiant_id = :etudiant_id
                  AND ecue_id = :ecue_id
                  AND annee_academique_id = :annee_academique_id
                  AND session_id = :session_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($keys);
    }

    /**
     * Récupère une évaluation par sa clé composite.
     * @param array $keys
     * @return mixed L'objet Evaluation ou false.
     */
    public function recupererParCleComposite(array $keys)
    {
        $sql = "SELECT * FROM evaluation
                WHERE enseignant_id = :enseignant_id
                  AND etudiant_id = :etudiant_id
                  AND ecue_id = :ecue_id
                  AND annee_academique_id = :annee_academique_id
                  AND session_id = :session_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($keys);
        return $stmt->fetchObject($this->nomClasseModele);
    }
}

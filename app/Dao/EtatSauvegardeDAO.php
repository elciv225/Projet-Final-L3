<?php

namespace App\Dao;

use PDO;
use App\Models\EtatSauvegarde;

class EtatSauvegardeDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'etat_sauvegarde', EtatSauvegarde::class, 'id');
    }

    /**
     * Crée un nouvel enregistrement etat_sauvegarde.
     *
     * @param EtatSauvegarde $etatSauvegarde
     * @return bool True si la création a réussi, false sinon.
     */
    public function creerEtatSauvegarde(EtatSauvegarde $etatSauvegarde): bool
    {
        $data = [
            // 'id' est auto-incrémenté
            'nom_table' => $etatSauvegarde->getNomTable(),
            'enregistrement_id' => $etatSauvegarde->getEnregistrementId(),
            'donnees' => $etatSauvegarde->getDonnees(), // Doit être une chaîne JSON
            // 'date_sauvegarde' a une valeur DEFAULT CURRENT_TIMESTAMP
            'traitement_id' => $etatSauvegarde->getTraitementId(),
            'utilisateur_id' => $etatSauvegarde->getUtilisateurId()
        ];

        // Gérer date_sauvegarde si elle est fournie et non null
        if ($etatSauvegarde->getDateSauvegarde() !== null) {
            $data['date_sauvegarde'] = $etatSauvegarde->getDateSauvegarde();
        }

        return parent::creer($data);
    }

    /**
     * Met à jour un enregistrement etat_sauvegarde.
     *
     * @param EtatSauvegarde $etatSauvegarde L'objet EtatSauvegarde à mettre à jour (doit avoir un ID).
     * @return bool True si la mise à jour a réussi, false sinon.
     */
    public function mettreAJourEtatSauvegarde(EtatSauvegarde $etatSauvegarde): bool
    {
        if ($etatSauvegarde->getId() === null) {
            return false; // Impossible de mettre à jour sans ID
        }

        $data = [
            'nom_table' => $etatSauvegarde->getNomTable(),
            'enregistrement_id' => $etatSauvegarde->getEnregistrementId(),
            'donnees' => $etatSauvegarde->getDonnees(),
            'date_sauvegarde' => $etatSauvegarde->getDateSauvegarde(),
            'traitement_id' => $etatSauvegarde->getTraitementId(),
            'utilisateur_id' => $etatSauvegarde->getUtilisateurId()
        ];
        return parent::mettreAJour((string)$etatSauvegarde->getId(), $data);
    }
}

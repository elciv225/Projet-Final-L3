<?php
// Données passées: $evaluations (array)
// Ce fichier ne contient que les lignes (<tr>) du tableau des évaluations.
?>
<?php if (isset($evaluations) && !empty($evaluations)): ?>
    <?php foreach ($evaluations as $eval):
        // Création d'une clé composite unique pour la ligne et la checkbox
        $compositeKey = json_encode([
            'enseignant_id' => $eval['enseignant_id'],
            'etudiant_id' => $eval['etudiant_id'],
            'ecue_id' => $eval['ecue_id'],
            'annee_academique_id' => $eval['annee_academique_id'],
            'session_id' => $eval['session_id']
        ]);
    ?>
        <tr data-composite-key='<?= htmlspecialchars($compositeKey, ENT_QUOTES, 'UTF-8') ?>'>
            <td><input type="checkbox" class="checkbox select-evaluation-item" value='<?= htmlspecialchars($compositeKey, ENT_QUOTES, 'UTF-8') ?>'></td>
            <td data-label="Enseignant"><?= htmlspecialchars($eval['enseignant_nom'] . ' ' . $eval['enseignant_prenoms']) ?></td>
            <td data-label="Étudiant"><?= htmlspecialchars($eval['etudiant_nom'] . ' ' . $eval['etudiant_prenoms']) ?></td>
            <td data-label="ECUE"><?= htmlspecialchars($eval['ecue_libelle']) ?></td>
            <td data-label="Année Acad."><?= htmlspecialchars($eval['annee_academique_id']) ?></td>
            <td data-label="Session"><?= htmlspecialchars($eval['session_id']) ?></td>
            <td data-label="Date Éval."><?= htmlspecialchars(date('d/m/Y', strtotime($eval['date_evaluation']))) ?></td>
            <td data-label="Note"><?= htmlspecialchars(number_format($eval['note'], 2, ',', ' ')) ?></td>
            <td>
                <button class="btn-action btn-edit-evaluation" title="Modifier">✏️</button>
                <button class="btn-action btn-delete-single-evaluation" title="Supprimer">🗑️</button>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="9" style="text-align: center; padding: 20px;">Aucune évaluation trouvée.</td></tr>
<?php endif; ?>

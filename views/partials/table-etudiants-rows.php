<?php
// Données passées: $etudiants (array)
// Ce fichier ne contient que les lignes (<tr>) du tableau des étudiants.
?>
<?php if (isset($etudiants) && !empty($etudiants)): ?>
    <?php foreach ($etudiants as $etudiant): ?>
        <tr data-id="<?= htmlspecialchars($etudiant['id'] ?? '') ?>"
            data-nom="<?= htmlspecialchars($etudiant['nom'] ?? '') ?>"
            data-prenoms="<?= htmlspecialchars($etudiant['prenoms'] ?? '') ?>"
            data-email="<?= htmlspecialchars($etudiant['email'] ?? '') ?>"
            data-date-naissance="<?= htmlspecialchars($etudiant['date_naissance'] ?? '') ?>"
            data-niveau-id="<?= htmlspecialchars($etudiant['niveau_etude_id'] ?? '') ?>"
            data-annee-id="<?= htmlspecialchars($etudiant['annee_academique_id'] ?? '') ?>"
            data-montant="<?= htmlspecialchars($etudiant['montant'] ?? '') ?>"
            data-numero-carte="<?= htmlspecialchars($etudiant['numero_carte'] ?? 'N/A') ?>"
            >
            <td><input type="checkbox" class="checkbox select-etudiant-item" name="ids[]" value="<?= htmlspecialchars($etudiant['id'] ?? '') ?>"></td>
            <td class="id-etudiant"><?= htmlspecialchars($etudiant['id'] ?? '') ?></td>
            <td class="numero-carte"><?= htmlspecialchars($etudiant['numero_carte'] ?? 'N/A') ?></td>
            <td class="nom-prenoms">
                <?= htmlspecialchars(($etudiant['nom'] ?? '') . ' ' . ($etudiant['prenoms'] ?? '')) ?>
            </td>
            <td class="email-etudiant"><?= htmlspecialchars($etudiant['email'] ?? '') ?></td>
            <td class="niveau-etude">
                <?= htmlspecialchars($etudiant['niveau_etude'] ?? 'Non inscrit') ?>
            </td>
            <td class="annee-academique">
                <?= htmlspecialchars($etudiant['annee_academique_id'] ?? 'N/A') ?>
            </td>
            <td class="montant-inscription"><?= htmlspecialchars(number_format($etudiant['montant'] ?? 0, 0, ',', ' ')) ?> FCFA</td>
            <td>
                <button class="btn-action btn-edit" title="Modifier l'inscription">✏️</button>
                <button class="btn-action btn-delete-single" title="Supprimer l'étudiant">🗑️</button>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="9" style="text-align: center; padding: 20px;">Aucun étudiant trouvé.</td>
    </tr>
<?php endif; ?>

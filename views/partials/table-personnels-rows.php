<?php
// Données passées: $personnels (array de tous les personnels fusionnés et typés)
?>
<?php if (isset($personnels) && !empty($personnels)): ?>
    <?php foreach ($personnels as $p): ?>
        <tr data-id="<?= htmlspecialchars($p['id'] ?? '') ?>"
            data-nom="<?= htmlspecialchars($p['nom'] ?? '') ?>"
            data-prenoms="<?= htmlspecialchars($p['prenoms'] ?? '') ?>"
            data-email="<?= htmlspecialchars($p['email'] ?? '') ?>"
            data-date-naissance="<?= htmlspecialchars($p['date_naissance'] ?? '') ?>"
            data-type-personnel="<?= htmlspecialchars($p['type_personnel_id'] ?? ($p['type_personnel'] ?? '')) ?>"
            data-grade-id="<?= htmlspecialchars($p['grade_id'] ?? '') ?>"
            data-fonction-id="<?= htmlspecialchars($p['fonction_id'] ?? '') ?>"
            data-specialite-id="<?= htmlspecialchars($p['specialite_id'] ?? '') ?>"
            data-date-grade="<?= htmlspecialchars($p['date_grade'] ?? '') ?>"
            data-date-fonction="<?= htmlspecialchars($p['date_fonction'] ?? '') ?>"
            data-date-specialite="<?= htmlspecialchars($p['date_specialite'] ?? '') ?>"
            >
            <td><input type="checkbox" class="checkbox select-personnel" name="ids[]" value="<?= htmlspecialchars($p['id'] ?? '') ?>"></td>
            <td><?= htmlspecialchars($p['id'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars(($p['nom'] ?? '') . ' ' . ($p['prenoms'] ?? '')) ?></td>
            <td><?= htmlspecialchars($p['email'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars(ucfirst($p['type_personnel_id'] ?? ($p['type_personnel'] ?? 'N/A'))) ?></td>
            <td>
                <?php
                $infoPrincipale = 'N/A';
                if (($p['type_personnel_id'] ?? ($p['type_personnel'] ?? '')) === 'enseignant') {
                    $infoPrincipale = htmlspecialchars($p['grade'] ?? ($p['fonction'] ?? 'N/A'));
                } elseif (($p['type_personnel_id'] ?? ($p['type_personnel'] ?? '')) === 'administratif') {
                    $infoPrincipale = htmlspecialchars($p['fonction'] ?? 'N/A');
                }
                echo $infoPrincipale;
                ?>
            </td>
            <td>
                <button class="btn-action btn-edit-personnel" title="Modifier">✏️</button>
                <button class="btn-action btn-delete-single-personnel" title="Supprimer">🗑️</button>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="7" style="text-align: center; padding: 20px;">Aucun personnel trouvé.</td>
    </tr>
<?php endif; ?>

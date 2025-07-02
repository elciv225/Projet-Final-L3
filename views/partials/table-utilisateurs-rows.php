<?php
// Données passées: $utilisateurs (array)
// Ce fichier ne contient que les lignes (<tr>) du tableau des utilisateurs.
?>
<?php if (isset($utilisateurs) && !empty($utilisateurs)): ?>
    <?php foreach ($utilisateurs as $user):
        // Préparation des data-attributes pour l'édition
        // Il est crucial que $user contiennent toutes les données nécessaires,
        // y compris date_naissance, type_utilisateur_id, groupe_utilisateur_id.
        // Si ce n'est pas le cas après un rechargement partiel, le JS d'édition échouera.
        // Assumons que $user est complet ici.
        $dataAttrs = 'data-id="'.htmlspecialchars($user['id']).'" ';
        $dataAttrs .= 'data-nom="'.htmlspecialchars($user['nom']).'" ';
        $dataAttrs .= 'data-prenoms="'.htmlspecialchars($user['prenoms']).'" ';
        $dataAttrs .= 'data-email="'.htmlspecialchars($user['email']).'" ';
        $dataAttrs .= 'data-date-naissance="'.htmlspecialchars($user['date_naissance'] ?? '').'" '; // Assurez-vous que ce champ est dans $user
        $dataAttrs .= 'data-type-utilisateur-id="'.htmlspecialchars($user['type_utilisateur_id'] ?? '').'" '; // Assurez-vous que ce champ est dans $user
        $dataAttrs .= 'data-groupe-utilisateur-id="'.htmlspecialchars($user['groupe_utilisateur_id'] ?? '').'" '; // Assurez-vous que ce champ est dans $user
    ?>
        <tr <?= $dataAttrs ?>>
            <td><input type="checkbox" class="checkbox select-user" name="ids[]" value="<?= htmlspecialchars($user['id']) ?>"></td>
            <td><?= htmlspecialchars($user['nom'] ?? '') ?></td>
            <td><?= htmlspecialchars($user['prenoms'] ?? '') ?></td>
            <td><?= htmlspecialchars($user['email'] ?? '') ?></td>
            <td><?= htmlspecialchars($user['type_user'] ?? 'N/A') ?></td> <!-- 'type_user' est le libellé -->
            <td><?= htmlspecialchars($user['groupe'] ?? 'N/A') ?></td> <!-- 'groupe' est le libellé -->
            <td><?= htmlspecialchars($user['id'] ?? '') ?></td> <!-- Login (ID) -->
            <td>
                <button class="btn-action btn-edit-user" title="Modifier">✏️</button>
                <button class="btn-action btn-delete-single-user" title="Supprimer">🗑️</button>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="8" style="text-align: center; padding: 20px;">Aucun utilisateur trouvé.</td>
    </tr>
<?php endif; ?>

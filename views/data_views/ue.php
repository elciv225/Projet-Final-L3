<?php
// Variables available:
// $parametre_label (e.g., "Ue", "Ecue")
// $parametre_type (e.g., "ue", "ecue")
// $liste_donnees (array of Ue or Ecue objects)
// $ues (array of all Ue objects, passed if $parametre_type is 'ecue')

$item_id_val = ''; // For pre-filling in edit mode
$libelle_val = '';
$credit_val = '';
$ue_id_val = ''; // For ECUE's UE link

// TODO: Add logic for edit mode pre-fill
?>

<form class="form-section ajax-form" method="post" action="/parametres-generaux/executer-action" data-target="#parametre-<?= htmlspecialchars($parametre_type) ?>-table-container">
    <input type="hidden" name="operation" value="ajouter">
    <input type="hidden" name="parametre_type" value="<?= htmlspecialchars($parametre_type) ?>">
    <input type="hidden" name="id_item" value="<?= $item_id_val ?>">

    <div class="section-header">
        <h3 class="section-title">Ajouter/Modifier <?= htmlspecialchars($parametre_label) ?></h3>
    </div>
    <div class="section-content">
        <div class="form-grid">
            <div class="form-group">
                <input type="text" id="param_libelle" name="libelle" class="form-input" placeholder=" " value="<?= $libelle_val ?>" required>
                <label for="param_libelle" class="form-label">Libellé <?= htmlspecialchars($parametre_label) ?></label>
            </div>
            <div class="form-group">
                <input type="number" id="param_credit" name="credit" class="form-input" placeholder=" " value="<?= $credit_val ?>" required min="0">
                <label for="param_credit" class="form-label">Crédits</label>
            </div>

            <?php if ($parametre_type === 'ecue'): ?>
                <div class="form-group">
                    <select class="form-input" id="param_ue_id" name="ue_id" required>
                        <option value="">Sélectionnez l'UE parente</option>
                        <?php if (isset($ues) && !empty($ues)): ?>
                            <?php foreach ($ues as $ue_parent): ?>
                                <option value="<?= htmlspecialchars($ue_parent->getId()) ?>" <?= ($ue_id_val == $ue_parent->getId()) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($ue_parent->getLibelle()) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <label class="form-label" for="param_ue_id">UE Parente</label>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="section-bottom">
        <h3 class="section-title">Action</h3>
        <div style="display: flex">
            <button class="btn btn-primary" type="submit" id="param_submit_button_ue_ecue">Ajouter <?= htmlspecialchars($parametre_label) ?></button>
        </div>
    </div>
</form>

<div class="table-container" id="parametre-<?= htmlspecialchars($parametre_type) ?>-table-container">
    <div class="table-header">
        <h3 class="table-title">Liste des <?= htmlspecialchars($parametre_label) ?></h3>
        <div class="header-actions">
             <div class="search-container">
                <span class="search-icon">🔍</span>
                <input type="text" id="paramUeEcueSearchInput" class="search-input" placeholder="Rechercher...">
            </div>
        </div>
    </div>

    <div class="table-scroll-wrapper scroll-custom">
        <table class="table">
            <thead>
            <tr>
                <th><input type="checkbox" class="checkbox master-checkbox-param-ue-ecue"></th>
                <th>ID</th>
                <th>Libellé</th>
                <th>Crédits</th>
                <?php if ($parametre_type === 'ecue'): ?>
                    <th>UE Parente (ID)</th>
                <?php endif; ?>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if (isset($liste_donnees) && !empty($liste_donnees)): ?>
                <?php foreach ($liste_donnees as $item): ?>
                    <tr>
                        <td><input type="checkbox" class="checkbox item-checkbox-param-ue-ecue" value="<?= htmlspecialchars($item->getId()) ?>"></td>
                        <td><?= htmlspecialchars($item->getId()) ?></td>
                        <td><?= htmlspecialchars($item->getLibelle()) ?></td>
                        <td><?= htmlspecialchars($item->getCredit()) ?></td>
                        <?php if ($parametre_type === 'ecue'): ?>
                            <td><?= htmlspecialchars($item->getUeId()) ?></td>
                        <?php endif; ?>
                        <td>
                            <button class="btn btn-action btn-edit-param-ue-ecue" data-id="<?= htmlspecialchars($item->getId()) ?>">✏️</button>
                            <button class="btn btn-action btn-delete-param-ue-ecue" data-id="<?= htmlspecialchars($item->getId()) ?>">🗑️</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= ($parametre_type === 'ecue') ? '6' : '5' ?>" class="text-center">Aucun enregistrement trouvé.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="table-footer">
        <div class="results-info">
            Affichage de <?= count($liste_donnees ?? []) ?> entrée(s).
        </div>
        <!-- Pagination if needed -->
    </div>
</div>

<script>
// Basic JS for edit/delete (similar to parametre-general.php, adapt if needed)
// Ensure data attributes and form field names match what the controller expects for UE/ECUE.
// For example, the submit button ID and parameter type in IDs should be unique if this JS is copy-pasted.
// This script section would need to be adapted for UE/ECUE specific editing and deleting.
</script>
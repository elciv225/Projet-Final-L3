<?php
// Données passées :
// $parametre_libelle (ex: "Niveaux d'Étude")
// $parametre_type (ex: "niveau_etude")
// $elements (array des éléments existants)
// $message_succes (optionnel)
?>

<div class="form-section table-container parametre-general-section" id="container-<?= htmlspecialchars($parametre_type) ?>">
    <?php if (isset($message_succes)): ?>
        <div class="alert alert-succes" role="alert">
            <?= htmlspecialchars($message_succes) ?>
        </div>
    <?php endif; ?>

    <div class="section-header">
        <h3 class="section-title">Gestion des <?= htmlspecialchars($parametre_libelle) ?></h3>
    </div>

    <!-- Formulaire d'ajout/modification -->
    <form action="/traitement-parametre-general" method="post" class="ajax-form form-parametre-general" data-target="#container-<?= htmlspecialchars($parametre_type) ?>">
        <input type="hidden" name="operation" value="ajouter">
        <input type="hidden" name="id" value=""> <!-- Pour la modification -->
        <input type="hidden" name="parametre_type" value="<?= htmlspecialchars($parametre_type) ?>">

        <div class="form-grid-simple">
            <div class="form-group">
                <input type="text" name="libelle" id="libelle-<?= htmlspecialchars($parametre_type) ?>" class="form-input" placeholder=" " required>
                <label for="libelle-<?= htmlspecialchars($parametre_type) ?>" class="form-label">Libellé</label>
            </div>
            <!-- Ajouter d'autres champs ici si nécessaire pour des paramètres plus complexes non gérés par des vues spécifiques -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Ajouter</button>
                <button type="button" class="btn btn-secondary btn-cancel-param" style="display: none;">Annuler</button>
            </div>
        </div>
    </form>

    <!-- Tableau des éléments existants -->
    <?php $tableId = 'table-param-' . htmlspecialchars($parametre_type); ?>
    <div class="table-header" style="margin-top: 20px;">
        <h4 class="table-title">Liste des <?= htmlspecialchars($parametre_libelle) ?> existants</h4>
        <div class="header-actions">
            <div class="search-container">
                <span class="search-icon">🔍</span>
                <input type="text" id="searchInput-<?= $tableId ?>" class="search-input search-input-param" placeholder="Rechercher...">
            </div>
            <form id="delete-form-<?= $tableId ?>" class="form-delete-selected-param ajax-form" method="post" action="/traitement-parametre-general" data-target="#container-<?= htmlspecialchars($parametre_type) ?>">
                <input type="hidden" name="operation" value="supprimer">
                <input type="hidden" name="parametre_type" value="<?= htmlspecialchars($parametre_type) ?>">
                <div id="hidden-inputs-for-delete-<?= $tableId ?>"></div>
                <button type="submit" class="btn btn-danger btn-delete-selected-param" disabled>Supprimer la sélection</button>
            </form>
        </div>
    </div>
    <div class="table-scroll-wrapper scroll-custom">
        <table class="table table-parametre-general" id="<?= $tableId ?>" data-parametre-type="<?= htmlspecialchars($parametre_type) ?>">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll-<?= $tableId ?>" class="checkbox select-all-param"></th>
                    <th>ID</th>
                    <th>Libellé</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($elements)): ?>
                    <?php foreach ($elements as $element): ?>
                        <tr data-id="<?= htmlspecialchars($element->getId()) ?>" data-libelle="<?= htmlspecialchars($element->getLibelle()) ?>">
                            <td><input type="checkbox" class="checkbox select-param-item" name="ids[]" value="<?= htmlspecialchars($element->getId()) ?>"></td>
                            <td><?= htmlspecialchars($element->getId()) ?></td>
                            <td><?= htmlspecialchars($element->getLibelle()) ?></td>
                            <td>
                                <button class="btn-action btn-edit-param" title="Modifier">✏️</button>
                                <button class="btn-action btn-delete-param" title="Supprimer">🗑️</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align:center;">Aucun <?= htmlspecialchars(strtolower($parametre_libelle)) ?> trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

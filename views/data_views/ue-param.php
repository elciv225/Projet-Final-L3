<?php
// Données passées :
// $parametre_libelle ("Unités d'Enseignement (UE)")
// $parametre_type ("ue")
// $elements (array des UE existantes, avec détails de niveau d'étude)
// $niveauxEtude (array des niveaux d'étude pour le select)
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

    <!-- Formulaire d'ajout/modification pour les UE -->
    <form action="/traitement-parametre-general" method="post" class="ajax-form form-parametre-general" data-target="#container-<?= htmlspecialchars($parametre_type) ?>">
        <input type="hidden" name="operation" value="ajouter">
        <input type="hidden" name="id" value=""> <!-- Pour la modification -->
        <input type="hidden" name="parametre_type" value="<?= htmlspecialchars($parametre_type) ?>">

        <div class="form-grid">
            <div class="form-group">
                <input type="text" name="libelle" id="libelle-<?= htmlspecialchars($parametre_type) ?>" class="form-input" placeholder=" " required>
                <label for="libelle-<?= htmlspecialchars($parametre_type) ?>" class="form-label">Libellé de l'UE</label>
            </div>
            <div class="form-group">
                <input type="number" name="credit" id="credit-<?= htmlspecialchars($parametre_type) ?>" class="form-input" placeholder=" " required min="0">
                <label for="credit-<?= htmlspecialchars($parametre_type) ?>" class="form-label">Crédits ECTS</label>
            </div>
            <div class="form-group">
                <select name="niveau_etude_id" id="niveau-etude-<?= htmlspecialchars($parametre_type) ?>" class="form-input" required>
                    <option value="" disabled selected>Choisir un niveau d'étude...</option>
                    <?php if (isset($niveauxEtude)): foreach ($niveauxEtude as $niveau): ?>
                        <option value="<?= htmlspecialchars($niveau->getId()) ?>"><?= htmlspecialchars($niveau->getLibelle()) ?></option>
                    <?php endforeach; endif; ?>
                </select>
                <label for="niveau-etude-<?= htmlspecialchars($parametre_type) ?>" class="form-label">Niveau d'Étude</label>
            </div>
        </div>
        <div class="form-actions" style="margin-top:1rem;">
            <button type="submit" class="btn btn-primary">Ajouter</button>
            <button type="button" class="btn btn-secondary btn-cancel-param" style="display: none;">Annuler</button>
        </div>
    </form>

    <!-- Tableau des UE existantes -->
    <?php $tableId = 'table-param-' . htmlspecialchars($parametre_type); ?>
    <div class="table-header" style="margin-top: 20px;">
        <h4 class="table-title">Liste des <?= htmlspecialchars($parametre_libelle) ?> existantes</h4>
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
                    <th>ID UE</th>
                    <th>Libellé</th>
                    <th>Crédits</th>
                    <th>Niveau d'Étude</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Assumons que $elements vient de UeDAO->recupererTousAvecDetails()
                // et contient 'niveau_etude_libelle' et 'niveau_etude_id'
                if (!empty($elements)):
                    foreach ($elements as $ue):
                ?>
                        <tr data-id="<?= htmlspecialchars($ue['id']) ?>"
                            data-libelle="<?= htmlspecialchars($ue['libelle']) ?>"
                            data-credit="<?= htmlspecialchars($ue['credit']) ?>"
                            data-niveau_etude_id="<?= htmlspecialchars($ue['niveau_etude_id']) ?>">
                            <td><input type="checkbox" class="checkbox select-param-item" name="ids[]" value="<?= htmlspecialchars($ue['id']) ?>"></td>
                            <td><?= htmlspecialchars($ue['id']) ?></td>
                            <td><?= htmlspecialchars($ue['libelle']) ?></td>
                            <td><?= htmlspecialchars($ue['credit']) ?></td>
                            <td><?= htmlspecialchars($ue['niveau_etude_libelle'] ?? 'N/A') ?></td>
                            <td>
                                <button class="btn-action btn-edit-param" title="Modifier">✏️</button>
                                <button class="btn-action btn-delete-param" title="Supprimer">🗑️</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center;">Aucune UE trouvée.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // Adaptation pour le btn-edit-param spécifique aux UE
    document.querySelectorAll('#container-<?= htmlspecialchars($parametre_type) ?> .btn-edit-param').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('tr');
            const form = document.querySelector('#container-<?= htmlspecialchars($parametre_type) ?> .form-parametre-general');

            form.querySelector('input[name="id"]').value = row.dataset.id;
            form.querySelector('input[name="libelle"]').value = row.dataset.libelle;
            form.querySelector('input[name="credit"]').value = row.dataset.credit;
            form.querySelector('select[name="niveau_etude_id"]').value = row.dataset.niveau_etude_id;

            form.querySelector('input[name="operation"]').value = 'modifier';
            form.querySelector('button[type="submit"]').textContent = 'Modifier';
            const btnCancel = form.querySelector('.btn-cancel-param');
            if(btnCancel) btnCancel.style.display = 'inline-block';

            form.querySelector('input[name="libelle"]').focus();
            window.scrollTo({ top: form.offsetTop - 20, behavior: 'smooth' });
        });
    });
</script>

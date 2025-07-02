<?php
// Données passées :
// $parametre_libelle ("Éléments Constitutifs (ECUE)")
// $parametre_type ("ecue")
// $elements (array des ECUEs existants, avec détails de l'UE associée)
// $ues (array des UE pour le select)
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

    <!-- Formulaire d'ajout/modification pour les ECUE -->
    <form action="/traitement-parametre-general" method="post" class="ajax-form form-parametre-general" data-target="#container-<?= htmlspecialchars($parametre_type) ?>">
        <input type="hidden" name="operation" value="ajouter">
        <input type="hidden" name="id" value=""> <!-- Pour la modification -->
        <input type="hidden" name="parametre_type" value="<?= htmlspecialchars($parametre_type) ?>">

        <div class="form-grid">
            <div class="form-group">
                <input type="text" name="libelle" id="libelle-<?= htmlspecialchars($parametre_type) ?>" class="form-input" placeholder=" " required>
                <label for="libelle-<?= htmlspecialchars($parametre_type) ?>" class="form-label">Libellé de l'ECUE</label>
            </div>
            <div class="form-group">
                <input type="number" name="credit_ecue" id="credit-ecue-<?= htmlspecialchars($parametre_type) ?>" class="form-input" placeholder=" " required min="0">
                <label for="credit-ecue-<?= htmlspecialchars($parametre_type) ?>" class="form-label">Crédits ECTS</label>
            </div>
            <div class="form-group">
                <select name="ue_id" id="ue-<?= htmlspecialchars($parametre_type) ?>" class="form-input" required>
                    <option value="" disabled selected>Choisir une UE...</option>
                    <?php if (isset($ues)): foreach ($ues as $ue): ?>
                        <option value="<?= htmlspecialchars($ue->getId()) ?>"><?= htmlspecialchars($ue->getLibelle()) ?></option>
                    <?php endforeach; endif; ?>
                </select>
                <label for="ue-<?= htmlspecialchars($parametre_type) ?>" class="form-label">Unité d'Enseignement (UE) Parente</label>
            </div>
             <div class="form-group">
                <input type="number" name="heure_cm" id="heure-cm-<?= htmlspecialchars($parametre_type) ?>" class="form-input" placeholder=" " min="0">
                <label for="heure-cm-<?= htmlspecialchars($parametre_type) ?>" class="form-label">Heures Cours Magistral (CM)</label>
            </div>
             <div class="form-group">
                <input type="number" name="heure_td" id="heure-td-<?= htmlspecialchars($parametre_type) ?>" class="form-input" placeholder=" " min="0">
                <label for="heure-td-<?= htmlspecialchars($parametre_type) ?>" class="form-label">Heures Travaux Dirigés (TD)</label>
            </div>
             <div class="form-group">
                <input type="number" name="heure_tp" id="heure-tp-<?= htmlspecialchars($parametre_type) ?>" class="form-input" placeholder=" " min="0">
                <label for="heure-tp-<?= htmlspecialchars($parametre_type) ?>" class="form-label">Heures Travaux Pratiques (TP)</label>
            </div>
        </div>
        <div class="form-actions" style="margin-top:1rem;">
            <button type="submit" class="btn btn-primary">Ajouter</button>
            <button type="button" class="btn btn-secondary btn-cancel-param" style="display: none;">Annuler</button>
        </div>
    </form>

    <!-- Tableau des ECUE existants -->
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
                    <th>ID ECUE</th>
                    <th>Libellé</th>
                    <th>Crédits</th>
                    <th>UE Parente</th>
                    <th>Vol. Horaire (CM/TD/TP)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Assumons que $elements vient de EcueDAO->recupererTousAvecDetails()
                // et contient 'ue_libelle' et 'ue_id'
                if (!empty($elements)):
                    foreach ($elements as $ecue):
                ?>
                        <tr data-id="<?= htmlspecialchars($ecue['id']) ?>"
                            data-libelle="<?= htmlspecialchars($ecue['libelle']) ?>"
                            data-credit_ecue="<?= htmlspecialchars($ecue['credit_ecue']) ?>"
                            data-ue_id="<?= htmlspecialchars($ecue['ue_id']) ?>"
                            data-heure_cm="<?= htmlspecialchars($ecue['heure_cm'] ?? '') ?>"
                            data-heure_td="<?= htmlspecialchars($ecue['heure_td'] ?? '') ?>"
                            data-heure_tp="<?= htmlspecialchars($ecue['heure_tp'] ?? '') ?>">
                            <td><input type="checkbox" class="checkbox select-param-item" name="ids[]" value="<?= htmlspecialchars($ecue['id']) ?>"></td>
                            <td><?= htmlspecialchars($ecue['id']) ?></td>
                            <td><?= htmlspecialchars($ecue['libelle']) ?></td>
                            <td><?= htmlspecialchars($ecue['credit_ecue']) ?></td>
                            <td><?= htmlspecialchars($ecue['ue_libelle'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars(($ecue['heure_cm'] ?? 0) . '/' . ($ecue['heure_td'] ?? 0) . '/' . ($ecue['heure_tp'] ?? 0)) ?></td>
                            <td>
                                <button class="btn-action btn-edit-param" title="Modifier">✏️</button>
                                <button class="btn-action btn-delete-param" title="Supprimer">🗑️</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align:center;">Aucun ECUE trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // Adaptation pour le btn-edit-param spécifique aux ECUE
    document.querySelectorAll('#container-<?= htmlspecialchars($parametre_type) ?> .btn-edit-param').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('tr');
            const form = document.querySelector('#container-<?= htmlspecialchars($parametre_type) ?> .form-parametre-general');

            form.querySelector('input[name="id"]').value = row.dataset.id;
            form.querySelector('input[name="libelle"]').value = row.dataset.libelle;
            form.querySelector('input[name="credit_ecue"]').value = row.dataset.credit_ecue;
            form.querySelector('select[name="ue_id"]').value = row.dataset.ue_id;
            form.querySelector('input[name="heure_cm"]').value = row.dataset.heure_cm;
            form.querySelector('input[name="heure_td"]').value = row.dataset.heure_td;
            form.querySelector('input[name="heure_tp"]').value = row.dataset.heure_tp;

            form.querySelector('input[name="operation"]').value = 'modifier';
            form.querySelector('button[type="submit"]').textContent = 'Modifier';
            const btnCancel = form.querySelector('.btn-cancel-param');
            if(btnCancel) btnCancel.style.display = 'inline-block';

            form.querySelector('input[name="libelle"]').focus();
            window.scrollTo({ top: form.offsetTop - 20, behavior: 'smooth' });
        });
    });
</script>

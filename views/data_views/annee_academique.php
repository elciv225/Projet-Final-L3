<?php
// Variables available:
// $parametre_label (e.g., "Annee Academique")
// $parametre_type (e.g., "annee_academique")
// $liste_donnees (array of AnneeAcademique objects)

$item_id_val = ''; // For pre-filling in edit mode
$date_debut_val = '';
$date_fin_val = '';

// TODO: Add logic for edit mode pre-fill
?>

<form class="form-section ajax-form" method="post" action="/parametres-generaux/executer-action" data-target="#parametre-<?= htmlspecialchars($parametre_type) ?>-table-container">
    <input type="hidden" name="operation" value="ajouter">
    <input type="hidden" name="parametre_type" value="<?= htmlspecialchars($parametre_type) ?>">
    <input type="hidden" name="id_item_original" value="<?= $item_id_val ?>"> <!-- For modification, to identify original record if ID itself is changed -->

    <div class="section-header">
        <h3 class="section-title">Ajouter/Modifier <?= htmlspecialchars($parametre_label) ?></h3>
    </div>
    <div class="section-content">
        <div class="form-grid">
            <div class="form-group">
                <input type="text" id="param_id" name="id" class="form-input" placeholder="ex: 2023-2024" value="<?= $item_id_val ?>" required>
                <label for="param_id" class="form-label">ID Année Académique (ex: 2023-2024)</label>
            </div>
            <div class="form-group">
                <input type="date" id="param_date_debut" name="date_debut" class="form-input" placeholder=" " value="<?= $date_debut_val ?>" required>
                <label for="param_date_debut" class="form-label">Date de Début</label>
            </div>
            <div class="form-group">
                <input type="date" id="param_date_fin" name="date_fin" class="form-input" placeholder=" " value="<?= $date_fin_val ?>" required>
                <label for="param_date_fin" class="form-label">Date de Fin</label>
            </div>
        </div>
    </div>
    <div class="section-bottom">
        <h3 class="section-title">Action</h3>
        <div style="display: flex">
            <button class="btn btn-primary" type="submit" id="param_submit_button_annee">Ajouter <?= htmlspecialchars($parametre_label) ?></button>
        </div>
    </div>
</form>

<div class="table-container" id="parametre-<?= htmlspecialchars($parametre_type) ?>-table-container">
    <div class="table-header">
        <h3 class="table-title">Liste des <?= htmlspecialchars($parametre_label) ?></h3>
        <div class="header-actions">
             <div class="search-container">
                <span class="search-icon">🔍</span>
                <input type="text" id="paramAnneeSearchInput" class="search-input" placeholder="Rechercher par ID...">
            </div>
        </div>
    </div>

    <div class="table-scroll-wrapper scroll-custom">
        <table class="table">
            <thead>
            <tr>
                <th><input type="checkbox" class="checkbox master-checkbox-param-annee"></th>
                <th>ID Année</th>
                <th>Date de Début</th>
                <th>Date de Fin</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if (isset($liste_donnees) && !empty($liste_donnees)): ?>
                <?php foreach ($liste_donnees as $item): ?>
                    <tr>
                        <td><input type="checkbox" class="checkbox item-checkbox-param-annee" value="<?= htmlspecialchars($item->getId()) ?>"></td>
                        <td><?= htmlspecialchars($item->getId()) ?></td>
                        <td><?= htmlspecialchars(date('d/m/Y', strtotime($item->getDateDebut()))) ?></td>
                        <td><?= htmlspecialchars(date('d/m/Y', strtotime($item->getDateFin()))) ?></td>
                        <td>
                            <button class="btn btn-action btn-edit-param-annee" data-id="<?= htmlspecialchars($item->getId()) ?>" data-debut="<?= htmlspecialchars($item->getDateDebut()) ?>" data-fin="<?= htmlspecialchars($item->getDateFin()) ?>">✏️</button>
                            <button class="btn btn-action btn-delete-param-annee" data-id="<?= htmlspecialchars($item->getId()) ?>">🗑️</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">Aucune année académique trouvée.</td>
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
// Basic JS for edit/delete for annee_academique
document.querySelectorAll('.btn-edit-param-annee').forEach(button => {
    button.addEventListener('click', function() {
        const itemId = this.dataset.id;
        const dateDebut = this.dataset.debut;
        const dateFin = this.dataset.fin;

        document.querySelector('input[name="id_item_original"]').value = itemId; // Hidden field for original ID
        document.querySelector('input[name="id"]').value = itemId; // ID field in form
        document.querySelector('input[name="date_debut"]').value = dateDebut;
        document.querySelector('input[name="date_fin"]').value = dateFin;

        document.querySelector('input[name="operation"]').value = 'modifier';
        document.getElementById('param_submit_button_annee').textContent = 'Modifier Année Académique';
    });
});

document.querySelectorAll('.btn-delete-param-annee').forEach(button => {
    button.addEventListener('click', function() {
        const itemId = this.dataset.id;
        if (confirm('Êtes-vous sûr de vouloir supprimer cette année académique ?')) {
            const form = document.createElement('form');
            form.method = 'post';
            form.action = '/parametres-generaux/executer-action';
            form.classList.add('ajax-form');
            form.dataset.target = '#parametre-<?= htmlspecialchars($parametre_type) ?>-table-container';

            const operationInput = document.createElement('input');
            operationInput.type = 'hidden';
            operationInput.name = 'operation';
            operationInput.value = 'supprimer';
            form.appendChild(operationInput);

            const typeInput = document.createElement('input');
            typeInput.type = 'hidden';
            typeInput.name = 'parametre_type';
            typeInput.value = '<?= htmlspecialchars($parametre_type) ?>'; // Should be 'annee_academique'
            form.appendChild(typeInput);

            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'id'; // Controller's executerActionParametre expects 'id' for deletion
            idInput.value = itemId;
            form.appendChild(idInput);

            document.body.appendChild(form);
            form.submit();
        }
    });
});
</script>

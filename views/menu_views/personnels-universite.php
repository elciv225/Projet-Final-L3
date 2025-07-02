<?php
// Données passées par le contrôleur:
// $personnels (array de tous les personnels)
// $fonctions (array de toutes les fonctions)
// $grades (array de tous les grades)
// $specialites (array de toutes les spécialités)
// $typesUtilisateur (array des types pour admin et enseignant)
// $groupesUtilisateur (array des groupes pour admin et enseignant)
?>
<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Gestion des Personnels de l'Université</h1>
            <p>Ajoutez, modifiez ou supprimez les informations des enseignants et du personnel administratif.</p>
        </div>
    </div>

    <!-- Formulaire d'ajout et de modification du personnel -->
    <form class="form-section  ajax-form" method="post" action="/traitement-personnel" data-target="#personnelTable > tbody">
        <input name="operation" id="form-operation-personnel" value="ajouter" type="hidden">
        <input name="id-personnel" id="id-personnel-form" value="" type="hidden">

        <div class="section-header">
            <h3 class="section-title" id="form-title-personnel">Ajouter un nouveau personnel</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <!-- Champs affichés uniquement en mode modification -->
                <div id="edit-only-fields-personnel" style="display: none; grid-column: 1 / -1; grid-template-columns: 1fr; gap: 1rem;">
                    <div class="form-group">
                        <input type="text" id="id-personnel-display" class="form-input" placeholder=" " disabled>
                        <label class="form-label" for="id-personnel-display">Identifiant du Personnel</label>
                    </div>
                </div>

                <!-- Champs communs -->
                <div class="form-group">
                    <input type="text" name="nom-personnel" id="nom-personnel" class="form-input" placeholder=" " required>
                    <label class="form-label" for="nom-personnel">Nom</label>
                </div>
                <div class="form-group">
                    <input type="text" name="prenom-personnel" id="prenom-personnel" class="form-input" placeholder=" " required>
                    <label class="form-label" for="prenom-personnel">Prénom(s)</label>
                </div>
                <div class="form-group">
                    <input type="email" name="email-personnel" id="email-personnel" class="form-input" placeholder=" " required>
                    <label class="form-label" for="email-personnel">Email</label>
                </div>
                <div class="form-group">
                    <input type="date" name="date-naissance-personnel" id="date-naissance-personnel" class="form-input" placeholder=" " required>
                    <label class="form-label" for="date-naissance-personnel">Date de naissance</label>
                </div>
                <div class="form-group">
                    <select class="form-input" id="type-personnel" name="type-personnel" required>
                        <option value="" disabled selected>Choisir un type...</option>
                        <option value="enseignant">Enseignant</option>
                        <option value="administratif">Personnel Administratif</option>
                    </select>
                    <label class="form-label" for="type-personnel">Type de Personnel</label>
                </div>
            </div>

            <!-- Champs spécifiques aux Enseignants -->
            <div id="champs-enseignant" style="display:none; margin-top: 1rem;">
                <h4 class="subsection-title">Informations Enseignant</h4>
                <div class="form-grid">
                    <div class="form-group">
                        <select class="form-input" id="grade-enseignant" name="grade-enseignant">
                            <option value="" disabled selected>Choisir un grade...</option>
                            <?php if (isset($grades)): foreach ($grades as $grade): ?>
                                <option value="<?= htmlspecialchars($grade->getId()) ?>"><?= htmlspecialchars($grade->getLibelle()) ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                        <label class="form-label" for="grade-enseignant">Grade</label>
                    </div>
                    <div class="form-group">
                        <input type="date" name="date-grade-enseignant" id="date-grade-enseignant" class="form-input" placeholder=" ">
                        <label class="form-label" for="date-grade-enseignant">Date d'obtention du Grade</label>
                    </div>
                    <div class="form-group">
                        <select class="form-input" id="fonction-enseignant" name="fonction-enseignant">
                            <option value="" disabled selected>Choisir une fonction...</option>
                            <?php if (isset($fonctions)): foreach ($fonctions as $fonction): ?>
                                <option value="<?= htmlspecialchars($fonction->getId()) ?>"><?= htmlspecialchars($fonction->getLibelle()) ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                        <label class="form-label" for="fonction-enseignant">Fonction</label>
                    </div>
                     <div class="form-group">
                        <input type="date" name="date-fonction-enseignant" id="date-fonction-enseignant" class="form-input" placeholder=" ">
                        <label class="form-label" for="date-fonction-enseignant">Date de prise de Fonction</label>
                    </div>
                    <div class="form-group">
                        <select class="form-input" id="specialite-enseignant" name="specialite-enseignant">
                            <option value="" disabled selected>Choisir une spécialité...</option>
                             <?php if (isset($specialites)): foreach ($specialites as $specialite): ?>
                                <option value="<?= htmlspecialchars($specialite->getId()) ?>"><?= htmlspecialchars($specialite->getLibelle()) ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                        <label class="form-label" for="specialite-enseignant">Spécialité</label>
                    </div>
                    <div class="form-group">
                        <input type="date" name="date-specialite-enseignant" id="date-specialite-enseignant" class="form-input" placeholder=" ">
                        <label class="form-label" for="date-specialite-enseignant">Date d'affectation Spécialité</label>
                    </div>
                </div>
            </div>

            <!-- Champs spécifiques au Personnel Administratif -->
            <div id="champs-administratif" style="display:none; margin-top: 1rem;">
                <h4 class="subsection-title">Informations Personnel Administratif</h4>
                <div class="form-grid">
                    <div class="form-group">
                        <select class="form-input" id="fonction-administratif" name="fonction-administratif">
                            <option value="" disabled selected>Choisir une fonction...</option>
                            <?php if (isset($fonctions)): foreach ($fonctions as $fonction): ?>
                                <option value="<?= htmlspecialchars($fonction->getId()) ?>"><?= htmlspecialchars($fonction->getLibelle()) ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                        <label class="form-label" for="fonction-administratif">Fonction</label>
                    </div>
                    <div class="form-group">
                        <input type="date" name="date-fonction-administratif" id="date-fonction-administratif" class="form-input" placeholder=" ">
                        <label class="form-label" for="date-fonction-administratif">Date de prise de Fonction</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="section-bottom">
            <h3 class="section-title">Action</h3>
            <div style="display: flex; gap: 10px;">
                <button class="btn btn-primary" id="btn-submit-form-personnel" type="submit">Ajouter</button>
                <button class="btn btn-secondary" id="btn-cancel-edit-personnel" type="button" style="display: none;">Annuler</button>
            </div>
        </div>
    </form>

    <!-- Tableau des personnels -->
    <div class="table-container" id="container-personnelTable">
        <div class="table-header">
            <h3 class="table-title">Liste des Personnels</h3>
            <div class="header-actions">
                <div class="search-container">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="searchInput-personnelTable" class="search-input" placeholder="Rechercher (Nom, Email, ID)...">
                </div>
                <form id="delete-form-personnelTable" class="ajax-form" method="post" action="/traitement-personnel" data-warning="Êtes-vous sûr de vouloir supprimer les personnels sélectionnés ?" data-target="#personnelTable tbody">
                    <input name="operation" value="supprimer" type="hidden">
                    <div id="hidden-inputs-for-delete-personnelTable"></div>
                    <button type="submit" class="btn btn-primary" id="btnSupprimerSelection-personnelTable">Supprimer la sélection</button>
                </form>
            </div>
        </div>
        <div class="table-scroll-wrapper scroll-custom">
            <table class="table" id="personnelTable">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll-personnelTable" class="checkbox"></th>
                        <th>ID</th>
                        <th>Nom & Prénom(s)</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Grade/Fonction Principale</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($personnels) && !empty($personnels)): ?>
                        <?php foreach ($personnels as $p): ?>
                            <tr data-id="<?= htmlspecialchars($p['id'] ?? '') ?>"
                                data-nom="<?= htmlspecialchars($p['nom'] ?? '') ?>"
                                data-prenoms="<?= htmlspecialchars($p['prenoms'] ?? '') ?>"
                                data-email="<?= htmlspecialchars($p['email'] ?? '') ?>"
                                data-date-naissance="<?= htmlspecialchars($p['date_naissance'] ?? '') ?>"
                                data-type-personnel="<?= htmlspecialchars($p['type_personnel_id'] ?? '') ?>"
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
                                <td><?= htmlspecialchars(ucfirst($p['type_personnel_id'] ?? 'N/A')) ?></td>
                                <td>
                                    <?php
                                    if (($p['type_personnel_id'] ?? '') === 'enseignant') {
                                        echo htmlspecialchars($p['grade'] ?? ($p['fonction'] ?? 'N/A'));
                                    } elseif (($p['type_personnel_id'] ?? '') === 'administratif') {
                                        echo htmlspecialchars($p['fonction'] ?? 'N/A');
                                    } else {
                                        echo 'N/A';
                                    }
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
                </tbody>
            </table>
        </div>
        <div class="table-footer">
            <div class="results-info" id="resultsInfo-personnelTable"></div>
            <div class="pagination" id="pagination-personnelTable"></div>
        </div>
    </div>
</main>

<!-- Le script JS spécifique à cette page doit être chargé ici ou après, dans le layout principal -->
<!-- Assurez-vous que public/assets/js/personnels-universite.js est bien créé et inclus -->
<script src="/assets/js/personnels-universite.js" defer></script>
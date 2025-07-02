<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Gestion des Enseignants</h1>
        </div>
    </div>

    <!-- Formulaire pour l'ajout et la modification d'un enseignant -->
    <form class="form-section ajax-form" method="post" action="/traitement-enseignant" data-target="#container-enseignantTable">
        <input name="operation" id="form-operation" value="ajouter" type="hidden">
        <input name="id-utilisateur" id="id-utilisateur-form" value="" type="hidden">

        <div class="section-header">
            <h3 class="section-title" id="form-title">Ajouter un nouvel enseignant</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <!-- Champs communs -->
                <div class="form-group">
                    <input type="text" name="nom-enseignant" id="nom-enseignant" class="form-input" placeholder=" " required>
                    <label class="form-label" for="nom-enseignant">Nom</label>
                </div>
                <div class="form-group">
                    <input type="text" name="prenom-enseignant" id="prenom-enseignant" class="form-input" placeholder=" " required>
                    <label class="form-label" for="prenom-enseignant">Prénom(s)</label>
                </div>
                <div class="form-group">
                    <input type="email" name="email-enseignant" id="email-enseignant" class="form-input" placeholder=" " required>
                    <label class="form-label" for="email-enseignant">Email</label>
                </div>
                <div class="form-group">
                    <input type="date" name="date-naissance" id="date-naissance" class="form-input" placeholder=" " required>
                    <label class="form-label" for="date-naissance">Date de naissance</label>
                </div>
                <div class="form-group">
                    <input type="text" name="login" id="login" class="form-input" placeholder=" " readonly>
                    <label class="form-label" for="login">Login (généré)</label>
                </div>
                <!-- Champs spécifiques à l'enseignant -->
                <div class="form-group">
                    <select name="id-grade" class="form-input" id="id-grade" required>
                        <option value="" disabled selected>Choisir un grade...</option>
                        <?php if (isset($grades)): foreach ($grades as $grade): ?>
                            <option value="<?= htmlspecialchars($grade->getId()) ?>"><?= htmlspecialchars($grade->getLibelle()) ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                    <label class="form-label" for="id-grade">Grade</label>
                </div>
                <div class="form-group">
                    <select name="id-specialite" class="form-input" id="id-specialite" required>
                        <option value="" disabled selected>Choisir une spécialité...</option>
                        <?php if (isset($specialites)): foreach ($specialites as $specialite): ?>
                            <option value="<?= htmlspecialchars($specialite->getId()) ?>"><?= htmlspecialchars($specialite->getLibelle()) ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                    <label class="form-label" for="id-specialite">Spécialité</label>
                </div>
                <div class="form-group">
                    <select name="id-fonction" class="form-input" id="id-fonction">
                        <option value="">Aucune fonction</option>
                        <?php if (isset($fonctions)): foreach ($fonctions as $fonction): ?>
                            <option value="<?= htmlspecialchars($fonction->getId()) ?>"><?= htmlspecialchars($fonction->getLibelle()) ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                    <label class="form-label" for="id-fonction">Fonction (Optionnel)</label>
                </div>
            </div>
        </div>
        <div class="section-bottom">
            <h3 class="section-title">Action</h3>
            <div style="display: flex; gap: 10px;">
                <button class="btn btn-primary" id="btn-submit-form" type="submit">Ajouter</button>
                <button class="btn btn-secondary" id="btn-cancel-edit" type="button" style="display: none;">Annuler</button>
            </div>
        </div>
    </form>

    <!-- Tableau des enseignants -->
    <div class="table-container" id="container-enseignantTable">
        <div class="table-header">
            <h3 class="table-title">Liste des Enseignants</h3>
            <div class="header-actions">
                <div class="search-container">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="searchInput-enseignantTable" class="search-input" placeholder="Rechercher...">
                </div>
                <form id="delete-form-enseignantTable" class="ajax-form" method="post" action="/traitement-enseignant" data-target="#container-enseignantTable">
                    <input name="operation" value="supprimer" type="hidden">
                    <div id="hidden-inputs-for-delete-enseignantTable"></div>
                    <button type="submit" class="btn btn-primary">Supprimer la sélection</button>
                </form>
            </div>
        </div>
        <div class="table-scroll-wrapper scroll-custom">
            <table class="table" id="enseignantTable">
                <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll-enseignantTable" class="checkbox"></th>
                    <th>ID</th>
                    <th>Nom & Prénom(s)</th>
                    <th>Email</th>
                    <th>Grade</th>
                    <th>Spécialité</th>
                    <th>Fonction</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($enseignants) && !empty($enseignants)): foreach ($enseignants as $ens): ?>
                    <tr data-user-id="<?= htmlspecialchars($ens['id']) ?>">
                        <td><input type="checkbox" class="checkbox" value="<?= htmlspecialchars($ens['id']) ?>"></td>
                        <td class="user-id"><?= htmlspecialchars($ens['id']) ?></td>
                        <td class="user-data"
                            data-nom="<?= htmlspecialchars($ens['nom']) ?>"
                            data-prenoms="<?= htmlspecialchars($ens['prenoms']) ?>"
                            data-email="<?= htmlspecialchars($ens['email']) ?>"
                            data-naissance="<?= htmlspecialchars($ens['date_naissance']) ?>">
                            <?= htmlspecialchars($ens['nom'] . ' ' . $ens['prenoms']) ?>
                        </td>
                        <td class="user-email"><?= htmlspecialchars($ens['email']) ?></td>
                        <td class="grade-data" data-grade-id="<?= htmlspecialchars($ens['grade_id']) ?>"><?= htmlspecialchars($ens['grade'] ?? 'N/A') ?></td>
                        <td class="specialite-data" data-specialite-id="<?= htmlspecialchars($ens['specialite_id']) ?>"><?= htmlspecialchars($ens['specialite'] ?? 'N/A') ?></td>
                        <td class="fonction-data" data-fonction-id="<?= htmlspecialchars($ens['fonction_id']) ?>"><?= htmlspecialchars($ens['fonction'] ?? 'N/A') ?></td>
                        <td>
                            <button class="btn-action btn-edit" title="Modifier">✏️</button>
                            <button class="btn-action btn-delete-single" title="Supprimer">🗑️</button>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="8" style="text-align: center;">Aucun enseignant trouvé.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="table-footer">
            <div class="results-info" id="resultsInfo-enseignantTable"></div>
            <div class="pagination" id="pagination-enseignantTable"></div>
        </div>
    </div>
</main>

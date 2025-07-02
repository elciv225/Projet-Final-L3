<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Gestion du Personnel Administratif</h1>
        </div>
    </div>

    <!-- Formulaire pour l'ajout et la modification -->
    <form class="form-section ajax-form" method="post" action="/traitement-personnel-admin" data-target="#container-adminTable">
        <input name="operation" id="form-operation" value="ajouter" type="hidden">
        <input name="id-utilisateur" id="id-utilisateur-form" value="" type="hidden">

        <div class="section-header">
            <h3 class="section-title" id="form-title">Ajouter un membre du personnel</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
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
                    <input type="date" name="date-naissance" id="date-naissance" class="form-input" placeholder=" " required>
                    <label class="form-label" for="date-naissance">Date de naissance</label>
                </div>
                <div class="form-group">
                    <select class="form-input" id="id-type-utilisateur" name="id-type-utilisateur" required>
                        <option value="" disabled selected>Choisir un type...</option>
                        <?php if (isset($typesUtilisateur)): foreach ($typesUtilisateur as $type): ?>
                            <option value="<?= htmlspecialchars($type->getId()) ?>">
                                <?= htmlspecialchars($type->getLibelle()) ?>
                            </option>
                        <?php endforeach; endif; ?>
                    </select>
                    <label class="form-label" for="id-type-utilisateur">Type d'utilisateur</label>
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

    <!-- Tableau du personnel -->
    <div class="table-container" id="container-adminTable">
        <div class="table-header">
            <h3 class="table-title">Liste du Personnel</h3>
            <div class="header-actions">
                <div class="search-container">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="searchInput-adminTable" class="search-input" placeholder="Rechercher...">
                </div>
                <form id="delete-form-adminTable" class="ajax-form" method="post" action="/traitement-personnel-admin" data-target="#container-adminTable">
                    <input name="operation" value="supprimer" type="hidden">
                    <div id="hidden-inputs-for-delete-adminTable"></div>
                    <button type="submit" class="btn btn-primary">Supprimer la sélection</button>
                </form>
            </div>
        </div>
        <div class="table-scroll-wrapper scroll-custom">
            <table class="table" id="adminTable">
                <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll-adminTable" class="checkbox"></th>
                    <th>ID</th>
                    <th>Nom & Prénom(s)</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($personnels) && !empty($personnels)): foreach ($personnels as $personnel): ?>
                    <tr data-user-id="<?= htmlspecialchars($personnel['id']) ?>">
                        <td><input type="checkbox" class="checkbox" value="<?= htmlspecialchars($personnel['id']) ?>"></td>
                        <td class="user-id"><?= htmlspecialchars($personnel['id']) ?></td>
                        <td class="user-data"
                            data-nom="<?= htmlspecialchars($personnel['nom']) ?>"
                            data-prenoms="<?= htmlspecialchars($personnel['prenoms']) ?>"
                            data-email="<?= htmlspecialchars($personnel['email']) ?>"
                            data-naissance="<?= htmlspecialchars($personnel['date_naissance']) ?>">
                            <?= htmlspecialchars($personnel['nom'] . ' ' . $personnel['prenoms']) ?>
                        </td>
                        <td class="user-email"><?= htmlspecialchars($personnel['email']) ?></td>
                        <td>
                            <button class="btn-action btn-edit" title="Modifier">✏️</button>
                            <button class="btn-action btn-delete-single" title="Supprimer">🗑️</button>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="5" style="text-align: center;">Aucun personnel trouvé.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="table-footer">
            <div class="results-info" id="resultsInfo-adminTable"></div>
            <div class="pagination" id="pagination-adminTable"></div>
        </div>
    </div>
</main>

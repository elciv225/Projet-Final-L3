<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Gestion des Utilisateurs</h1>
        </div>
    </div>

    <form class="form-section  ajax-form" method="post" action="/traitement-utilisateur"
          data-target=".table-scroll-wrapper">
        <input name="operation" value="ajouter" type="hidden">
        <div class="section-header">
            <h3 class="section-title">Informations Générales de l'utilisateur</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <input type="text" name="nom-utilisateur" id="nom-utilisateur" class="form-input" placeholder=" ">
                    <label class="form-label" for="nom-utilisateur">Nom</label>
                </div>
                <div class="form-group">
                    <input type="text" name="prenom-utilisateur" id="prenom-utilisateur" class="form-input"
                           placeholder=" ">
                    <label class="form-label" for="prenom-utilisateur">Prénom</label>
                </div>
                <div class="form-group">
                    <input type="email" name="email-utilisateur" id="email-utilisateur" class="form-input"
                           placeholder=" ">
                    <label class="form-label" for="email-utilisateur">Email</label>
                </div>
                <div class="form-group">
                    <input type="date" name="date-naissance" id="date-naissance" class="form-input" placeholder=" ">
                    <label class="form-label" for="date-naissance">Date de naissance</label>
                </div>
                <div class="form-group">
                    <select class="form-input" id="id-type-utilisateur" name="id-type-utilisateur">
                        <option value="">Type Utilisateur</option>
                        <?php if (isset($typesUtilisateur)): ?>
                            <?php foreach ($typesUtilisateur as $typeUtilisateur): ?>
                                <option value="<?= $typeUtilisateur->getId() ?>"
                                        data-category-id="<?= $typeUtilisateur->getCategorieUtilisateurId() ?>">
                                    <?= htmlspecialchars($typeUtilisateur->getLibelle()) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <label class="form-label" for="id-type-utilisateur">Type d'utilisateur</label>
                </div>
                <div class="form-group">
                    <?php
                    $groupCategoryMap = [
                        'GRP_ETUDIANTS' => 'CAT_ETUDIANT',
                        'GRP_ETUDIANT_STD' => 'CAT_ETUDIANT',
                        'GRP_VALID_RAPPORT' => 'CAT_ENSEIGNANT',
                        'GRP_ADMIN_PEDAGO' => 'CAT_ADMIN'
                    ];
                    ?>
                    <select class="form-input" id="id-groupe-utilisateur" name="id-groupe-utilisateur">
                        <option value="">Groupe Utilisateur</option>
                        <?php if (isset($groupesUtilisateur)): ?>
                            <?php foreach ($groupesUtilisateur as $groupeUtilisateur): ?>
                                <option value="<?= $groupeUtilisateur->getId() ?>"
                                        data-category-map="<?= $groupCategoryMap[$groupeUtilisateur->getId()] ?? '' ?>">
                                    <?= htmlspecialchars($groupeUtilisateur->getLibelle()) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <label class="form-label" for="id-groupe-utilisateur">Groupe utilisateur</label>
                </div>
                <div class="form-group">
                    <input type="text" name="login" id="login" class="form-input" placeholder="" value="login généré"
                           readonly>
                    <label class="form-label" for="login">Login</label>
                </div>
            </div>
        </div>
        <div class="section-bottom">
            <h3 class="section-title">Action</h3>
            <div style="display: flex">
                <button class="btn btn-primary" type="submit">Créer</button>
            </div>
        </div>
    </form>

    <div class="table-container" id="container-userTable">
        <div class="table-header">
            <h3 class="table-title">Liste des Utilisateurs</h3>
            <div class="header-actions">
                <div class="search-container">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="searchInput-userTable" class="search-input"
                           placeholder="Rechercher par ...">
                </div>
            </div>
            <div class="header-actions">
                <button id="btnExportPDF-userTable" class="btn btn-secondary">🕐 Exporter en PDF</button>
                <button id="btnExportExcel-userTable" class="btn btn-secondary">📤 Exporter sur Excel</button>
                <button id="btnPrint-userTable" class="btn btn-secondary">📊 Imprimer</button>

                <form id="delete-form-userTable" class="ajax-form" method="post" action="/traitement-utilisateur"
                      data-warning="" data-target=".table-scroll-wrapper">
                    <input name="operation" value="supprimer" type="hidden">
                    <div id="hidden-inputs-for-delete-userTable"></div>
                    <button type="submit" class="btn btn-primary" id="btnSupprimerSelection-userTable">Supprimer
                    </button>
                </form>
            </div>
        </div>

        <div style="padding: 0 24px; border-bottom: 1px solid #E5E7EB;">
            <div class="table-tabs">
                <div class="tab active">Tout sélectionner</div>
            </div>
        </div>
        <div class="table-scroll-wrapper scroll-custom">
            <table class="table" id="userTable">
                <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll-userTable" class="checkbox"></th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Type d'utilisateur</th>
                    <th>Groupe utilisateur</th>
                    <th>Login</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($utilisateurs) && !empty($utilisateurs)): ?>
                    <?php foreach ($utilisateurs as $utilisateur): ?>
                        <tr>
                            <td><input type="checkbox" class="checkbox" value="<?= $utilisateur['id'] ?>"></td>
                            <td><?= htmlspecialchars($utilisateur['nom'] ?? '') ?></td>
                            <td><?= htmlspecialchars($utilisateur['prenoms'] ?? '') ?></td>
                            <td><?= htmlspecialchars($utilisateur['email'] ?? '') ?></td>
                            <td><?= htmlspecialchars($utilisateur['type_user'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($utilisateur['groupe'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($utilisateur['id'] ?? '') ?></td>
                            <td>
                                <button class="btn-action">✏️</button>
                                <button class="btn-action">🗑️</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align: center;">Aucun utilisateur trouvé.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="table-footer">
            <div class="results-info" id="resultsInfo-userTable"></div>
            <div class="pagination" id="pagination-userTable"></div>
        </div>
    </div>

</main>
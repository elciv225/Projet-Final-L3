<?php
// Données passées par le contrôleur: $utilisateurs, $typesUtilisateur, $groupesUtilisateur
?>
<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Gestion des Utilisateurs</h1>
            <p>Créez, modifiez ou supprimez les comptes utilisateurs du système.</p>
        </div>
    </div>

    <!-- Formulaire d'ajout et de modification des utilisateurs -->
    <form class="form-section  ajax-form" method="post" action="/traitement-utilisateur" data-target="#userTable > tbody">
        <input name="operation" value="ajouter" type="hidden">
        <!-- L'input pour id-utilisateur sera ajouté par JS en mode édition si besoin -->

        <div class="section-header">
            <h3 class="section-title">Informations Générales de l'Utilisateur</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <input type="text" name="nom-utilisateur" id="nom-utilisateur" class="form-input" placeholder=" " required>
                    <label class="form-label" for="nom-utilisateur">Nom</label>
                </div>
                <div class="form-group">
                    <input type="text" name="prenom-utilisateur" id="prenom-utilisateur" class="form-input" placeholder=" " required>
                    <label class="form-label" for="prenom-utilisateur">Prénom(s)</label>
                </div>
                <div class="form-group">
                    <input type="email" name="email-utilisateur" id="email-utilisateur" class="form-input" placeholder=" " required>
                    <label class="form-label" for="email-utilisateur">Email</label>
                </div>
                <div class="form-group">
                    <input type="date" name="date-naissance" id="date-naissance" class="form-input" placeholder=" " required>
                    <label class="form-label" for="date-naissance">Date de naissance</label>
                </div>
                <div class="form-group">
                    <select class="form-input" id="id-type-utilisateur" name="id-type-utilisateur" required>
                        <option value="" disabled selected>Choisir un type...</option>
                        <?php if (isset($typesUtilisateur)): foreach ($typesUtilisateur as $type): ?>
                            <option value="<?= htmlspecialchars($type->getId()) ?>" data-category-id="<?= htmlspecialchars($type->getCategorieUtilisateurId()) ?>">
                                <?= htmlspecialchars($type->getLibelle()) ?>
                            </option>
                        <?php endforeach; endif; ?>
                    </select>
                    <label class="form-label" for="id-type-utilisateur">Type d'utilisateur</label>
                </div>
                <div class="form-group">
                     <?php
                    // Mapping des catégories de groupe pour le filtrage JS
                    // Ce mapping doit être cohérent avec la structure de vos données
                    // Exemple: GRP_ETUDIANTS appartient à CAT_ETUDIANT
                    $groupCategoryMapJs = [];
                    if (isset($groupesUtilisateur)) {
                        foreach ($groupesUtilisateur as $groupe) {
                            // Supposons que l'ID du groupe contient une indication de sa catégorie
                            // ou que vous avez une autre méthode pour déterminer cette catégorie.
                            // Ceci est un exemple simplifié.
                            if (str_starts_with($groupe->getId(), 'GRP_ETUD')) $catId = 'CAT_ETUDIANT';
                            elseif (str_starts_with($groupe->getId(), 'GRP_VALID_RAPPORT')) $catId = 'CAT_ENSEIGNANT';
                             elseif (str_starts_with($groupe->getId(), 'GRP_ADMIN')) $catId = 'CAT_ADMIN';
                            else $catId = 'CAT_AUTRE'; // Fallback
                            $groupCategoryMapJs[$groupe->getId()] = $catId;
                        }
                    }
                    ?>
                    <select class="form-input" id="id-groupe-utilisateur" name="id-groupe-utilisateur" required>
                        <option value="" disabled selected>Choisir un groupe...</option>
                        <?php if (isset($groupesUtilisateur)): foreach ($groupesUtilisateur as $groupe): ?>
                            <option value="<?= htmlspecialchars($groupe->getId()) ?>" data-category-map="<?= $groupCategoryMapJs[$groupe->getId()] ?? '' ?>">
                                <?= htmlspecialchars($groupe->getLibelle()) ?>
                            </option>
                        <?php endforeach; endif; ?>
                    </select>
                    <label class="form-label" for="id-groupe-utilisateur">Groupe utilisateur</label>
                </div>
                <div class="form-group">
                    <input type="text" name="login" id="login" class="form-input" placeholder=" " value="login généré" readonly>
                    <label class="form-label" for="login">Login (Auto-généré)</label>
                </div>
                 <!-- Bouton Annuler pour le mode édition, initialement caché -->
                <div class="form-group" style="grid-column: 1 / -1; text-align: right; display: none;" id="cancel-button-container">
                    <button class="btn btn-secondary" id="btn-cancel-edit-user" type="button">Annuler</button>
                </div>
            </div>
        </div>
        <div class="section-bottom">
            <h3 class="section-title">Action</h3>
            <div style="display: flex; gap: 10px;">
                <button class="btn btn-primary" type="submit">Créer</button>
                 <!-- Le bouton Annuler est déplacé dans le form-grid pour une meilleure mise en page -->
            </div>
        </div>
    </form>

    <!-- Tableau des utilisateurs -->
    <div class="table-container" id="container-userTable">
        <div class="table-header">
            <h3 class="table-title">Liste des Utilisateurs</h3>
            <div class="header-actions">
                <div class="search-container">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="searchInput-userTable" class="search-input" placeholder="Rechercher (Nom, Email, Login)...">
                </div>
                <form id="delete-form-userTable" class="ajax-form" method="post" action="/traitement-utilisateur" data-warning="" data-target="#userTable tbody">
                    <input name="operation" value="supprimer" type="hidden">
                    <div id="hidden-inputs-for-delete-userTable"></div>
                    <button type="submit" class="btn btn-primary" id="btnSupprimerSelection-userTable">Supprimer la sélection</button>
                </form>
            </div>
        </div>
        <div class="table-scroll-wrapper scroll-custom">
            <table class="table" id="userTable">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll-userTable" class="checkbox select-all-users"></th>
                        <th>Nom</th>
                        <th>Prénom(s)</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Groupe</th>
                        <th>Login (ID)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($utilisateurs) && !empty($utilisateurs)): ?>
                        <?php foreach ($utilisateurs as $user):
                            // Préparation des data-attributes pour l'édition
                            $dataAttrs = 'data-id="'.htmlspecialchars($user['id']).'" ';
                            $dataAttrs .= 'data-nom="'.htmlspecialchars($user['nom']).'" ';
                            $dataAttrs .= 'data-prenoms="'.htmlspecialchars($user['prenoms']).'" ';
                            $dataAttrs .= 'data-email="'.htmlspecialchars($user['email']).'" ';
                            // Pour la date de naissance, type et groupe, il faut les récupérer depuis la source de données complète.
                            // $daoUtilisateur->recupererTousAvecDetails() doit les fournir.
                            // Supposons qu'ils sont nommés 'date_naissance', 'type_utilisateur_id', 'groupe_utilisateur_id'
                            // dans le tableau $user retourné par recupererTousAvecDetails().
                            // Si ce n'est pas le cas, il faudra ajuster UtilisateurDAO.
                            $userFull = (new \App\Dao\UtilisateurDAO(System\Database\Database::getConnection()))->recupererParId($user['id']);
                            if ($userFull) {
                                $dataAttrs .= 'data-date-naissance="'.htmlspecialchars($userFull->getDateNaissance() ?? '').'" ';
                                $dataAttrs .= 'data-type-utilisateur-id="'.htmlspecialchars($userFull->getTypeUtilisateurId() ?? '').'" ';
                                $dataAttrs .= 'data-groupe-utilisateur-id="'.htmlspecialchars($userFull->getGroupeUtilisateurId() ?? '').'" ';
                            }
                        ?>
                            <tr <?= $dataAttrs ?>>
                                <td><input type="checkbox" class="checkbox select-user" name="ids[]" value="<?= htmlspecialchars($user['id']) ?>"></td>
                                <td><?= htmlspecialchars($user['nom'] ?? '') ?></td>
                                <td><?= htmlspecialchars($user['prenoms'] ?? '') ?></td>
                                <td><?= htmlspecialchars($user['email'] ?? '') ?></td>
                                <td><?= htmlspecialchars($user['type_user'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($user['groupe'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($user['id'] ?? '') ?></td>
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
                </tbody>
            </table>
        </div>
        <div class="table-footer">
            <div class="results-info" id="resultsInfo-userTable"></div>
            <div class="pagination" id="pagination-userTable"></div>
        </div>
    </div>
</main>

<script src="/assets/js/utilisateurs.js" defer></script>
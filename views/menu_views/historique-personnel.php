<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Historique des Utilisateurs</h1>
        </div>
        <div class="form-group small-width right-align  mb-20">
            <form method="post" action="/charger-donnee-historique-utilisateur" class="ajax-form"
                  data-target=".form-section">
                <select id="selectTypeUtilisateur" class="form-input select-submit" name="type-utilisateur">
                    <option value="">Sélectionner un type d'utilisateur</option>
                    <option value="enseignant">Enseignant</option>
                    <option value="personnel_administratif">Personnel Administratif</option>
                </select>
                <label for="selectTypeUtilisateur" class="form-label">Type Utilisateur</label>
            </form>
        </div>
    </div>

    <!-- Section de sélection des filtres -->
    <form class="form-section ajax-form" method="post" action="/charger-historique-personnel" data-target=".table">
        <div class="section-header">
            <h3 class="section-title">Informations Générales de l'utilisateur</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <div class="select-wrapper">
                        <select id="selectTypeHistorique" class="form-input"
                                name="type-historique" <?= (isset($selectActive) && $selectActive === true) ? '' : 'disabled' ?>>
                            <option value="">Sélectionner un type d'historique</option>
                            <option value="fonction">Historique Fonction</option>
                            <option value="grade">Historique Grade</option>
                        </select>
                        <label for="selectTypeHistorique" class="form-label">Type historique</label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="select-wrapper">
                        <select id="selectUtilisateurSpecifique" name="utilisateur"
                                class="form-input" <?= (isset($selectActive) && $selectActive === true) ? '' : 'disabled' ?>>
                            <option value="">Sélectionner un utilisateur</option>
                            <?php if (isset($listeUtilisateur)): ?>
                                <?php foreach ($listeUtilisateur as $utilisateur): ?>
                                    <option value="<?= $utilisateur['utilisateur_id'] ?>"><?= $utilisateur['nom-prenom'] ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <label for="selectUtilisateurSpecifique" class="form-label">Utilisateur selectionné</label>
                    </div>
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

    <!-- Tableau d'historique -->
    <div class="table-container">
        <div class="table-header">
            <h3>Détails de l'Historique</h3>
        </div>
        <div class="table-scroll-wrapper scroll-custom">
        <table class="table">
            <thead>
            <tr id="enTeteTableHistorique">
                <?php if (!empty($entete)): ?>
                    <?php foreach ($entete as $titre): ?>
                        <th><?= htmlspecialchars($titre) ?></th>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tr>
            </thead>
            <tbody id="corpsTableHistorique">
            <?php if (!empty($corps)): ?>
                <?php foreach ($corps as $ligne): ?>
                    <tr>
                        <?php foreach ($ligne as $valeur): ?>
                            <td><?= htmlspecialchars($valeur) ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= (!empty($entete)) ? count($entete) : 3; ?>" style="text-align: center; padding: 20px;">
                        <?php if (isset($donneesChargees) && $donneesChargees): ?>
                            Aucune donnée trouvée pour les critères sélectionnés.
                        <?php else: ?>
                            Veuillez sélectionner les filtres ci-dessus pour afficher l'historique.
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
        </div>
        <div class="table-footer">
            <div class="results-info">
                Affichage de 1 à 9 sur 240 entrées
            </div>
            <div class="pagination">
                <button class="pagination-btn">‹</button>
                <button class="pagination-btn active">1</button>
                <button class="pagination-btn">2</button>
                <button class="pagination-btn">3</button>
                <span>...</span>
                <button class="pagination-btn">12</button>
                <button class="pagination-btn">›</button>
            </div>
        </div>
    </div>
</main>

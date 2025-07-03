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
                <?php if (isset($pagination) && $pagination['totalItems'] > 0): ?>
                    Affichage de <?= $pagination['startIndex'] ?> à <?= $pagination['endIndex'] ?> sur <?= $pagination['totalItems'] ?> entrées
                <?php else: ?>
                    Aucune entrée à afficher
                <?php endif; ?>
            </div>
            <?php if (isset($pagination) && $pagination['totalPages'] > 1): ?>
            <div class="pagination">
                <!-- Bouton précédent -->
                <?php if ($pagination['page'] > 1): ?>
                    <button class="pagination-btn pagination-prev" data-page="<?= $pagination['page'] - 1 ?>">‹</button>
                <?php else: ?>
                    <button class="pagination-btn" disabled>‹</button>
                <?php endif; ?>

                <!-- Première page -->
                <button class="pagination-btn <?= $pagination['page'] == 1 ? 'active' : '' ?>" data-page="1">1</button>

                <!-- Pages intermédiaires -->
                <?php
                $startPage = max(2, $pagination['page'] - 1);
                $endPage = min($pagination['totalPages'] - 1, $pagination['page'] + 1);

                if ($startPage > 2): ?>
                    <span>...</span>
                <?php endif;

                for ($i = $startPage; $i <= $endPage; $i++): ?>
                    <button class="pagination-btn <?= $pagination['page'] == $i ? 'active' : '' ?>" data-page="<?= $i ?>"><?= $i ?></button>
                <?php endfor;

                if ($endPage < $pagination['totalPages'] - 1): ?>
                    <span>...</span>
                <?php endif; ?>

                <!-- Dernière page -->
                <?php if ($pagination['totalPages'] > 1): ?>
                    <button class="pagination-btn <?= $pagination['page'] == $pagination['totalPages'] ? 'active' : '' ?>" data-page="<?= $pagination['totalPages'] ?>"><?= $pagination['totalPages'] ?></button>
                <?php endif; ?>

                <!-- Bouton suivant -->
                <?php if ($pagination['page'] < $pagination['totalPages']): ?>
                    <button class="pagination-btn pagination-next" data-page="<?= $pagination['page'] + 1 ?>">›</button>
                <?php else: ?>
                    <button class="pagination-btn" disabled>›</button>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ajouter des écouteurs d'événements pour les boutons de pagination
    document.querySelectorAll('.pagination-btn').forEach(function(button) {
        if (!button.disabled && button.hasAttribute('data-page')) {
            button.addEventListener('click', function() {
                const page = this.getAttribute('data-page');
                navigateToPage(page);
            });
        }
    });

    // Fonction pour naviguer vers une page spécifique
    function navigateToPage(page) {
        // Récupérer les valeurs des filtres actuels
        const typeHistorique = document.getElementById('selectTypeHistorique').value;
        const utilisateur = document.getElementById('selectUtilisateurSpecifique').value;

        // Créer un formulaire temporaire pour soumettre la requête
        const form = document.createElement('form');
        form.method = 'post';
        form.action = '/charger-historique-personnel';
        form.classList.add('ajax-form');
        form.setAttribute('data-target', '.table');

        // Ajouter les champs cachés pour les filtres
        const typeHistoriqueInput = document.createElement('input');
        typeHistoriqueInput.type = 'hidden';
        typeHistoriqueInput.name = 'type-historique';
        typeHistoriqueInput.value = typeHistorique;
        form.appendChild(typeHistoriqueInput);

        const utilisateurInput = document.createElement('input');
        utilisateurInput.type = 'hidden';
        utilisateurInput.name = 'utilisateur';
        utilisateurInput.value = utilisateur;
        form.appendChild(utilisateurInput);

        // Ajouter le champ caché pour la page
        const pageInput = document.createElement('input');
        pageInput.type = 'hidden';
        pageInput.name = 'page';
        pageInput.value = page;
        form.appendChild(pageInput);

        // Ajouter le formulaire au document et le soumettre
        document.body.appendChild(form);

        // Utiliser la fonction submitAjaxForm si elle existe, sinon soumettre normalement
        if (typeof submitAjaxForm === 'function') {
            submitAjaxForm(form);
        } else {
            form.submit();
        }

        // Supprimer le formulaire temporaire
        document.body.removeChild(form);
    }
});
</script>

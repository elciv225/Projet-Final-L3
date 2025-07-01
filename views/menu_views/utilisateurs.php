<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Gestion des Utilisateurs</h1>
        </div>
        <div class="form-group small-width right-align  mb-20">
            <form action="/charger-formulaire-categorie" method="post" class="ajax-form" data-target="#zone-dynamique">
                <select class="form-input select-submit" id="categorie-utilisateur" name="categorie-utilisateur">
                    <option value="">Sélectionnez une Catégorie</option>
                    <?php if (isset($categories) && !empty($categories)): ?>
                        <?php foreach ($categories as $categorie): ?>
                            <option value="<?= htmlspecialchars($categorie->getId()) ?>">
                                <?= htmlspecialchars($categorie->getLibelle()) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <label class="form-label" for="categorie-utilisateur">Catégorie Utilisateur</label>
            </form>
        </div>
    </div>

    <form class="form-section  ajax-form" method="post" action="/traitement-utilisateur" data-target=".table-scroll-wrapper" data-warning="Êtes-vous sûr de vouloir effectuer cette action ?">
        <input name="operation" value="ajouter" type="hidden">
        <!-- This hidden field will be populated by JS when a category is selected -->
        <input name="categorie_slug" id="categorie_slug_main_form" value="" type="hidden">

        <div class="section-header">
            <h3 class="section-title">Informations Générales de l'utilisateur</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <input type="text" name="nom" id="nom-utilisateur" class="form-input" placeholder=" " required>
                    <label class="form-label" for="nom-utilisateur">Nom</label>
                </div>
                <div class="form-group">
                    <input type="text" name="prenom" id="prenom-utilisateur" class="form-input"
                           placeholder=" " required>
                    <label class="form-label" for="prenom-utilisateur">Prénom(s)</label>
                </div>
                <div class="form-group">
                    <input type="email" name="email" id="email-utilisateur" class="form-input"
                           placeholder=" " required>
                    <label class="form-label" for="email-utilisateur">Email</label>
                </div>
                <div class="form-group">
                    <input type="date" name="date_naissance" id="date-naissance" class="form-input" placeholder=" ">
                    <label class="form-label" for="date-naissance">Date de naissance</label>
                </div>
                 <div class="form-group">
                    <input type="text" name="adresse" id="adresse-utilisateur" class="form-input" placeholder=" ">
                    <label class="form-label" for="adresse-utilisateur">Adresse</label>
                </div>
                <div class="form-group">
                    <input type="tel" name="telephone" id="telephone-utilisateur" class="form-input" placeholder=" ">
                    <label class="form-label" for="telephone-utilisateur">Téléphone</label>
                </div>
                <div class="form-group">
                    <select class="form-input" id="id-type-utilisateur" name="id_type_utilisateur" required>
                        <option value="">Sélectionnez Type Utilisateur</option>
                         <?php if (isset($typesUtilisateur) && !empty($typesUtilisateur)): ?>
                            <?php foreach ($typesUtilisateur as $type): ?>
                                <option value="<?= htmlspecialchars($type->getId()) ?>">
                                    <?= htmlspecialchars($type->getLibelle()) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <label class="form-label" for="id-type-utilisateur">Type d'utilisateur</label>
                </div>
                <div class="form-group">
                    <select class="form-input" id="id-groupe-utilisateur" name="id_groupe_utilisateur" required>
                        <option value="">Sélectionnez Groupe Utilisateur</option>
                        <?php if (isset($groupesUtilisateur) && !empty($groupesUtilisateur)): ?>
                            <?php foreach ($groupesUtilisateur as $groupe): ?>
                                <option value="<?= htmlspecialchars($groupe->getId()) ?>">
                                    <?= htmlspecialchars($groupe->getLibelle()) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <label class="form-label" for="id-groupe-utilisateur">Groupe utilisateur</label>
                </div>
                <div class="form-group">
                    <select class="form-input" id="id-niveau-acces" name="id_niveau_acces_donnees" required>
                        <option value="">Sélectionnez Niveau d'accès</option>
                         <?php if (isset($niveauxAcces) && !empty($niveauxAcces)): ?>
                            <?php foreach ($niveauxAcces as $niveau): ?>
                                <option value="<?= htmlspecialchars($niveau->getId()) ?>">
                                    <?= htmlspecialchars($niveau->getLibelle()) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <label class="form-label" for="id-niveau-acces">Niveau d'accès</label>
                </div>
                <div class="form-group">
                    <input type="text" name="login" id="login" class="form-input" placeholder=" " required>
                    <label class="form-label" for="login">Login</label>
                </div>
                 <div class="form-group">
                    <input type="password" name="mot_de_passe" id="mot_de_passe" class="form-input" placeholder=" ">
                    <label class="form-label" for="mot_de_passe">Mot de passe (laisser vide si inchangé)</label>
                </div>
            </div>
        </div>
        <div class="section-bottom">
            <h3 class="section-title">Action</h3>
            <div style="display: flex">
                <button class="btn btn-primary" type="submit">Créer / Modifier Utilisateur</button>
            </div>
        </div>
    </form>

    <div id="zone-dynamique">
        <!-- Le contenu spécifique à la catégorie (étudiant, enseignant, etc.) sera chargé ici par AJAX -->
        <!-- Ce contenu devrait avoir son propre formulaire ou des champs complémentaires -->
        <!-- et potentiellement son propre bouton de soumission s'il est indépendant, -->
        <!-- ou alors les champs ici sont ajoutés au formulaire principal dynamiquement. -->
        <p class="text-center p-20">Veuillez sélectionner une catégorie d'utilisateur pour voir les options spécifiques et la liste.</p>
    </div>
</main>
<script>
    // Script pour mettre à jour le champ caché categorie_slug_main_form
    // lorsque la catégorie est changée dans le selecteur principal.
    document.addEventListener('DOMContentLoaded', function() {
        const categorieSelect = document.getElementById('categorie-utilisateur');
        const categorieSlugInput = document.getElementById('categorie_slug_main_form');

        if (categorieSelect && categorieSlugInput) {
            categorieSelect.addEventListener('change', function() {
                categorieSlugInput.value = this.value;
                // Le formulaire AJAX pour charger la zone dynamique se soumettra automatiquement
                // grâce à la classe 'select-submit'.
                // Si le formulaire principal doit être utilisé pour ajouter/modifier,
                // il faut s'assurer que les champs de la zone-dynamique sont bien inclus
                // ou que la soumission est gérée de manière coordonnée.
            });
            // Set initial value if a category is already selected (e.g. on page reload with filters)
             if(categorieSelect.value){
                categorieSlugInput.value = categorieSelect.value;
             }
        }
    });
</script>
                           disabled>
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

    <div id="zone-dynamique">
        <div class="table-container">
            <div class="table-header">
                <h3 class="table-title">Liste des Utilisateurs</h3>
                <div class="header-actions">
                    <div class="search-container">
                        <span class="search-icon">🔍</span>
                        <input type="text" id="searchInput" class="search-input" placeholder="Rechercher par ...">
                    </div>
                </div>
                <div class="header-actions">
                    <button id="btnExportPDF" class="btn btn-secondary">🕐 Exporter en PDF</button>
                    <button id="btnExportExcel" class="btn btn-secondary">📤 Exporter sur Excel</button>
                    <button id="btnPrint" class="btn btn-secondary">📊 Imprimer</button>
                    <button class="btn btn-primary" id="btnSupprimerSelection">Supprimer</button>
                </div>

            </div>

            <div style="padding: 0 24px; border-bottom: 1px solid #E5E7EB;">
                <div class="table-tabs">
                    <div class="tab active">Tout sélectionner</div>
                </div>
            </div>
            <div class="table-scroll-wrapper scroll-custom">
                <table class="table">
                    <thead>
                    <tr>
                        <th><input type="checkbox" class="checkbox"></th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Date de naissance</th>
                        <th>Type d'utilisateur</th>
                        <th>Photo</th>
                        <th>Groupe utilisateur</th>
                        <th>Niveau d'accès</th>
                        <th>Login</th>
                        <th>Mot de passe</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- Les données utilisateur apparaîtront ici -->
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
    </div>
</main>

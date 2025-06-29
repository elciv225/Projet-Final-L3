<!-- Main Content -->
<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Gestion des Utilisateurs</h1>
        </div>
    </div>

    <!-- Informations Utilisateur -->
    <div class="form-section">
        <div class="section-header">
            <h3 class="section-title">Détails de l'Utilisateur</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <input type="text" name="id" class="form-input" placeholder=" " id="user-id" required>
                    <label class="form-label" for="user-id">Matricule (ID)</label>
                </div>
                <div class="form-group">
                    <input type="text" name="nom" class="form-input" placeholder=" " id="user-lastname" required>
                    <label class="form-label" for="user-lastname">Nom</label>
                </div>
                <div class="form-group">
                    <input type="text" name="prenoms" class="form-input" placeholder=" " id="user-firstname" required>
                    <label class="form-label" for="user-firstname">Prénoms</label>
                </div>
                <div class="form-group">
                    <input type="email" name="email" class="form-input" placeholder=" " id="user-mail" required>
                    <label class="form-label" for="user-mail">Email</label>
                </div>
                <div class="form-group">
                    <input type="date" name="date_naissance" class="form-input" placeholder=" " id="birth-date">
                    <label class="form-label" for="birth-date">Date de Naissance</label>
                </div>
                 <div class="form-group">
                    <label class="form-label" for="photo">Photo</label>
                    <input type="file" name="photo" class="form-input" id="photo">
                </div>
                <!-- Genre field can be added here if it's decided to add it to utilisateur table -->
                 <div class="form-group radio-group">
                    <label>Genre:</label>
                    <div class="radio-option">
                        <input type="radio" id="genreM" name="genre" value="M">
                        <label for="genreM">M</label>
                    </div>
                    <div class="radio-option">
                        <input type="radio" id="genreF" name="genre" value="F">
                        <label for="genreF">F</label>
                    </div>
                    <div class="radio-option">
                        <input type="radio" id="genreND" name="genre" value="ND">
                        <label for="genreND">N.D</label>
                    </div>
                </div>
                 <div class="form-group">
                    <input type="tel" name="telephone" class="form-input" placeholder=" " id="telephone">
                    <label class="form-label" for="telephone">Téléphone</label>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations de Connexion et Rôles -->
    <div class="form-section">
        <div class="section-header">
            <h3 class="section-title">Informations de Connexion et Rôles</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <input type="text" name="login" class="form-input" placeholder=" " id="login" required>
                    <label class="form-label" for="login">Login</label>
                </div>
                <div class="form-group">
                    <input type="password" name="mot_de_passe" class="form-input" placeholder=" " id="mot_de_passe">
                    <label class="form-label" for="mot_de_passe">Mot de Passe (laisser vide pour ne pas changer)</label>
                </div>
                <div class="form-group">
                    <select name="groupe_utilisateur_id" id="groupe_utilisateur_id" class="form-input" required>
                        <option value="">Sélectionnez un groupe</option>
                        <!-- Options à peupler depuis la table groupe_utilisateur -->
                    </select>
                    <label class="form-label" for="groupe_utilisateur_id">Groupe Utilisateur</label>
                </div>
                <div class="form-group">
                    <select name="type_utilisateur_id" id="type_utilisateur_id" class="form-input" required>
                        <option value="">Sélectionnez un type</option>
                        <!-- Options à peupler depuis la table type_utilisateur -->
                    </select>
                    <label class="form-label" for="type_utilisateur_id">Type d'utilisateur</label>
                </div>
                <div class="form-group">
                    <select name="niveau_acces_donnees_id" id="niveau_acces_donnees_id" class="form-input" required>
                        <option value="">Sélectionnez un niveau d'accès</option>
                        <!-- Options à peupler depuis la table niveau_acces_donnees -->
                    </select>
                    <label class="form-label" for="niveau_acces_donnees_id">Niveau d'accès</label>
                </div>
                <div class="form-group">
                    <select name="fonction_id" class="form-input" id="fonction_id">
                         <option value="">Sélectionnez une fonction (si applicable)</option>
                        <!-- Options à peupler depuis la table fonction -->
                    </select>
                    <label class="form-label" for="fonction_id">Fonction</label>
                </div>
            </div>
        </div>
    </div>

    <div style="display: flex; justify-content: flex-end; padding: 20px 0;">
        <button class="btn btn-primary" id="btnValider">Valider</button>
    </div>


    <!-- Orders Table -->
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
                <div class="tab active">Tout selectioner</div>
                <div class="tab"></div>
                <div class="tab"></div>
                <div class="tab"></div>
                <div class="tab"></div>
            </div>
        </div>

        <table class="table">
            <thead>
            <tr>
                <th><input type="checkbox" class="checkbox"></th>
                <th>Nom</th>
                <th>Groupe Utilisateur</th>
                <th>Type Utilisateur</th>
                <th>Fonction</th>
                <th>Niveau d'acces</th>
                <th>Login</th>
                <th>Mot de passe</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

        <div class="table-footer">
            <div class="results-info">
                Showing 1-9 of 240 entries
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
<script src="assets/js/utilisateurs.js"
<!-- Export PDF / Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

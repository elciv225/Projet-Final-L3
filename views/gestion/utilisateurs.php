<!-- Main Content -->
<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Gestion des Utilisateurs</h1>
        </div>
    </div>

    <!-- Informations Generales de l'utilisateur -->
    <div class="form-section">
        <div class="section-header">
            <h3 class="section-title">Information Generales de l'utilisateur</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <input type="text" name="userName" class="form-input" placeholder=" " id="userName">
                    <label class="form-label" for="userName">Nom d'utilisateur</label>
                </div>
                <div class="form-group">
                    <input type="text" name="fonction" class="form-input" placeholder=" " id="fonction">
                    <label class="form-label" for="">Fonction</label>
                </div>
                <div class="form-group">
                    <input type="text" name="type-utilisateur" class="form-input" placeholder=" " id="type-utilisateur">
                    <label class="form-label" for="type-utilisateur">Type d'utilisateur</label>
                </div>
                <div class="form-group">
                    <div class="form-group">
                        <select class="form-input" id="groupe-utilisateur" name="groupe-utilisateur">
                            <option value="">Sélectionnez un groupe utilisateur</option>
                            <option value="etudiant">Etudiants</option>
                            <option value="personnel-administratif">Personnels Administratifs</option>
                            <option value="enseignant">Enseignants</option>
                        </select>
                        <label class="form-label" for="groupe-utilisateur">Groupe Utilisateur</label>
                    </div>
                </div>
            </div>
            <div class="form-grid" style=" margin-top: 20px;">
                <div class="form-group" style="padding-right: 300px;">
                    <input type="text" name="niveau-acces" class="form-input" placeholder=" " id="niveau-acces">
                    <label class="form-label" for="niveau-acces">Niveau d'acces</label>
                </div>
                <div class="form-group" style="padding-right: 300px;">
                    <input type="text" name="login" class="form-input" placeholder=" " id="login">
                    <label class="form-label" for="login">Login</label>
                </div>
                <div class="form-group" style="padding-right: 300px;">
                    <input type="text" name="password" class="form-input" placeholder=" " id="password">
                    <label class="form-label" for="password">Mot De Passe</label>
                </div>
            </div>

        </div>
    </div>

    <!-- Mise a jour des informations de l'utilisateur -->
    <div class="form-section">
        <div class="section-header">
            <h3 class="section-title">Mise à jour des information de l'utilisateur</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <input type="text" name="user-lastname" class="form-input" placeholder=" " id="user-lastname">
                    <label class="form-label" for="user-lastname">Nom</label>
                </div>

                <div class="form-group">
                    <input type="text" name="user-firstname" class="form-input" placeholder=" " id="user-firstname">
                    <label class="form-label" for="user-firstname">Prenom</label>
                </div>
                <div class="form-group">
                    <input type="date" name="birth-date" class="form-input" placeholder=" " id="birth-date">
                    <label class="form-label" for="birth-date">Date de Naissance</label>
                </div>
                <div class="form-group">
                    <input type="email" name="user-mail" class="form-input" placeholder=" " id="user-mail">
                    <label class="form-label" for="user-mail">Email</label>
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

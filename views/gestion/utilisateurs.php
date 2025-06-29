<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Gestion des Utilisateurs</h1>
        </div>
    </div>

    <div class="form-section">
        <div class="section-header">
            <h3 class="section-title">Informations Générales de l'utilisateur</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <input type="text" name="id-utilisateur" id="id-utilisateur" class="form-input" placeholder=" ">
                    <label class="form-label" for="id-utilisateur">ID Utilisateur</label>
                </div>
                <div class="form-group">
                    <input type="text" name="nom-utilisateur" id="nom-utilisateur" class="form-input" placeholder=" ">
                    <label class="form-label" for="nom-utilisateur">Nom</label>
                </div>
                <div class="form-group">
                    <input type="text" name="prenom-utilisateur" id="prenom-utilisateur" class="form-input" placeholder=" ">
                    <label class="form-label" for="prenom-utilisateur">Prénom</label>
                </div>
                <div class="form-group">
                    <input type="email" name="email-utilisateur" id="email-utilisateur" class="form-input" placeholder=" ">
                    <label class="form-label" for="email-utilisateur">Email</label>
                </div>
                <div class="form-group">
                    <input type="date" name="date-naissance" id="date-naissance" class="form-input" placeholder=" ">
                    <label class="form-label" for="date-naissance">Date de naissance</label>
                </div>
                <div class="form-group">
                    <input type="text" name="id-type-utilisateur" id="id-type-utilisateur" class="form-input" placeholder=" ">
                    <label class="form-label" for="id-type-utilisateur">ID Type d'utilisateur</label>
                </div>
                <div class="form-group">
                    <input type="text" name="photo-utilisateur" id="photo-utilisateur" class="form-input" placeholder=" ">
                    <label class="form-label" for="photo-utilisateur">Photo (URL ou nom)</label>
                </div>
                <div class="form-group">
                    <input type="text" name="id-groupe-utilisateur" id="id-groupe-utilisateur" class="form-input" placeholder=" ">
                    <label class="form-label" for="id-groupe-utilisateur">ID Groupe utilisateur</label>
                </div>
            </div>
            <div class="form-grid" style="margin-top: 20px;">
                <div class="form-group">
                    <input type="text" name="id-niveau-acces" id="id-niveau-acces" class="form-input" placeholder=" ">
                    <label class="form-label" for="id-niveau-acces">ID Niveau d'accès</label>
                </div>
                <div class="form-group">
                    <input type="text" name="login" id="login" class="form-input" placeholder=" ">
                    <label class="form-label" for="login">Login</label>
                </div>
                <div class="form-group">
                    <input type="password" name="mot-de-passe" id="mot-de-passe" class="form-input" placeholder=" ">
                    <label class="form-label" for="mot-de-passe">Mot de passe</label>
                </div>
            </div>
        </div>
    </div>

    <div style="display: flex; justify-content: flex-end; padding: 20px 0;">
        <button class="btn btn-primary" id="btnValider">Valider</button>
    </div>

    <div class="table-container">
        <div class="table-header">
            <h3 class="table-title">Liste des Utilisateurs</h3>
            <div class="header-actions">
                <div class="search-container">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="searchInput" class="search-input" placeholder="Rechercher par ...">
                </div>
            </div> <div class="header-actions"> <button id="btnExportPDF" class="btn btn-secondary">🕐 Exporter en PDF</button>
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

<!-- Scripts -->
<script src="assets/js/utilisateurs.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

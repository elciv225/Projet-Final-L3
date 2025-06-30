<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Évaluation des Étudiants</h1>
        </div>
    </div>

    <!-- Formulaire de Saisie des Notes -->
    <div class="form-section">
        <div class="section-header">
            <h3 class="section-title">Nouvelle Évaluation</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <select class="form-input" id="id-utilisateur" name="id-utilisateur">
                        <option value="">Numero Matricule</option>
                        <option value=""></option>
                        <option value=""></option>
                    </select>
                </div>
                <div class="form-group">
                    <select class="form-input" id="id-utilisateur" name="id-utilisatuer">
                        <option value="">Numero Carte Etudiant</option>
                        <option value=""></option>
                        <option value=""></option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" id="idEcueInput" class="form-input" placeholder=" ">
                    <label class="form-label" for="idEcueInput">ID ECUE</label>
                </div>
                <div class="form-group">
                    <input type="date" id="date-evaluation" class="form-input" placeholder=" ">
                    <label class="form-label" for="date-evaluation">Date d'Évaluation</label>
                </div>
                <div class="form-group">
                    <input type="number" id="note" class="form-input" placeholder=" " min="0" max="20">
                    <label class="form-label" for="note">Note (/20)</label>
                </div>
            </div>
        </div>
    </div>

    <div style="display: flex; justify-content: flex-end; padding: 20px 0;">
        <button class="btn btn-primary" id="btnValider">Valider</button>
    </div>

    <!-- Historique des Évaluations -->
    <div class="table-container">
        <div class="table-header">
            <h3 class="table-title">Historique des Évaluations</h3>
            <div class="header-actions">
                <div class="search-container">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="searchInput" class="search-input" placeholder="Rechercher...">
                </div>
                <button id="btnExportPDF" class="btn btn-secondary">🕐 Exporter PDF</button>
                <button id="btnExportExcel" class="btn btn-secondary">📤 Exporter Excel</button>
                <button id="btnPrint" class="btn btn-secondary">📊 Imprimer</button>
                <button class="btn btn-primary" id="btnSupprimerSelection">Supprimer</button>
            </div>
        </div>

        <table class="table">
            <thead>
            <tr>
                <th><input type="checkbox" class="checkbox"></th>
                <th>ID Enseignant</th>
                <th>ID Étudiant</th>
                <th>ID ECUE</th>
                <th>Date</th>
                <th>Note</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <!-- Données dynamiques -->
            </tbody>
        </table>

        <div class="table-footer">
            <div class="results-info">Affichage de 1 à X sur Y entrées</div>
            <div class="pagination">
                <button class="pagination-btn">‹</button>
                <button class="pagination-btn active">1</button>
                <button class="pagination-btn">2</button>
                <span>...</span>
                <button class="pagination-btn">›</button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="/assets/js/evaluation-etudiant.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</main>

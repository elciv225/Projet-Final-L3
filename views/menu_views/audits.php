<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Audits du Système</h1>
        </div>
    </div>

    <!-- Section de filtrage des audits -->
    <div class="form-section">
        <div class="section-header">
            <h3 class="section-title">Filtres</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <div class="select-wrapper">
                        <select id="typeAuditSelect" class="custom-select">
                            <option value="">Tous les types d'audit</option>
                            <option value="connexion">Connexions</option>
                            <option value="modification">Modifications</option>
                            <option value="suppression">Suppressions</option>
                            <option value="creation">Créations</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="select-wrapper">
                        <select id="utilisateurSelect" class="custom-select">
                            <option value="">Tous les utilisateurs</option>
                            <!-- Options populées par JS -->
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <input type="date" id="dateDebutInput" class="form-input" placeholder=" ">
                    <label for="dateDebutInput" class="form-label">Date de début</label>
                </div>
                <div class="form-group">
                    <input type="date" id="dateFinInput" class="form-input" placeholder=" ">
                    <label for="dateFinInput" class="form-label">Date de fin</label>
                </div>
            </div>
        </div>
        <div class="section-bottom">
            <button class="btn btn-primary" id="filterButton">Filtrer</button>
            <button class="btn btn-secondary" id="resetButton">Réinitialiser</button>
        </div>
    </div>

    <!-- Tableau des audits -->
    <div class="table-container">
        <div class="table-header">
            <h3>Journal des Audits</h3>
            <div class="header-actions">
                <div class="search-container">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="searchInput" class="search-input" placeholder="Rechercher...">
                </div>
                <button id="btnExportPDF" class="btn btn-secondary">🕐 PDF</button>
                <button id="btnExportExcel" class="btn btn-secondary">📤 Excel</button>
                <button id="btnPrint" class="btn btn-secondary">📊 Imprimer</button>
            </div>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Date et Heure</th>
                <th>Utilisateur</th>
                <th>Type d'Action</th>
                <th>Table Concernée</th>
                <th>Description</th>
                <th>Adresse IP</th>
            </tr>
            </thead>
            <tbody id="auditTableBody">
            <!-- Les données seront chargées ici par JavaScript -->
            </tbody>
        </table>
        <div class="table-footer">
            <div class="results-info" id="resultsInfo">
                Affichage 0-0 sur 0 entrées
            </div>
            <div class="pagination" id="pagination-controls">
                <button class="pagination-btn" id="prevPage">‹</button>
                <!-- Page numbers will be inserted here by JavaScript -->
                <button class="pagination-btn" id="nextPage">›</button>
            </div>
        </div>
    </div>
</main>
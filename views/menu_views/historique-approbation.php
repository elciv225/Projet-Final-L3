<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Historique des Approbations</h1>
        </div>
    </div>

    <!-- Section de saisie des approbations -->
    <div class="form-section">
        <div class="section-header">
            <h3 class="section-title">Informations Générales de l'utilisateur</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <div class="select-wrapper">
                        <select id="niveauApprobationSelect" class="custom-select" required>
                            <option value="">Sélectionner un Niveau d'Approbation</option>
                            <!-- Options populées par JS -->
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="select-wrapper">
                        <select id="compteRenduSelect" class="custom-select" required>
                            <option value="">Sélectionner un Compte Rendu</option>
                            <!-- Options populées par JS -->
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <input type="date" id="dateApprobationInput" class="form-input" placeholder=" " required>
                    <label for="dateApprobationInput" class="form-label">Date d'Approbation</label>
                </div>
            </div>
        </div>
        <div class="section-bottom">
            <h3 class="section-title">Action</h3>
            <div style="display: flex; gap: 7px">
                <button class="btn btn-secondary" id="addButton">➕ Ajouter</button>
                <button class="btn btn-secondary" id="modifyButton">✏️ Modifier</button>
                <button class="btn btn-secondary" id="deleteButton">🗑️ Supprimer</button>
                <button class="btn btn-secondary" id="printButton">🖨️ Imprimer</button>
                <button class="btn btn-primary" id="validateButton">✓ Valider Tout</button>
            </div>
        </div>
    </div>

    <!-- Tableau d'historique des approbations -->
    <div class="table-container">
        <div class="table-header">
            <h3>Historique des Approbations</h3>
            <div class="header-actions">
                <div class="search-container">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="searchInput" class="search-input" placeholder="Rechercher...">
                </div>
                <button id="btnExportPDF" class="btn btn-secondary">🕐 PDF</button>
                <button id="btnExportExcel" class="btn btn-secondary">📤 Excel</button>
                <button id="btnPrint" class="btn btn-secondary">📊 Imprimer</button>
                <button class="btn btn-primary" id="btnSupprimerSelection">🗑️ Supprimer Sélection</button>
            </div>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th><input type="checkbox" class="checkbox" id="masterCheckbox"></th>
                <th>Niveau d'Approbation</th>
                <th>Compte Rendu</th>
                <th>Date d'Approbation</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody id="approbationTableBody">
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
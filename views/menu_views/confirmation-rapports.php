<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Confirmation des Rapports de Stage</h1>
        </div>
    </div>

    <!-- Section de filtrage des rapports -->
    <div class="form-section">
        <div class="section-header">
            <h3 class="section-title">Filtres</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <div class="select-wrapper">
                        <select id="statutRapportSelect" class="custom-select">
                            <option value="">Tous les statuts</option>
                            <option value="depose">Déposé</option>
                            <option value="valide">Validé</option>
                            <option value="approuve">Approuvé</option>
                            <option value="encadrants_assignes">Encadrants assignés</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="select-wrapper">
                        <select id="etudiantSelect" class="custom-select">
                            <option value="">Tous les étudiants</option>
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

    <!-- Tableau des rapports -->
    <div class="table-container">
        <div class="table-header">
            <h3>Rapports en attente de confirmation</h3>
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
                <th><input type="checkbox" class="checkbox" id="masterCheckbox"></th>
                <th>ID Rapport</th>
                <th>Étudiant</th>
                <th>Titre</th>
                <th>Date de dépôt</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody id="rapportsTableBody">
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

    <!-- Section d'actions sur les rapports sélectionnés -->
    <div class="form-section">
        <div class="section-header">
            <h3 class="section-title">Actions sur les rapports sélectionnés</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <div class="select-wrapper">
                        <select id="actionSelect" class="custom-select">
                            <option value="">Sélectionner une action</option>
                            <option value="valider">Valider</option>
                            <option value="approuver">Approuver</option>
                            <option value="assigner">Assigner des encadrants</option>
                            <option value="rejeter">Rejeter</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <textarea id="commentaireInput" class="form-input" placeholder=" " rows="3"></textarea>
                    <label for="commentaireInput" class="form-label">Commentaire (optionnel)</label>
                </div>
            </div>
        </div>
        <div class="section-bottom">
            <button class="btn btn-primary" id="applyActionButton">Appliquer l'action</button>
        </div>
    </div>
</main>

<script src="/assets/js/confirmation-rapports.js"></script>

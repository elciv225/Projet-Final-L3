<main class="main-content">
    <header class="page-header">
        <div class="header-left">
            <h1>Gestion des ECUE</h1>
        </div>
    </header>

    <div class="form-section">
        <div class="section-header">
            <h3 class="section-title">Ajouter/Modifier ECUE</h3>
        </div>
        <div class="section-content">
            <form id="ecueForm" class="ajax-form" data-target="self"> <!-- Assuming self update or handled by JS -->
                <div class="form-grid">
                    <div class="form-group">
                        <input type="text" id="ecue_id" name="id" class="form-input" placeholder=" " required>
                        <label for="ecue_id" class="form-label">ID ECUE (e.g., ECUE_ALGEBRE1)</label>
                    </div>
                    <div class="form-group">
                        <input type="text" id="ecue_libelle" name="libelle" class="form-input" placeholder=" " required>
                        <label for="ecue_libelle" class="form-label">Libellé de l'ECUE</label>
                    </div>
                    <div class="form-group">
                        <input type="number" id="ecue_credit" name="credit" class="form-input" placeholder=" " required min="0">
                        <label for="ecue_credit" class="form-label">Crédits</label>
                    </div>
                    <div class="form-group">
                        <select id="ecue_ue_id" name="ue_id" class="form-input" required>
                            <option value="">Sélectionnez l'UE parente</option>
                            <!-- Options à peupler par PHP/JS depuis la table ue -->
                        </select>
                        <label for="ecue_ue_id" class="form-label">Unité d'Enseignement (UE)</label>
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; padding: 20px 0;">
                    <button type="submit" class="btn btn-primary" id="btnValiderEcue">Valider</button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-container">
        <div class="table-header">
            <h3 class="table-title">Liste des ECUEs</h3>
            <div class="header-actions">
                <div class="search-container">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="searchInputEcue" class="search-input" placeholder="Rechercher...">
                </div>
            </div>
            <div class="header-actions">
                <!-- <button class="btn btn-primary" id="btnAddEcue">➕ Ajouter</button> --> <!-- Form is always visible -->
                <button class="btn btn-secondary" id="btnSupprimerSelectionEcue">Supprimer Sélection</button>
            </div>
        </div>

        <table class="table" id="ecueTable">
            <thead>
            <tr>
                <th><input type="checkbox" class="checkbox" id="selectAllEcueCheckbox"></th>
                <th>ID ECUE</th>
                <th>Libellé</th>
                <th>Crédits</th>
                <th>UE Parente (ID)</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody id="ecueTableBody">
            <!-- Les lignes de données seront chargées ici par JavaScript -->
            </tbody>
        </table>
        <div class="table-footer">
            <div class="results-info" id="ecueResultsInfo">
                <!-- Showing 1-X of Y entries -->
            </div>
            <div class="pagination" id="ecuePagination">
                <!-- Pagination buttons -->
            </div>
        </div>
    </div>
</main>
<!-- Le script JS pour ECUE sera consolidé dans gestion.js plus tard -->
<!-- <script src="/assets/js/ecue.js" defer></script> -->

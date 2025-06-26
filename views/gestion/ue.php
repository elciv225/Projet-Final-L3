<main class="main-content">
    <header class="page-header">
        <div class="header-left">
            <h1>Gestion des UE</h1>
        </div>
    </header>

    <div class="form-section">
        <div class="section-header">
            <h3 class="section-title">Mise à jour des UE</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <input type="text" id="idUeInput" class="form-input" placeholder=" " required>
                    <label for="idUeInput" class="form-label">ID UE</label>
                </div>
                <div class="form-group">
                    <div class="select-wrapper">
                        <select id="niveauSelect" class="custom-select" required>
                            <option value="">Niveau</option>
                            <option value="L1">Licence 1</option>
                            <option value="L2">Licence 2</option>
                            <option value="L3">Licence 3</option>
                            <option value="M1">Master 1</option>
                            <option value="M2">Master 2</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="select-wrapper">
                        <select id="semestreSelect" class="custom-select" required>
                            <option value="">Semestre</option>
                            <option value="S1">Semestre 1</option>
                            <option value="S2">Semestre 2</option>
                            <option value="S3">Semestre 3</option>
                            <option value="S4">Semestre 4</option>
                            <option value="S5">Semestre 5</option>
                            <option value="S6">Semestre 6</option>
                            <option value="S7">Semestre 7</option>
                            <option value="S8">Semestre 8</option>
                            <option value="S9">Semestre 9</option>
                            <option value="S10">Semestre 10</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <input type="number" id="creditInput" class="form-input" placeholder=" " required>
                    <label for="creditInput" class="form-label">Crédit</label>
                </div>
                <div class="form-group">
                    <div class="select-wrapper">
                        <select id="anneeAcadSelect" class="custom-select" required>
                            <option value="">Année Académique</option>
                            <option value="2023-2024">2023-2024</option>
                            <option value="2024-2025">2024-2025</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-container">
        <div class="table-header">
            <h3 class="table-title">Listes des enregistrements</h3>
            <div class="header-actions">
                <div class="search-container">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="searchInput" class="search-input" placeholder="Rechercher par ...">
                </div>
            </div>
            <div class="header-actions">
                <button class="btn btn-secondary" id="addButton">➕ Ajouter</button>
                <button class="btn btn-secondary" id="modifyButton">✏️ Modifier</button>
                <button class="btn btn-secondary" id="deleteButton">🗑️ Supprimer</button>
                <button class="btn btn-secondary" id="printButton">🖨️ Imprimer</button>
                <button class="btn btn-primary" id="validateButton">✓ Valider</button>
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
                <th>Semestre</th>
                <th>ID UE</th>
                <th>Niveau</th>
                <th>Crédit</th>
            </tr>
            </thead>
            <tbody id="ueTableBody">
            <!-- Sample data rows -->
            <tr>
                <td><input type="checkbox" class="checkbox"></td>
                <td>S1</td>
                <td>INF201</td>
                <td>L2</td>
                <td>6</td>
            </tr>
            <tr>
                <td><input type="checkbox" class="checkbox"></td>
                <td>S1</td>
                <td>INF201</td>
                <td>L2</td>
                <td>6</td>
            </tr>

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
<script src="/assets/js/ue.js" defer></script>
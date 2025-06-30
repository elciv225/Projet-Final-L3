<div class="form-section">
    <div class="section-header">
        <h3 class="section-title">Mise à jour des <?= $parametre ?? "Param" ?></h3>
    </div>
    <div class="section-content">
        <div class="form-grid">
            <div class="form-group">
                <input type="text" id="libelle" name="<?= $parametre ?? "Param" ?>" class="form-input" placeholder=" " required>
                <label for="libelle" class="form-label">Libelle de l'UE</label>
            </div>
            <div class="form-group">
                <input type="number" id="creditInput" value="Calculé automatiquement" class="form-input" placeholder=" "
                       disabled>
                <label for="creditInput" class="form-label"><?= $parametre ?? "Param" ?></label>
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
    <div class="table-scroll-wrapper scroll-custom">
        <table class="table">
            <thead>
            <tr>
                <th><input type="checkbox" class="checkbox"></th>
                <th>ID UE</th>
                <th>Libelle</th>
            </tr>
            </thead>
            <tbody id="ueTableBody">
            <!-- Sample data rows -->
            <tr>
                <td><input type="checkbox" class="checkbox"></td>
                <td>INF201</td>
                <td>INF201</td>
            </tr>
            <tr>
                <td><input type="checkbox" class="checkbox"></td>
                <td>INF201</td>
                <td>L2</td>
            </tr>

            </tbody>
        </table>
    </div>
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
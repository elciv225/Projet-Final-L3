<main class="main-content">
    <header class="page-header">
        <div class="header-left">
            <h1>MAJ NIVEAU D'ACCES DES DONNEES</h1>
        </div>
    </header>

    <section class="header-section">
        <div class="form-group">
            <input type="text" id="id-niveau-acces" class="form-input" placeholder=" " required>
            <label for="id-niveau-acces" class="form-label"></label>
        </div>
        <div class="form-group">
            <input type="text" id="libelle-niveau-acces" class="form-input" placeholder=" " required>
            <label for="libelle-niveau-acces" class="form-label">Niveau d'acces</label>
        </div>
    </section>

    <div class="table-container">
        <div class="table-header">
            <h3>Listes des enregistrements</h3>
            <div class="button-group">
                <button class="btn btn-secondary" id="addButton">➕ Ajouter</button>
                <button class="btn btn-secondary" id="modifyButton">✏️ Modifier</button>
                <button class="btn btn-secondary" id="deleteButton">🗑️ Supprimer</button>
                <button class="btn btn-secondary" id="printButton">🖨️ Imprimer</button>
                <button class="btn btn-primary" id="validateButton">✓ Valider</button>
            </div>
        </div>
        <table class="data-table">
            <thead>
            <tr>
                <th>ID ANNEE-ACADEMIQUE</th>
                <th>Date Debut</th>
                <th>Date Fin</th>
            </tr>
            </thead>
            <tbody id="ecueTableBody">
            <!-- Sample data rows -->
            <tr>
                <td>INF201</td>
                <td>INF201</td>
                <td>6</td>
            </tr>
            <tr>
                <td>MTH202</td>
                <td>MTH202</td>
                <td>5</td>
            </tr>
            <tr>
                <td>PHY301</td>
                <td>PHY301</td>
                <td>4</td>
            </tr>
            <tr>
                <td>CHM101</td>
                <td>CHM101</td>
                <td>3</td>
            </tr>
            </tbody>
        </table>
    </div>
</main>
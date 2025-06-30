<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Reglement Frais D'Inscription</h1>
        </div>
    </div>

    <!-- Sélection de l'année académique -->
    <div class="form-group small-width right-align  mb-20">
        <select class="form-input" id="id-annee-academique" name="id-annee-academique">
            <option value="">Année-Académique</option>
            <option value=""></option>
            <option value=""></option>
        </select>
    </div>
    <!-- Informations de l'etudiant -->
    <div class="form-section">
        <div class="section-header">
            <h3 class="section-title">Information Etudiant</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <input type="text" name="id-utilisateur" class="form-input" placeholder=" " id="id-utilisateur">
                    <label class="form-label" for="id-utilisateur">Numero Carte d'Etudiant</label>
                </div>
                <div class="form-group">
                    <input type="text" name="id-niveau-etude" class="form-input" placeholder=" " id="id-niveau-etude">
                    <label class="form-label" for="id-niveau-etude">Niveau d'Etude</label>
                </div>
            </div>

        </div>
    </div>

    <!-- Reglements des frais d'inscription -->
    <div class="form-section">
        <div class="section-header">
            <h3 class="section-title">Reglements Frais D'Inscription</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <input type="date" name="date-inscription" class="form-input" placeholder=" " id="date-inscription">
                    <label class="form-label" for="date-inscription">Date d'inscription</label>
                </div>
                <div class="form-group">
                    <input type="number" name="montant" class="form-input" placeholder=" " id="montant">
                    <label class="form-label" for="montant">Montant</label>
                </div>
                <div class="form-group">
                    <input type="number" name="totalpaye" class="form-input" placeholder=" " id="totalpaye">
                    <label class="form-label" for="totalpaye">Total Payé</label>
                </div>
                <div class="form-group">
                    <input type="number" name="restepaye" class="form-input" placeholder=" " id="restepaye">
                    <label class="form-label" for="restepaye">Reste à Payé</label>
                </div>
                <div class="form-group">
                    <input type="number" name="montantapaye" class="form-input" placeholder=" " id="montantapaye">
                    <label class="form-label" for="montantapaye">Montant à Payé</label>
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
            <h3 class="table-title">Historique</h3>
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
                <th>Numero Carte d'Etudiant</th>
                <th>Niveau D'Etude</th>
                <th>Date d'inscription</th>
                <th>Montant</th>
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
<!-- Bibliothèque pour Excel -->w
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<!-- Bibliothèque pour PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

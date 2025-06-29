<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Reglement Frais D'Inscription</h1>
        </div>
    </div>


    <form id="reglementForm">
        <div class="form-group small-width right-align mb-20">
            <select class="form-input" id="annee_academique_id_filter" name="annee_academique_id_filter">
                <option value="">Filtrer par Année Académique</option>
                <!-- Options à peupler par PHP/JS -->
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
                        <input type="text" name="utilisateur_id" class="form-input" placeholder=" " id="student-number" required>
                        <label class="form-label" for="student-number">Numéro Carte d'Etudiant (ID Utilisateur)</label>
                    </div>
                    <div class="form-group">
                        <!-- Nom, Prénoms, Niveau d'Etude are display-only, fetched after entering student ID -->
                        <input type="text" name="studentLastnameDisplay" class="form-input" placeholder=" " id="student-lastname" readonly disabled>
                        <label class="form-label" for="student-lastname">Nom (auto)</label>
                    </div>
                    <div class="form-group">
                        <input type="text" name="studentFirstnameDisplay" class="form-input" placeholder=" " id="student-firstname" readonly disabled>
                        <label class="form-label" for="student-firstname">Prénoms (auto)</label>
                    </div>
                    <div class="form-group">
                         <select class="form-input" id="niveau_etude_id" name="niveau_etude_id" required>
                            <option value="">Niveau d'Étude pour Inscription</option>
                            <!-- Options à peupler par PHP/JS, e.g. based on student or generally -->
                        </select>
                        <label class="form-label" for="niveau_etude_id">Niveau d'Etude (Inscription)</label>
                    </div>
                    <div class="form-group">
                        <select class="form-input" id="annee_academique_id_inscription" name="annee_academique_id" required>
                            <option value="">Année Académique pour Inscription</option>
                            <!-- Options à peupler par PHP/JS -->
                        </select>
                        <label class="form-label" for="annee_academique_id_inscription">Année Académique (Inscription)</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reglements des frais d'inscription -->
        <div class="form-section">
            <div class="section-header">
                <h3 class="section-title">Règlements Frais D'Inscription</h3>
            </div>
            <div class="section-content">
                <div class="form-grid">
                    <div class="form-group">
                        <input type="number" name="montant" class="form-input" placeholder=" " id="montant" required min="0" step="any">
                        <label class="form-label" for="montant">Montant Payé</label>
                    </div>
                    <div class="form-group">
                        <input type="date" name="date_inscription" class="form-input" placeholder=" " id="date_inscription" required>
                        <label class="form-label" for="date_inscription">Date de Règlement/Inscription</label>
                    </div>
                    <!-- Calculated fields removed from direct input, will be displayed by JS if needed -->
                    <div class="form-group">
                        <input type="text" name="montant_a_payer_display" class="form-input" placeholder=" " id="montant_a_payer_display" readonly disabled>
                        <label class="form-label" for="montant_a_payer_display">Montant Total à Payer (info)</label>
                    </div>
                     <div class="form-group">
                        <input type="text" name="total_deja_paye_display" class="form-input" placeholder=" " id="total_deja_paye_display" readonly disabled>
                        <label class="form-label" for="total_deja_paye_display">Total Déjà Payé (info)</label>
                    </div>
                    <div class="form-group">
                        <input type="text" name="reste_a_payer_display" class="form-input" placeholder=" " id="reste_a_payer_display" readonly disabled>
                        <label class="form-label" for="reste_a_payer_display">Reste à Payer (info)</label>
                    </div>
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; padding: 20px 0;">
            <button type="submit" class="btn btn-primary" id="btnValider">Valider Paiement</button>
        </div>
    </form>
    <!-- End Form -->

    <!-- Table for history -->
    <div class="table-container">
        <div class="table-header">
            <h3 class="table-title">Historique des Règlements</h3>
            <div class="header-actions">
                <div class="search-container">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="searchInputReglement" class="search-input" placeholder="Rechercher par N° Etudiant...">
                </div>
            </div>
            <div class="header-actions">
                <button id="btnExportPDF" class="btn btn-secondary">Exporter en PDF</button>
                <button id="btnExportExcel" class="btn btn-secondary">Exporter sur Excel</button>
                <button id="btnPrint" class="btn btn-secondary">Imprimer</button>
                <button class="btn btn-primary" id="btnSupprimerSelectionReglement">Supprimer Sélection</button>
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
                    <input type="montantpaye" name="montantpaye" class="form-input" placeholder=" " id="montantpaye">
                    <label class="form-label" for="montantpaye">Montant Payé</label>
                </div>

                <div class="form-group">
                    <input type="date" name="datereglement" class="form-input" placeholder=" " id="datereglement">
                    <label class="form-label" for="datereglement">Date de reglement</label>
                </div>
                <div class="form-group">
                    <input type="text" name="totalpaye" class="form-input" placeholder=" " id="totalpaye">
                    <label class="form-label" for="totalpaye">Total Payé</label>
                </div>
                <div class="form-group">
                    <input type="text" name="restepaye" class="form-input" placeholder=" " id="restepaye">
                    <label class="form-label" for="restepaye">Reste à Payé</label>
                </div>
                <div class="form-group">
                    <input type="text" name="montantapaye" class="form-input" placeholder=" " id="montantapaye">
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
                <th>Nom</th>
                <th>Prenom</th>
                <th>Niveau D'Etude</th>
                <th>Montant Payé</th>
                <th>Date de Règlement</th>
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
<!-- Bibliothèque pour Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<!-- Bibliothèque pour PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

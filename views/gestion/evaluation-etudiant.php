<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Evaluation Etudiants</h1>
        </div>
    </div>


    <div class="form-group small-width right-align  mb-20">
        <select class="form-input" id="annee-academique" name="annee-academique">
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
                    <input type="text" name="studentNumber" class="form-input" placeholder=" " id="student-number">
                    <label class="form-label" for="student-number">Numéro Carte d'Etudiant</label>
                </div>
                <div class="form-group">
                    <input type="text" name="studentLastname" class="form-input" placeholder=" " id="student-lastname">
                    <label class="form-label" for="student-lastname">Nom</label>
                </div>
                <div class="form-group">
                    <input type="text" name="studentFirstname" class="form-input" placeholder=" " id="student-firstname">
                    <label class="form-label" for="student-firstname">Prénoms</label>
                </div>
                <div class="form-group">
                    <input type="text" name="promotion" class="form-input" placeholder=" " id="promotion">
                    <label class="form-label" for="promotion">Promotion</label>
                </div>
            </div>

        </div>
    </div>

    <!-- Notes de l'etudiant -->
    <div class="form-section">
        <div class="section-header">
            <h3 class="section-title">Notes</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <input type="text" name="niveauEtude" class="form-input" placeholder=" " id="niveauEtude">
                    <label class="form-label" for="niveauEtude">Niveau d'Etude</label>
                </div>

                <div class="form-group">
                    <input type="text" name="semestre" class="form-input" placeholder=" " id="semestre">
                    <label class="form-label" for="semestre">Semestre</label>
                </div>
                <div class="form-group">
                    <div id="ue-container">
                        <div class="field-row">
                            <div class="form-group">
                                <input type="text" name="ue" class="form-input" placeholder=" ">
                                <label class="form-label">UE</label>
                            </div>
                            <button type="button" class="small-round-btn" onclick="addField('ue-container', 'ue', 'UE')">+</button>
                            <button type="button" class="small-round-btn" onclick="removeField(this)">−</button>
                        </div>
                    </div>

                </div>
                <div class="form-group">
                    <div id="moyenne-container">
                        <div class="field-row">
                            <div class="form-group">
                                <input type="text" name="moyenne" class="form-input" placeholder=" ">
                                <label class="form-label">Moyenne</label>
                            </div>
                            <button type="button" class="small-round-btn" onclick="addField('moyenne-container', 'moyenne', 'Moyenne')">+</button>
                            <button type="button" class="small-round-btn" onclick="removeField(this)">−</button>
                        </div>
                    </div>

                </div>
                <div class="form-group">
                    <div id="credit-container">
                        <div class="field-row">
                            <div class="form-group">
                                <input type="text" name="credit" class="form-input" placeholder=" ">
                                <label class="form-label">Credit</label>
                            </div>
                            <button type="button" class="small-round-btn" onclick="addField('credit-container', 'credit', 'Credit')">+</button>
                            <button type="button" class="small-round-btn" onclick="removeField(this)">−</button>
                        </div>
                    </div>
                </div>
                <div class="form-group double-width align-right">
                    <input type="text" name="moyenne_semestre" class="form-input" placeholder=" " id="moyenne_semestre">
                    <label class="form-label" for="moyenne_semestre">Moyenne Semestre</label>
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
                <th>Promotion</th>
                <th>Moyenne Semestre</th>
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

<script src="/assets/js/evaluation-etudiant.js"></script>
<!-- Bibliothèque pour Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<!-- Bibliothèque pour PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>


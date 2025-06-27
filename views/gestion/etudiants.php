<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Etudiants</h1>
        </div>
    </div>

    <!-- Informations Generales -->
    <div class="form-section">
        <div class="section-header">
            <h3 class="section-title">Information Generales</h3>
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
                    <input type="text" name="studentFirstname" class="form-input" placeholder=" "
                           id="student-firstname">
                    <label class="form-label" for="student-firstname">Prénoms</label>
                </div>
                <div class="form-group">
                    <input type="date" name="dateBirth" class="form-input" placeholder=" " id="birth-date">
                    <label class="form-label" for="birth-date">Date de Naissance</label>
                </div>
            </div>
            <div class="form-grid" style=" margin-top: 20px;">
                <div class="form-group" >
                    <input type="mail" name="email" class="form-input" placeholder=" " id="email">
                    <label class="form-label" for="email">Email</label>
                </div>
                <div class="radio-group">
                    <label>Genre:</label>
                    <div class="radio-option">
                        <input type="radio" id="genreM" name="genre" value="M">
                        <label for="genreM">M</label>
                    </div>
                    <div class="radio-option">
                        <input class="radio-option" type="radio" id="genreF" name="genre" value="F">
                        <label for="genreF">F</label>
                    </div>
                    <div class="radio-option">
                        <input class="radio-option" type="radio" id="genreND" name="genre" value="ND">
                        <label for="genreND">N.D</label>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Informations carriere -->
    <div class="form-section">
        <div class="section-header">
            <h3 class="section-title">Information Academique</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <input type="text" name="niveauEtude" class="form-input" placeholder=" " id="niveauEtude">
                    <label class="form-label" for="niveauEtude">Niveau d'Etude</label>
                </div>

                <div class="form-group">
                    <input type="text" name="annee-academique" class="form-input" placeholder=" " id="annee-academique">
                    <label class="form-label" for="annee-academique">Annee-Academique</label>
                </div>
                <div class="form-group">
                    <input type="text" name="contact" class="form-input" placeholder=" " id="contact">
                    <label class="form-label" for="contact">contact</label>
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
            <h3 class="table-title">Liste des Etudiants</h3>
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

        <div>
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
                <th>Date de naissance</th>
                <th>Email</th>
                <th>Niveau d'Etude</th>
                <th>Annee-Academique</th>
                <th>Contact</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><input type="checkbox" class="checkbox"></td>
                <td>ETU001</td>
                <td>Koné</td>
                <td>Fatoumata</td>
                <td>15/03/2002</td>
                <td>fatoumata.kone@example.com</td>
                <td>Licence 3</td>
                <td>2024-2025</td>
                <td>0700000001</td>
                <td></td>
            </tr>
            <tr>
                <td><input type="checkbox" class="checkbox"></td>
                <td>ETU002</td>
                <td>Traoré</td>
                <td>Moussa</td>
                <td>22/07/2001</td>
                <td>moussa.traore@example.com</td>
                <td>Master 1</td>
                <td>2024-2025</td>
                <td>0700000002</td>
                <td></td>
            </tr>
            <tr>
                <td><input type="checkbox" class="checkbox"></td>
                <td>ETU003</td>
                <td>Diaby</td>
                <td>Aïcha</td>
                <td>01/11/2003</td>
                <td>aicha.diaby@example.com</td>
                <td>Licence 2</td>
                <td>2024-2025</td>
                <td>0700000003</td>
                <td></td>
            </tr>
            <tr>
                <td><input type="checkbox" class="checkbox"></td>
                <td>ETU004</td>
                <td>Kouassi</td>
                <td>Jean-Luc</td>
                <td>05/09/2000</td>
                <td>jeanluc.kouassi@example.com</td>
                <td>Master 2</td>
                <td>2024-2025</td>
                <td>0700000004</td>
                <td></td>
            </tr>
            <tr>
                <td><input type="checkbox" class="checkbox"></td>
                <td>ETU005</td>
                <td>Yao</td>
                <td>Marie</td>
                <td>10/01/2004</td>
                <td>marie.yao@example.com</td>
                <td>Licence 1</td>
                <td>2024-2025</td>
                <td>0700000005</td>
                <td></td>
            </tr>
            <tr>
                <td><input type="checkbox" class="checkbox"></td>
                <td>ETU006</td>
                <td>Doumbia</td>
                <td>Bakary</td>
                <td>18/04/2002</td>
                <td>bakary.doumbia@example.com</td>
                <td>Licence 3</td>
                <td>2024-2025</td>
                <td>0700000006</td>
                <td></td>
            </tr>
            <tr>
                <td><input type="checkbox" class="checkbox"></td>
                <td>ETU007</td>
                <td>Adjoua</td>
                <td>Fanta</td>
                <td>29/08/2001</td>
                <td>fanta.adjoua@example.com</td>
                <td>Master 1</td>
                <td>2024-2025</td>
                <td>0700000007</td>
                <td></td>
            </tr>
            <tr>
                <td><input type="checkbox" class="checkbox"></td>
                <td>ETU008</td>
                <td>Koffi</td>
                <td>Serge</td>
                <td>03/12/2003</td>
                <td>serge.koffi@example.com</td>
                <td>Licence 2</td>
                <td>2024-2025</td>
                <td>0700000008</td>
                <td></td>
            </tr>
            <tr>
                <td><input type="checkbox" class="checkbox"></td>
                <td>ETU009</td>
                <td>Sanogo</td>
                <td>Aminata</td>
                <td>25/06/2000</td>
                <td>aminata.sanogo@example.com</td>
                <td>Master 2</td>
                <td>2024-2025</td>
                <td>0700000009</td>
                <td></td>
            </tr>
            <tr>
                <td><input type="checkbox" class="checkbox"></td>
                <td>ETU010</td>
                <td>N'Guessan</td>
                <td>Marc</td>
                <td>12/02/2004</td>
                <td>marc.nguessan@example.com</td>
                <td>Licence 1</td>
                <td>2024-2025</td>
                <td>0700000010</td>
                <td></td>
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
<script src="/assets/js/etudiants.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

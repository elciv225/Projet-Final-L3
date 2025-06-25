<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Personnel Administratif</h1>
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
                    <input type="text" name="personnelNumber" class="form-input" placeholder=" " id="personnel-number">
                    <label class="form-label" for="personnel-number">Numéro Matricule</label>
                </div>
                <div class="form-group">
                    <input type="text" name="personnelLastname" class="form-input" placeholder=" " id="personnel-lastname">
                    <label class="form-label" for="personnel-lastname">Nom</label>
                </div>
                <div class="form-group">
                    <input type="text" name="personnelFirstname" class="form-input" placeholder=" " id="personnel-firstname">
                    <label class="form-label" for="personnel-firstname">Prénoms</label>
                </div>
                <div class="form-group">
                    <input type="date" name="dateBirth" class="form-input" placeholder=" " id="birth-date">
                    <label class="form-label" for="birth-date">Date de Naissance</label>
                </div>
            </div>
            <div class="form-grid" style=" margin-top: 20px;">
                <div class="form-group" style=" padding-right: 300px;">
                    <input type="mail" name="email" class="form-input" placeholder=" " id="email">
                    <label class="form-label" for="email">Email</label>
                </div>
                <div class="radio-group">
                    <label>Genre:</label>
                    <div class="radio-option">
                        <input  type="radio" id="genreM" name="genre" value="M">
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
            <h3 class="section-title">Information sur la Carrière</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <select class="form-input" id="poste" name="poste">
                        <option value="">Sélectionnez un poste</option>
                        <option value="secretaire">Secrétaire</option>
                        <option value="comptable">Comptable</option>
                        <option value="rh">Ressources Humaines</option>
                        <option value="informatique">Informatique</option>
                    </select>
                    <label class="form-label" for="poste">Poste occupé</label>
                </div>
                <div class="form-group">
                    <input type="date" name="dateAffectation" class="form-input" placeholder=" " id="date-affectation">
                    <label class="form-label" for="date-affectation">Date d'Affectation</label>
                </div>
                <div class="form-group">
                    <input type="date" name="dateEmbauche" class="form-input" placeholder=" " id="date-embauche">
                    <label class="form-label" for="date-embauche">Date D'Embauche</label>
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
            <h3 class="table-title">Liste du personnel administratif</h3>
            <div class="header-actions">
                <div class="search-container">
                    <span class="search-icon">🔍</span>
                    <input type="text" name="search" class="search-input" placeholder="Rechercher par ...">
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
                <th>Numero Matricule</th>
                <th>Nom</th>
                <th>Prenom</th>
                <th>Date de naissance</th>
                <th>Email</th>
                <th>Poste Occupé</th>
                <th>Date d'Embauche</th>
                <th>Date d'Affectation</th>
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
<script src="/assets/js/personnel-administratif.js" defer></script>
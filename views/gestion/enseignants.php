<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Enseignants</h1>
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
                    <input type="text" name="id" class="form-input" placeholder=" " id="teacher-number" required>
                    <label class="form-label" for="teacher-number">Numéro Matricule (ID)</label>
                </div>
                <div class="form-group">
                    <input type="text" name="nom" class="form-input" placeholder=" " id="teacher-lastname" required>
                    <label class="form-label" for="teacher-lastname">Nom</label>
                </div>
                <div class="form-group">
                    <input type="text" name="prenoms" class="form-input" placeholder=" " id="teacher-firstname" required>
                    <label class="form-label" for="teacher-firstname">Prénoms</label>
                </div>
                <div class="form-group">
                    <input type="date" name="date_naissance" class="form-input" placeholder=" " id="birth-date">
                    <label class="form-label" for="birth-date">Date de Naissance</label>
                </div>
                <div class="form-group">
                    <input type="email" name="email" class="form-input" placeholder=" " id="email" required>
                    <label class="form-label" for="email">Email</label>
                </div>
                 <div class="form-group radio-group">
                    <label>Genre:</label>
                    <div class="radio-option">
                        <input type="radio" id="genreM" name="genre" value="M">
                        <label for="genreM">M</label>
                    </div>
                    <div class="radio-option">
                        <input type="radio" id="genreF" name="genre" value="F">
                        <label for="genreF">F</label>
                    </div>
                    <div class="radio-option">
                        <input type="radio" id="genreND" name="genre" value="ND">
                        <label for="genreND">N.D</label>
                    </div>
                </div>
                <div class="form-group">
                    <input type="tel" name="telephone" class="form-input" placeholder=" " id="telephone">
                    <label class="form-label" for="telephone">Téléphone</label>
                </div>
                <div class="form-group">
                    <input type="text" name="login" class="form-input" placeholder=" " id="login" required>
                    <label class="form-label" for="login">Login</label>
                </div>
                <div class="form-group">
                    <input type="password" name="mot_de_passe" class="form-input" placeholder=" " id="mot_de_passe">
                    <label class="form-label" for="mot_de_passe">Mot de passe (laisser vide pour ne pas changer)</label>
                </div>
                <div class="form-group">
                    <label class="form-label" for="photo">Photo</label>
                    <input type="file" name="photo" class="form-input" id="photo">
                </div>
            </div>
        </div>
    </div>

    <!-- Informations Administratives et Rôles -->
    <div class="form-section">
        <div class="section-header">
            <h3 class="section-title">Information Administratives et Rôles</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <select name="groupe_utilisateur_id" id="groupe_utilisateur_id" class="form-input" required>
                        <option value="">Sélectionnez un groupe</option>
                        <!-- Options à peupler -->
                    </select>
                    <label class="form-label" for="groupe_utilisateur_id">Groupe Utilisateur</label>
                </div>
                <div class="form-group">
                    <select name="type_utilisateur_id" id="type_utilisateur_id" class="form-input" required>
                        <option value="">Sélectionnez un type</option>
                        <!-- Options à peupler -->
                    </select>
                    <label class="form-label" for="type_utilisateur_id">Type Utilisateur</label>
                </div>
                <div class="form-group">
                    <select name="niveau_acces_donnees_id" id="niveau_acces_donnees_id" class="form-input" required>
                        <option value="">Sélectionnez un niveau d'accès</option>
                        <!-- Options à peupler -->
                    </select>
                    <label class="form-label" for="niveau_acces_donnees_id">Niveau d'Accès Données</label>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations carriere -->
    <div class="form-section">
        <div class="section-header">
            <h3 class="section-title">Information sur la carrière</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <select name="grade_id" class="form-input" id="grade_id">
                        <option value="">Sélectionnez un grade</option>
                        <!-- Options à peupler depuis la table grade -->
                    </select>
                    <label class="form-label" for="grade_id">Grade</label>
                </div>
                <div class="form-group">
                    <input type="date" name="date_affectation_grade" class="form-input" placeholder=" " id="date_affectation_grade">
                    <label class="form-label" for="date_affectation_grade">Date du Grade</label>
                </div>
                <div class="form-group">
                     <select name="fonction_id" class="form-input" id="fonction_id">
                        <option value="">Sélectionnez une fonction</option>
                        <!-- Options à peupler depuis la table fonction -->
                    </select>
                    <label class="form-label" for="fonction_id">Fonction</label>
                </div>
                <div class="form-group">
                    <input type="date" name="date_affectation_fonction" class="form-input" placeholder=" " id="date_affectation_fonction">
                    <label class="form-label" for="date_affectation_fonction">Date de la Fonction</label>
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
            <h3 class="table-title">Liste des Enseignants</h3>
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
                <th>Numero Matricule</th>
                <th>Nom</th>
                <th>Prenom</th>
                <th>Date de naissance</th>
                <th>Email</th>
                <th>Grade</th>
                <th>Fonction</th>
                <th>Contact</th>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="/assets/js/enseignants.js" defer></script>
<!-- Bibliothèque pour Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<!-- Bibliothèque pour PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Reglement Frais D'Inscription</h1>
        </div>
    </div>

    <!-- Sélection de l'année académique -->
    <div class="filters-bar mb-20" style="display: flex; justify-content: space-between; align-items: center; padding: 10px; background-color: #f9f9f9; border-radius: 8px;">
        <div class="form-group small-width">
            <select class="form-input" id="id_annee_academique_filtre" name="id_annee_academique_filtre">
                <option value="">Filtrer par Année Académique</option>
                <?php if (isset($anneesAcademiques) && !empty($anneesAcademiques)): ?>
                    <?php foreach ($anneesAcademiques as $annee): ?>
                        <option value="<?= htmlspecialchars($annee->getId()) ?>">
                            <?= htmlspecialchars($annee->getId()) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <label class="form-label" for="id_annee_academique_filtre">Année Académique</label>
        </div>
         <div class="form-group small-width">
            <select class="form-input" id="id_etudiant_filtre" name="id_etudiant_filtre">
                <option value="">Filtrer par Étudiant</option>
                 <?php if (isset($etudiants) && !empty($etudiants)): ?>
                    <?php foreach ($etudiants as $etudiant): ?>
                        <option value="<?= htmlspecialchars($etudiant->getUtilisateurId()) ?>">
                           <?= htmlspecialchars($etudiant->getUtilisateurId()) ?> - (<?= htmlspecialchars($etudiant->getNomComplet() ?? 'N/A') ?>) <?php // TODO: Adjust to actual methods ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <label class="form-label" for="id_etudiant_filtre">Étudiant</label>
        </div>
        <div class="form-group small-width">
            <select class="form-input" id="id_niveau_etude_filtre" name="id_niveau_etude_filtre">
                <option value="">Filtrer par Niveau d'Étude</option>
                <?php if (isset($niveauxEtude) && !empty($niveauxEtude)): ?>
                    <?php foreach ($niveauxEtude as $niveau): ?>
                        <option value="<?= htmlspecialchars($niveau->getId()) ?>">
                            <?= htmlspecialchars($niveau->getLibelle()) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <label class="form-label" for="id_niveau_etude_filtre">Niveau d'Étude</label>
        </div>
        <button class="btn btn-primary" id="btnFiltrerReglements">Filtrer</button>
    </div>

    <!-- Informations de l'etudiant (pour nouvelle saisie de règlement) -->
    <div class="form-section">
        <div class="section-header">
            <h3 class="section-title">Saisie Nouveau Règlement</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                 <div class="form-group">
                    <select class="form-input" id="id_etudiant_saisie" name="id_etudiant_saisie" required>
                        <option value="">Sélectionnez un Étudiant</option>
                         <?php if (isset($etudiants) && !empty($etudiants)): ?>
                            <?php foreach ($etudiants as $etudiant): ?>
                                <option value="<?= htmlspecialchars($etudiant->getUtilisateurId()) ?>">
                                   <?= htmlspecialchars($etudiant->getUtilisateurId()) ?> - (<?= htmlspecialchars($etudiant->getNomComplet() ?? 'N/A') ?>) <?php // TODO: Adjust to actual methods ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <label class="form-label" for="id_etudiant_saisie">Étudiant</label>
                </div>
                <div class="form-group">
                     <select class="form-input" id="id_niveau_etude_saisie" name="id_niveau_etude_saisie" required>
                        <option value="">Sélectionnez Niveau d'Étude</option>
                        <?php if (isset($niveauxEtude) && !empty($niveauxEtude)): ?>
                            <?php foreach ($niveauxEtude as $niveau): ?>
                                <option value="<?= htmlspecialchars($niveau->getId()) ?>">
                                    <?= htmlspecialchars($niveau->getLibelle()) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <label class="form-label" for="id_niveau_etude_saisie">Niveau d'Étude</label>
                </div>
                 <div class="form-group">
                    <select class="form-input" id="id_annee_academique_saisie" name="id_annee_academique_saisie" required>
                        <option value="">Sélectionnez Année Académique</option>
                        <?php if (isset($anneesAcademiques) && !empty($anneesAcademiques)): ?>
                            <?php foreach ($anneesAcademiques as $annee): ?>
                                <option value="<?= htmlspecialchars($annee->getId()) ?>">
                                    <?= htmlspecialchars($annee->getId()) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <label class="form-label" for="id_annee_academique_saisie">Année Académique</label>
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

<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Évaluation des Étudiants</h1>
        </div>
    </div>

    <!-- Formulaire de Saisie des Notes -->
    <div class="form-section">
        <div class="section-header">
            <h3 class="section-title">Nouvelle Évaluation</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <select class="form-input" id="id_etudiant" name="id_etudiant" required>
                        <option value="">Sélectionnez un Étudiant</option>
                        <?php if (isset($etudiants) && !empty($etudiants)): ?>
                            <?php foreach ($etudiants as $etudiant): ?>
                                <?php // Assuming $etudiant object has methods like getId() and a way to get nom/prenom, possibly via a joined utilisateur object or a dedicated method in Etudiant model ?>
                                <option value="<?= htmlspecialchars($etudiant->getUtilisateurId()) // ou $etudiant->getId() if it's the direct PK ?>">
                                    <?= htmlspecialchars($etudiant->getUtilisateurId()) ?> - (<?= htmlspecialchars($etudiant->getNomComplet() ?? 'N/A') ?>)  <?php // TODO: Adjust to actual methods for student name/identifier ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <label class="form-label" for="id_etudiant">Étudiant</label>
                </div>
                <div class="form-group">
                    <select class="form-input" id="id_annee_academique" name="id_annee_academique" required>
                        <option value="">Sélectionnez Année Académique</option>
                         <?php if (isset($anneesAcademiques) && !empty($anneesAcademiques)): ?>
                            <?php foreach ($anneesAcademiques as $annee): ?>
                                <option value="<?= htmlspecialchars($annee->getId()) ?>">
                                    <?= htmlspecialchars($annee->getId()) ?> (<?= htmlspecialchars(date('d/m/Y', strtotime($annee->getDateDebut()))) ?> - <?= htmlspecialchars(date('d/m/Y', strtotime($annee->getDateFin()))) ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <label class="form-label" for="id_annee_academique">Année Académique</label>
                </div>
                <div class="form-group">
                     <select class="form-input" id="id_ue" name="id_ue">
                        <option value="">Sélectionnez une UE (optionnel)</option>
                         <?php if (isset($ues) && !empty($ues)): ?>
                            <?php foreach ($ues as $ue): ?>
                                <option value="<?= htmlspecialchars($ue->getId()) ?>">
                                    <?= htmlspecialchars($ue->getLibelle()) ?> (<?= htmlspecialchars($ue->getCredit()) ?> crédits)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <label class="form-label" for="id_ue">Unité d'Enseignement (UE)</label>
                </div>
                <div class="form-group">
                    <select class="form-input" id="id_ecue" name="id_ecue" required>
                        <option value="">Sélectionnez un ECUE</option>
                         <?php if (isset($ecues) && !empty($ecues)): ?>
                            <?php foreach ($ecues as $ecue): ?>
                                <option value="<?= htmlspecialchars($ecue->getId()) ?>">
                                     <?= htmlspecialchars($ecue->getLibelle()) ?> (UE: <?= htmlspecialchars($ecue->getUeId()) ?>) <?php // TODO: Get UE Libelle if possible ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <label class="form-label" for="id_ecue">Élément Constitutif (ECUE)</label>
                </div>
                <div class="form-group">
                    <input type="date" id="date_evaluation" name="date_evaluation" class="form-input" placeholder=" " required value="<?= date('Y-m-d') ?>">
                    <label class="form-label" for="date_evaluation">Date d'Évaluation</label>
                </div>
                <div class="form-group">
                    <input type="number" id="note" class="form-input" placeholder=" " min="0" max="20">
                    <label class="form-label" for="note">Note (/20)</label>
                </div>
            </div>
        </div>
    </div>

    <div style="display: flex; justify-content: flex-end; padding: 20px 0;">
        <button class="btn btn-primary" id="btnValider">Valider</button>
    </div>

    <!-- Historique des Évaluations -->
    <div class="table-container">
        <div class="table-header">
            <h3 class="table-title">Historique des Évaluations</h3>
            <div class="header-actions">
                <div class="search-container">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="searchInput" class="search-input" placeholder="Rechercher...">
                </div>
                <button id="btnExportPDF" class="btn btn-secondary">🕐 Exporter PDF</button>
                <button id="btnExportExcel" class="btn btn-secondary">📤 Exporter Excel</button>
                <button id="btnPrint" class="btn btn-secondary">📊 Imprimer</button>
                <button class="btn btn-primary" id="btnSupprimerSelection">Supprimer</button>
            </div>
        </div>

        <table class="table">
            <thead>
            <tr>
                <th><input type="checkbox" class="checkbox"></th>
                <th>ID Enseignant</th>
                <th>ID Étudiant</th>
                <th>ID ECUE</th>
                <th>Date</th>
                <th>Note</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <!-- Données dynamiques -->
            </tbody>
        </table>

        <div class="table-footer">
            <div class="results-info">Affichage de 1 à X sur Y entrées</div>
            <div class="pagination">
                <button class="pagination-btn">‹</button>
                <button class="pagination-btn active">1</button>
                <button class="pagination-btn">2</button>
                <span>...</span>
                <button class="pagination-btn">›</button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="/assets/js/evaluation-etudiant.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</main>

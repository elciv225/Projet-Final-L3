<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Évaluation des Étudiants</h1>
        </div>
    </div>

    <!-- Formulaire de Saisie des Notes -->
    <form class="form-section ajax-form" method="post" action="/ajouter-evaluation" data-target=".table-container">
        <div class="section-header">
            <h3 class="section-title">Nouvelle Évaluation</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <select class="form-input" id="enseignant_id" name="enseignant_id" required>
                        <option value="">Sélectionner un enseignant</option>
                        <?php if (isset($enseignants)): foreach ($enseignants as $enseignant): ?>
                            <option value="<?= htmlspecialchars($enseignant['id']) ?>">
                                <?= htmlspecialchars($enseignant['nom_complet']) ?>
                            </option>
                        <?php endforeach; endif; ?>
                    </select>
                    <label class="form-label" for="enseignant_id">Enseignant</label>
                </div>
                <div class="form-group">
                    <select class="form-input" id="etudiant_id" name="etudiant_id" required>
                        <option value="">Sélectionner un étudiant</option>
                        <?php if (isset($etudiants)): foreach ($etudiants as $etudiant): ?>
                            <option value="<?= htmlspecialchars($etudiant['id']) ?>" 
                                    data-carte="<?= htmlspecialchars($etudiant['numero_carte'] ?? '') ?>">
                                <?= htmlspecialchars($etudiant['nom_complet']) ?>
                            </option>
                        <?php endforeach; endif; ?>
                    </select>
                    <label class="form-label" for="etudiant_id">Étudiant</label>
                </div>
                <div class="form-group">
                    <select class="form-input" id="ecue_id" name="ecue_id" required>
                        <option value="">Sélectionner un ECUE</option>
                        <?php if (isset($ecues)): foreach ($ecues as $ecue): ?>
                            <option value="<?= htmlspecialchars($ecue['id']) ?>">
                                <?= htmlspecialchars($ecue['libelle']) ?> (<?= htmlspecialchars($ecue['ue_libelle']) ?>)
                            </option>
                        <?php endforeach; endif; ?>
                    </select>
                    <label class="form-label" for="ecue_id">ECUE</label>
                </div>
                <div class="form-group">
                    <input type="date" id="date_evaluation" name="date_evaluation" class="form-input" placeholder=" " required>
                    <label class="form-label" for="date_evaluation">Date d'Évaluation</label>
                </div>
                <div class="form-group">
                    <input type="number" id="note" name="note" class="form-input" placeholder=" " min="0" max="20" required>
                    <label class="form-label" for="note">Note (/20)</label>
                </div>
            </div>
        </div>
        <div class="section-bottom">
            <button type="submit" class="btn btn-primary">Enregistrer l'évaluation</button>
        </div>
    </form>

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
            </div>
        </div>

        <div class="table-scroll-wrapper scroll-custom">
            <table class="table" id="evaluationTable">
                <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll-evaluationTable" class="checkbox"></th>
                    <th>Enseignant</th>
                    <th>Étudiant</th>
                    <th>ECUE</th>
                    <th>Date</th>
                    <th>Note</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($evaluations) && !empty($evaluations)): ?>
                    <?php foreach ($evaluations as $evaluation): ?>
                        <tr>
                            <td><input type="checkbox" class="checkbox" value="<?= htmlspecialchars($evaluation['enseignant_id'] . '_' . $evaluation['etudiant_id'] . '_' . $evaluation['ecue_id']) ?>"></td>
                            <td><?= htmlspecialchars($evaluation['enseignant_nom']) ?></td>
                            <td><?= htmlspecialchars($evaluation['etudiant_nom']) ?></td>
                            <td><?= htmlspecialchars($evaluation['ecue_libelle']) ?></td>
                            <td><?= htmlspecialchars($evaluation['date_evaluation']) ?></td>
                            <td><?= htmlspecialchars($evaluation['note']) ?>/20</td>
                            <td>
                                <button class="btn-action btn-edit" title="Modifier">✏️</button>
                                <button class="btn-action btn-delete-single" title="Supprimer">🗑️</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 20px;">Aucune évaluation trouvée.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            <div class="results-info" id="resultsInfo-evaluationTable">
                <?php if (isset($evaluations)): ?>
                    Affichage de 1 à <?= count($evaluations) ?> sur <?= count($evaluations) ?> entrées
                <?php else: ?>
                    Affichage de 0 à 0 sur 0 entrées
                <?php endif; ?>
            </div>
            <div class="pagination" id="pagination-evaluationTable">
                <!-- La pagination sera gérée par JavaScript -->
            </div>
        </div>
    </div>

    <!-- Scripts pour la pagination et l'export -->
    <script src="/assets/js/table-utils.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</main>

<?php
// Données passées par le contrôleur :
// $evaluations, $etudiants, $enseignants, $ecues, $anneesAcademiques, $sessionsExamen
?>
<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Évaluation des Étudiants</h1>
            <p>Saisissez et gérez les notes des étudiants pour les différentes ECUEs.</p>
        </div>
    </div>

    <!-- Formulaire de Saisie/Modification des Notes -->
    <form id="form-evaluation" class="form-section ajax-form" method="post" action="/evaluation-etudiant/executer-action" data-target="#evaluationTable > tbody">
        <input type="hidden" name="operation" id="evaluation-operation" value="ajouter">
        <!-- Champs cachés pour stocker les clés originales en mode modification -->
        <input type="hidden" name="id_enseignant_original" id="id_enseignant_original">
        <input type="hidden" name="id_etudiant_original" id="id_etudiant_original">
        <input type="hidden" name="id_ecue_original" id="id_ecue_original">
        <input type="hidden" name="annee_academique_id_original" id="annee_academique_id_original">
        <input type="hidden" name="session_id_original" id="session_id_original">

        <div class="section-header">
            <h3 class="section-title" id="evaluation-form-title">Nouvelle Évaluation</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <select class="form-input" id="enseignant_id" name="enseignant_id" required>
                        <option value="" disabled selected>Choisir un enseignant...</option>
                        <?php if (isset($enseignants)): foreach ($enseignants as $ens): ?>
                            <option value="<?= htmlspecialchars($ens['id']) ?>"><?= htmlspecialchars($ens['nom'] . ' ' . $ens['prenoms']) ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                    <label class="form-label" for="enseignant_id">Enseignant</label>
                </div>
                <div class="form-group">
                    <select class="form-input" id="etudiant_id" name="etudiant_id" required>
                        <option value="" disabled selected>Choisir un étudiant...</option>
                         <?php if (isset($etudiants)): foreach ($etudiants as $etu): ?>
                            <option value="<?= htmlspecialchars($etu['id']) ?>"><?= htmlspecialchars($etu['nom'] . ' ' . $etu['prenoms'] . ' (' . $etu['id'] . ')') ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                    <label class="form-label" for="etudiant_id">Étudiant</label>
                </div>
                <div class="form-group">
                    <select class="form-input" id="ecue_id" name="ecue_id" required>
                        <option value="" disabled selected>Choisir une ECUE...</option>
                        <?php if (isset($ecues)): foreach ($ecues as $ecue): ?>
                            <option value="<?= htmlspecialchars($ecue['id']) ?>"><?= htmlspecialchars($ecue['libelle']) ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                    <label class="form-label" for="ecue_id">ECUE</label>
                </div>
                <div class="form-group">
                    <select class="form-input" id="annee_academique_id" name="annee_academique_id" required>
                        <option value="" disabled selected>Choisir une année académique...</option>
                        <?php if (isset($anneesAcademiques)): foreach ($anneesAcademiques as $annee): ?>
                            <option value="<?= htmlspecialchars($annee->getId()) ?>"><?= htmlspecialchars($annee->getId()) ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                    <label class="form-label" for="annee_academique_id">Année Académique</label>
                </div>
                <div class="form-group">
                    <select class="form-input" id="session_id" name="session_id" required>
                        <option value="" disabled selected>Choisir une session...</option>
                         <?php if (isset($sessionsExamen)): foreach ($sessionsExamen as $session): ?>
                            <option value="<?= htmlspecialchars($session->getId()) ?>"><?= htmlspecialchars($session->getLibelle()) ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                    <label class="form-label" for="session_id">Session</label>
                </div>
                <div class="form-group">
                    <input type="date" id="date_evaluation" name="date_evaluation" class="form-input" value="<?= date('Y-m-d') ?>" required>
                    <label class="form-label" for="date_evaluation">Date d'Évaluation</label>
                </div>
                <div class="form-group">
                    <input type="number" id="note" name="note" class="form-input" placeholder=" " min="0" max="20" step="0.01" required>
                    <label class="form-label" for="note">Note (/20)</label>
                </div>
            </div>
        </div>
        <div class="section-bottom">
            <h3 class="section-title">Action</h3>
            <div style="display: flex; gap: 10px;">
                <button class="btn btn-primary" type="submit" id="btn-submit-evaluation">Ajouter</button>
                <button class="btn btn-secondary" type="button" id="btn-cancel-evaluation" style="display: none;">Annuler</button>
            </div>
        </div>
    </form>

    <!-- Historique des Évaluations -->
    <div id="evaluationTableContainer" class="table-container" style="margin-top:20px;">
        <div class="table-header">
            <h3 class="table-title">Historique des Évaluations</h3>
            <div class="header-actions">
                <div class="search-container">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="searchInputEvaluation" class="search-input" placeholder="Rechercher...">
                </div>
                <form id="delete-form-evaluation" class="ajax-form" method="post" action="/evaluation-etudiant/executer-action" data-target="#evaluationTableContainer" data-warning="Êtes-vous sûr de vouloir supprimer les évaluations sélectionnées ?">
                    <input type="hidden" name="operation" value="supprimer">
                    <div id="hidden-inputs-for-delete-evaluation"></div> <!-- Pour les IDs à supprimer -->
                    <button type="submit" class="btn btn-primary" id="btnSupprimerSelectionEvaluation">Supprimer Sélection</button>
                </form>
            </div>
        </div>

        <div class="table-scroll-wrapper scroll-custom">
            <table class="table" id="evaluationTable">
                <thead>
                <tr>
                    <th><input type="checkbox" id="selectAllEvaluations" class="checkbox"></th>
                    <th>Enseignant</th>
                    <th>Étudiant</th>
                    <th>ECUE</th>
                    <th>Année Acad.</th>
                    <th>Session</th>
                    <th>Date Éval.</th>
                    <th>Note</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                    <?php if (isset($evaluations) && !empty($evaluations)): ?>
                        <?php foreach ($evaluations as $eval):
                            // Création d'une clé composite unique pour la ligne et la checkbox
                            $compositeKey = json_encode([
                                'enseignant_id' => $eval['enseignant_id'],
                                'etudiant_id' => $eval['etudiant_id'],
                                'ecue_id' => $eval['ecue_id'],
                                'annee_academique_id' => $eval['annee_academique_id'],
                                'session_id' => $eval['session_id']
                            ]);
                        ?>
                            <tr data-composite-key='<?= htmlspecialchars($compositeKey, ENT_QUOTES, 'UTF-8') ?>'>
                                <td><input type="checkbox" class="checkbox select-evaluation-item" value='<?= htmlspecialchars($compositeKey, ENT_QUOTES, 'UTF-8') ?>'></td>
                                <td data-label="Enseignant"><?= htmlspecialchars($eval['enseignant_nom'] . ' ' . $eval['enseignant_prenoms']) ?></td>
                                <td data-label="Étudiant"><?= htmlspecialchars($eval['etudiant_nom'] . ' ' . $eval['etudiant_prenoms']) ?></td>
                                <td data-label="ECUE"><?= htmlspecialchars($eval['ecue_libelle']) ?></td>
                                <td data-label="Année Acad."><?= htmlspecialchars($eval['annee_academique_id']) ?></td>
                                <td data-label="Session"><?= htmlspecialchars($eval['session_id']) ?></td>
                                <td data-label="Date Éval."><?= htmlspecialchars(date('d/m/Y', strtotime($eval['date_evaluation']))) ?></td>
                                <td data-label="Note"><?= htmlspecialchars(number_format($eval['note'], 2, ',', ' ')) ?></td>
                                <td>
                                    <button class="btn-action btn-edit-evaluation" title="Modifier">✏️</button>
                                    <button class="btn-action btn-delete-single-evaluation" title="Supprimer">🗑️</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="9" style="text-align: center; padding: 20px;">Aucune évaluation trouvée.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            <div class="results-info" id="resultsInfoEvaluation"></div>
            <div class="pagination" id="paginationEvaluation"></div>
        </div>
    </div>
</main>

<!-- Scripts -->
<script src="/assets/js/evaluation-etudiant.js" defer></script>
<!-- Les bibliothèques d'export peuvent être incluses dans le layout principal si utilisées sur plusieurs pages -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js" defer></script>

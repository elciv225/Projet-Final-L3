<?php
// Supposons que $etudiants, $niveauxEtude, et $anneesAcademiques sont disponibles ici
// et passés par le contrôleur.
?>
<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Gestion des Étudiants</h1>
        </div>
    </div>

    <!-- Formulaire d'inscription et de modification -->
    <form class="form-section ajax-form" method="post" action="/traitement-etudiant" data-target="#etudiantTable tbody">
        <input name="operation" id="form-operation" value="inscrire" type="hidden">
        <input name="id-etudiant" id="id-etudiant-form" value="" type="hidden">

        <div class="section-header"><h3 class="section-title" id="form-title">Inscrire un nouvel étudiant</h3></div>
        <div class="section-content">
            <div class="form-grid">
                <!-- Champs affichés uniquement en mode modification -->
                <div id="edit-only-fields" style="display: none; grid-column: 1 / -1; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <input type="text" id="id-etudiant-display" class="form-input" placeholder=" " disabled>
                        <label class="form-label" for="id-etudiant-display">Identifiant Permanent</label>
                    </div>
                    <div class="form-group">
                        <input type="text" id="numero-carte-display" class="form-input" placeholder=" " disabled>
                        <label class="form-label" for="numero-carte-display">Numéro Carte Étudiant</label>
                    </div>
                </div>

                <!-- Champs standards -->
                <div class="form-group">
                    <input type="text" name="nom-etudiant" id="nom-etudiant" class="form-input" placeholder=" " >
                    <label class="form-label" for="nom-etudiant">Nom</label>
                </div>
                <div class="form-group">
                    <input type="text" name="prenom-etudiant" id="prenom-etudiant" class="form-input" placeholder=" " >
                    <label class="form-label" for="prenom-etudiant">Prénom(s)</label>
                </div>
                <div class="form-group">
                    <input type="email" name="email-etudiant" id="email-etudiant" class="form-input" placeholder=" " >
                    <label class="form-label" for="email-etudiant">Email</label>
                </div>
                <div class="form-group">
                    <input type="date" name="date-naissance" id="date-naissance" class="form-input" placeholder=" " >
                    <label class="form-label" for="date-naissance">Date de naissance</label>
                </div>
                <div class="form-group">
                    <select class="form-input" id="id-niveau-etude" name="id-niveau-etude" >
                        <option value="" disabled selected>Choisir un niveau...</option>
                        <?php if (isset($niveauxEtude)): foreach ($niveauxEtude as $niveau): ?>
                            <option value="<?= htmlspecialchars($niveau->getId()) ?>"><?= htmlspecialchars($niveau->getLibelle()) ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                    <label class="form-label" for="id-niveau-etude">Niveau d'étude</label>
                </div>
                <div class="form-group">
                    <select class="form-input" id="id-annee-academique" name="id-annee-academique" >
                        <option value="" disabled selected>Choisir une année...</option>
                        <?php if (isset($anneesAcademiques)): foreach ($anneesAcademiques as $annee): ?>
                            <option value="<?= htmlspecialchars($annee->getId()) ?>"><?= htmlspecialchars($annee->getId()) ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                    <label class="form-label" for="id-annee-academique">Année Académique</label>
                </div>
                <div class="form-group">
                    <input type="number" name="montant-inscription" id="montant-inscription" class="form-input" placeholder=" " >
                    <label class="form-label" for="montant-inscription">Montant Inscription (FCFA)</label>
                </div>
            </div>
        </div>
        <div class="section-bottom">
            <h3 class="section-title">Action</h3>
            <div style="display: flex; gap: 10px;">
                <button class="btn btn-primary" id="btn-submit-form" type="submit">Inscrire</button>
                <button class="btn btn-secondary" id="btn-cancel-edit" type="button" style="display: none;">Annuler</button>
            </div>
        </div>
    </form>

    <!-- Zone où le tableau sera chargé/rechargé via AJAX -->

        <div class="table-container" id="container-etudiantTable">
            <div class="table-header">
                <h3 class="table-title">Liste des Étudiants Inscrits</h3>
                <div class="header-actions">
                    <div class="search-container">
                        <span class="search-icon">🔍</span>
                        <input type="text" id="searchInput-etudiantTable" class="search-input" placeholder="Rechercher...">
                    </div>
                    <form id="delete-form-etudiantTable" class="ajax-form" method="post" action="/traitement-etudiant" data-warning="" data-target=".table-scroll-wrapper">
                        <input name="operation" value="supprimer" type="hidden">
                        <div id="hidden-inputs-for-delete-etudiantTable"></div>
                        <button type="submit" class="btn btn-primary" id="btnSupprimerSelection-etudiantTable">Supprimer la sélection</button>
                    </form>
                </div>
            </div>
            <div class="table-scroll-wrapper scroll-custom">
                <table class="table" id="etudiantTable">
                    <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll-etudiantTable" class="checkbox"></th>
                        <th>Identifiant Permanent</th>
                        <th>N° Carte</th>
                        <th>Nom & Prénom(s)</th>
                        <th>Email</th>
                        <th>Niveau</th>
                        <th>Année Acad.</th>
                        <th>Montant</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($etudiants) && !empty($etudiants)): ?>
                        <?php foreach ($etudiants as $etudiant): ?>
                            <tr>
                                <td><input type="checkbox" class="checkbox" name="ids[]" value="<?= htmlspecialchars($etudiant['id'] ?? '') ?>"></td>
                                <td class="id-etudiant"><?= htmlspecialchars($etudiant['id'] ?? '') ?></td>
                                <td class="numero-carte"><?= htmlspecialchars($etudiant['numero_carte'] ?? 'N/A') ?></td>
                                <td class="nom-prenoms"
                                    data-nom="<?= htmlspecialchars($etudiant['nom'] ?? '') ?>"
                                    data-prenoms="<?= htmlspecialchars($etudiant['prenoms'] ?? '') ?>"
                                    data-date-naissance="<?= htmlspecialchars($etudiant['date_naissance'] ?? '') ?>">
                                    <?= htmlspecialchars(($etudiant['nom'] ?? '') . ' ' . ($etudiant['prenoms'] ?? '')) ?>
                                </td>
                                <td class="email-etudiant"><?= htmlspecialchars($etudiant['email'] ?? '') ?></td>
                                <td class="niveau-etude" data-niveau-id="<?= htmlspecialchars($etudiant['niveau_etude_id'] ?? '') ?>">
                                    <?= htmlspecialchars($etudiant['niveau_etude'] ?? 'Non inscrit') ?>
                                </td>
                                <td class="annee-academique" data-annee-id="<?= htmlspecialchars($etudiant['annee_academique_id'] ?? '') ?>">
                                    <?= htmlspecialchars($etudiant['annee_academique_id'] ?? 'N/A') ?>
                                </td>
                                <td class="montant-inscription"><?= htmlspecialchars($etudiant['montant'] ?? 'N/A') ?></td>
                                <td>
                                    <button class="btn-action btn-edit" title="Modifier l'inscription" data-id="<?= htmlspecialchars($etudiant['id']) ?>">✏️</button>
                                    <button class="btn-action btn-delete-single" title="Supprimer l'étudiant" data-id="<?= htmlspecialchars($etudiant['id']) ?>">🗑️</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 20px;">Aucun étudiant trouvé.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="table-footer">
                <div class="results-info" id="resultsInfo-etudiantTable"></div>
                <div class="pagination" id="pagination-etudiantTable"></div>
            </div>
        </div>

</main>

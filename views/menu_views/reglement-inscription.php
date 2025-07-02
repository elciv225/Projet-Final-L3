<?php
// Données passées par le contrôleur : $anneesAcademiques
?>
<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Règlement des Frais d'Inscription</h1>
            <p>Enregistrez et suivez les paiements des frais d'inscription des étudiants.</p>
        </div>
    </div>

    <!-- Section de recherche et sélection -->
    <div class="form-section">
        <div class="section-header"><h3 class="section-title">Recherche de l'Étudiant</h3></div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <select class="form-input" id="select-annee-academique-reglement" name="annee_academique_id">
                        <option value="" disabled selected>Choisir une année académique...</option>
                        <?php if (isset($anneesAcademiques)): foreach ($anneesAcademiques as $annee): ?>
                            <option value="<?= htmlspecialchars($annee->getId()) ?>"><?= htmlspecialchars($annee->getId()) ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                    <label class="form-label" for="select-annee-academique-reglement">Année Académique</label>
                </div>
                <div class="form-group">
                    <input type="text" name="etudiant_id_recherche" id="etudiant-id-recherche" class="form-input" placeholder=" ">
                    <label class="form-label" for="etudiant-id-recherche">ID Étudiant (Matricule)</label>
                </div>
                 <div class="form-group" style="align-self: flex-end;">
                    <button class="btn btn-primary" id="btn-rechercher-etudiant-reglement" type="button">Rechercher</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations de l'étudiant et de l'inscription (chargées via AJAX) -->
    <div id="info-etudiant-inscription-container" class="form-section" style="display: none;">
        <div class="section-header"><h3 class="section-title">Détails de l'Inscription</h3></div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <input type="text" id="info-nom-prenoms" class="form-input" placeholder=" " readonly>
                    <label class="form-label" for="info-nom-prenoms">Nom et Prénom(s)</label>
                </div>
                <div class="form-group">
                    <input type="text" id="info-niveau-etude" class="form-input" placeholder=" " readonly>
                    <label class="form-label" for="info-niveau-etude">Niveau d'Étude</label>
                </div>
                 <div class="form-group">
                    <input type="text" id="info-montant-initial" class="form-input" placeholder=" " readonly>
                    <label class="form-label" for="info-montant-initial">Montant Inscription (FCFA)</label>
                </div>
                <div class="form-group">
                    <input type="text" id="info-total-paye" class="form-input" placeholder=" " readonly>
                    <label class="form-label" for="info-total-paye">Total Déjà Payé (FCFA)</label>
                </div>
                 <div class="form-group">
                    <input type="text" id="info-reste-a-payer" class="form-input" placeholder=" " readonly>
                    <label class="form-label" for="info-reste-a-payer">Reste à Payer (FCFA)</label>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire d'enregistrement de paiement (affiché après recherche fructueuse) -->
    <form id="form-enregistrement-paiement" class="form-section ajax-form" method="post" action="/reglement-inscription/enregistrer-paiement" data-target="#info-etudiant-inscription-container" style="display: none;">
        <input type="hidden" name="etudiant_id" id="paiement-etudiant-id">
        <input type="hidden" name="annee_academique_id" id="paiement-annee-id">

        <div class="section-header"><h3 class="section-title">Enregistrer un Paiement</h3></div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <input type="number" name="montant_a_payer" id="montant-a-payer" class="form-input" placeholder=" " required min="1">
                    <label class="form-label" for="montant-a-payer">Montant à Payer (FCFA)</label>
                </div>
                <div class="form-group">
                    <input type="date" name="date_paiement" id="date-paiement" class="form-input" placeholder=" " value="<?= date('Y-m-d') ?>" required>
                    <label class="form-label" for="date-paiement">Date du Paiement</label>
                </div>
            </div>
        </div>
        <div class="section-bottom">
            <h3 class="section-title">Action</h3>
            <button class="btn btn-primary" type="submit">Enregistrer Paiement</button>
        </div>
    </form>

    <!-- Historique des paiements (chargé via AJAX) -->
    <div id="historique-paiements-container" class="table-container" style="display: none; margin-top: 20px;">
        <div class="table-header">
            <h3 class="table-title">Historique des Paiements pour cette Inscription</h3>
             <div class="header-actions">
                 <button id="btnExportPDFHistorique" class="btn btn-secondary btn-sm">Exporter PDF</button>
                 <button id="btnExportExcelHistorique" class="btn btn-secondary btn-sm">Exporter Excel</button>
            </div>
        </div>
        <div class="table-scroll-wrapper scroll-custom">
            <table class="table" id="table-historique-paiements">
                <thead>
                    <tr>
                        <th>Date du Paiement</th>
                        <th>Montant Payé (FCFA)</th>
                        <th>Référence (si applicable)</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Les lignes seront injectées par JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Bibliothèques pour export (si utilisées côté client) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js" defer></script>


<script src="/assets/js/reglement-inscription.js" defer></script>

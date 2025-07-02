<?php
// Données passées par le contrôleur :
// (Initialement, aucune donnée spécifique à part le titre et le heading)
// Après AJAX : $enteteTable, $lignesTable, $typeHistoriqueDemandee, $nomPersonnel, $utilisateurId, $donneesChargees
?>
<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Historique du Personnel</h1>
            <p>Consultez l'historique des fonctions, grades et spécialités du personnel.</p>
        </div>
        <div class="form-group small-width right-align mb-20">
            <!-- Le select pour le type d'utilisateur est le premier filtre -->
            <select id="selectTypeUtilisateur" class="form-input" name="type_utilisateur_filtre">
                <option value="">Sélectionner un type de personnel...</option>
                <option value="enseignant">Enseignant</option>
                <option value="personnel_administratif">Personnel Administratif</option>
            </select>
            <label for="selectTypeUtilisateur" class="form-label visually-hidden">Type de Personnel</label>
        </div>
    </div>

    <!-- Section de sélection des filtres pour l'historique -->
    <form class="form-section ajax-form" method="post" action="/historique-personnel/afficher" data-target="#zoneTableHistorique">
        <div class="section-header">
            <h3 class="section-title">Filtres de Recherche d'Historique</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <select id="selectUtilisateurSpecifique" name="utilisateur" class="form-input" disabled>
                        <option value="">Sélectionner d'abord un type de personnel...</option>
                        <?php /* Les options seront chargées par JS */ ?>
                    </select>
                    <label for="selectUtilisateurSpecifique" class="form-label">Personnel</label>
                </div>
                <div class="form-group">
                    <select id="selectTypeHistorique" class="form-input" name="type-historique" disabled>
                        <option value="">Sélectionner un type d'historique...</option>
                        <option value="fonction">Historique des Fonctions</option>
                        <option value="grade">Historique des Grades</option>
                        <option value="specialite">Historique des Spécialités (Enseignants)</option>
                    </select>
                    <label for="selectTypeHistorique" class="form-label">Type d'Historique</label>
                </div>
            </div>
        </div>
        <div class="section-bottom">
            <h3 class="section-title">Action</h3>
            <button class="btn btn-primary" type="submit" disabled>Rechercher</button>
        </div>
    </form>

    <!-- Zone où le tableau d'historique sera chargé -->
    <div id="zoneTableHistorique" class="table-container" style="margin-top: 20px;">
        <!-- Le contenu (table ou message) sera injecté ici par AJAX -->
        <?php if (isset($donneesChargees) && $donneesChargees): ?>
            <?php include __DIR__ . '/../partials/table-historique-contenu.php'; // Inclure la vue partielle ?>
        <?php else: ?>
            <div class="table-scroll-wrapper scroll-custom">
                <table class="table">
                    <thead><tr><th>Veuillez sélectionner les filtres pour afficher l'historique.</th></tr></thead>
                    <tbody><tr><td></td></tr></tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</main>

<script src="/assets/js/historique-personnel.js" defer></script>

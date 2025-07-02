<?php
// Données passées par le contrôleur : $stats (optionnel)
?>
<main class="main-content">
    <header class="page-header">
        <div class="header-left">
            <h1>Gestion des Paramètres Généraux</h1>
            <p>Configurez ici les différents paramètres et nomenclatures de l'application.</p>
        </div>
        <div class="form-group small-width right-align mb-20">
            <!-- Changement de l'action pour pointer vers la nouvelle méthode du contrôleur -->
            <!-- La soumission se fait en GET pour charger la vue, ou le JS peut faire un appel GET -->
            <form action="/parametres-generaux/charger-vue" method="get" class="ajax-form" data-target="#zone-dynamique">
                <select class="form-input select-submit" id="paramatre-specifique" name="parametre">
                    <option value="" disabled selected>Choisir un paramètre...</option>
                    <option value="annee_academique">Années Académiques</option>
                    <option value="niveau_etude">Niveaux d'Étude</option>
                    <option value="grade">Grades</option>
                    <option value="fonction">Fonctions</option>
                    <option value="specialite">Spécialités</option>
                    <option value="entreprise">Entreprises</option>
                    <option value="categorie_menu">Catégories de Menu</option>
                    <option value="menu">Menus</option>
                    <option value="ue">Unités d'Enseignement (UE)</option>
                    <option value="ecue">Éléments Constitutifs (ECUE)</option>
                    <option value="categorie_utilisateur">Catégories Utilisateur</option>
                    <option value="groupe_utilisateur">Groupes Utilisateur</option>
                    <option value="niveau_acces_donnees">Niveaux d'Accès Données</option>
                    <option value="statut_jury">Statuts Jury</option>
                    <option value="niveau_approbation">Niveaux d'Approbation</option>
                    <option value="traitement">Traitements</option>
                    <option value="action">Actions</option>
                </select>
                <label for="paramatre-specifique" class="form-label visually-hidden">Sélectionner un Paramètre</label>
            </form>
        </div>
    </header>

    <div id="zone-dynamique" class="container">
        <!-- Message initial ou statistiques -->
        <?php if (isset($stats) && !empty($stats)): ?>
            <div class="stats-grid">
                <?php if (isset($stats['nb_ue'])): ?>
                <div class="stat-card">
                    <div class="stat-header"><span class="stat-title">Unités d'Enseignement</span><span class="stat-icon blue">📘</span></div>
                    <div class="stat-value"><?= htmlspecialchars($stats['nb_ue']) ?></div>
                </div>
                <?php endif; ?>
                <?php if (isset($stats['nb_annee_academique'])): ?>
                <div class="stat-card">
                    <div class="stat-header"><span class="stat-title">Années Académiques</span><span class="stat-icon green">📅</span></div>
                    <div class="stat-value"><?= htmlspecialchars($stats['nb_annee_academique']) ?></div>
                </div>
                <?php endif; ?>
                <!-- Ajouter d'autres cartes de statistiques ici -->
            </div>
        <?php else: ?>
            <p class="text-center p-4">Veuillez sélectionner un paramètre dans la liste déroulante ci-dessus pour commencer la configuration.</p>
        <?php endif; ?>
    </div>
</main>

<script src="/assets/js/parametres-generaux.js" defer></script>
<main class="main-content">
    <header class="page-header">
        <div class="header-left">
            <h1>Gestion des Paramètres spécifique</h1>
        </div>

        <form action="/charger-formulaire-paramatre-specifique" method="post" class="ajax-form" data-target="#zone-dynamique">
            <select class="form-input select-submit" id="paramatre-specifique" name="parametre-specifique">
                <option value="">Paramètre</option>
                <option value="ue">UE</option>
                <option value="ecue">ECUE</option>
                <option value="entreprise">Entreprise</option>
                <option value="niveau_etude">Niveau d'étude</option>
                <option value="grade">Grade</option>
                <option value="specialite">Spécialité</option>
                <option value="fonction">Fonction</option>
                <option value="groupe_utilisateur">Groupe d'utilisateur</option>
                <option value="niveau_acces_donnees">Niveau d'accès aux données</option>
                <option value="statut_jury">Statut du Jury</option>
                <option value="niveau_approbation">Niveau d'approbation</option>
                <option value="traitement">Traitement</option>
                <option value="action">Action</option>
                <option value="annee_academique">Année Académique</option>
                <option value="categorie_menu">Catégie Menu</option>
                <option value="menu">Menu</option>
            </select>
            <label for="parametre-specifique" class="form-label">Paramètre Spécifique</label>
        </form>
    </header>

    <div id="zone-dynamique">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">UE</span>
                    <span class="stat-icon blue"></span>
                </div>
                <div class="stat-value">29</div>
            </div>
        </div>
    </div>
</main>

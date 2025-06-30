<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Historique des Utilisateurs</h1>
        </div>
    </div>

    <!-- Section de sélection des filtres -->
    <div class="selection-section">
        <div class="form-group">
            <div class="select-wrapper">
                <select id="selectTypeUtilisateur" class="form-input">
                    <option value="">Sélectionner un type d'utilisateur</option>
                    <option value="enseignant">Enseignant</option>
                    <option value="personnel_administratif">Personnel Administratif</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="select-wrapper">
                <select id="selectTypeHistorique" class="form-input" disabled>
                    <option value="">Sélectionner un type d'historique</option>
                    <option value="fonction">Historique Fonction</option>
                    <option value="grade">Historique Grade</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="select-wrapper">
                <select id="selectUtilisateurSpecifique" class="form-input" disabled>
                    <option value="">Sélectionner un utilisateur</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tableau d'historique -->
    <div class="table-container">
        <div class="table-header">
            <h3>Détails de l'Historique</h3>
        </div>
        <table class="table">
            <thead>
            <tr id="enTeteTableHistorique">
                <!-- L'entete est dynamiquement chargé ici par JavaScript -->
            </tr>
            </thead>
            <tbody id="corpsTableHistorique">
            <!-- Les données sont dynamiquement chargées ici par JavaScript -->
            <tr>
                <td colspan="3" style="text-align: center; padding: 20px;">Veuillez sélectionner les filtres ci-dessus pour afficher l'historique.</td>
            </tr>
            </tbody>
        </table>
    </div>
</main>
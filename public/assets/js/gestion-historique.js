document.addEventListener('DOMContentLoaded', function() {
    // Références des éléments HTML (IDs mis à jour en français)
    const selectTypeUtilisateur = document.getElementById('selectTypeUtilisateur');
    const selectTypeHistorique = document.getElementById('selectTypeHistorique');
    const selectUtilisateurSpecifique = document.getElementById('selectUtilisateurSpecifique');
    const enTeteTableHistorique = document.getElementById('enTeteTableHistorique');
    const corpsTableHistorique = document.getElementById('corpsTableHistorique');

    // --- Données factices (à remplacer par des appels API réels) ---

    // Données des utilisateurs (enseignants et personnel administratif)
    const allUsers = [
        { id: 'ENS001', name: 'Dupont Jean', type: 'enseignant' },
        { id: 'ENS002', name: 'Martin Sophie', type: 'enseignant' },
        { id: 'ADM001', name: 'Durand Paul', type: 'personnel_administratif' },
        { id: 'ADM002', name: 'Petit Marie', type: 'personnel_administratif' }
    ];

    // Données pour historique_fonction
    const historiqueFonctionData = [
        { utilisateur_id: 'ENS001', fonction_id: 'FCT_CHEF_DEPT', fonction_nom: 'Chef de Département', date_occupation: '2022-09-01' },
        { utilisateur_id: 'ENS001', fonction_id: 'FCT_SECRETAIRE', fonction_nom: 'Secrétaire', date_occupation: '2020-01-15' },
        { utilisateur_id: 'ADM001', fonction_id: 'FCT_RESP_RH', fonction_nom: 'Responsable RH', date_occupation: '2021-03-01' }
    ];

    // Données pour historique_grade
    const historiqueGradeData = [
        { utilisateur_id: 'ENS001', grade_id: 'GRD_MAITRE_CONF', grade_nom: 'Maître de Conférences', date_grade: '2021-01-01' },
        { utilisateur_id: 'ENS001', grade_id: 'GRD_ASSISTANT', grade_nom: 'Assistant', date_grade: '2018-09-01' },
        { utilisateur_id: 'ENS002', grade_id: 'GRD_PROFESSEUR', grade_nom: 'Professeur', date_grade: '2023-05-01' }
    ];

    // Données des fonctions (pour afficher le nom complet)
    const fonctions = {
        'FCT_CHEF_DEPT': 'Chef de Département',
        'FCT_SECRETAIRE': 'Secrétaire',
        'FCT_RESP_RH': 'Responsable RH'
    };

    // Données des grades (pour afficher le nom complet)
    const grades = {
        'GRD_MAITRE_CONF': 'Maître de Conférences',
        'GRD_ASSISTANT': 'Assistant',
        'GRD_PROFESSEUR': 'Professeur'
    };

    // --- Fonctions de peuplement des menus déroulants et du tableau ---

    /**
     * Peuple le menu déroulant des utilisateurs en fonction du type sélectionné.
     */
    function populateSpecificUserSelect() {
        const selectedUserType = selectTypeUtilisateur.value;
        selectUtilisateurSpecifique.innerHTML = '<option value="">Sélectionner un utilisateur</option>';
        selectUtilisateurSpecifique.disabled = true; // Désactiver par défaut

        if (selectedUserType) {
            const filteredUsers = allUsers.filter(user => user.type === selectedUserType);
            filteredUsers.forEach(user => {
                const option = document.createElement('option');
                option.value = user.id;
                option.textContent = `${user.name} (${user.id})`;
                selectUtilisateurSpecifique.appendChild(option);
            });
            selectUtilisateurSpecifique.disabled = false; // Activer si un type est sélectionné
        }
        selectUtilisateurSpecifique.value = ''; // Réinitialiser la sélection de l'utilisateur
        renderHistoryTable(); // Re-rendre le tableau après le changement de type d'utilisateur
    }

    /**
     * Rend le tableau d'historique en fonction des sélections.
     */
    function renderHistoryTable() {
        const selectedUserType = selectTypeUtilisateur.value;
        const selectedHistoricalType = selectTypeHistorique.value;
        const selectedUserId = selectUtilisateurSpecifique.value;

        enTeteTableHistorique.innerHTML = ''; // Effacer les en-têtes
        corpsTableHistorique.innerHTML = ''; // Effacer le corps du tableau

        if (!selectedUserType || !selectedHistoricalType || !selectedUserId) {
            // Afficher un message si les filtres ne sont pas entièrement sélectionnés
            const row = document.createElement('tr');
            row.innerHTML = `<td colspan="3" style="text-align: center; padding: 20px;">Veuillez sélectionner un type d'utilisateur, un type d'historique et un utilisateur.</td>`;
            corpsTableHistorique.appendChild(row);
            return;
        }

        let dataToShow = [];
        let headers = [];

        if (selectedHistoricalType === 'fonction') {
            headers = ['ID Utilisateur', 'Fonction', 'Date d\'Occupation'];
            dataToShow = historiqueFonctionData.filter(item => item.utilisateur_id === selectedUserId);
        } else if (selectedHistoricalType === 'grade') {
            headers = ['ID Utilisateur', 'Grade', 'Date du Grade'];
            dataToShow = historiqueGradeData.filter(item => item.utilisateur_id === selectedUserId);
        }

        // Créer les en-têtes du tableau
        headers.forEach(headerText => {
            const th = document.createElement('th');
            th.textContent = headerText;
            enTeteTableHistorique.appendChild(th);
        });

        // Remplir le corps du tableau
        if (dataToShow.length === 0) {
            const row = document.createElement('tr');
            row.innerHTML = `<td colspan="${headers.length}" style="text-align: center; padding: 20px;">Aucun historique trouvé pour cet utilisateur.</td>`;
            corpsTableHistorique.appendChild(row);
        } else {
            dataToShow.forEach(item => {
                const row = document.createElement('tr');
                if (selectedHistoricalType === 'fonction') {
                    const fonctionNom = fonctions[item.fonction_id] || item.fonction_id;
                    row.innerHTML = `
                            <td>${item.utilisateur_id}</td>
                            <td>${fonctionNom}</td>
                            <td>${item.date_occupation}</td>
                        `;
                } else if (selectedHistoricalType === 'grade') {
                    const gradeNom = grades[item.grade_id] || item.grade_id;
                    row.innerHTML = `
                            <td>${item.utilisateur_id}</td>
                            <td>${gradeNom}</td>
                            <td>${item.date_grade}</td>
                        `;
                }
                corpsTableHistorique.appendChild(row);
            });
        }
    }

    // --- Écouteurs d'événements ---

    selectTypeUtilisateur.addEventListener('change', function() {
        populateSpecificUserSelect();
        selectTypeHistorique.disabled = !this.value;
        selectTypeHistorique.value = '';
        renderHistoryTable();
    });

    selectTypeHistorique.addEventListener('change', function() {
        renderHistoryTable();
    });

    selectUtilisateurSpecifique.addEventListener('change', function() {
        renderHistoryTable();
    });

    // Appel initial pour s'assurer que le tableau est vide ou affiche le message par défaut
    renderHistoryTable();
});
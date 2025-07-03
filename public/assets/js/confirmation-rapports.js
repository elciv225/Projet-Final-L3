/**
 * Script pour la gestion des rapports dans la page de confirmation des rapports
 */
document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    let currentPage = 1;
    let totalPages = 1;
    let itemsPerPage = 10;
    let selectedRapports = [];

    // Éléments DOM
    const rapportsTableBody = document.getElementById('rapportsTableBody');
    const resultsInfo = document.getElementById('resultsInfo');
    const paginationControls = document.getElementById('pagination-controls');
    const prevPageBtn = document.getElementById('prevPage');
    const nextPageBtn = document.getElementById('nextPage');
    const filterButton = document.getElementById('filterButton');
    const resetButton = document.getElementById('resetButton');
    const searchInput = document.getElementById('searchInput');
    const masterCheckbox = document.getElementById('masterCheckbox');
    const applyActionButton = document.getElementById('applyActionButton');
    const etudiantSelect = document.getElementById('etudiantSelect');

    // Initialisation
    init();

    /**
     * Initialise la page
     */
    function init() {
        // Charger les étudiants dans le select
        chargerEtudiants();
        
        // Charger les rapports
        chargerRapports();
        
        // Ajouter les écouteurs d'événements
        ajouterEcouteurs();
    }

    /**
     * Ajoute les écouteurs d'événements
     */
    function ajouterEcouteurs() {
        // Filtrage
        if (filterButton) {
            filterButton.addEventListener('click', function() {
                currentPage = 1;
                chargerRapports();
            });
        }
        
        // Réinitialisation des filtres
        if (resetButton) {
            resetButton.addEventListener('click', function() {
                // Réinitialiser les filtres
                document.getElementById('statutRapportSelect').value = '';
                document.getElementById('etudiantSelect').value = '';
                document.getElementById('dateDebutInput').value = '';
                document.getElementById('dateFinInput').value = '';
                searchInput.value = '';
                
                // Recharger les rapports
                currentPage = 1;
                chargerRapports();
            });
        }
        
        // Recherche
        if (searchInput) {
            searchInput.addEventListener('input', debounce(function() {
                currentPage = 1;
                chargerRapports();
            }, 500));
        }
        
        // Pagination
        if (prevPageBtn) {
            prevPageBtn.addEventListener('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    chargerRapports();
                }
            });
        }
        
        if (nextPageBtn) {
            nextPageBtn.addEventListener('click', function() {
                if (currentPage < totalPages) {
                    currentPage++;
                    chargerRapports();
                }
            });
        }
        
        // Sélection de tous les rapports
        if (masterCheckbox) {
            masterCheckbox.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.rapport-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = masterCheckbox.checked;
                    const rapportId = checkbox.getAttribute('data-rapport-id');
                    
                    if (masterCheckbox.checked) {
                        if (!selectedRapports.includes(rapportId)) {
                            selectedRapports.push(rapportId);
                        }
                    } else {
                        selectedRapports = selectedRapports.filter(id => id !== rapportId);
                    }
                });
                
                updateActionButtonState();
            });
        }
        
        // Application d'une action
        if (applyActionButton) {
            applyActionButton.addEventListener('click', function() {
                const action = document.getElementById('actionSelect').value;
                const commentaire = document.getElementById('commentaireInput').value;
                
                if (!action) {
                    alert('Veuillez sélectionner une action à appliquer.');
                    return;
                }
                
                if (selectedRapports.length === 0) {
                    alert('Veuillez sélectionner au moins un rapport.');
                    return;
                }
                
                executerAction(action, selectedRapports, commentaire);
            });
        }
    }

    /**
     * Charge la liste des étudiants dans le select
     */
    function chargerEtudiants() {
        if (!etudiantSelect) return;
        
        fetch('/confirmation-rapports/get-etudiants', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.statut === 'succes') {
                // Vider le select sauf la première option
                while (etudiantSelect.options.length > 1) {
                    etudiantSelect.remove(1);
                }
                
                // Ajouter les étudiants
                data.data.forEach(etudiant => {
                    const option = document.createElement('option');
                    option.value = etudiant.id;
                    option.textContent = etudiant.nom_complet;
                    etudiantSelect.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des étudiants:', error);
        });
    }

    /**
     * Charge les rapports avec pagination et filtrage
     */
    function chargerRapports() {
        // Afficher un indicateur de chargement
        rapportsTableBody.innerHTML = '<tr><td colspan="7" class="text-center">Chargement...</td></tr>';
        
        // Récupérer les filtres
        const statut = document.getElementById('statutRapportSelect').value;
        const etudiantId = document.getElementById('etudiantSelect').value;
        const dateDebut = document.getElementById('dateDebutInput').value;
        const dateFin = document.getElementById('dateFinInput').value;
        const recherche = searchInput.value;
        
        // Préparer les données pour la requête
        const formData = new FormData();
        formData.append('page', currentPage);
        formData.append('limit', itemsPerPage);
        
        if (statut) formData.append('statut', statut);
        if (etudiantId) formData.append('etudiant_id', etudiantId);
        if (dateDebut) formData.append('date_debut', dateDebut);
        if (dateFin) formData.append('date_fin', dateFin);
        if (recherche) formData.append('recherche', recherche);
        
        // Envoyer la requête
        fetch('/confirmation-rapports/get-rapports', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.statut === 'succes') {
                afficherRapports(data.data);
            } else {
                rapportsTableBody.innerHTML = '<tr><td colspan="7" class="text-center">Erreur lors du chargement des rapports.</td></tr>';
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des rapports:', error);
            rapportsTableBody.innerHTML = '<tr><td colspan="7" class="text-center">Erreur lors du chargement des rapports.</td></tr>';
        });
    }

    /**
     * Affiche les rapports dans le tableau
     * @param {Object} data Les données des rapports
     */
    function afficherRapports(data) {
        // Mettre à jour les variables de pagination
        currentPage = data.page;
        totalPages = data.pages;
        itemsPerPage = data.limit;
        
        // Vider le tableau
        rapportsTableBody.innerHTML = '';
        
        // Si aucun rapport
        if (data.rapports.length === 0) {
            rapportsTableBody.innerHTML = '<tr><td colspan="7" class="text-center">Aucun rapport trouvé.</td></tr>';
            resultsInfo.textContent = 'Affichage 0-0 sur 0 entrées';
            mettreAJourPagination();
            return;
        }
        
        // Afficher les rapports
        data.rapports.forEach(rapport => {
            const row = document.createElement('tr');
            
            // Checkbox
            const checkboxCell = document.createElement('td');
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.className = 'checkbox rapport-checkbox';
            checkbox.setAttribute('data-rapport-id', rapport.rapport_id);
            checkbox.checked = selectedRapports.includes(rapport.rapport_id);
            checkbox.addEventListener('change', function() {
                if (checkbox.checked) {
                    if (!selectedRapports.includes(rapport.rapport_id)) {
                        selectedRapports.push(rapport.rapport_id);
                    }
                } else {
                    selectedRapports = selectedRapports.filter(id => id !== rapport.rapport_id);
                    masterCheckbox.checked = false;
                }
                
                updateActionButtonState();
            });
            checkboxCell.appendChild(checkbox);
            row.appendChild(checkboxCell);
            
            // ID Rapport
            const idCell = document.createElement('td');
            idCell.textContent = rapport.rapport_id;
            row.appendChild(idCell);
            
            // Étudiant
            const etudiantCell = document.createElement('td');
            etudiantCell.textContent = rapport.etudiant_nom;
            row.appendChild(etudiantCell);
            
            // Titre
            const titreCell = document.createElement('td');
            titreCell.textContent = rapport.titre;
            row.appendChild(titreCell);
            
            // Date de dépôt
            const dateCell = document.createElement('td');
            dateCell.textContent = formatDate(rapport.date_depot);
            row.appendChild(dateCell);
            
            // Statut
            const statutCell = document.createElement('td');
            statutCell.textContent = formatStatut(rapport.statut);
            statutCell.className = 'statut-' + rapport.statut;
            row.appendChild(statutCell);
            
            // Actions
            const actionsCell = document.createElement('td');
            const viewBtn = document.createElement('button');
            viewBtn.className = 'btn btn-icon';
            viewBtn.innerHTML = '👁️';
            viewBtn.title = 'Voir le rapport';
            viewBtn.addEventListener('click', function() {
                // Implémenter la visualisation du rapport
                alert('Visualisation du rapport ' + rapport.rapport_id);
            });
            actionsCell.appendChild(viewBtn);
            row.appendChild(actionsCell);
            
            rapportsTableBody.appendChild(row);
        });
        
        // Mettre à jour les informations de pagination
        const debut = (currentPage - 1) * itemsPerPage + 1;
        const fin = Math.min(debut + data.rapports.length - 1, data.total);
        resultsInfo.textContent = `Affichage ${debut}-${fin} sur ${data.total} entrées`;
        
        // Mettre à jour la pagination
        mettreAJourPagination();
    }

    /**
     * Met à jour les contrôles de pagination
     */
    function mettreAJourPagination() {
        // Supprimer les boutons de page existants
        const pageButtons = paginationControls.querySelectorAll('.page-btn');
        pageButtons.forEach(btn => btn.remove());
        
        // Désactiver/activer les boutons précédent/suivant
        prevPageBtn.disabled = currentPage <= 1;
        nextPageBtn.disabled = currentPage >= totalPages;
        
        // Si pas de pages, ne rien afficher
        if (totalPages <= 0) {
            return;
        }
        
        // Déterminer les pages à afficher
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(totalPages, startPage + 4);
        
        if (endPage - startPage < 4) {
            startPage = Math.max(1, endPage - 4);
        }
        
        // Créer les boutons de page
        for (let i = startPage; i <= endPage; i++) {
            const pageBtn = document.createElement('button');
            pageBtn.className = 'pagination-btn page-btn' + (i === currentPage ? ' active' : '');
            pageBtn.textContent = i;
            pageBtn.addEventListener('click', function() {
                currentPage = i;
                chargerRapports();
            });
            
            // Insérer avant le bouton suivant
            paginationControls.insertBefore(pageBtn, nextPageBtn);
        }
    }

    /**
     * Exécute une action sur les rapports sélectionnés
     * @param {string} action L'action à exécuter
     * @param {Array} rapportIds Les IDs des rapports
     * @param {string} commentaire Le commentaire (optionnel)
     */
    function executerAction(action, rapportIds, commentaire) {
        const formData = new FormData();
        formData.append('action', action);
        formData.append('rapport_ids', JSON.stringify(rapportIds));
        formData.append('commentaire', commentaire);
        
        fetch('/confirmation-rapports/executer-action', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.statut === 'succes') {
                alert('Action exécutée avec succès.');
                // Réinitialiser la sélection
                selectedRapports = [];
                masterCheckbox.checked = false;
                // Recharger les rapports
                chargerRapports();
            } else {
                alert('Erreur lors de l\'exécution de l\'action: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur lors de l\'exécution de l\'action:', error);
            alert('Erreur lors de l\'exécution de l\'action.');
        });
    }

    /**
     * Met à jour l'état du bouton d'action
     */
    function updateActionButtonState() {
        if (applyActionButton) {
            applyActionButton.disabled = selectedRapports.length === 0;
        }
    }

    /**
     * Formate une date pour l'affichage
     * @param {string} dateStr La date à formater
     * @returns {string} La date formatée
     */
    function formatDate(dateStr) {
        if (!dateStr) return '';
        const date = new Date(dateStr);
        return date.toLocaleDateString('fr-FR');
    }

    /**
     * Formate un statut pour l'affichage
     * @param {string} statut Le statut à formater
     * @returns {string} Le statut formaté
     */
    function formatStatut(statut) {
        switch (statut) {
            case 'depose': return 'Déposé';
            case 'valide': return 'Validé';
            case 'approuve': return 'Approuvé';
            case 'encadrants_assignes': return 'Encadrants assignés';
            default: return 'Inconnu';
        }
    }

    /**
     * Fonction debounce pour limiter les appels à une fonction
     * @param {Function} func La fonction à exécuter
     * @param {number} wait Le délai d'attente en ms
     * @returns {Function} La fonction debounced
     */
    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                func.apply(context, args);
            }, wait);
        };
    }
});
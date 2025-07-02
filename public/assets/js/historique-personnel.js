// =========================================================================
//  SCRIPT POUR LA GESTION DE L'HISTORIQUE DU PERSONNEL
// =========================================================================
(function () {
    const selectTypeUtilisateur = document.getElementById('selectTypeUtilisateur');
    const formFiltres = document.querySelector('form[action="/charger-historique-personnel"]');
    const selectTypeHistorique = document.getElementById('selectTypeHistorique');
    const selectUtilisateurSpecifique = document.getElementById('selectUtilisateurSpecifique');
    const btnCreerSubmit = formFiltres ? formFiltres.querySelector('button[type="submit"]') : null; // Le bouton "Créer" qui devient "Rechercher"

    // Cache des personnels pour éviter les appels AJAX répétés si non nécessaire
    let cachePersonnel = {
        enseignant: null,
        personnel_administratif: null
    };

    if (!selectTypeUtilisateur || !formFiltres || !selectTypeHistorique || !selectUtilisateurSpecifique || !btnCreerSubmit) {
        console.error("Un ou plusieurs éléments DOM sont manquants pour la page d'historique du personnel.");
        return;
    }

    // Initialement, le bouton "Créer" (Rechercher) devrait être pour la recherche
    btnCreerSubmit.textContent = 'Rechercher';


    // --- GESTION DE LA SÉLECTION DU TYPE D'UTILISATEUR ---
    selectTypeUtilisateur.addEventListener('change', async function () {
        const typeUtilisateur = this.value;
        selectUtilisateurSpecifique.innerHTML = '<option value="">Chargement...</option>'; // Message de chargement
        selectUtilisateurSpecifique.disabled = true;
        selectTypeHistorique.disabled = true;
        btnCreerSubmit.disabled = true;


        if (!typeUtilisateur) {
            selectUtilisateurSpecifique.innerHTML = '<option value="">Sélectionner un utilisateur</option>';
            return;
        }

        // Vérifier le cache
        if (cachePersonnel[typeUtilisateur]) {
            populateSelectUtilisateur(cachePersonnel[typeUtilisateur]);
            return;
        }

        try {
            // L'action du formulaire est maintenant GET pour charger les utilisateurs
            const response = await fetch(`/historique-personnel/charger-personnels?type_utilisateur=${typeUtilisateur}`);
            const data = await response.json();

            if (response.ok && data.personnels) {
                cachePersonnel[typeUtilisateur] = data.personnels; // Mettre en cache
                populateSelectUtilisateur(data.personnels);
            } else {
                showPopup(data.error || "Erreur lors du chargement des personnels.", "error");
                selectUtilisateurSpecifique.innerHTML = '<option value="">Erreur de chargement</option>';
            }
        } catch (error) {
            console.error("Erreur AJAX pour charger les personnels:", error);
            showPopup("Erreur de communication serveur.", "error");
            selectUtilisateurSpecifique.innerHTML = '<option value="">Erreur serveur</option>';
        }
    });

    function populateSelectUtilisateur(personnels) {
        selectUtilisateurSpecifique.innerHTML = '<option value="">Sélectionner un utilisateur</option>';
        if (personnels && personnels.length > 0) {
            personnels.forEach(personnel => {
                const option = document.createElement('option');
                option.value = personnel.id; // Assurez-vous que l'ID est 'id'
                option.textContent = `${personnel.nom} ${personnel.prenoms} (${personnel.id})`;
                selectUtilisateurSpecifique.appendChild(option);
            });
            selectUtilisateurSpecifique.disabled = false;
            selectTypeHistorique.disabled = false;
        } else {
            selectUtilisateurSpecifique.innerHTML = '<option value="">Aucun utilisateur trouvé</option>';
        }
    }

    selectTypeHistorique.addEventListener('change', toggleSubmitButtonState);
    selectUtilisateurSpecifique.addEventListener('change', toggleSubmitButtonState);

    function toggleSubmitButtonState() {
        if (selectTypeHistorique.value && selectUtilisateurSpecifique.value) {
            btnCreerSubmit.disabled = false;
        } else {
            btnCreerSubmit.disabled = true;
        }
    }
    toggleSubmitButtonState(); // État initial


    // --- GESTION DE LA SOUMISSION DU FORMULAIRE DE FILTRES ---
    // La soumission est gérée par ajax.js grâce à la classe 'ajax-form'.
    // ajax.js s'occupera de faire la requête POST vers '/charger-historique-personnel'
    // et de mettre à jour la cible (la table) avec la réponse HTML.
    // Il faut s'assurer que le contrôleur renvoie bien le HTML partiel de la table.

    // --- FONCTION POPUP (si non globale) ---
    function showPopup(message, type = 'info') {
        if (typeof window.showPopup === 'function') {
            window.showPopup(message, type);
        } else {
            alert(`${type.toUpperCase()}: ${message}`);
        }
    }

    // --- REBINDING POUR AJAX (si la table est rechargée entièrement) ---
    // Si la table est rechargée entièrement par AJAX, et qu'elle contient des éléments interactifs
    // (comme des boutons d'édition/suppression par ligne d'historique), il faudrait
    // ré-attacher les événements ici, de manière similaire à ce qui est fait dans
    // `parametres-generaux.js` avec `initializeFormParametre`.
    // Pour l'instant, le tableau est simple et n'a pas d'interactions par ligne.

    // Note: Le bouton "Créer" du formulaire de filtres sert en fait à "Rechercher" ou "Afficher" l'historique.
    // Il n'y a pas de fonctionnalité de création d'entrées d'historique directement depuis cette interface
    // dans la version actuelle de la maquette. La création se fait via les modules Personnels, etc.

})();

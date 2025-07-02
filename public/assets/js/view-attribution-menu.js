document.addEventListener('DOMContentLoaded', function () {
    const userGroupSelect = document.getElementById('userGroupSelect');
    const masterCheckbox = document.getElementById('masterPermissionCheckbox');
    const permissionsTableBody = document.getElementById('permissionsTableBody');
    const validateButton = document.getElementById('validateButton');

    if (!userGroupSelect || !masterCheckbox || !permissionsTableBody || !validateButton) {
        return;
    }

    // Fonction pour charger les permissions via AJAX
    async function loadPermissionsForGroup(groupId) {
        if (!groupId) {
            resetPermissionsTable();
            return;
        }

        // Utilise la fonction ajaxRequest globale si elle existe
        if (typeof window.ajaxRequest === 'function') {
            try {
                const data = await window.ajaxRequest('/traitement-attribution-menu', {
                    method: 'POST',
                    body: new URLSearchParams({ operation: 'charger', groupe_id: groupId }),
                    showLoader: true
                });
                renderPermissions(data);
            } catch (error) {
                console.error("Erreur lors du chargement des permissions:", error);
                if (typeof window.showPopup === 'function') {
                    window.showPopup("Erreur de chargement des permissions.", "error");
                }
            }
        }
    }

    // Fonction pour afficher les permissions récupérées
    function renderPermissions(permissions) {
        resetPermissionsTable();
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            const traitementId = checkbox.closest('tr').dataset.traitementId;
            const actionId = checkbox.dataset.actionId;

            if (permissions[traitementId] && permissions[traitementId][actionId]) {
                checkbox.checked = true;
            }
        });
        updateMasterCheckboxState();
    }

    // Réinitialise le tableau à son état par défaut
    function resetPermissionsTable() {
        document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false);
        updateMasterCheckboxState();
    }

    // Met à jour l'état de la case "Tout sélectionner"
    function updateMasterCheckboxState() {
        const allCheckboxes = document.querySelectorAll('.permission-checkbox');
        if (allCheckboxes.length === 0) {
            masterCheckbox.checked = false;
            masterCheckbox.indeterminate = false;
            return;
        }
        const checkedCount = document.querySelectorAll('.permission-checkbox:checked').length;
        masterCheckbox.checked = checkedCount === allCheckboxes.length;
        masterCheckbox.indeterminate = checkedCount > 0 && checkedCount < allCheckboxes.length;
    }

    // Écouteur sur le menu déroulant des groupes
    userGroupSelect.addEventListener('change', () => {
        loadPermissionsForGroup(userGroupSelect.value);
    });

    // Écouteur sur la case "Tout sélectionner"
    masterCheckbox.addEventListener('change', function () {
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Écouteurs sur toutes les cases de permission individuelles
    permissionsTableBody.addEventListener('change', function(e) {
        if (e.target.classList.contains('permission-checkbox')) {
            updateMasterCheckboxState();
        }
    });

    // Écouteur sur le bouton de validation
    validateButton.addEventListener('click', async function() {
        const groupId = userGroupSelect.value;
        if (!groupId) {
            if (typeof window.showPopup === 'function') {
                window.showPopup("Veuillez d'abord sélectionner un groupe.", "warning");
            }
            return;
        }

        const permissions = {};
        document.querySelectorAll('tr[data-traitement-id]').forEach(row => {
            const traitementId = row.dataset.traitementId;
            permissions[traitementId] = {};
            row.querySelectorAll('.permission-checkbox').forEach(checkbox => {
                const actionId = checkbox.dataset.actionId;
                permissions[traitementId][actionId] = checkbox.checked;
            });
        });

        if (typeof window.ajaxRequest === 'function') {
            try {
                await window.ajaxRequest('/traitement-attribution-menu', {
                    method: 'POST',
                    body: new URLSearchParams({
                        operation: 'sauvegarder',
                        groupe_id: groupId,
                        permissions: JSON.stringify(permissions)
                    }),
                    showLoader: true
                });
                // Le message de succès est géré par ajaxRequest
            } catch (error) {
                console.error("Erreur lors de la sauvegarde:", error);
            }
        }
    });

    // Initialiser l'état au chargement de la page
    resetPermissionsTable();
});

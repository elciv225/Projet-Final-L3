// =========================================================================
//  SCRIPT POUR LA GESTION DE L'ATTRIBUTION DES MENUS AUX GROUPES
// =========================================================================
(function () {
    const userGroupSelect = document.getElementById('userGroupSelect');
    const masterPermissionCheckbox = document.getElementById('masterPermissionCheckbox');
    const permissionsTableBody = document.getElementById('permissionsTableBody');
    const savePermissionsButton = document.getElementById('savePermissionsButton');

    if (!userGroupSelect || !masterPermissionCheckbox || !permissionsTableBody || !savePermissionsButton) {
        console.error("Un ou plusieurs éléments DOM clés sont manquants pour l'attribution des menus.");
        return;
    }

    /**
     * Charge les permissions pour le groupe sélectionné via AJAX.
     */
    async function loadPermissionsForGroup(groupeId) {
        if (!groupeId) {
            permissionsTableBody.innerHTML = `<tr><td colspan="5" style="text-align: center; color: var(--text-disabled); padding: 40px;">Veuillez sélectionner un groupe pour charger les permissions.</td></tr>`;
            updateMasterCheckboxState();
            return;
        }

        permissionsTableBody.innerHTML = `<tr><td colspan="5" style="text-align: center; padding: 40px;">Chargement des permissions...</td></tr>`;

        try {
            const response = await fetch(`/attribution-menu/charger-permissions?groupe_id=${groupeId}`);
            if (!response.ok) {
                throw new Error(`Erreur HTTP: ${response.status}`);
            }
            const data = await response.json();

            if (data.error) {
                permissionsTableBody.innerHTML = `<tr><td colspan="5" style="text-align: center; color: red; padding: 40px;">${data.error}</td></tr>`;
            } else {
                renderPermissionsTable(data.menusParCategorie || {});
            }
        } catch (error) {
            console.error("Erreur lors du chargement des permissions:", error);
            permissionsTableBody.innerHTML = `<tr><td colspan="5" style="text-align: center; color: red; padding: 40px;">Impossible de charger les permissions.</td></tr>`;
        }
        updateMasterCheckboxState();
    }

    /**
     * Affiche le tableau des permissions basé sur les données reçues.
     */
    function renderPermissionsTable(menusParCategorie) {
        permissionsTableBody.innerHTML = ''; // Vider le contenu actuel

        if (Object.keys(menusParCategorie).length === 0) {
            permissionsTableBody.innerHTML = `<tr><td colspan="5" style="text-align: center; padding: 40px;">Aucun menu trouvé ou configuré.</td></tr>`;
            return;
        }

        for (const categorieId in menusParCategorie) {
            const categorie = menusParCategorie[categorieId];
            const categoryRow = document.createElement('tr');
            categoryRow.classList.add('category-row');
            categoryRow.innerHTML = `<td colspan="5"><strong>${escapeHTML(categorie.libelle)}</strong></td>`;
            permissionsTableBody.appendChild(categoryRow);

            if (categorie.menus && categorie.menus.length > 0) {
                categorie.menus.forEach(menu => {
                    const menuId = escapeHTML(menu.id);
                    const perms = menu.permissions || {};
                    const row = document.createElement('tr');
                    row.dataset.menuId = menuId;
                    row.innerHTML = `
                        <td>${escapeHTML(menu.libelle)}</td>
                        <td><div class="checkbox-container"><input type="checkbox" class="checkbox permission-checkbox" data-permission-type="peut_ajouter" ${perms.peut_ajouter ? 'checked' : ''}></div></td>
                        <td><div class="checkbox-container"><input type="checkbox" class="checkbox permission-checkbox" data-permission-type="peut_modifier" ${perms.peut_modifier ? 'checked' : ''}></div></td>
                        <td><div class="checkbox-container"><input type="checkbox" class="checkbox permission-checkbox" data-permission-type="peut_supprimer" ${perms.peut_supprimer ? 'checked' : ''}></div></td>
                        <td><div class="checkbox-container"><input type="checkbox" class="checkbox permission-checkbox" data-permission-type="peut_imprimer" ${perms.peut_imprimer ? 'checked' : ''}></div></td>
                    `;
                    permissionsTableBody.appendChild(row);
                });
            } else {
                 const emptyRow = document.createElement('tr');
                 emptyRow.innerHTML = `<td colspan="5" style="text-align:center; font-style:italic;">Aucun menu dans cette catégorie.</td>`;
                 permissionsTableBody.appendChild(emptyRow);
            }
        }
        addPermissionCheckboxListeners();
        updateMasterCheckboxState();
    }

    function escapeHTML(str) {
        if (str === null || str === undefined) return '';
        return String(str).replace(/[&<>"']/g, function (match) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            }[match];
        });
    }


    /**
     * Ajoute les écouteurs d'événements aux checkboxes de permission individuelle.
     */
    function addPermissionCheckboxListeners() {
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            // Retirer l'ancien écouteur pour éviter les duplications si la fonction est appelée plusieurs fois
            checkbox.removeEventListener('change', updateMasterCheckboxState);
            checkbox.addEventListener('change', updateMasterCheckboxState);
        });
    }

    /**
     * Met à jour l'état de la master checkbox (Tout Sélectionner).
     */
    function updateMasterCheckboxState() {
        const allCheckboxes = document.querySelectorAll('.permission-checkbox');
        if (allCheckboxes.length === 0) {
            masterPermissionCheckbox.checked = false;
            masterPermissionCheckbox.indeterminate = false;
            return;
        }
        const checkedCount = document.querySelectorAll('.permission-checkbox:checked').length;
        masterPermissionCheckbox.checked = checkedCount === allCheckboxes.length;
        masterPermissionCheckbox.indeterminate = checkedCount > 0 && checkedCount < allCheckboxes.length;
    }

    // Événement pour le changement de groupe sélectionné
    userGroupSelect.addEventListener('change', function () {
        loadPermissionsForGroup(this.value);
    });

    // Événement pour la master checkbox
    masterPermissionCheckbox.addEventListener('change', function () {
        const isChecked = this.checked;
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.checked = isChecked;
        });
    });

    // Événement pour le bouton de sauvegarde
    savePermissionsButton.addEventListener('click', async function () {
        const selectedGroupId = userGroupSelect.value;
        if (!selectedGroupId) {
            // Utiliser la fonction showPopup si disponible, sinon alert.
            if (typeof window.showPopup === 'function') {
                window.showPopup("Veuillez sélectionner un groupe utilisateur.", "error");
            } else {
                alert("Veuillez sélectionner un groupe utilisateur.");
            }
            return;
        }

        const permissionsToSave = {};
        document.querySelectorAll('#permissionsTableBody tr[data-menu-id]').forEach(row => {
            const menuId = row.dataset.menuId;
            permissionsToSave[menuId] = {
                peut_ajouter: row.querySelector('[data-permission-type="peut_ajouter"]').checked,
                peut_modifier: row.querySelector('[data-permission-type="peut_modifier"]').checked,
                peut_supprimer: row.querySelector('[data-permission-type="peut_supprimer"]').checked,
                peut_imprimer: row.querySelector('[data-permission-type="peut_imprimer"]').checked,
            };
        });

        try {
            const formData = new FormData();
            formData.append('groupe_id', selectedGroupId);
            formData.append('permissions', JSON.stringify(permissionsToSave));
            // CSRF token si nécessaire : formData.append('csrf_token', 'VOTRE_TOKEN_CSRF');

            const response = await fetch('/attribution-menu/mettre-a-jour', {
                method: 'POST',
                body: formData // Utiliser FormData pour simuler une soumission de formulaire
                               // ou envoyer en JSON: body: JSON.stringify({ groupe_id: ..., permissions: ...}), headers: {'Content-Type': 'application/json'}
            });

            const result = await response.json();

            if (result.success) {
                if (typeof window.showPopup === 'function') {
                    window.showPopup(result.message || "Permissions enregistrées avec succès!", "succes");
                } else {
                    alert(result.message || "Permissions enregistrées avec succès!");
                }
            } else {
                throw new Error(result.error || "Erreur lors de la sauvegarde.");
            }
        } catch (error) {
            console.error("Erreur lors de la sauvegarde des permissions:", error);
            if (typeof window.showPopup === 'function') {
                window.showPopup(error.message || "Impossible d'enregistrer les permissions.", "error");
            } else {
                alert(error.message || "Impossible d'enregistrer les permissions.");
            }
        }
    });

    // Initialiser l'état de la master checkbox au chargement de la page
    updateMasterCheckboxState();

})();

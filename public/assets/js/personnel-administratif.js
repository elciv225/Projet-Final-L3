// On attend que l'ensemble du contenu HTML de la page soit chargé et analysé
// avant d'exécuter le code JavaScript. Cela garantit que tous les éléments
// comme 'btnValider' existent bien quand le script tente de les manipuler.
document.addEventListener('DOMContentLoaded', function () {

    let rowToEdit = null;

    const initPersonnelPage = () => {
        const validerBtn = document.getElementById('btnValider');
        const tableBody = document.querySelector('.table tbody');
        const supprimerSelectionBtn = document.getElementById('btnSupprimerSelection');

        if (!validerBtn || !tableBody || !supprimerSelectionBtn) {
            console.error("Un ou plusieurs éléments essentiels de la page personnel sont manquants.");
            return;
        }

        validerBtn.addEventListener('click', function () {
            const matricule = document.getElementById('personnel-number').value.trim();
            const nom = document.getElementById('personnel-lastname').value.trim();
            const prenom = document.getElementById('personnel-firstname').value.trim();
            const dateNaissance = document.getElementById('birth-date').value;
            const email = document.getElementById('email').value.trim();
            const poste = document.getElementById('poste').value;
            const dateEmbauche = document.getElementById('date-embauche').value;
            const dateAffectation = document.getElementById('date-affectation').value;

            // === Vérifications ===
            if (!matricule || !nom || !prenom || !dateNaissance || !email || !poste || !dateEmbauche || !dateAffectation) {
                showPopup("Veuillez remplir tous les champs !", "warning");
                return;
            }
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showPopup("Adresse email invalide !", "error");
                return;
            }
            const dateNaiss = new Date(dateNaissance);
            const aujourdHui = new Date();
            const ageMin = new Date(aujourdHui.getFullYear() - 17, aujourdHui.getMonth(), aujourdHui.getDate());
            if (dateNaiss > aujourdHui) {
                showPopup("La date de naissance ne peut pas être dans le futur.", "error");
                return;
            }
            if (dateNaiss > ageMin) {
                showPopup("Le personnel doit avoir au moins 17 ans.", "error");
                return;
            }
            if (new Date(dateEmbauche) > new Date(dateAffectation)) {
                showPopup("La date d'embauche ne peut pas être postérieure à la date d'affectation !", "error");
                return;
            }

            if (!rowToEdit) {
                const lignes = document.querySelectorAll('.table tbody tr');
                for (let ligne of lignes) {
                    if (ligne.cells[1]?.textContent === matricule) {
                        showPopup("Ce matricule existe déjà !", "error");
                        return;
                    }
                }
            }

            if (rowToEdit) {
                // Modification
                rowToEdit.cells[1].textContent = matricule;
                rowToEdit.cells[2].textContent = nom;
                rowToEdit.cells[3].textContent = prenom;
                rowToEdit.cells[4].textContent = dateNaissance;
                rowToEdit.cells[5].textContent = email;
                rowToEdit.cells[6].textContent = poste;
                rowToEdit.cells[7].textContent = dateEmbauche;
                rowToEdit.cells[8].textContent = dateAffectation;
                rowToEdit.classList.remove('editing');
                rowToEdit = null;
                validerBtn.textContent = 'Valider';
                showPopup('Personnel mis à jour avec succès.', 'success');
            } else {
                // Ajout
                const newRow = tableBody.insertRow();
                newRow.innerHTML = `
                    <td><input type="checkbox" class="checkbox"></td>
                    <td>${matricule}</td>
                    <td>${nom}</td>
                    <td>${prenom}</td>
                    <td>${dateNaissance}</td>
                    <td>${email}</td>
                    <td>${poste}</td>
                    <td>${dateEmbauche}</td>
                    <td>${dateAffectation}</td>
                    <td>
                        <div class="table-actions">
                            <button class="action-btn edit-btn">✏️</button>
                            <button class="action-btn delete-btn">🗑️</button>
                        </div>
                    </td>
                `;
                showPopup('Personnel ajouté avec succès.', 'success');
            }

            // Nettoyage du formulaire
            document.querySelectorAll('.form-input').forEach(input => input.value = '');
            document.getElementById('poste').value = '';
        });

        // Gestion des actions sur le tableau
        tableBody.addEventListener('click', function (e) {
            const deleteBtn = e.target.closest('.delete-btn');
            const editBtn = e.target.closest('.edit-btn');

            if (deleteBtn) {
                const row = deleteBtn.closest('tr');
                showWarningCard("Voulez-vous vraiment supprimer ce membre ?", () => {
                    row.remove();
                    showPopup("Personnel supprimé.", "info");
                });
            }

            if (editBtn) {
                const row = editBtn.closest('tr');
                if(rowToEdit) {
                    rowToEdit.classList.remove('editing');
                }
                rowToEdit = row;
                rowToEdit.classList.add('editing');

                document.getElementById('personnel-number').value = row.cells[1].textContent;
                document.getElementById('personnel-lastname').value = row.cells[2].textContent;
                document.getElementById('personnel-firstname').value = row.cells[3].textContent;
                document.getElementById('birth-date').value = row.cells[4].textContent;
                document.getElementById('email').value = row.cells[5].textContent;
                document.getElementById('poste').value = row.cells[6].textContent;
                document.getElementById('date-embauche').value = row.cells[7].textContent;
                document.getElementById('date-affectation').value = row.cells[8].textContent;

                validerBtn.textContent = 'Mettre à jour';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });

        // Suppression multiple
        supprimerSelectionBtn.addEventListener('click', function () {
            const checkboxes = document.querySelectorAll('.table tbody .checkbox:checked');
            if (checkboxes.length === 0) {
                showPopup("Veuillez cocher au moins une ligne !", "warning");
                return;
            }
            showWarningCard(`Confirmez-vous la suppression des ${checkboxes.length} ligne(s) sélectionnée(s) ?`, () => {
                checkboxes.forEach(checkbox => checkbox.closest('tr').remove());
                showPopup("La sélection a été supprimée.", "success");
            });
        });
    }

    // Appel initial
    initPersonnelPage();

    // On l'ajoute au rebinders pour que ça fonctionne après une navigation AJAX
    window.ajaxRebinders.push(initPersonnelPage);
});

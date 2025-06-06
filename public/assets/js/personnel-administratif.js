let rowToEdit = null;

document.getElementById('btnValider').addEventListener('click', function () {
    const matricule = document.getElementById('personnel-number').value.trim();
    const nom = document.getElementById('personnel-lastname').value.trim();
    const prenom = document.getElementById('personnel-firstname').value.trim();
    const dateNaissance = document.getElementById('birth-date').value;
    const email = document.getElementById('email').value.trim();
    const poste = document.getElementById('poste').value.trim();
    const dateEmbauche = document.getElementById('date-embauche').value;
    const dateAffectation = document.getElementById('date-affectation').value;

    // === Vérifications ===

    if (!matricule || !nom || !prenom || !dateNaissance || !email || !poste || !dateEmbauche || !dateAffectation) {
        alert("Veuillez remplir tous les champs !");
        return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert("Adresse email invalide !");
        return;
    }

    // Vérification de la date de naissance
    const dateNaiss = new Date(dateNaissance);
    const aujourdHui = new Date();
    const ageMin = new Date();
    ageMin.setFullYear(aujourdHui.getFullYear() - 17);

    if (dateNaiss > aujourdHui) {
        alert("La date de naissance ne peut pas être dans le futur.");
        return;
    }

    if (dateNaiss > ageMin) {
        alert("Le personnel doit avoir au moins 17 ans.");
        return;
    }

    if (new Date(dateEmbauche) > new Date(dateAffectation)) {
        alert("La date d'embauche ne peut pas être postérieure à la date d'affectation !");
        return;
    }

    // Vérification de l’unicité du matricule si on ajoute
    if (!rowToEdit) {
        const lignes = document.querySelectorAll('.table tbody tr');
        for (let ligne of lignes) {
            const celluleMatricule = ligne.children[1]?.textContent;
            if (celluleMatricule === matricule) {
                alert("Ce matricule existe déjà !");
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

        rowToEdit = null;
        document.getElementById('btnValider').textContent = 'Valider';
    } else {
        // Ajout d'une nouvelle ligne
        const tbody = document.querySelector('.table tbody');
        const newRow = document.createElement('tr');

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

        tbody.appendChild(newRow);
    }

    // Nettoyage
    document.querySelectorAll('.form-input').forEach(input => input.value = '');
});

// === Supprimer une ligne ===
document.querySelector('.table tbody').addEventListener('click', function (e) {
    if (e.target.classList.contains('delete-btn')) {
        const row = e.target.closest('tr');
        row.remove();
    }
});

// === Modifier une ligne ===
document.querySelector('.table tbody').addEventListener('click', function (e) {
    if (e.target.classList.contains('edit-btn')) {
        const row = e.target.closest('tr');
        rowToEdit = row;

        document.getElementById('personnel-number').value = row.cells[1].textContent;
        document.getElementById('personnel-lastname').value = row.cells[2].textContent;
        document.getElementById('personnel-firstname').value = row.cells[3].textContent;
        document.getElementById('birth-date').value = row.cells[4].textContent;
        document.getElementById('email').value = row.cells[5].textContent;
        document.getElementById('poste').value = row.cells[6].textContent;
        document.getElementById('date-embauche').value = row.cells[7].textContent;
        document.getElementById('date-affectation').value = row.cells[8].textContent;

        document.getElementById('btnValider').textContent = 'Mettre à jour';
    }
});

// === Suppression multiple ===
document.getElementById('btnSupprimerSelection').addEventListener('click', function () {
    const checkboxes = document.querySelectorAll('.table tbody .checkbox:checked');
    if (checkboxes.length === 0) {
        alert("Veuillez cocher au moins une ligne !");
        return;
    }

    if (confirm("Confirmez-vous la suppression des lignes sélectionnées ?")) {
        checkboxes.forEach(checkbox => {
            const row = checkbox.closest('tr');
            row.remove();
        });
    }
});


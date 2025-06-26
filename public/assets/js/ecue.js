let rowToEdit = null;

document.getElementById('btnValider').addEventListener('click', function () {
    const numerocarte = document.getElementById('student-number').value.trim();
    const nom = document.getElementById('student-lastname').value.trim();
    const prenom = document.getElementById('student-firstname').value.trim();
    const dateNaissance = document.getElementById('birth-date').value;
    const email = document.getElementById('email').value.trim();
    const niveauEtude = document.getElementById('niveauEtude').value.trim();
    const anneeAcademique = document.getElementById('annee-academique').value;
    const contact = document.getElementById('contact').value;

    // === Vérifications ===

    if (!numerocarte || !nom || !prenom || !dateNaissance || !email || !niveauEtude || !anneeAcademique || !contact) {
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
    ageMin.setFullYear(aujourdHui.getFullYear() - 20);

    if (dateNaiss > aujourdHui) {
        alert("La date de naissance ne peut pas être dans le futur.");
        return;
    }

    if (dateNaiss > ageMin) {
        alert("Vous n'etes pas en age d'etre un etudiant.");
        return;
    }

    // Vérification de l’unicité du matricule si on ajoute
    if (!rowToEdit) {
        const lignes = document.querySelectorAll('.table tbody tr');
        for (let ligne of lignes) {
            const cellulenumerocarte = ligne.children[1]?.textContent;
            if (cellulenumerocarte === numerocarte) {
                alert("Ce numero de carte existe déjà !");
                return;
            }
        }
    }

    if (rowToEdit) {
        // Modification
        rowToEdit.cells[1].textContent = numerocarte;
        rowToEdit.cells[2].textContent = nom;
        rowToEdit.cells[3].textContent = prenom;
        rowToEdit.cells[4].textContent = dateNaissance;
        rowToEdit.cells[5].textContent = email;
        rowToEdit.cells[6].textContent = niveauEtude;
        rowToEdit.cells[7].textContent = anneeAcademique;
        rowToEdit.cells[8].textContent = contact;

        rowToEdit = null;
        document.getElementById('btnValider').textContent = 'Valider';
    } else {
        // Ajout d'une nouvelle ligne
        const tbody = document.querySelector('.table tbody');
        const newRow = document.createElement('tr');

        newRow.innerHTML = `
            <td><input type="checkbox" class="checkbox"></td>
            <td>${numerocarte}</td>
            <td>${nom}</td>
            <td>${prenom}</td>
            <td>${dateNaissance}</td>
            <td>${email}</td>
            <td>${niveauEtude}</td>
            <td>${anneeAcademique}</td>
            <td>${contact}</td>
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

        document.getElementById('student-number').value = row.cells[1].textContent;
        document.getElementById('student-lastname').value = row.cells[2].textContent;
        document.getElementById('student-firstname').value = row.cells[3].textContent;
        document.getElementById('birth-date').value = row.cells[4].textContent;
        document.getElementById('email').value = row.cells[5].textContent;
        document.getElementById('niveauEtude').value = row.cells[6].textContent;
        document.getElementById('annee-academique').value = row.cells[7].textContent;
        document.getElementById('contact').value = row.cells[8].textContent;

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

//Barre de recherche
document.getElementById('searchInput').addEventListener('keyup', function () {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('.table tbody tr');

    rows.forEach(row => {
        const rowText = row.textContent.toLowerCase();
        if (rowText.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// 🕐 Exporter en PDF
document.getElementById("btnExportPDF").addEventListener("click", async function () {
    const {jsPDF} = window.jspdf;
    const doc = new jsPDF();

    const table = document.querySelector(".table");
    const rows = table.querySelectorAll("tr");

    let y = 10;
    doc.setFontSize(12);
    doc.text("Liste du personnel", 10, y);
    y += 10;

    rows.forEach(row => {
        let x = 10;
        row.querySelectorAll("th, td").forEach(cell => {
            const text = cell.innerText || cell.textContent;
            doc.text(text.trim(), x, y);
            x += 30; // Espace entre les colonnes
        });
        y += 10;
    });

    doc.save("personnel.pdf");
});

// 📤 Exporter sur Excel
document.getElementById("btnExportExcel").addEventListener("click", function () {
    const table = document.querySelector(".table");
    const wb = XLSX.utils.table_to_book(table, {sheet: "Personnel"});
    XLSX.writeFile(wb, "personnel.xlsx");
});

// 📊 Imprimer
document.getElementById("btnPrint").addEventListener("click", function () {
    const tableHTML = document.querySelector(".table").outerHTML;
    const newWindow = window.open("", "", "width=900,height=700");
    newWindow.document.write(`
            <html>
            <head>
                <title>Impression</title>
                <style>
                    table { width: 100%; border-collapse: collapse; }
                    th, td { border: 1px solid #000; padding: 8px; text-align: left; }
                </style>
            </head>
            <body>
                <h2>Liste du personnel</h2>
                ${tableHTML}
            </body>
            </html>
        `);
    newWindow.document.close();
    newWindow.print();
});

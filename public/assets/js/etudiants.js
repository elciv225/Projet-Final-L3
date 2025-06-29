let rowToEdit = null;

document.getElementById('btnValider').addEventListener('click', function () {
    const idUtilisateur = document.getElementById('id-utilisateur').value.trim();
    const niveauEtude = document.getElementById('niveauEtude').value.trim();
    const anneeAcademique = document.getElementById('annee-academique').value.trim();

    // === Vérifications ===
    if (!idUtilisateur || !niveauEtude || !anneeAcademique) {
        alert("Veuillez remplir tous les champs !");
        return;
    }

    // Vérification d’unicité du numéro
    if (!rowToEdit) {
        const lignes = document.querySelectorAll('.table tbody tr');
        for (let ligne of lignes) {
            const celluleID = ligne.children[1]?.textContent.trim();
            if (celluleID === idUtilisateur) {
                alert("Ce numéro de carte existe déjà !");
                return;
            }
        }
    }

    if (rowToEdit) {
        // Mise à jour
        rowToEdit.cells[1].textContent = idUtilisateur;
        rowToEdit.cells[6].textContent = niveauEtude;
        rowToEdit.cells[7].textContent = anneeAcademique;

        rowToEdit = null;
        document.getElementById('btnValider').textContent = 'Valider';
    } else {
        // Nouvelle ligne
        const tbody = document.querySelector('.table tbody');
        const newRow = document.createElement('tr');

        newRow.innerHTML = `
            <td><input type="checkbox" class="checkbox"></td>
            <td>${idUtilisateur}</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>${niveauEtude}</td>
            <td>${anneeAcademique}</td>
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
    document.getElementById('id-utilisateur').value = '';
    document.getElementById('niveauEtude').value = '';
    document.getElementById('annee-academique').value = '';
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

        document.getElementById('id-utilisateur').value = row.cells[1].textContent.trim();
        document.getElementById('niveauEtude').value = row.cells[6].textContent.trim();
        document.getElementById('annee-academique').value = row.cells[7].textContent.trim();

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

// === Recherche ===
document.getElementById('searchInput').addEventListener('keyup', function () {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('.table tbody tr');

    rows.forEach(row => {
        const rowText = row.textContent.toLowerCase();
        row.style.display = rowText.includes(searchTerm) ? '' : 'none';
    });
});

// === Exporter en PDF ===
document.getElementById("btnExportPDF").addEventListener("click", async function () {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const table = document.querySelector(".table");
    const rows = table.querySelectorAll("tr");

    let y = 10;
    doc.setFontSize(12);
    doc.text("Liste des étudiants", 10, y);
    y += 10;

    rows.forEach(row => {
        let x = 10;
        row.querySelectorAll("th, td").forEach(cell => {
            const text = cell.innerText || cell.textContent;
            doc.text(text.trim(), x, y);
            x += 30;
        });
        y += 10;
    });

    doc.save("etudiants.pdf");
});

// === Exporter en Excel ===
document.getElementById("btnExportExcel").addEventListener("click", function () {
    const table = document.querySelector(".table");
    const wb = XLSX.utils.table_to_book(table, { sheet: "Etudiants" });
    XLSX.writeFile(wb, "etudiants.xlsx");
});

// === Imprimer ===
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
            <h2>Liste des étudiants</h2>
            ${tableHTML}
        </body>
        </html>
    `);
    newWindow.document.close();
    newWindow.print();
});

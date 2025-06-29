let rowToEdit = null;

document.getElementById('btnValider').addEventListener('click', function () {
    const utilisateurId = document.getElementById('id-utilisateur').value.trim();
    const niveauEtudeId = document.getElementById('id-niveau-etude').value.trim();
    const anneeAcademiqueId = document.getElementById('id-annee-academique').value.trim();
    const dateInscription = document.getElementById('date-inscription').value;
    const montant = document.getElementById('montant').value;

    // Vérifications
    if (!utilisateurId || !niveauEtudeId || !anneeAcademiqueId || !dateInscription || !montant) {
        alert("Veuillez remplir tous les champs obligatoires !");
        return;
    }

    // Vérification de l’unicité (ajout)
    if (!rowToEdit) {
        const lignes = document.querySelectorAll('.table tbody tr');
        for (let ligne of lignes) {
            const cellId = ligne.children[1]?.textContent;
            if (cellId === utilisateurId) {
                alert("Ce numéro de carte est déjà inscrit !");
                return;
            }
        }
    }

    if (rowToEdit) {
        // Mode mise à jour
        rowToEdit.cells[1].textContent = utilisateurId;
        rowToEdit.cells[2].textContent = niveauEtudeId;
        rowToEdit.cells[3].textContent = dateInscription;
        rowToEdit.cells[4].textContent = montant;

        rowToEdit = null;
        document.getElementById('btnValider').textContent = 'Valider';
    } else {
        // Ajout d'une nouvelle ligne
        const tbody = document.querySelector('.table tbody');
        const newRow = document.createElement('tr');

        newRow.innerHTML = `
            <td><input type="checkbox" class="checkbox"></td>
            <td>${utilisateurId}</td>
            <td>${niveauEtudeId}</td>
            <td>${dateInscription}</td>
            <td>${montant}</td>
            <td>
                <div class="table-actions">
                    <button class="action-btn edit-btn">✏️</button>
                    <button class="action-btn delete-btn">🗑️</button>
                </div>
            </td>
        `;

        tbody.appendChild(newRow);
    }

    // Nettoyage du formulaire
    document.querySelectorAll('.form-input').forEach(input => input.value = '');
    document.getElementById('btnValider').textContent = 'Valider';
});

// === Modifier une ligne ===
document.querySelector('.table tbody').addEventListener('click', function (e) {
    if (e.target.classList.contains('edit-btn')) {
        const row = e.target.closest('tr');
        rowToEdit = row;

        document.getElementById('id-utilisateur').value = row.cells[1].textContent;
        document.getElementById('id-niveau-etude').value = row.cells[2].textContent;
        document.getElementById('date-inscription').value = row.cells[3].textContent;
        document.getElementById('montant').value = row.cells[4].textContent;

        document.getElementById('btnValider').textContent = 'Mettre à jour';
    }
});

// === Supprimer une ligne ===
document.querySelector('.table tbody').addEventListener('click', function (e) {
    if (e.target.classList.contains('delete-btn')) {
        const row = e.target.closest('tr');
        row.remove();
    }
});

// === Supprimer les lignes sélectionnées ===
document.getElementById('btnSupprimerSelection').addEventListener('click', function () {
    const checkboxes = document.querySelectorAll('.table tbody .checkbox:checked');
    if (checkboxes.length === 0) {
        alert("Veuillez sélectionner au moins une ligne !");
        return;
    }

    if (confirm("Voulez-vous supprimer les lignes sélectionnées ?")) {
        checkboxes.forEach(checkbox => checkbox.closest('tr').remove());
    }
});

// === Recherche en temps réel ===
document.getElementById('searchInput').addEventListener('keyup', function () {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('.table tbody tr');

    rows.forEach(row => {
        const rowText = row.textContent.toLowerCase();
        row.style.display = rowText.includes(searchTerm) ? '' : 'none';
    });
});

// === Export PDF ===
document.getElementById("btnExportPDF").addEventListener("click", async function () {
    const {jsPDF} = window.jspdf;
    const doc = new jsPDF();

    const table = document.querySelector(".table");
    const rows = table.querySelectorAll("tr");

    let y = 10;
    doc.setFontSize(12);
    doc.text("Historique des inscriptions", 10, y);
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

    doc.save("inscriptions.pdf");
});

// === Export Excel ===
document.getElementById("btnExportExcel").addEventListener("click", function () {
    const table = document.querySelector(".table");
    const wb = XLSX.utils.table_to_book(table, {sheet: "Inscriptions"});
    XLSX.writeFile(wb, "inscriptions.xlsx");
});

// === Impression ===
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
            <h2>Historique des inscriptions</h2>
            ${tableHTML}
        </body>
        </html>
    `);
    newWindow.document.close();
    newWindow.print();
});

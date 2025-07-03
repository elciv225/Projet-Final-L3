let rowToEdit = null;

document.getElementById('btnValider').addEventListener('click', function () {
    const enseignantId = document.getElementById('id-enseignant').value.trim();
    const etudiantId = document.getElementById('id-etudiant').value.trim();
    const ecueId = document.getElementById('idEcueInput').value.trim();
    const dateEvaluation = document.getElementById('date-evaluation').value;
    const note = document.getElementById('note').value.trim();

    // === Validation ===
    if (!enseignantId || !etudiantId || !ecueId || !dateEvaluation || note === '') {
        window.showPopup("Veuillez remplir tous les champs !", "warning");
        return;
    }

    if (note < 0 || note > 20) {
        window.showPopup("La note doit être comprise entre 0 et 20.", "warning");
        return;
    }

    // Vérification de l'unicité (clé primaire composite : id-enseignant, id-etudiant, idEcueInput)
    if (!rowToEdit) {
        const lignes = document.querySelectorAll('.table tbody tr');
        for (let ligne of lignes) {
            const idEnseignantCell = ligne.cells[1]?.textContent;
            const idEtudiantCell = ligne.cells[2]?.textContent;
            const idEcueCell = ligne.cells[3]?.textContent;

            if (
                idEnseignantCell === enseignantId &&
                idEtudiantCell === etudiantId &&
                idEcueCell === ecueId
            ) {
                window.showPopup("Cette évaluation existe déjà !", "warning");
                return;
            }
        }
    }

    if (rowToEdit) {
        // === Modification
        rowToEdit.cells[1].textContent = enseignantId;
        rowToEdit.cells[2].textContent = etudiantId;
        rowToEdit.cells[3].textContent = ecueId;
        rowToEdit.cells[4].textContent = dateEvaluation;
        rowToEdit.cells[5].textContent = note;

        rowToEdit = null;
        document.getElementById('btnValider').textContent = 'Valider';
    } else {
        // === Ajout
        const tbody = document.querySelector('.table tbody');
        const newRow = document.createElement('tr');

        newRow.innerHTML = `
            <td><input type="checkbox" class="checkbox"></td>
            <td>${enseignantId}</td>
            <td>${etudiantId}</td>
            <td>${ecueId}</td>
            <td>${dateEvaluation}</td>
            <td>${note}</td>
            <td>
                <div class="table-actions">
                    <button class="action-btn edit-btn">✏️</button>
                    <button class="action-btn delete-btn">🗑️</button>
                </div>
            </td>
        `;
        tbody.appendChild(newRow);
    }

    // Réinitialisation des champs
    document.querySelectorAll('.form-input').forEach(input => input.value = '');
});


// === Modifier une ligne ===
document.querySelector('.table tbody').addEventListener('click', function (e) {
    if (e.target.classList.contains('edit-btn')) {
        const row = e.target.closest('tr');
        rowToEdit = row;

        document.getElementById('id-enseignant').value = row.cells[1].textContent;
        document.getElementById('id-etudiant').value = row.cells[2].textContent;
        document.getElementById('idEcueInput').value = row.cells[3].textContent;
        document.getElementById('date-evaluation').value = row.cells[4].textContent;
        document.getElementById('note').value = row.cells[5].textContent;

        document.getElementById('btnValider').textContent = 'Mettre à jour';
    }
});

// === Supprimer une ligne ===
document.querySelector('.table tbody').addEventListener('click', function (e) {
    if (e.target.classList.contains('delete-btn')) {
        e.target.closest('tr').remove();
    }
});

// === Supprimer les lignes sélectionnées ===
document.getElementById('btnSupprimerSelection').addEventListener('click', function () {
    const checkedRows = document.querySelectorAll('.table tbody .checkbox:checked');
    if (checkedRows.length === 0) {
        window.showPopup("Veuillez cocher au moins une ligne !", "warning");
        return;
    }

    window.showWarningCard("Confirmez-vous la suppression des lignes sélectionnées ?", function() {
        checkedRows.forEach(cb => cb.closest('tr').remove());
        window.showPopup("Lignes supprimées avec succès", "success");
    });
});

// === Recherche en direct ===
document.getElementById('searchInput').addEventListener('keyup', function () {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('.table tbody tr');

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// === Export PDF ===
document.getElementById("btnExportPDF").addEventListener("click", function () {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    doc.setFontSize(12);
    doc.text("Historique des Évaluations", 10, 10);

    let y = 20;
    document.querySelectorAll(".table tr").forEach(row => {
        let x = 10;
        row.querySelectorAll("th, td").forEach(cell => {
            doc.text(cell.textContent.trim(), x, y);
            x += 30;
        });
        y += 10;
    });

    doc.save("evaluations.pdf");
});

// === Export Excel ===
document.getElementById("btnExportExcel").addEventListener("click", function () {
    const table = document.querySelector(".table");
    const wb = XLSX.utils.table_to_book(table, { sheet: "Évaluations" });
    XLSX.writeFile(wb, "evaluations.xlsx");
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
            <h2>Historique des Évaluations</h2>
            ${tableHTML}
        </body>
        </html>
    `);

    newWindow.document.close();
    newWindow.print();
});

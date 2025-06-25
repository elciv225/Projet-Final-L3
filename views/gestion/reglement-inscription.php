<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Reglement Frais D'Inscription</h1>
        </div>
    </div>


    <div class="form-group small-width right-align  mb-20">
        <select class="form-input" id="annee-academique" name="annee-academique">
            <option value="">Année-Académique</option>
            <option value=""></option>
            <option value=""></option>
        </select>
    </div>
    <!-- Informations de l'etudiant -->
    <div class="form-section">
        <div class="section-header">
            <h3 class="section-title">Information Etudiant</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <input type="text" name="studentNumber" class="form-input" placeholder=" " id="student-number">
                    <label class="form-label" for="student-number">Numero Carte d'Etudiant</label>
                </div>
                <div class="form-group">
                    <input type="text" name="studentLastname" class="form-input" placeholder=" " id="student-lastname">
                    <label class="form-label" for="student-lastname">Nom</label>
                </div>
                <div class="form-group">
                    <input type="text" name="studentFirstname" class="form-input" placeholder=" "
                           id="student-firstname">
                    <label class="form-label" for="student-firstname">Prénoms</label>
                </div>
                <div class="form-group">
                    <input type="text" name="niveauEtude" class="form-input" placeholder=" " id="niveauEtude">
                    <label class="form-label" for="niveauEtude">Niveau d'Etude</label>
                </div>
            </div>

        </div>
    </div>

    <!-- Reglements des frais d'inscription -->
    <div class="form-section">
        <div class="section-header">
            <h3 class="section-title">Reglements Frais D'Inscription</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <input type="montantpaye" name="montantpaye" class="form-input" placeholder=" " id="montantpaye">
                    <label class="form-label" for="montantpaye">Montant Payé</label>
                </div>

                <div class="form-group">
                    <input type="date" name="datereglement" class="form-input" placeholder=" " id="datereglement">
                    <label class="form-label" for="datereglement">Date de reglement</label>
                </div>
                <div class="form-group">
                    <input type="text" name="totalpaye" class="form-input" placeholder=" " id="totalpaye">
                    <label class="form-label" for="totalpaye">Total Payé</label>
                </div>
                <div class="form-group">
                    <input type="text" name="restepaye" class="form-input" placeholder=" " id="restepaye">
                    <label class="form-label" for="restepaye">Reste à Payé</label>
                </div>
                <div class="form-group">
                    <input type="text" name="montantapaye" class="form-input" placeholder=" " id="montantapaye">
                    <label class="form-label" for="montantapaye">Montant à Payé</label>
                </div>
            </div>
        </div>
    </div>

    <div style="display: flex; justify-content: flex-end; padding: 20px 0;">
        <button class="btn btn-primary" id="btnValider">Valider</button>
    </div>


    <!-- Orders Table -->
    <div class="table-container">
        <div class="table-header">
            <h3 class="table-title">Historique</h3>
            <div class="header-actions">
                <div class="search-container">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="searchInput" class="search-input" placeholder="Rechercher par ...">
                </div>


            </div>
            <div class="header-actions">
                <button id="btnExportPDF" class="btn btn-secondary">🕐 Exporter en PDF</button>
                <button id="btnExportExcel" class="btn btn-secondary">📤 Exporter sur Excel</button>
                <button id="btnPrint" class="btn btn-secondary">📊 Imprimer</button>
                <button class="btn btn-primary" id="btnSupprimerSelection">Supprimer</button>
            </div>
        </div>

        <div style="padding: 0 24px; border-bottom: 1px solid #E5E7EB;">
            <div class="table-tabs">
                <div class="tab active">Tout selectioner</div>
                <div class="tab"></div>
                <div class="tab"></div>
                <div class="tab"></div>
                <div class="tab"></div>
            </div>
        </div>

        <table class="table">
            <thead>
            <tr>
                <th><input type="checkbox" class="checkbox"></th>
                <th>Numero Carte d'Etudiant</th>
                <th>Nom</th>
                <th>Prenom</th>
                <th>Niveau D'Etude</th>
                <th>Montant Payé</th>
                <th>Date de Règlement</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

        <div class="table-footer">
            <div class="results-info">
                Showing 1-9 of 240 entries
            </div>
            <div class="pagination">
                <button class="pagination-btn">‹</button>
                <button class="pagination-btn active">1</button>
                <button class="pagination-btn">2</button>
                <button class="pagination-btn">3</button>
                <span>...</span>
                <button class="pagination-btn">12</button>
                <button class="pagination-btn">›</button>
            </div>
        </div>
    </div>
</main>

<script>
    let rowToEdit = null;

    document.getElementById('btnValider').addEventListener('click', function () {
        const numerocarte = document.getElementById('student-number').value.trim();
        const nom = document.getElementById('student-lastname').value.trim();
        const prenom = document.getElementById('student-firstname').value.trim();
        const niveauetude = document.getElementById('niveauEtude').value.trim();
        const montantpaye = document.getElementById('montantpaye').value;
        const datereglement = document.getElementById('datereglement').value.trim();

        // === Vérifications ===

        if (!numerocarte) {
            alert("Veuillez remplir ce champs !");
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
            rowToEdit.cells[4].textContent = niveauetude;
            rowToEdit.cells[5].textContent = montantpaye;
            rowToEdit.cells[6].textContent = datereglement;

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
            <td>${niveauetude}</td>
            <td>${montantpaye}</td>
            <td>${datereglement}</td>
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
            document.getElementById('niveauEtude').value = row.cells[4].textContent;
            document.getElementById('montantpaye').value = row.cells[5].textContent;
            document.getElementById('datereglement').value = row.cells[6].textContent;

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


    function addField(containerId, fieldName, label) {
        const container = document.getElementById(containerId);
        const newRow = document.createElement('div');
        newRow.className = 'field-row';

        newRow.innerHTML = `
    <div class="form-group">
      <input type="text" name="${fieldName}" class="form-input" placeholder=" ">
      <label class="form-label">${label}</label>
    </div>
    <button type="button" class="small-round-btn" onclick="addField('${containerId}', '${fieldName}', '${label}')">+</button>
    <button type="button" class="small-round-btn" onclick="removeField(this)">−</button>
  `;

        container.appendChild(newRow);
    }

    function removeField(button) {
        const row = button.parentElement;
        row.remove();
    }

</script>


<!-- Bibliothèque pour Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<!-- Bibliothèque pour PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
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
                <h2>Historique</h2>
                ${tableHTML}
            </body>
            </html>
        `);
        newWindow.document.close();
        newWindow.print();
    });
</script>
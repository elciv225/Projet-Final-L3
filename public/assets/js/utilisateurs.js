let rowToEdit = null;

document.getElementById('btnValider').addEventListener('click', function () {
    const idUtilisateur = document.getElementById('id-utilisateur').value.trim();
    const nom = document.getElementById('nom-utilisateur').value.trim();
    const prenom = document.getElementById('prenom-utilisateur').value.trim();
    const email = document.getElementById('email-utilisateur').value.trim();
    const dateNaissance = document.getElementById('date-naissance').value;
    const idTypeUtilisateur = document.getElementById('id-type-utilisateur').value.trim();
    const groupe = document.getElementById('id-groupe-utilisateur').value.trim();
    const niveauAcces = document.getElementById('id-niveau-acces').value.trim();
    const login = document.getElementById('login').value.trim();
    const password = document.getElementById('mot-de-passe').value.trim();

    if (!idUtilisateur || !nom || !prenom || !email || !dateNaissance || !idTypeUtilisateur || !groupe || !niveauAcces || !login || !password) {
        alert("Veuillez remplir tous les champs !");
        return;
    }

    // Validation email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert("Adresse email invalide.");
        return;
    }

    // Validation date de naissance
    const dateNaiss = new Date(dateNaissance);
    const aujourdHui = new Date();
    const age = aujourdHui.getFullYear() - dateNaiss.getFullYear();
    if (dateNaiss > aujourdHui) {
        alert("La date de naissance ne peut pas être dans le futur.");
        return;
    }
    if (age < 15) {
        alert("L'utilisateur doit avoir au moins 15 ans.");
        return;
    }

    const tbody = document.querySelector('.table tbody');

    if (rowToEdit) {
        const cells = rowToEdit.cells;
        cells[1].textContent = nom;
        cells[2].textContent = prenom;
        cells[3].textContent = email;
        cells[4].textContent = dateNaissance;
        cells[5].textContent = idTypeUtilisateur;
        cells[6].textContent = groupe;
        cells[7].textContent = niveauAcces;
        cells[8].textContent = login;
        cells[9].textContent = password;
        rowToEdit = null;
        document.getElementById('btnValider').textContent = 'Valider';
    } else {
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td><input type="checkbox" class="checkbox"></td>
            <td>${nom}</td>
            <td>${prenom}</td>
            <td>${email}</td>
            <td>${dateNaissance}</td>
            <td>${idTypeUtilisateur}</td>
            <td>${groupe}</td>
            <td>${niveauAcces}</td>
            <td>${login}</td>
            <td>${password}</td>
            <td>
                <div class="table-actions">
                    <button class="action-btn edit-btn">✏️</button>
                    <button class="action-btn delete-btn">🗑️</button>
                </div>
            </td>
        `;
        tbody.appendChild(newRow);
    }

    document.querySelectorAll('.form-input').forEach(input => input.value = '');
});

document.querySelector('.table tbody').addEventListener('click', function (e) {
    if (e.target.classList.contains('delete-btn')) {
        e.target.closest('tr').remove();
    }

    if (e.target.classList.contains('edit-btn')) {
        const row = e.target.closest('tr');
        rowToEdit = row;

        document.getElementById('nom-utilisateur').value = row.cells[1].textContent;
        document.getElementById('prenom-utilisateur').value = row.cells[2].textContent;
        document.getElementById('email-utilisateur').value = row.cells[3].textContent;
        document.getElementById('date-naissance').value = row.cells[4].textContent;
        document.getElementById('id-type-utilisateur').value = row.cells[5].textContent;
        document.getElementById('id-groupe-utilisateur').value = row.cells[7].textContent;
        document.getElementById('id-niveau-acces').value = row.cells[8].textContent;
        document.getElementById('login').value = row.cells[9].textContent;
        document.getElementById('mot-de-passe').value = row.cells[10].textContent;

        document.getElementById('btnValider').textContent = 'Mettre à jour';
    }
});

document.getElementById('btnSupprimerSelection').addEventListener('click', function () {
    const checkboxes = document.querySelectorAll('.table tbody .checkbox:checked');
    if (checkboxes.length === 0) return alert("Sélectionnez au moins une ligne.");
    if (confirm("Voulez-vous supprimer les lignes sélectionnées ?")) {
        checkboxes.forEach(cb => cb.closest('tr').remove());
    }
});

document.getElementById('searchInput').addEventListener('keyup', function () {
    const value = this.value.toLowerCase();
    document.querySelectorAll('.table tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(value) ? '' : 'none';
    });
});

document.getElementById("btnExportPDF").addEventListener("click", async function () {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    const table = document.querySelector(".table");
    let y = 10;
    doc.text("Liste des utilisateurs", 10, y);
    y += 10;
    table.querySelectorAll("tr").forEach(row => {
        let x = 10;
        row.querySelectorAll("th, td").forEach(cell => {
            doc.text(cell.textContent.trim(), x, y);
            x += 30;
        });
        y += 10;
    });
    doc.save("utilisateurs.pdf");
});

document.getElementById("btnExportExcel").addEventListener("click", function () {
    const table = document.querySelector(".table");
    const wb = XLSX.utils.table_to_book(table, { sheet: "Utilisateurs" });
    XLSX.writeFile(wb, "utilisateurs.xlsx");
});

document.getElementById("btnPrint").addEventListener("click", function () {
    const printWindow = window.open('', '', 'height=700,width=900');
    printWindow.document.write('<html><head><title>Impression</title>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h1>Liste des utilisateurs</h1>');
    printWindow.document.write(document.querySelector(".table").outerHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
});

// === Données simulées ===
const niveauxApprobation = [
    { id: 'APPROB_CHEF_DEPT', name: 'Approbation Chef de Département' },
    { id: 'APPROB_DIRECTEUR', name: 'Approbation Directeur' },
    { id: 'APPROB_RECTEUR', name: 'Approbation Recteur' }
];

const comptesRendus = [
    { id: 'CR_REUNION_1', title: 'Réunion Janvier 2024' },
    { id: 'CR_PROJET_X', title: 'Projet X Lancement' },
    { id: 'CR_SEMINAIRE', title: 'Séminaire annuel' }
];

let historique = [];
let currentPage = 1;
const rowsPerPage = 5;

// === Initialisation ===
document.addEventListener('DOMContentLoaded', () => {
    populateSelect('niveauApprobationSelect', niveauxApprobation, 'name');
    populateSelect('compteRenduSelect', comptesRendus, 'title');

    document.getElementById('addButton').addEventListener('click', handleAdd);
    document.getElementById('searchInput').addEventListener('input', renderTable);
    document.getElementById('btnExportPDF').addEventListener('click', exportPDF);
    document.getElementById('btnExportExcel').addEventListener('click', exportExcel);
    document.getElementById('printButton').addEventListener('click', () => window.print());
    document.getElementById('btnPrint').addEventListener('click', () => window.print());
    document.getElementById('prevPage').addEventListener('click', () => changePage(-1));
    document.getElementById('nextPage').addEventListener('click', () => changePage(1));

    renderTable();
});

function populateSelect(id, items, labelKey) {
    const select = document.getElementById(id);
    items.forEach(item => {
        const opt = document.createElement('option');
        opt.value = item.id;
        opt.textContent = item[labelKey];
        select.appendChild(opt);
    });
}

function handleAdd() {
    const niveau = document.getElementById('niveauApprobationSelect').value;
    const cr = document.getElementById('compteRenduSelect').value;
    const date = document.getElementById('dateApprobationInput').value;
    if (!niveau || !cr || !date) return alert('Veuillez remplir tous les champs.');

    historique.push({ id: Date.now(), niveau, cr, date });
    renderTable();
    clearForm();
}

function clearForm() {
    document.getElementById('niveauApprobationSelect').value = '';
    document.getElementById('compteRenduSelect').value = '';
    document.getElementById('dateApprobationInput').value = '';
}

function renderTable() {
    const tbody = document.getElementById('approbationTableBody');
    const filter = document.getElementById('searchInput').value.toLowerCase();
    tbody.innerHTML = '';

    const filtered = historique.filter(h =>
        h.date.includes(filter) ||
        niveauxApprobation.find(n => n.id === h.niveau)?.name.toLowerCase().includes(filter) ||
        comptesRendus.find(c => c.id === h.cr)?.title.toLowerCase().includes(filter)
    );

    const start = (currentPage - 1) * rowsPerPage;
    const pageItems = filtered.slice(start, start + rowsPerPage);

    pageItems.forEach(item => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
      <td><input type="checkbox"></td>
      <td>${niveauxApprobation.find(n => n.id === item.niveau)?.name}</td>
      <td>${comptesRendus.find(c => c.id === item.cr)?.title}</td>
      <td>${item.date}</td>
      <td><button class="btn">🗑️</button></td>
    `;
        tbody.appendChild(tr);
    });

    document.getElementById('resultsInfo').textContent = `Affichage ${start + 1}-${start + pageItems.length} sur ${filtered.length} entrées`;
}

function changePage(offset) {
    currentPage += offset;
    if (currentPage < 1) currentPage = 1;
    renderTable();
}

function exportPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    const data = historique.map(h => [
        niveauxApprobation.find(n => n.id === h.niveau)?.name,
        comptesRendus.find(c => c.id === h.cr)?.title,
        h.date
    ]);
    doc.autoTable({ head: [['Niveau', 'Compte Rendu', 'Date']], body: data });
    doc.save('approbations.pdf');
}

function exportExcel() {
    let csv = 'Niveau,Compte Rendu,Date\n';
    historique.forEach(h => {
        const niveau = niveauxApprobation.find(n => n.id === h.niveau)?.name;
        const cr = comptesRendus.find(c => c.id === h.cr)?.title;
        csv += `${niveau},${cr},${h.date}\n`;
    });
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'approbations.csv';
    link.click();
}

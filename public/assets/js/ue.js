document.addEventListener('DOMContentLoaded', () => {
    const idUeInput = document.getElementById('idUeInput');
    const niveauSelect = document.getElementById('niveauSelect');
    const semestreSelect = document.getElementById('semestreSelect');
    const creditInput = document.getElementById('creditInput');
    const anneeAcadSelect = document.getElementById('anneeAcadSelect');
    // Mappage entre semestre et niveau
    const semestreToNiveau = {
        S1: 'L1', S2: 'L1',
        S3: 'L2', S4: 'L2',
        S5: 'L3', S6: 'L3',
        S7: 'M1', S8: 'M1',
        S9: 'M2', S10: 'M2'
    };

    const niveauToSemestres = {
        L1: ['S1', 'S2'],
        L2: ['S3', 'S4'],
        L3: ['S5', 'S6'],
        M1: ['S7', 'S8'],
        M2: ['S9', 'S10']
    };

// Lorsque le semestre change → remplir automatiquement le niveau
    semestreSelect.addEventListener('change', () => {
        const semestre = semestreSelect.value;
        if (semestreToNiveau[semestre]) {
            niveauSelect.value = semestreToNiveau[semestre];
        }
    });

// Lorsque le niveau change → vérifier que le semestre est cohérent
    niveauSelect.addEventListener('change', () => {
        const niveau = niveauSelect.value;
        const semestre = semestreSelect.value;
        if (niveau && semestre && !niveauToSemestres[niveau].includes(semestre)) {
            alert(`Ce semestre ne correspond pas au niveau selectionné.`);
            semestreSelect.value = ''; // Réinitialise le semestre
        }
    });


    const addButton = document.getElementById('addButton');
    const modifyButton = document.getElementById('modifyButton');
    const deleteButton = document.getElementById('deleteButton');
    const printButton = document.getElementById('printButton');
    const validateButton = document.getElementById('validateButton');
    const ueTableBody = document.getElementById('ueTableBody');

    let ueData = [];
    let selectedRowIndex = null;

    function renderUeTable() {
        ueTableBody.innerHTML = '';
        ueData.forEach((ue, index) => {
            const row = document.createElement('tr');
            if (index === selectedRowIndex) row.classList.add('selected');
            row.innerHTML = `
          <td>${ue.semestre}</td>
          <td>${ue.idEcue}</td>
          <td>${ue.niveau}</td>
          <td>${ue.credit}</td>
        `;
            row.addEventListener('click', () => {
                selectedRowIndex = index;
                fillFormWithSelected(index);
                renderUeTable();
            });
            ueTableBody.appendChild(row);
        });
    }

    function clearInputs() {
        idUeInput.value = '';
        niveauSelect.value = '';
        semestreSelect.value = '';
        creditInput.value = '';
        anneeAcadSelect.value = '';
    }

    function fillFormWithSelected(index) {
        const ue = ueData[index];
        idUeInput.value = ue.idUe;
        niveauSelect.value = ue.niveau;
        semestreSelect.value = ue.semestre;
        creditInput.value = ue.credit;
    }

    addButton.addEventListener('click', () => {
        const ue = {
            idUe: idUeInput.value.trim(),
            niveau: niveauSelect.value,
            semestre: semestreSelect.value,
            credit: parseInt(creditInput.value)
        };
        if (!ue.idUe || !ue.niveau || !ue.semestre || isNaN(ue.credit)) {
            alert('Veuillez remplir tous les champs.');
            return;
        }
        if (isNaN(ue.credit) || ue.credit <= 0) {
            alert('Le champ "Crédit" doit être un nombre strictement positif.');
            creditInput.focus();
            return;
        }

        ueData.push(ue);
        clearInputs();
        renderUeTable();
    });

    modifyButton.addEventListener('click', () => {
        if (selectedRowIndex === null) {
            alert('Sélectionnez une ligne à modifier.');
            return;
        }
        ueData[selectedRowIndex] = {
            idUe: idUeInput.value.trim(),
            niveau: niveauSelect.value,
            semestre: semestreSelect.value,
            credit: parseInt(creditInput.value)
        };
        renderUeTable();
        clearInputs();
    });

    deleteButton.addEventListener('click', () => {
        if (selectedRowIndex === null) {
            alert('Sélectionnez une ligne à supprimer.');
            return;
        }
        ueData.splice(selectedRowIndex, 1);
        selectedRowIndex = null;
        renderUeTable();
        clearInputs();
    });

    printButton.addEventListener('click', () => {
        window.print();
    });

    validateButton.addEventListener('click', () => {
        console.log('UE validées :', ueData);
        alert('Validation réussie (voir console)');
    });

    renderUeTable();
});

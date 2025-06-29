document.addEventListener('DOMContentLoaded', () => {
    const idUeInput = document.getElementById('idUeInput');
    const libelleInput = document.getElementById('libelleUe');
    const creditInput = document.getElementById('creditInput');
    const idEcueInput = document.getElementById('idEcueInput');

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
                <td>${ue.idUe}</td>
                <td>${ue.libelle}</td>
                <td>${ue.credit}</td>
                <td>${ue.idEcue}</td>
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
        libelleInput.value = '';
        creditInput.value = '';
        idEcueInput.value = '';
        selectedRowIndex = null;
    }

    function fillFormWithSelected(index) {
        const ue = ueData[index];
        idUeInput.value = ue.idUe;
        libelleInput.value = ue.libelle;
        creditInput.value = ue.credit;
        idEcueInput.value = ue.idEcue;
    }

    addButton.addEventListener('click', () => {
        const ue = {
            idUe: idUeInput.value.trim(),
            libelle: libelleInput.value.trim(),
            credit: parseInt(creditInput.value),
            idEcue: idEcueInput.value.trim()
        };

        if (!ue.idUe || !ue.libelle || isNaN(ue.credit) || !ue.idEcue) {
            alert('Veuillez remplir tous les champs.');
            return;
        }

        if (ue.credit <= 0) {
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

        const ue = {
            idUe: idUeInput.value.trim(),
            libelle: libelleInput.value.trim(),
            credit: parseInt(creditInput.value),
            idEcue: idEcueInput.value.trim()
        };

        if (!ue.idUe || !ue.libelle || isNaN(ue.credit) || !ue.idEcue) {
            alert('Veuillez remplir tous les champs.');
            return;
        }

        if (ue.credit <= 0) {
            alert('Le champ "Crédit" doit être un nombre strictement positif.');
            creditInput.focus();
            return;
        }

        ueData[selectedRowIndex] = ue;
        clearInputs();
        renderUeTable();
    });

    deleteButton.addEventListener('click', () => {
        if (selectedRowIndex === null) {
            alert('Sélectionnez une ligne à supprimer.');
            return;
        }

        ueData.splice(selectedRowIndex, 1);
        selectedRowIndex = null;
        clearInputs();
        renderUeTable();
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

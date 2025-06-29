document.addEventListener('DOMContentLoaded', () => {
    const idEcueInput = document.getElementById('idEcueInput');
    const libelleInput = document.getElementById('libelleEcue');
    const creditInput = document.getElementById('creditInput');

    const addButton = document.getElementById('addButton');
    const modifyButton = document.getElementById('modifyButton');
    const deleteButton = document.getElementById('deleteButton');
    const printButton = document.getElementById('printButton');
    const validateButton = document.getElementById('validateButton');
    const ecueTableBody = document.getElementById('ecueTableBody');

    let ecueData = [];
    let selectedRowIndex = null;

    function renderEcueTable() {
        ecueTableBody.innerHTML = '';
        ecueData.forEach((ecue, index) => {
            const row = document.createElement('tr');
            if (index === selectedRowIndex) row.classList.add('selected');
            row.innerHTML = `
                <td>${ecue.idEcue}</td>
                <td>${ecue.libelleEcue}</td>
                <td>${ecue.credit}</td>
            `;
            row.addEventListener('click', () => {
                selectedRowIndex = index;
                fillFormWithSelected(index);
                renderEcueTable();
            });
            ecueTableBody.appendChild(row);
        });
    }

    function clearInputs() {
        idEcueInput.value = '';
        libelleInput.value = '';
        creditInput.value = '';
        selectedRowIndex = null;
    }

    function fillFormWithSelected(index) {
        const ecue = ecueData[index];
        idEcueInput.value = ecue.idEcue;
        libelleInput.value = ecue.libelleEcue;
        creditInput.value = ecue.credit;
    }

    addButton.addEventListener('click', () => {
        const ecue = {
            idEcue: idEcueInput.value.trim(),
            libelleEcue: libelleInput.value.trim(),
            credit: parseInt(creditInput.value)
        };

        if (!ecue.idEcue || !ecue.libelleEcue || isNaN(ecue.credit)) {
            alert('Veuillez remplir tous les champs.');
            return;
        }

        if (ecue.credit <= 0) {
            alert('Le champ "Crédit" doit être un nombre strictement positif.');
            creditInput.focus();
            return;
        }

        ecueData.push(ecue);
        clearInputs();
        renderEcueTable();
    });

    modifyButton.addEventListener('click', () => {
        if (selectedRowIndex === null) {
            alert('Sélectionnez une ligne à modifier.');
            return;
        }

        const ecue = {
            idEcue: idEcueInput.value.trim(),
            libelleEcue: libelleInput.value.trim(),
            credit: parseInt(creditInput.value)
        };

        if (!ecue.idEcue || !ecue.libelleEcue || isNaN(ecue.credit)) {
            alert('Veuillez remplir tous les champs.');
            return;
        }

        if (ecue.credit <= 0) {
            alert('Le champ "Crédit" doit être un nombre strictement positif.');
            creditInput.focus();
            return;
        }

        ecueData[selectedRowIndex] = ecue;
        clearInputs();
        renderEcueTable();
    });

    deleteButton.addEventListener('click', () => {
        if (selectedRowIndex === null) {
            alert('Sélectionnez une ligne à supprimer.');
            return;
        }
        ecueData.splice(selectedRowIndex, 1);
        selectedRowIndex = null;
        clearInputs();
        renderEcueTable();
    });

    printButton.addEventListener('click', () => {
        window.print();
    });

    validateButton.addEventListener('click', () => {
        console.log('ECUE validées :', ecueData);
        alert('Validation réussie (voir console)');
    });

    renderEcueTable();
});

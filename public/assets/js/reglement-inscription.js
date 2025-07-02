// =========================================================================
//  SCRIPT POUR LA GESTION DU RÈGLEMENT DES FRAIS D'INSCRIPTION
// =========================================================================
(function () {
    const selectAnnee = document.getElementById('select-annee-academique-reglement');
    const inputEtudiantId = document.getElementById('etudiant-id-recherche');
    const btnRechercher = document.getElementById('btn-rechercher-etudiant-reglement');

    const infoContainer = document.getElementById('info-etudiant-inscription-container');
    const infoNomPrenoms = document.getElementById('info-nom-prenoms');
    const infoNiveauEtude = document.getElementById('info-niveau-etude');
    const infoMontantInitial = document.getElementById('info-montant-initial');
    const infoTotalPaye = document.getElementById('info-total-paye');
    const infoResteAPayer = document.getElementById('info-reste-a-payer');

    const formPaiement = document.getElementById('form-enregistrement-paiement');
    const paiementEtudiantIdInput = document.getElementById('paiement-etudiant-id');
    const paiementAnneeIdInput = document.getElementById('paiement-annee-id');
    const inputMontantAPayer = document.getElementById('montant-a-payer');

    const historiqueContainer = document.getElementById('historique-paiements-container');
    const tableHistoriqueBody = document.getElementById('table-historique-paiements')?.querySelector('tbody');

    const btnExportPDFHistorique = document.getElementById('btnExportPDFHistorique');
    const btnExportExcelHistorique = document.getElementById('btnExportExcelHistorique');


    if (!selectAnnee || !inputEtudiantId || !btnRechercher || !infoContainer || !formPaiement || !historiqueContainer || !tableHistoriqueBody) {
        console.error("Un ou plusieurs éléments DOM sont manquants pour la page de règlement des inscriptions.");
        return;
    }

    // --- FONCTIONS UTILITAIRES ---
    function showPopup(message, type = 'info') {
        // Utiliser la fonction globale showPopup si elle existe (définie dans main.js ou ajax.js)
        if (typeof window.showPopup === 'function') {
            window.showPopup(message, type);
        } else {
            alert(`${type.toUpperCase()}: ${message}`);
        }
    }

    function escapeHTML(str) {
        if (str === null || str === undefined) return '';
        return String(str).replace(/[&<>"']/g, match => ({'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'}[match]));
    }


    // --- GESTION DE LA RECHERCHE ---
    btnRechercher.addEventListener('click', async function () {
        const anneeId = selectAnnee.value;
        const etudiantId = inputEtudiantId.value.trim();

        if (!anneeId || !etudiantId) {
            showPopup("Veuillez sélectionner une année académique et saisir un ID étudiant.", "error");
            return;
        }

        try {
            const response = await fetch(`/reglement-inscription/rechercher-etudiant?etudiant_id=${etudiantId}&annee_academique_id=${anneeId}`);
            const data = await response.json();

            if (response.ok && data.etudiant) {
                displayInfoEtudiant(data.etudiant);
                await loadHistoriquePaiements(etudiantId, anneeId); // Charger l'historique
                infoContainer.style.display = 'block';
                formPaiement.style.display = 'block';
                historiqueContainer.style.display = 'block';
            } else {
                showPopup(data.error || "Étudiant non trouvé ou non inscrit pour cette année.", "error");
                resetInfoDisplay();
            }
        } catch (error) {
            console.error("Erreur lors de la recherche de l'étudiant:", error);
            showPopup("Erreur de communication avec le serveur.", "error");
            resetInfoDisplay();
        }
    });

    function displayInfoEtudiant(etudiantData) {
        infoNomPrenoms.value = `${escapeHTML(etudiantData.nom || '')} ${escapeHTML(etudiantData.prenoms || '')}`;
        infoNiveauEtude.value = escapeHTML(etudiantData.niveau_etude_libelle || 'N/A');
        infoMontantInitial.value = parseFloat(etudiantData.montant_initial || 0).toLocaleString('fr-FR');
        infoTotalPaye.value = parseFloat(etudiantData.total_deja_paye || 0).toLocaleString('fr-FR');
        infoResteAPayer.value = parseFloat(etudiantData.reste_a_payer || 0).toLocaleString('fr-FR');

        paiementEtudiantIdInput.value = etudiantData.etudiant_id;
        paiementAnneeIdInput.value = etudiantData.annee_academique_id;

        // Mettre à jour le max pour le montant à payer
        const reste = parseFloat(etudiantData.reste_a_payer || 0);
        inputMontantAPayer.max = reste;
        if (reste <= 0) {
            inputMontantAPayer.disabled = true;
            inputMontantAPayer.placeholder = "Aucun règlement requis";
            formPaiement.querySelector('button[type="submit"]').disabled = true;
        } else {
            inputMontantAPayer.disabled = false;
            inputMontantAPayer.placeholder = " ";
             formPaiement.querySelector('button[type="submit"]').disabled = false;
        }
    }

    function resetInfoDisplay() {
        infoContainer.style.display = 'none';
        formPaiement.style.display = 'none';
        historiqueContainer.style.display = 'none';
        infoNomPrenoms.value = '';
        infoNiveauEtude.value = '';
        infoMontantInitial.value = '';
        infoTotalPaye.value = '';
        infoResteAPayer.value = '';
        paiementEtudiantIdInput.value = '';
        paiementAnneeIdInput.value = '';
        inputMontantAPayer.value = '';
        inputMontantAPayer.max = '';
        inputMontantAPayer.disabled = false;
        inputMontantAPayer.placeholder = " ";
        formPaiement.querySelector('button[type="submit"]').disabled = false;
        tableHistoriqueBody.innerHTML = '';
    }

    // --- GESTION DE L'ENREGISTREMENT DU PAIEMENT ---
    // La soumission du formulaire est gérée par ajax.js grâce à la classe 'ajax-form'.
    // Il faut s'assurer que la réponse du serveur (en cas de succès) contient les données mises à jour
    // de l'étudiant pour rafraîchir l'affichage.
    // La fonction displayInfoEtudiant sera appelée par ajax.js si la réponse contient data.etudiant.
    // Et il faudra aussi recharger l'historique.

    // --- GESTION DE L'HISTORIQUE DES PAIEMENTS ---
    async function loadHistoriquePaiements(etudiantId, anneeId) {
        tableHistoriqueBody.innerHTML = `<tr><td colspan="3" style="text-align:center;">Chargement de l'historique...</td></tr>`;
        try {
            const response = await fetch(`/reglement-inscription/historique?etudiant_id=${etudiantId}&annee_academique_id=${anneeId}`);
            const data = await response.json();

            if (response.ok && data.historique) {
                renderHistoriqueTable(data.historique);
            } else {
                tableHistoriqueBody.innerHTML = `<tr><td colspan="3" style="text-align:center; color:red;">${data.error || "Erreur lors du chargement de l'historique."}</td></tr>`;
            }
        } catch (error) {
            console.error("Erreur AJAX pour l'historique:", error);
            tableHistoriqueBody.innerHTML = `<tr><td colspan="3" style="text-align:center; color:red;">Impossible de charger l'historique.</td></tr>`;
        }
    }

    function renderHistoriqueTable(historique) {
        tableHistoriqueBody.innerHTML = '';
        if (historique.length === 0) {
            tableHistoriqueBody.innerHTML = `<tr><td colspan="3" style="text-align:center;">Aucun paiement enregistré pour cette inscription.</td></tr>`;
            return;
        }
        historique.forEach(paiement => {
            const row = tableHistoriqueBody.insertRow();
            row.insertCell().textContent = new Date(paiement.date_paiement).toLocaleDateString('fr-FR');
            row.insertCell().textContent = parseFloat(paiement.montant_paye).toLocaleString('fr-FR');
            row.insertCell().textContent = escapeHTML(paiement.reference_paiement || 'N/A');
        });
    }

    // --- GESTION DES EXPORTS ---
    if (btnExportPDFHistorique) {
        btnExportPDFHistorique.addEventListener("click", function () {
            const { jsPDF } = window.jspdf;
            if (!jsPDF) {
                showPopup("La librairie jsPDF n'est pas chargée.", "error");
                return;
            }
            const doc = new jsPDF();
            const nomEtudiant = infoNomPrenoms.value || "Inconnu";
            const anneeAcad = selectAnnee.options[selectAnnee.selectedIndex]?.text || "N/A";

            doc.setFontSize(16);
            doc.text(`Historique des Paiements - ${nomEtudiant}`, 14, 15);
            doc.setFontSize(12);
            doc.text(`Année Académique: ${anneeAcad}`, 14, 22);

            if (typeof doc.autoTable === 'function') {
                doc.autoTable({
                    html: '#table-historique-paiements',
                    startY: 30,
                    theme: 'grid',
                    headStyles: { fillColor: [22, 160, 133] },
                });
            } else {
                 showPopup("La librairie jsPDF-AutoTable n'est pas chargée pour l'export PDF.", "error");
                 return;
            }
            doc.save(`historique_paiements_${paiementEtudiantIdInput.value}_${paiementAnneeIdInput.value}.pdf`);
        });
    }

    if (btnExportExcelHistorique) {
        btnExportExcelHistorique.addEventListener("click", function () {
            if (typeof XLSX === 'undefined') {
                showPopup("La librairie XLSX (SheetJS) n'est pas chargée pour l'export Excel.", "error");
                return;
            }
            const table = document.getElementById("table-historique-paiements");
            const wb = XLSX.utils.table_to_book(table, { sheet: "Historique Paiements" });
            XLSX.writeFile(wb, `historique_paiements_${paiementEtudiantIdInput.value}_${paiementAnneeIdInput.value}.xlsx`);
        });
    }


    // --- REBINDING POUR AJAX ---
    // Si le formulaire de paiement est soumis via AJAX et que la réponse met à jour
    // les informations de l'étudiant et doit aussi recharger l'historique.
    if (typeof window.addAjaxSuccessListener === 'function') {
        window.addAjaxSuccessListener(async function(form, data) {
            if (form.id === 'form-enregistrement-paiement' && data.etudiant) {
                // Le formulaire de paiement a été soumis avec succès
                displayInfoEtudiant(data.etudiant); // Mettre à jour les infos principales
                await loadHistoriquePaiements(data.etudiant.etudiant_id, data.etudiant.annee_academique_id); // Recharger l'historique
            }
        });
    }


})();

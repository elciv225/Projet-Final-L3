
    // Mock data - replace with actual data fetching
    const studentsData = {
    "1": {
    name: "Dupont, Jean",
    id: "E12345",
    photo: "image-placeholder-profile.png",
    report: {
    title: "Analyse de systèmes distribués",
    supervisor: "Prof. K. Anya",
    submissionDate: "15/07/2024",
    status: "En cours de validation",
    statusClass: "status-pending"
}
},
    "2": {
    name: "Martin, Alice",
    id: "E67890",
    photo: "image-placeholder-profile2.png",
    report: {
    title: "IA pour la reconnaissance d'images",
    supervisor: "Dr. B. Charles",
    submissionDate: "18/07/2024",
    status: "À venir",
    statusClass: "status-upcoming"
}
},
    "3": {
    name: "Bernard, Lucas",
    id: "E24680",
    photo: "image-placeholder-profile3.png",
    report: {
    title: "Sécurité des réseaux IoT",
    supervisor: "Prof. D. Elara",
    submissionDate: "20/07/2024",
    status: "À venir",
    statusClass: "status-upcoming"
}
},
    "4": {
    name: "Petit, Chloé",
    id: "E13579",
    photo: "image-placeholder-profile4.png",
    report: {
    title: "Optimisation d'algorithmes génétiques",
    supervisor: "Prof. F. Gael",
    submissionDate: "10/06/2024",
    status: "Validé",
    statusClass: "status-validated"
}
},
    "5": {
    name: "Leroy, Tom",
    id: "E97531",
    photo: "image-placeholder-profile5.png",
    report: {
    title: "Développement d'une application mobile",
    supervisor: "Dr. H. Ines",
    submissionDate: "05/05/2024",
    status: "Rejeté",
    statusClass: "status-rejected"
}
}
};
    const studentItems = document.querySelectorAll('.student-item');
    const studentNameDisplay = document.getElementById('studentName');
    const studentIdDisplay = document.getElementById('studentId');
    const studentPhotoDisplay = document.getElementById('studentPhoto');
    const reportTitleDisplay = document.getElementById('reportTitle');
    const reportSupervisorDisplay = document.getElementById('reportSupervisor');
    const reportDateDisplay = document.getElementById('reportDate');
    const reportStatusDisplay = document.getElementById('reportStatus');
    const discussionHeaderTitle = document.querySelector('.central-section .discussion-header h2');
    studentItems.forEach(item => {
    item.addEventListener('click', () => {
        studentItems.forEach(i => i.classList.remove('active'));
        item.classList.add('active');
        const studentId = item.dataset.studentId;
        const student = studentsData[studentId];
        if (student) {
            studentNameDisplay.textContent = student.name;
            studentIdDisplay.textContent = `ID: ${student.id}`;
            studentPhotoDisplay.src = student.photo;
            studentPhotoDisplay.alt = `Photo de ${student.name}`;
            reportTitleDisplay.textContent = student.report.title;
            reportSupervisorDisplay.textContent = student.report.supervisor;
            reportDateDisplay.textContent = student.report.submissionDate;
            reportStatusDisplay.textContent = student.report.status;
            reportStatusDisplay.className = `info-value ${student.report.statusClass}`;
            discussionHeaderTitle.textContent = `Discussion: Rapport de ${student.name.split(', ')[1]} ${student.name.split(', ')[0]}`;
            document.getElementById('discussionArea').innerHTML = `<div class="message received"><div class="message-sender">Système</div>Bienvenue dans la discussion pour ${student.name}.</div>`;
            resetVotes();
            if (window.innerWidth < 1024) { // Auto-hide left panel on mobile after selection
                document.querySelector('.left-section').classList.remove('mobile-visible');
                document.getElementById('toggleLeftNav').textContent = "Afficher Étudiants";
            }
        }
    });
});
    const toggleHistoryBtn = document.getElementById('toggleHistory');
    const historyList = document.getElementById('historyList');
    toggleHistoryBtn.addEventListener('click', () => {
    historyList.classList.toggle('hidden');
    const icon = toggleHistoryBtn.querySelector('.material-icons-outlined');
    icon.textContent = historyList.classList.contains('hidden') ? 'history' : 'expand_less';
});
    const messageInput = document.getElementById('messageInput');
    const sendMessageBtn = document.getElementById('sendMessageBtn');
    const discussionArea = document.getElementById('discussionArea');
    sendMessageBtn.addEventListener('click', sendMessage);
    messageInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
    e.preventDefault();
    sendMessage();
}
});

    function sendMessage() {
    const messageText = messageInput.value.trim();
    if (messageText) {
    const messageDiv = document.createElement('div');
    messageDiv.classList.add('message', 'sent');
    messageDiv.innerHTML = `<div class="message-sender">Vous (Prof. Durand)</div>${messageText.replace(/\n/g, '<br>')}`;
    discussionArea.appendChild(messageDiv);
    messageInput.value = '';
    discussionArea.scrollTop = discussionArea.scrollHeight;
    // Auto-resize textarea
    messageInput.style.height = 'auto';
    messageInput.style.height = messageInput.scrollHeight + 'px';
}
}

    messageInput.addEventListener('input', () => {
    messageInput.style.height = 'auto';
    messageInput.style.height = messageInput.scrollHeight + 'px';
});
    const agreeBtn = document.getElementById('agreeBtn');
    const disagreeBtn = document.getElementById('disagreeBtn');
    const voteDurandDisplay = document.getElementById('voteDurand');
    const voteLemoineDisplay = document.getElementById('voteLemoine');
    const voteRossiDisplay = document.getElementById('voteRossi');
    const closeSessionBtn = document.getElementById('closeSessionBtn');
    const voteResultsSummary = document.getElementById('voteResultsSummary');
    const disagreementAlert = document.getElementById('disagreementAlert');
    let votes = {
    lemoine: 'agree',
    durand: null,
    rossi: null
};

    function updateVoteDisplay(member, vote) {
    const displayElement = document.getElementById(`vote${member.charAt(0).toUpperCase() + member.slice(1)}`);
    if (vote === 'agree') {
    displayElement.textContent = "D'accord";
    displayElement.className = 'vote-indicator vote-agree';
} else if (vote === 'disagree') {
    displayElement.textContent = "Pas d'accord";
    displayElement.className = 'vote-indicator vote-disagree';
} else {
    displayElement.textContent = "En attente";
    displayElement.className = 'vote-indicator vote-pending';
}
}

    updateVoteDisplay('lemoine', votes.lemoine);
    agreeBtn.addEventListener('click', () => castVote('durand', 'agree'));
    disagreeBtn.addEventListener('click', () => castVote('durand', 'disagree'));

    function castVote(member, vote) {
    votes[member] = vote;
    updateVoteDisplay(member, vote);
    checkVotingStatus();
    if (member === 'durand' && !votes.rossi) {
    setTimeout(() => {
    const randomVote = Math.random() < 0.7 ? 'agree' : 'disagree';
    votes.rossi = randomVote;
    updateVoteDisplay('rossi', votes.rossi);
    checkVotingStatus();
}, 1500);
}
}

    function resetVotes() {
    votes = {
        lemoine: Math.random() < 0.8 ? 'agree' : 'disagree',
        durand: null,
        rossi: null
    };
    updateVoteDisplay('lemoine', votes.lemoine);
    updateVoteDisplay('durand', votes.durand);
    updateVoteDisplay('rossi', votes.rossi);
    closeSessionBtn.disabled = true;
    voteResultsSummary.classList.add('hidden');
    voteResultsSummary.textContent = "Résultat du vote: En attente.";
    disagreementAlert.classList.remove('active');
    agreeBtn.disabled = false;
    disagreeBtn.disabled = false;
    document.getElementById('voteComment').value = '';
    checkVotingStatus();
}

    function checkVotingStatus() {
    const allVoted = Object.values(votes).every(v => v !== null);
    if (allVoted) {
    agreeBtn.disabled = true;
    disagreeBtn.disabled = true;
    const uniqueVotes = new Set(Object.values(votes));
    if (uniqueVotes.size === 1) {
    closeSessionBtn.disabled = false;
    voteResultsSummary.textContent = `Validation ${votes.durand === 'agree' ? 'ACCEPTÉE' : 'REJETÉE'} (Unanime)`;
    voteResultsSummary.classList.remove('hidden');
    disagreementAlert.classList.remove('active');
    voteResultsSummary.style.backgroundColor = votes.durand === 'agree' ? 'var(--success)' : 'var(--error)';
    voteResultsSummary.style.color = 'white';
} else {
    closeSessionBtn.disabled = true;
    voteResultsSummary.textContent = "Désaccord. Session non finalisable.";
    voteResultsSummary.style.backgroundColor = 'var(--warning)';
    voteResultsSummary.style.color = 'var(--text-primary)';
    voteResultsSummary.classList.remove('hidden');
    disagreementAlert.classList.add('active');
}
} else {
    closeSessionBtn.disabled = true;
    voteResultsSummary.classList.add('hidden');
    disagreementAlert.classList.remove('active');
    if (!votes.durand) {
    agreeBtn.disabled = false;
    disagreeBtn.disabled = false;
}
}
}

    checkVotingStatus();
    closeSessionBtn.addEventListener('click', () => {
    if (!closeSessionBtn.disabled) {
    alert("Session clôturée. Le statut du rapport est mis à jour.");
    const activeStudentItem = document.querySelector('.student-item.active');
    if (activeStudentItem) {
    const statusBadge = activeStudentItem.querySelector('.status-badge');
    const allAgreed = Object.values(votes).every(v => v === 'agree');
    if (allAgreed) {
    statusBadge.textContent = 'Validé';
    statusBadge.className = 'status-badge status-validated';
    reportStatusDisplay.textContent = 'Validé';
    reportStatusDisplay.className = 'info-value status-validated';
} else {
    statusBadge.textContent = 'Rejeté';
    statusBadge.className = 'status-badge status-rejected';
    reportStatusDisplay.textContent = 'Rejeté';
    reportStatusDisplay.className = 'info-value status-rejected';
}
}
    const nextStudent = activeStudentItem ? activeStudentItem.nextElementSibling : null;
    if (nextStudent && nextStudent.classList.contains('student-item')) {
    nextStudent.click();
} else {
    discussionHeaderTitle.textContent = "File d'attente terminée";
    if (activeStudentItem) activeStudentItem.classList.remove('active');
    studentNameDisplay.textContent = "-";
    studentIdDisplay.textContent = "ID: -";
    studentPhotoDisplay.src = "";
    studentPhotoDisplay.alt = "Aucun étudiant sélectionné";
    reportTitleDisplay.textContent = "-";
    reportSupervisorDisplay.textContent = "-";
    reportDateDisplay.textContent = "-";
    reportStatusDisplay.textContent = "-";
    reportStatusDisplay.className = "info-value";
    document.getElementById('discussionArea').innerHTML = `<div class="message received"><div class="message-sender">Système</div>Aucun étudiant en cours.</div>`;
    resetVotes();
    closeSessionBtn.disabled = true;
}
}
});
    const votingAreaContainer = document.getElementById('votingAreaContainer');
    const toggleVotingAreaBtn = document.getElementById('toggleVotingArea');
    toggleVotingAreaBtn.addEventListener('click', () => {
    votingAreaContainer.classList.toggle('collapsed');
});
    // Make voting area collapsed by default
    votingAreaContainer.classList.add('collapsed');
    // Responsive navigation toggles
    const toggleLeftNavBtn = document.getElementById('toggleLeftNav');
    const toggleRightNavBtn = document.getElementById('toggleRightNav');
    const leftSection = document.querySelector('.left-section');
    const rightSection = document.querySelector('.right-section');
    toggleLeftNavBtn.addEventListener('click', () => {
    leftSection.classList.toggle('mobile-visible');
    if (leftSection.classList.contains('mobile-visible')) {
    toggleLeftNavBtn.textContent = "Cacher Étudiants";
    rightSection.classList.remove('mobile-visible'); // Hide right if left is shown
    toggleRightNavBtn.textContent = "Afficher Détails";
} else {
    toggleLeftNavBtn.textContent = "Afficher Étudiants";
}
});
    toggleRightNavBtn.addEventListener('click', () => {
    rightSection.classList.toggle('mobile-visible');
    if (rightSection.classList.contains('mobile-visible')) {
    toggleRightNavBtn.textContent = "Cacher Détails";
    leftSection.classList.remove('mobile-visible'); // Hide left if right is shown
    toggleLeftNavBtn.textContent = "Afficher Étudiants";
} else {
    toggleRightNavBtn.textContent = "Afficher Détails";
}
});
    // Ensure correct display on resize
    window.addEventListener('resize', () => {
    if (window.innerWidth >= 1024) {
    leftSection.classList.remove('mobile-visible');
    rightSection.classList.remove('mobile-visible');
    toggleLeftNavBtn.textContent = "Afficher Étudiants";
    toggleRightNavBtn.textContent = "Afficher Détails";
}
});

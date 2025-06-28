<link rel="stylesheet" href="/assets/css/commission.css">
<main class="main-content">
    <button class="mobile-nav-toggle" id="toggleLeftNav">Afficher Étudiants</button>
    <aside class="left-section">
        <h2>File d'attente</h2>
        <div class="search-bar">
            <span class="material-icons-outlined">search</span>
            <input placeholder="Rechercher un étudiant..." type="text"/>
        </div>
        <ul class="student-list">
            <li class="student-item active" data-student-id="1">
                <div>
                    <div class="student-name">Dupont, Jean</div>
                    <div style="font-size: 0.8em; color: var(--text-secondary);">ID: E12345</div>
                </div>
                <span class="status-badge status-in-progress">En cours</span>
            </li>
            <li class="student-item" data-student-id="2">
                <div>
                    <div class="student-name">Martin, Alice</div>
                    <div style="font-size: 0.8em; color: var(--text-secondary);">ID: E67890</div>
                </div>
                <span class="status-badge status-upcoming">À venir</span>
            </li>
            <li class="student-item" data-student-id="3">
                <div>
                    <div class="student-name">Bernard, Lucas</div>
                    <div style="font-size: 0.8em; color: var(--text-secondary);">ID: E24680</div>
                </div>
                <span class="status-badge status-upcoming">À venir</span>
            </li>
        </ul>
        <div class="history-toggle" id="toggleHistory">
            <span class="material-icons-outlined">history</span>
            <span>Historique des rapports</span>
        </div>
        <ul class="student-list hidden" id="historyList">
            <li class="student-item" data-student-id="4">
                <div>
                    <div class="student-name">Petit, Chloé</div>
                    <div style="font-size: 0.8em; color: var(--text-secondary);">ID: E13579</div>
                </div>
                <span class="status-badge status-validated">Validé</span>
            </li>
            <li class="student-item" data-student-id="5">
                <div>
                    <div class="student-name">Leroy, Tom</div>
                    <div style="font-size: 0.8em; color: var(--text-secondary);">ID: E97531</div>
                </div>
                <span class="status-badge status-rejected">Rejeté</span>
            </li>
        </ul>
    </aside>
    <main class="central-section">
        <div class="discussion-header">
            <h2>Discussion: Rapport de Jean Dupont</h2>
            <button class="close-session-btn" disabled="" id="closeSessionBtn">Clôturer la session</button>
        </div>
        <div class="chat-container">
            <div class="discussion-area" id="discussionArea">
                <div class="message received">
                    <div class="message-sender">Prof. Lemoine</div>
                    Le plan du rapport est bien structuré.
                </div>
                <div class="message sent">
                    <div class="message-sender">Vous (Prof. Durand)</div>
                    Je suis d'accord, la problématique est claire.
                </div>
                <div class="message received">
                    <div class="message-sender">Dr. Rossi</div>
                    Quelques coquilles à corriger dans la bibliographie cependant.
                </div>
            </div>
            <div class="message-input-area">
                <textarea id="messageInput" placeholder="Écrire un message..." rows="1"></textarea>
                <button id="sendMessageBtn"><span class="material-icons-outlined">send</span></button>
            </div>
        </div>
        <div class="voting-area" id="votingAreaContainer">
            <h3 id="toggleVotingArea">Vote sur la validation <span class="material-icons-outlined">expand_more</span>
            </h3>
            <div class="voting-content">
                <div class="voting-buttons">
                    <button class="vote-btn agree-btn" id="agreeBtn">D'accord</button>
                    <button class="vote-btn disagree-btn" id="disagreeBtn">Pas d'accord</button>
                </div>
                <div class="voting-status" id="votingStatus">
                    <div class="member-vote">
                        <span class="member-name">Prof. Lemoine:</span>
                        <span class="vote-indicator vote-agree" id="voteLemoine">D'accord</span>
                    </div>
                    <div class="member-vote">
                        <span class="member-name">Vous (Prof. Durand):</span>
                        <span class="vote-indicator vote-pending" id="voteDurand">En attente</span>
                    </div>
                    <div class="member-vote">
                        <span class="member-name">Dr. Rossi:</span>
                        <span class="vote-indicator vote-pending" id="voteRossi">En attente</span>
                    </div>
                </div>
                <div class="alert-disagreement" id="disagreementAlert">
                    <span class="material-icons-outlined">warning</span>
                    Désaccord. Veuillez justifier.
                </div>
                <div class="vote-results-summary hidden" id="voteResultsSummary">
                    Résultat du vote: En attente.
                </div>
                <div class="comment-box">
                    <textarea id="voteComment" placeholder="Commentaire de vote (optionnel)..." rows="2"></textarea>
                </div>
            </div>
        </div>
    </main>
    <button class="mobile-nav-toggle" id="toggleRightNav">Afficher Détails</button>
    <aside class="right-section">
        <div class="student-profile">
            <img alt="Photo de l'étudiant Jean Dupont" id="studentPhoto"
                 src="https://lh3.googleusercontent.com/aida-public/AB6AXuDwEYaUWVGkduwoKOio8dhr6iBQcfXCLmyIzcjrk7lp267K7dbsCtG6gIrRodaaNc8uuFs0MtxhPuuEFb3KTfzAOhmllCG0Yh8-N2rAMmcTsTU5OpqYOgCu0AB9TzHut3BVv9FYxrsJzZxToZ4avJ3tMIoizEIbg-To3IP1M4vsiJmahu05Ci4whJhJph7zkOWU_A-q8lxkH10306NtvHYY-2vcpDWoK8WJma-pn7q8ZFSeII4ZRhphL8nQQf5AjUSdlJ15BFtYccQ"/>
            <h3 class="name" id="studentName">Dupont, Jean</h3>
            <p class="id" id="studentId">ID: E12345</p>
        </div>
        <div class="report-info">
            <h3>Informations du rapport</h3>
            <div class="info-item">
                <span class="info-label">Titre:</span>
                <span class="info-value" id="reportTitle">Analyse de systèmes distribués</span>
            </div>
            <div class="info-item">
                <span class="info-label">Superviseur:</span>
                <span class="info-value" id="reportSupervisor">Prof. K. Anya</span>
            </div>
            <div class="info-item">
                <span class="info-label">Soumission:</span>
                <span class="info-value" id="reportDate">15/07/2024</span>
            </div>
            <div class="info-item">
                <span class="info-label">Statut:</span>
                <span class="info-value status-pending" id="reportStatus">En cours</span>
            </div>
        </div>
        <div class="report-actions">
            <button class="report-action-btn" id="downloadReportBtn">
                <span class="material-icons-outlined">download</span>
                Télécharger
            </button>
            <button class="report-action-btn" id="viewReportBtn">
                <span class="material-icons-outlined">visibility</span>
                Visualiser
            </button>
        </div>
    </aside>
</main>
<script src="/assets/js/discussion.js" defer></script>
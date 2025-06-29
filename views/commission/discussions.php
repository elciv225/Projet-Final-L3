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
            <?php
                // Example of how current user ID and name would be fetched in PHP.
                // This is highly dependent on the actual authentication system.
                // session_start(); // Ensure session is started
                // $currentAuteurId = $_SESSION['user']['id'] ?? 'DEFAULT_USER_ID_IF_NOT_LOGGED_IN';
                // $currentUserFullName = $_SESSION['user']['nom_complet'] ?? 'Utilisateur Anonyme';
                // $websocketPort = $_ENV['WEBSOCKET_PORT'] ?? '8080';

                // --- Fallback for when PHP session data isn't available in this context ---
                $currentAuteurId = 'PHP_USER_ID_PLACEHOLDER'; // Replace with actual PHP variable
                $currentUserFullName = 'PHP_USER_NAME_PLACEHOLDER'; // Replace with actual PHP variable
                $websocketPort = '8080'; // Default, can be overridden by .env in websocket_server.php
            ?>
            <script>
                // Pass PHP variables to JavaScript
                window.currentAuteurId = <?php echo json_encode($currentAuteurId); ?>;
                window.currentUserFullName = <?php echo json_encode($currentUserFullName); ?>;
                window.websocketPort = <?php echo json_encode($websocketPort); ?>;
            </script>

            <li class="student-item active" data-student-id="1" data-discussion-id="discussion_rapport_E12345">
                <div>
                    <div class="student-name">Dupont, Jean</div>
                    <div style="font-size: 0.8em; color: var(--text-secondary);">ID: E12345</div>
                </div>
                <span class="status-badge status-in-progress">En cours</span>
            </li>
            <?php
                // PHP Example: Assume $students is an array of student objects/arrays
                // Each student object should have a `discussion_id` property/key
                // And an `id` for `data-student-id` (matching mock data keys if used)
                // And the necessary fields like nom_complet, matricule, statut_css_class, statut_libelle
                /*
                foreach ($students as $student) {
                    echo '<li class="student-item" data-student-id="' . htmlspecialchars($student->id_for_js_mock_data) . '" data-discussion-id="' . htmlspecialchars($student->discussion_id) . '">';
                    echo '<div>';
                    echo '<div class="student-name">' . htmlspecialchars($student->nom_complet) . '</div>';
                    echo '<div style="font-size: 0.8em; color: var(--text-secondary);">ID: ' . htmlspecialchars($student->matricule) . '</div>';
                    echo '</div>';
                    echo '<span class="status-badge status-' . htmlspecialchars($student->statut_css_class) . '">' . htmlspecialchars($student->statut_libelle) . '</span>';
                    echo '</li>';
                }
                */
            ?>
            <li class="student-item" data-student-id="2" data-discussion-id="discussion_rapport_E67890">
                <div>
                    <div class="student-name">Martin, Alice</div>
                    <div style="font-size: 0.8em; color: var(--text-secondary);">ID: E67890</div>
                </div>
                <span class="status-badge status-upcoming">À venir</span>
            </li>
            <li class="student-item" data-student-id="3" data-discussion-id="discussion_rapport_E24680">
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
            <li class="student-item" data-student-id="4" data-discussion-id="discussion_rapport_E13579_history">
                <div>
                    <div class="student-name">Petit, Chloé</div>
                    <div style="font-size: 0.8em; color: var(--text-secondary);">ID: E13579</div>
                </div>
                <span class="status-badge status-validated">Validé</span>
            </li>
            <li class="student-item" data-student-id="5" data-discussion-id="discussion_rapport_E97531_history">
                <div>
                    <div class="student-name">Leroy, Tom</div>
                    <div style="font-size: 0.8em; color: var(--text-secondary);">ID: E97531</div>
                </div>
                <span class="status-badge status-rejected">Rejeté</span>
            </li>
        </ul>
    </aside>
    <main class="central-section">
        <div class="page-header discussion-page-header"> <!-- Adapted class -->
            <div class="header-left"> <!-- Mimic structure from main-content.php -->
                <h2 id="discussionTitle">Discussion: Rapport de Jean Dupont</h2>
                <!-- Optional: <p class="header-subtitle">Session de discussion en temps réel</p> -->
            </div>
            <div class="header-right">
                 <button class="btn btn-primary close-session-btn" disabled id="closeSessionBtn">Clôturer la session</button> <!-- Added btn classes -->
            </div>
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
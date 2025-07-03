<link rel="stylesheet" href="/assets/css/commission.css">
<main class="main-content">
    <button class="mobile-nav-toggle" id="toggleLeftNav">Afficher Rapports</button>
    <aside class="left-section">
        <h2>Rapports à examiner</h2>
        <div class="search-bar">
            <span class="material-icons-outlined">search</span>
            <input placeholder="Rechercher un rapport..." type="text" id="searchInput"/>
        </div>

        <!-- Rapports en attente de validation -->
        <h3 class="section-title">En attente de validation</h3>
        <ul class="student-list" id="pendingReportsList">
            <?php if (empty($rapports_en_attente)): ?>
                <li class="empty-list-message">Aucun rapport en attente de validation</li>
            <?php else: ?>
                <?php foreach ($rapports_en_attente as $rapport): ?>
                    <?php 
                        $infoDepot = $depotDAO->getInfosDepot($rapport->getId());
                        if (!$infoDepot) continue;
                    ?>
                    <li class="student-item" data-report-id="<?= htmlspecialchars($rapport->getId()) ?>">
                        <a href="/commission/discussion?rapport_id=<?= htmlspecialchars($rapport->getId()) ?>&etudiant_id=<?= htmlspecialchars($infoDepot['utilisateur_id']) ?>">
                            <div>
                                <div class="student-name"><?= htmlspecialchars($infoDepot['nom'] . ', ' . $infoDepot['prenoms']) ?></div>
                                <div class="report-title"><?= htmlspecialchars($rapport->getTitre()) ?></div>
                                <div style="font-size: 0.8em; color: var(--text-secondary);">Soumis le: <?= htmlspecialchars(date('d/m/Y', strtotime($infoDepot['date_depot']))) ?></div>
                            </div>
                            <span class="status-badge status-pending">En attente</span>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>

        <!-- Rapports validés mais pas encore approuvés -->
        <h3 class="section-title">Validés (en attente d'approbation)</h3>
        <ul class="student-list" id="validatedReportsList">
            <?php if (empty($rapports_valides)): ?>
                <li class="empty-list-message">Aucun rapport validé en attente d'approbation</li>
            <?php else: ?>
                <?php foreach ($rapports_valides as $rapport): ?>
                    <?php 
                        $infoDepot = $depotDAO->getInfosDepot($rapport->getId());
                        $infoValidation = $validationDAO->getInfosValidation($rapport->getId());
                        if (!$infoDepot || !$infoValidation) continue;
                    ?>
                    <li class="student-item" data-report-id="<?= htmlspecialchars($rapport->getId()) ?>">
                        <a href="/commission/discussion?rapport_id=<?= htmlspecialchars($rapport->getId()) ?>&etudiant_id=<?= htmlspecialchars($infoDepot['utilisateur_id']) ?>">
                            <div>
                                <div class="student-name"><?= htmlspecialchars($infoDepot['nom'] . ', ' . $infoDepot['prenoms']) ?></div>
                                <div class="report-title"><?= htmlspecialchars($rapport->getTitre()) ?></div>
                                <div style="font-size: 0.8em; color: var(--text-secondary);">Validé le: <?= htmlspecialchars(date('d/m/Y', strtotime($infoValidation['date_validation']))) ?></div>
                            </div>
                            <span class="status-badge status-validated">Validé</span>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>

        <!-- Discussions en cours -->
        <h3 class="section-title">Discussions en cours</h3>
        <ul class="student-list" id="discussionsList">
            <?php if (empty($discussions)): ?>
                <li class="empty-list-message">Aucune discussion en cours</li>
            <?php else: ?>
                <?php foreach ($discussions as $discussion): ?>
                    <li class="student-item" data-discussion-id="<?= htmlspecialchars($discussion['discussion_id']) ?>">
                        <a href="/commission/discussion?rapport_id=<?= htmlspecialchars($discussion['rapport_id']) ?>&etudiant_id=<?= htmlspecialchars($discussion['etudiant_id']) ?>">
                            <div>
                                <div class="student-name"><?= htmlspecialchars($discussion['nom_etudiant'] . ', ' . $discussion['prenoms_etudiant']) ?></div>
                                <div class="report-title"><?= htmlspecialchars($discussion['titre_rapport']) ?></div>
                                <div style="font-size: 0.8em; color: var(--text-secondary);"><?= htmlspecialchars($discussion['nb_messages']) ?> messages</div>
                            </div>
                            <span class="status-badge status-in-progress">Discussion</span>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </aside>

    <main class="central-section">
        <div class="welcome-message">
            <h2>Bienvenue dans l'espace de discussion de la commission</h2>
            <p>Sélectionnez un rapport dans la liste à gauche pour commencer une discussion ou continuer une discussion existante.</p>
            <p>En tant que membre de la commission, vous pouvez :</p>
            <ul>
                <li>Discuter des rapports soumis par les étudiants</li>
                <li>Voter pour approuver ou désapprouver un rapport</li>
                <li>Ajouter des commentaires pour justifier votre décision</li>
            </ul>
            <p>Une fois qu'un rapport est approuvé par tous les membres de la commission, des encadrants lui seront automatiquement assignés.</p>
        </div>
    </main>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const allItems = document.querySelectorAll('.student-item');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();

            allItems.forEach(item => {
                const name = item.querySelector('.student-name').textContent.toLowerCase();
                const title = item.querySelector('.report-title')?.textContent.toLowerCase() || '';

                if (name.includes(searchTerm) || title.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Mobile navigation toggle
        const toggleLeftNav = document.getElementById('toggleLeftNav');
        const leftSection = document.querySelector('.left-section');

        if (toggleLeftNav && leftSection) {
            toggleLeftNav.addEventListener('click', function() {
                leftSection.classList.toggle('visible');
            });
        }
    });
</script>

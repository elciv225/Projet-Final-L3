<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1><?= $heading ?? 'Tableau de bord' ?></h1>
            <p class="header-subtitle">Vue d'ensemble de l'espace administrateur</p>
        </div>
        <div class="header-actions">
            <div class="search-container">
                <span class="search-icon">🔍</span>
                <input type="text" name="search" class="search-input" placeholder="Rechercher...">
            </div>
            <button class="btn btn-primary">
                <span>📊</span>
                Nouveau rapport
            </button>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-title">Total Étudiants</span>
                <span class="stat-icon blue"></span>
            </div>
            <div class="stat-value"><?= number_format($stats['totalEtudiants'] ?? 0, 0, ',', ' ') ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-title">Enseignants</span>
                <span class="stat-icon green"></span>
            </div>
            <div class="stat-value"><?= number_format($stats['totalEnseignants'] ?? 0, 0, ',', ' ') ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-title">Personnel Admin</span>
                <span class="stat-icon orange"></span>
            </div>
            <div class="stat-value"><?= number_format($stats['totalPersonnelAdmin'] ?? 0, 0, ',', ' ') ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-title">Cours Actifs</span>
                <span class="stat-icon red"></span>
            </div>
            <div class="stat-value"><?= number_format($stats['totalCoursActifs'] ?? 0, 0, ',', ' ') ?></div>
        </div>
    </div>

    <!-- Accès rapides -->
    <div class="form-section">
        <div class="section-header">
            <h3 class="section-title">Accès Rapides</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <a href="/espace-administrateur/gestion/personnel-administratif" class="nav-link-ajax btn btn-secondary" data-target="#content-area">
                    <span>👨‍💼</span>
                    Gérer le Personnel
                </a>
                <a href="/espace-administrateur/gestion/enseignants" class="nav-link-ajax btn btn-secondary" data-target="#content-area">
                    <span>👨‍🏫</span>
                    Gérer les Enseignants
                </a>
                <a href="/espace-administrateur/gestion/etudiants" class="nav-link-ajax btn btn-secondary" data-target="#content-area">
                    <span>👨‍🎓</span>
                    Gérer les Étudiants
                </a>
                <button class="btn btn-secondary">
                    <span>📊</span>
                    Rapports & Statistiques
                </button>
            </div>
        </div>
    </div>

    <!-- Activités récentes -->
    <div class="table-container">
        <div class="table-header">
            <h3 class="table-title">Activités Récentes</h3>
            <div class="header-actions">
                <button class="btn btn-secondary">Voir tout</button>
            </div>
        </div>

        <table class="table">
            <thead>
            <tr>
                <th>Utilisateur</th>
                <th>Action</th>
                <th>Module</th>
                <th>Date</th>
                <th>Statut</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($recentActivities)): ?>
                <tr>
                    <td colspan="5" class="text-center">Aucune activité récente</td>
                </tr>
            <?php else: ?>
                <?php foreach ($recentActivities as $index => $activity): ?>
                    <?php 
                        // Générer une couleur de fond aléatoire pour l'avatar
                        $colors = ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899'];
                        $bgColor = $colors[$index % count($colors)];

                        // Générer les initiales pour l'avatar
                        $initials = mb_substr($activity['nom'] ?? 'U', 0, 1) . mb_substr($activity['prenoms'] ?? 'U', 0, 1);

                        // Formater le nom complet
                        $fullName = $activity['nom'] . ' ' . $activity['prenoms'];

                        // Déterminer le statut
                        $status = $activity['statut'] ?? 'Terminé';
                        $statusClass = strtolower($status) === 'terminé' ? 'completed' : 'pending';
                    ?>
                    <tr>
                        <td>
                            <div class="customer-cell">
                                <div class="customer-avatar" style="background: <?= $bgColor ?>;"><?= $initials ?></div>
                                <div>
                                    <div class="order-id"><?= $fullName ?></div>
                                    <div style="font-size: 12px; color: var(--text-disabled);"><?= $activity['type_utilisateur'] ?? 'Utilisateur' ?></div>
                                </div>
                            </div>
                        </td>
                        <td><?= $activity['action'] ?? 'Action' ?></td>
                        <td><?= $activity['module'] ?? 'Module' ?></td>
                        <td><?= $activity['date_activite'] ? 'Le ' . date('d/m/Y H:i', strtotime($activity['date_activite'])) : 'Date inconnue' ?></td>
                        <td><span class="status-badge <?= $statusClass ?>"><span class="status-dot"></span><?= $status ?></span></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>

        <div class="table-footer">
            <div class="results-info">
                Affichage des 3 dernières activités
            </div>
            <div class="pagination">
                <button class="pagination-btn">‹</button>
                <button class="pagination-btn active">1</button>
                <button class="pagination-btn">2</button>
                <button class="pagination-btn">3</button>
                <button class="pagination-btn">›</button>
            </div>
        </div>
    </div>

    <!-- Informations système -->
    <div class="form-section">
        <div class="section-header">
            <h3 class="section-title">État du Système</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <div class="stat-value" style="font-size: 18px; color: var(--primary-color);"><?= $systemInfo['disponibilite'] ?? '99.9%' ?></div>
                    <label class="form-label">Disponibilité système</label>
                </div>
                <div class="form-group">
                    <div class="stat-value" style="font-size: 18px; color: var(--primary-color);"><?= $systemInfo['espace_utilise'] ?? '0 MB' ?></div>
                    <label class="form-label">Espace utilisé</label>
                </div>
                <div class="form-group">
                    <div class="stat-value" style="font-size: 18px; color: var(--primary-color);"><?= $systemInfo['connexions_actives'] ?? '0' ?></div>
                    <label class="form-label">Connexions actives</label>
                </div>
                <div class="form-group">
                    <div class="stat-value" style="font-size: 18px; color: var(--primary-color);"><?= $systemInfo['version_systeme'] ?? 'v1.0.0' ?></div>
                    <label class="form-label">Version système</label>
                </div>
            </div>
        </div>
    </div>
</main>

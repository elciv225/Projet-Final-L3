<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Espace Étudiant - Gestion de Rapport de Stage' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="data:,">
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/ajax.css">
    <link rel="stylesheet" href="/assets/css/espace-utilisateur.css">
</head>
<body>

    <!-- Contenu principal -->
    <main class="main-wrapper">
        <div class="main-container">
            <!-- Header avec données utilisateur -->
            <header class="dashboard-header">
                <div class="header-title">
                    <h1>Bonjour, <?= htmlspecialchars($utilisateur['prenoms'] ?? 'Utilisateur') ?> !</h1>
                    <p>Bienvenue dans votre espace étudiant</p>
                </div>
                <div class="header-actions">
                    <div class="notifications-bell">
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
                        </svg>
                        <span class="notification-count">3</span>
                    </div>
                    <div class="user-profile">
                        <div class="user-avatar">
                            <?= htmlspecialchars($utilisateur['initiales'] ?? 'U') ?>
                        </div>
                        <div class="user-info">
                            <div class="user-name"><?= htmlspecialchars($utilisateur['nom_complet'] ?? 'Utilisateur') ?></div>
                            <div class="user-email"><?= htmlspecialchars($utilisateur['email'] ?? '') ?></div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Grille du dashboard -->
            <div class="dashboard-grid">
                <!-- Widget principal avec informations personnalisées -->
                <div class="widget main-action">
                    <h2>Rapport de Stage</h2>
                    <p>Bonjour <?= htmlspecialchars($utilisateur['prenoms'] ?? 'Étudiant') ?>, 
                       gérez votre rapport de stage et suivez son avancement.</p>
                    <a class="nav-link-ajax" href="/soumission-rapport">Soumettre le rapport</a>
                </div>

                <!-- Widget de soutenance -->
                <div class="widget soutenance-widget">
                    <div class="widget-header">
                        <h3 class="widget-title">Ma Soutenance</h3>
                    </div>
                    <div class="soutenance-date">
                        <div class="day">15</div>
                        <div class="month-year">Décembre 2024</div>
                    </div>
                    <div class="jury-list">
                        <div class="jury-member">
                            <div class="jury-avatar">PR</div>
                            <div class="jury-info">
                                <div class="name">Prof. Martin</div>
                                <div class="role">Président du jury</div>
                            </div>
                        </div>
                        <div class="jury-member">
                            <div class="jury-avatar">DR</div>
                            <div class="jury-info">
                                <div class="name">Dr. Dubois</div>
                                <div class="role">Rapporteur</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Widget de progression -->
                <div class="widget progress-widget">
                    <div class="widget-header">
                        <h3 class="widget-title">Progression de mon rapport</h3>
                    </div>
                    <div class="status-tracker">
                        <div class="status-line" style="width: 60%;"></div>
                        <div class="status-step completed">
                            <div class="status-dot"></div>
                            <span>Dépôt</span>
                        </div>
                        <div class="status-step completed">
                            <div class="status-dot"></div>
                            <span>Validation</span>
                        </div>
                        <div class="status-step active">
                            <div class="status-dot"></div>
                            <span>Approbation</span>
                        </div>
                        <div class="status-step">
                            <div class="status-dot"></div>
                            <span>Soutenance</span>
                        </div>
                    </div>
                </div>

                <!-- Widget d'informations utilisateur -->
                <div class="widget">
                    <div class="widget-header">
                        <h3 class="widget-title">Mes Informations</h3>
                    </div>
                    <div class="user-details">
                        <p><strong>Matricule:</strong> <?= htmlspecialchars($utilisateur['id'] ?? 'N/A') ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($utilisateur['email'] ?? 'N/A') ?></p>
                        <p><strong>Type:</strong> <?= htmlspecialchars($utilisateur['type_utilisateur_id']  ?? 'N/A') ?></p>
                    </div>
                </div>

                <!-- Widget de raccourcis -->
                <div class="widget">
                    <div class="widget-header">
                        <h3 class="widget-title">Actions Rapides</h3>
                    </div>
                    <div class="quick-actions">
                        <button class="btn btn-primary">Modifier mes informations</button>
                        <button class="btn btn-secondary">Télécharger mon rapport</button>
                        <button class="btn btn-outline">Contacter un encadrant</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>

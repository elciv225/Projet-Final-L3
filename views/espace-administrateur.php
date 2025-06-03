<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/menu.css">
    <link rel="stylesheet" href="/assets/css/gestion.css">
    <link rel="stylesheet" href="/assets/css/ajax.css">
    <title><?= $title ?? 'Espace Administrateur' ?></title>
</head>
<body>
<div id="main-container" class="main-container">
    <!-- Menu Vertical -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo">Projet XXX</div>
            <input type="text" class="sidebar-search" placeholder="Rechercher...">
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">Principal</div>
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="/espace-administrateur"
                           class="nav-link-ajax <?= (!isset($currentSection)) ? 'active' : '' ?>"
                           data-target="#content-area">
                            <span class="nav-icon">📊</span>
                            <span>Tableau de bord</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/espace-administrateur/gestion/personnel-administratif"
                           class="nav-link-ajax <?= (isset($currentSection) && $currentSection === 'personnel-administratif') ? 'active' : '' ?>"
                           data-target="#content-area">
                            <span class="nav-icon">👨‍💼</span>
                            <span>Personnel Administratif</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/espace-administrateur/gestion/enseignants"
                           class="nav-link-ajax <?= (isset($currentSection) && $currentSection === 'enseignants') ? 'active' : '' ?>"
                           data-target="#content-area">
                            <span class="nav-icon">👨‍🏫</span>
                            <span>Enseignants</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/espace-administrateur/gestion/etudiants"
                           class="nav-link-ajax <?= (isset($currentSection) && $currentSection === 'etudiants') ? 'active' : '' ?>"
                           data-target="#content-area">
                            <span class="nav-icon">👨‍🎓</span>
                            <span>Étudiants</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link-ajax">
                            <span class="nav-icon">📊</span>
                            <span>Rapports</span>
                            <span class="nav-badge">3</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Configuration</div>
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <span class="nav-icon">🔗</span>
                            <span>Intégrations</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <span class="nav-icon">💳</span>
                            <span>Paiements</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <span class="nav-icon">⚙️</span>
                            <span>Paramètres</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <span class="nav-icon">🔒</span>
                            <span>Sécurité</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">États</div>
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <span class="nav-icon">📄</span>
                            <span>Bulletins</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <span class="nav-icon">💰</span>
                            <span>Règlements</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <span class="nav-icon">🧾</span>
                            <span>Reçus</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <span class="nav-icon">📋</span>
                            <span>Rapports de stage</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="user-section">
            <div class="user-info">
                <div class="user-avatar">KL</div>
                <div class="user-details">
                    <div class="username">KOUAKOU Laurent</div>
                    <div class="user-role">Administrateur</div>
                </div>
                <div class="user-menu">⋯</div>
            </div>
        </div>
    </aside>

    <!-- Zone de contenu principal - Mise à jour dynamiquement -->
    <div id="content-area" >
        <?php if (isset($sectionContent)): ?>
            <!-- Affichage d'une section spécifique lors du rechargement -->
            <?php
            $viewPath = BASE_PATH . '/views/' . $sectionContent . '.php';
            if (file_exists($viewPath)) {
                include $viewPath;
            } else {
                echo '<main class="main-content"><h1>Section non trouvée</h1></main>';
            }
            ?>
        <?php elseif (isset($heading)): ?>
            <!-- Contenu par défaut du dashboard -->
            <main class="main-content">
                <div class="page-header">
                    <div class="header-left">
                        <h1><?= $heading ?></h1>
                        <p class="header-subtitle">Vue d'ensemble de l'espace administrateur</p>
                    </div>
                </div>

                <!-- Statistiques rapides -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-title">Total Étudiants</span>
                            <span class="stat-icon blue"></span>
                        </div>
                        <div class="stat-value">1,247</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-title">Enseignants</span>
                            <span class="stat-icon green"></span>
                        </div>
                        <div class="stat-value">89</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-title">Personnel Admin</span>
                            <span class="stat-icon orange"></span>
                        </div>
                        <div class="stat-value">24</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-title">Cours Actifs</span>
                            <span class="stat-icon red"></span>
                        </div>
                        <div class="stat-value">156</div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-header">
                        <h3 class="section-title">Accès Rapides</h3>
                    </div>
                    <div class="section-content">
                        <p><?= $content ?? 'Sélectionnez une section dans le menu pour commencer.' ?></p>
                        <div class="form-grid" style="margin-top: 20px;">
                            <a href="/espace-administrateur/gestion/personnel-administratif"
                               class="nav-link-ajax btn btn-primary" data-target="#content-area">
                                <span>👨‍💼</span>
                                Gérer le Personnel
                            </a>
                            <a href="/espace-administrateur/gestion/enseignants" class="nav-link-ajax btn btn-secondary"
                               data-target="#content-area">
                                <span>👨‍🏫</span>
                                Gérer les Enseignants
                            </a>
                            <a href="/espace-administrateur/gestion/etudiants" class="nav-link-ajax btn btn-secondary"
                               data-target="#content-area">
                                <span>👨‍🎓</span>
                                Gérer les Étudiants
                            </a>
                        </div>
                    </div>
                </div>
            </main>
        <?php else: ?>
            <!-- Fallback si aucun contenu n'est défini -->
            <main class="main-content">
                <div class="page-header">
                    <div class="header-left">
                        <h1>Chargement...</h1>
                    </div>
                </div>
            </main>
        <?php endif; ?>
    </div>
</div>

<!-- Scripts -->
<script src="/assets/js/ajax.js"></script>
<script src="/assets/js/personnel-administratif.js"></script>
<script>
    // Script d'initialisation spécifique à l'espace admin
    document.addEventListener('DOMContentLoaded', function () {
        // Gestion de la recherche dans le menu
        const searchInput = document.querySelector('.sidebar-search');
        if (searchInput) {
            searchInput.addEventListener('input', function (e) {
                const searchTerm = e.target.value.toLowerCase();
                const navLinks = document.querySelectorAll('.nav-link, .nav-link-ajax');

                navLinks.forEach(link => {
                    const linkText = link.textContent.toLowerCase();
                    const navItem = link.closest('.nav-item');

                    if (linkText.includes(searchTerm)) {
                        navItem.style.display = '';
                    } else {
                        navItem.style.display = 'none';
                    }
                });
            });
        }

        // Gestion du menu utilisateur
        const userMenu = document.querySelector('.user-menu');
        if (userMenu) {
            userMenu.addEventListener('click', function () {
                showPopup('Menu utilisateur - À implémenter', 'info');
            });
        }
    });

    // Fonction pour recharger une section spécifique
    function reloadSection(sectionUrl) {
        const activeLink = document.querySelector(`[href="${sectionUrl}"]`);
        if (activeLink && activeLink.classList.contains('nav-link-ajax')) {
            activeLink.click();
        }
    }

    // Fonction utilitaire pour mettre à jour les badges de notification
    function updateNotificationBadge(selector, count) {
        const badge = document.querySelector(selector);
        if (badge) {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'inline' : 'none';
        }
    }
</script>
</body>
</html>
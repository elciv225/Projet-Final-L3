<?php

use System\Http\Response;

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/menu.css">
    <link rel="stylesheet" href="/assets/css/gestion.css">
    <link rel="stylesheet" href="/assets/css/ajax.css">
    <link rel="icon" href="data:,">
    <title><?= $title ?? 'Espace Administrateur' ?></title>
</head>
<body>
<div id="main-container" class="main-container">
    <!-- Menu Vertical Dynamique -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo">Projet XXX</div>
            <input type="text" class="sidebar-search" placeholder="Rechercher...">
        </div>

        <nav class="sidebar-nav scroll-custom">
            <div class="nav-section">
                <div class="nav-section-title">Principal</div>
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="/index"
                           class="nav-link-ajax <?= (!isset($currentSection)) ? 'active' : '' ?>"
                           data-target="#content-area">
                            <span class="nav-icon">📊</span>
                            <span>Tableau de bord</span>
                        </a>
                    </li>
                </ul>
            </div>

            <?php if (!empty($modules)): ?>
                <?php foreach ($modules as $categoryName => $categoryModules): ?>
                    <div class="nav-section">
                        <div class="nav-section-title"><?= ucfirst($categoryName) ?></div>
                        <ul class="nav-list">
                            <?php foreach ($categoryModules as $moduleName => $moduleConfig): ?>
                                <li class="nav-item">
                                    <a href="/index/<?= $categoryName ?>/<?= $moduleName ?>"
                                       class="nav-link-ajax <?= (isset($currentSection) && $currentSection === $moduleName && isset($currentCategory) && $currentCategory === $categoryName) ? 'active' : '' ?>"
                                       data-target="#content-area"
                                       data-module="<?= $moduleName ?>"
                                       data-category="<?= $categoryName ?>">
                                        <span class="nav-icon"><?= $moduleConfig['icone'] ?></span>
                                        <span><?= $moduleConfig['label'] ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
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
    <div id="content-area">

        <?php if (isset($moduleContent)): ?>

            <!-- Contenu d'un module spécifique -->
            <?php
            if ($moduleContent instanceof Response) {
                // Si c'est une Response, on doit l'afficher
                $moduleContent->send();
            } else {
                echo $moduleContent;
            }
            ?>
        <?php elseif (isset($heading)): ?>
            <!-- Contenu par défaut du dashboard -->
            <?php include BASE_PATH . '/views/admin/main-content.php'; ?>
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
<script src="/assets/js/traitements.js"></script>
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

        // Gestion des données de module pour le debugging
        window.adminModules = <?= json_encode($modules ?? []) ?>;

        // Fonction utilitaire pour accéder aux modules
        window.getModuleInfo = function(category, moduleName) {
            return window.adminModules[category] && window.adminModules[category][moduleName]
                ? window.adminModules[category][moduleName]
                : null;
        };
    });

    // Fonction pour recharger une section spécifique
    function reloadSection(sectionUrl) {
        const activeLink = document.querySelector(`[href="${sectionUrl}"]`);
        if (activeLink && activeLink.classList.contains('nav-link-ajax')) {
            activeLink.click();
        }
    }

    // Fonction pour recharger un module spécifique
    function reloadModule(category, moduleName) {
        const moduleUrl = `/index/${category}/${moduleName}`;
        reloadSection(moduleUrl);
    }

    // Fonction utilitaire pour mettre à jour les badges de notification
    function updateNotificationBadge(selector, count) {
        const badge = document.querySelector(selector);
        if (badge) {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'inline' : 'none';
        }
    }

    // Fonction pour ajouter dynamiquement un module au menu (pour les modules chargés à la volée)
    function addModuleToMenu(category, moduleName, moduleConfig) {
        const categorySection = document.querySelector(`[data-category="${category}"]`)?.closest('.nav-section');

        if (categorySection) {
            const navList = categorySection.querySelector('.nav-list');
            const newItem = document.createElement('li');
            newItem.className = 'nav-item';
            newItem.innerHTML = `
                <a href="/index/${category}/${moduleName}"
                   class="nav-link-ajax"
                   data-target="#content-area"
                   data-module="${moduleName}"
                   data-category="${category}">
                    <span class="nav-icon">${moduleConfig.icon}</span>
                    <span>${moduleConfig.label}</span>
                </a>
            `;
            navList.appendChild(newItem);
        }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/gsap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/ScrollTrigger.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/ScrollSmoother.min.js"></script>
</body>
</html>
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

        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">Principal</div>
                <ul class="nav-list">
                    <li class="nav-item">
                    <a href="/index" <!-- Assuming /index is the dashboard route -->
                       class="nav-link-ajax <?= (!isset($currentSection) && empty($_SERVER['REQUEST_URI']) || $_SERVER['REQUEST_URI'] === '/index' || $_SERVER['REQUEST_URI'] === '/') ? 'active' : '' ?>"
                           data-target="#content-area">
                            <span class="nav-icon">📊</span>
                            <span>Tableau de bord</span>
                        </a>
                    </li>
                </ul>
            </div>

        <?php
        // Instantiate services
        $authService = new \App\Services\AuthService();
        $menuService = new \App\Services\MenuService();
        $userMenuItems = [];
        $currentUser = $authService->getCurrentUser(); // Get full user data

        if ($authService->isAuthenticated()) {
            $groupeUtilisateurId = $authService->getUserGroupId();
            if ($groupeUtilisateurId) {
                $userMenuItems = $menuService->getMenuItemsForGroup($groupeUtilisateurId);
            }
        }
        // else: $userMenuItems remains empty, or you could load a default public menu if desired.

        // The MenuService returns a tree. We might need to adapt its output or this loop
        // if the old $modules structure was significantly different (e.g. explicit categories vs. parent_id).
        // For now, assuming $userMenuItems is a flat list of root items, each with a 'children' array.
        // Or, if menu items themselves represent categories, we iterate through them.
        // Let's assume the service returns menu items that can be categories (parent_id IS NULL)
        // and these categories have children.

        foreach ($userMenuItems as $menuItem): // $menuItem is a root menu item (category)
            if (empty($menuItem['children']) && empty($menuItem['url'])) continue; // Skip empty categories without direct link
        ?>
            <div class="nav-section">
                <div class="nav-section-title"><?= htmlspecialchars($menuItem['libelle']) ?></div>
                <ul class="nav-list">
                    <?php if (!empty($menuItem['url']) && empty($menuItem['children'])): // Root item itself is a link ?>
                         <li class="nav-item">
                            <a href="<?= htmlspecialchars($menuItem['url']) ?>"
                               class="nav-link-ajax <?= (isset($currentPath) && $currentPath === $menuItem['url']) ? 'active' : '' ?>"
                               data-target="#content-area">
                                <?php if (!empty($menuItem['icon'])): ?><span class="nav-icon"><?= $menuItem['icon'] ?></span><?php endif; ?>
                                <span><?= htmlspecialchars($menuItem['libelle']) ?></span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php foreach ($menuItem['children'] as $childItem): ?>
                        <li class="nav-item">
                            <a href="<?= htmlspecialchars($childItem['url']) ?>"
                               class="nav-link-ajax <?= (isset($currentPath) && $currentPath === $childItem['url']) ? 'active' : '' ?>"
                               data-target="#content-area">
                                <?php if (!empty($childItem['icon'])): ?><span class="nav-icon"><?= $childItem['icon'] ?></span><?php endif; ?>
                                <span><?= htmlspecialchars($childItem['libelle']) ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
        </nav>

        <div class="user-section">
            <div class="user-info">
            <div class="user-avatar"><?= $currentUser ? strtoupper(substr($currentUser['prenoms'], 0, 1) . substr($currentUser['nom'], 0, 1)) : 'GU' ?></div>
                <div class="user-details">
                <div class="username"><?= $currentUser ? htmlspecialchars($currentUser['prenoms'] . ' ' . $currentUser['nom']) : 'Guest User' ?></div>
                <div class="user-role">
                    <?php
                        if ($currentUser && $currentUser['groupe_utilisateur_id']) {
                            // In a real app, you'd fetch the group libelle from GroupeUtilisateurDAO
                            echo htmlspecialchars($currentUser['groupe_utilisateur_id']);
                        } else {
                            echo 'Non connecté';
                        }
                    ?>
                </div>
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
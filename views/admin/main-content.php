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
            <tr>
                <td>
                    <div class="customer-cell">
                        <div class="customer-avatar" style="background: #3B82F6;">JD</div>
                        <div>
                            <div class="order-id">Jean Dupont</div>
                            <div style="font-size: 12px; color: var(--text-disabled);">Enseignant</div>
                        </div>
                    </div>
                </td>
                <td>Ajout d'une note</td>
                <td>Évaluations</td>
                <td>Il y a 5 min</td>
                <td><span class="status-badge completed"><span class="status-dot"></span>Terminé</span></td>
            </tr>
            <tr>
                <td>
                    <div class="customer-cell">
                        <div class="customer-avatar" style="background: #10B981;">MT</div>
                        <div>
                            <div class="order-id">Marie Traoré</div>
                            <div style="font-size: 12px; color: var(--text-disabled);">Administrateur</div>
                        </div>
                    </div>
                </td>
                <td>Création d'un compte</td>
                <td>Personnel</td>
                <td>Il y a 12 min</td>
                <td><span class="status-badge completed"><span class="status-dot"></span>Terminé</span></td>
            </tr>
            <tr>
                <td>
                    <div class="customer-cell">
                        <div class="customer-avatar" style="background: #F59E0B;">AK</div>
                        <div>
                            <div class="order-id">Amélie Kouassi</div>
                            <div style="font-size: 12px; color: var(--text-disabled);">Étudiant</div>
                        </div>
                    </div>
                </td>
                <td>Inscription à un cours</td>
                <td>Inscriptions</td>
                <td>Il y a 1h</td>
                <td><span class="status-badge pending"><span class="status-dot"></span>En attente</span></td>
            </tr>
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
                    <div class="stat-value" style="font-size: 18px; color: var(--primary-color);">99.8%</div>
                    <label class="form-label">Disponibilité système</label>
                </div>
                <div class="form-group">
                    <div class="stat-value" style="font-size: 18px; color: var(--primary-color);">2.4 GB</div>
                    <label class="form-label">Espace utilisé</label>
                </div>
                <div class="form-group">
                    <div class="stat-value" style="font-size: 18px; color: var(--primary-color);">847</div>
                    <label class="form-label">Connexions actives</label>
                </div>
                <div class="form-group">
                    <div class="stat-value" style="font-size: 18px; color: var(--primary-color);">v2.1.3</div>
                    <label class="form-label">Version système</label>
                </div>
            </div>
        </div>
    </div>
</main>
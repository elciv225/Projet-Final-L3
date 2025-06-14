<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Espace Commission</h1>
            <p class="header-subtitle">Analyse et validation des rapports de stage</p>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header"><span>Rapports en attente</span></div>
            <div class="stat-value">8</div>
        </div>
        <div class="stat-card">
            <div class="stat-header"><span>Rapports Validés</span></div>
            <div class="stat-value">42</div>
        </div>
        <div class="stat-card">
            <div class="stat-header"><span>Rapports Refusés</span></div>
            <div class="stat-value">5</div>
        </div>
        <div class="stat-card">
            <div class="stat-header"><span>Taux de Validation</span></div>
            <div class="stat-value">89%</div>
        </div>
    </div>

    <!-- Liste des rapports à traiter -->
    <div class="table-container">
        <div class="table-header">
            <h3 class="table-title">Rapports à analyser</h3>
            <button class="btn btn-secondary">Filtrer</button>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>Étudiant</th>
                <th>Titre du rapport</th>
                <th>Date Soumission</th>
                <th>Statut Vote</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Amélie Kouassi</td>
                <td>Développement d'une app...</td>
                <td>12/06/2025</td>
                <td><span class="status-badge pending"><span class="status-dot"></span>En attente</span></td>
                <td class="actions-cell">
                    <button class="btn btn-secondary" onclick="openModal()">📊 Analyser</button>
                </td>
            </tr>
            <tr>
                <td>Marc Petit</td>
                <td>Optimisation de BDD...</td>
                <td>11/06/2025</td>
                <td><span class="status-badge pending"><span class="status-dot"></span>En attente</span></td>
                <td class="actions-cell">
                    <button class="btn btn-secondary" onclick="openModal()">📊 Analyser</button>
                </td>
            </tr>
            <tr>
                <td>Julien Bernard</td>
                <td>Migration d'une infra cloud...</td>
                <td>09/06/2025</td>
                <td><span class="status-badge validated"><span class="status-dot"></span>Validé (4/5)</span></td>
                <td class="actions-cell">
                    <button class="btn btn-secondary" disabled>Terminé</button>
                </td>
            </tr>
            <tr>
                <td>Fanta Diop</td>
                <td>Cybersécurité et audits...</td>
                <td>05/06/2025</td>
                <td><span class="status-badge rejected"><span class="status-dot"></span>Refusé (1/5)</span></td>
                <td class="actions-cell">
                    <button class="btn btn-secondary" disabled>Terminé</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</main>
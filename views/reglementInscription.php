<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Projet XXX' ?></title>

    <style>
        @import url("https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap");

        :root {
            /* Configuration du thème */
            color-scheme: light dark;
            /* Couleurs de base */
            --primary-color: #1A5E63;
            --secondary-color: #FFC857;
            /* Couleurs des composants - Mode clair */
            --background-primary: #F9FAFA;
            --background-secondary: #F7F9FA;
            --background-input: #ECF0F1;
            /* Text */
            --text-primary: #050E10;
            --text-secondary: #0A1B20;
            --text-disabled: #BDC3C7;
            /* Boutons */
            --button-primary: #1A5E63;
            --button-primary-hover: #15484B;
            --button-secondary: #FFC857;
            --button-secondary-hover: #FCCF6C;
            --button-disabled: #E0E6E8;
            /* États */
            --success: rgb(102 187 106 / 55%);
            --warning: rgb(255 193 7 / 55%);
            --error: rgb(239 83 80 / 55%);
            --info: rgb(100 181 246 / 55%);
            /* Bordures */
            --border-light: #87999A;
            --border-medium: #6B7B7C;
            --border-dark: #162122;
            /* Degradés */
            --gradient-hover: linear-gradient(to bottom,
            rgb(240 240 240 / 80%), rgb(220 220 220 / 90%)
            );
            /* Overlays et shadows */
            --overlay: rgb(44 62 80 / 10%);
            --shadow: rgb(0 0 0 / 5%) 0px 1px 2px 0px;
            --shadow-sm: 0 1px 3px rgb(0 0 0 / 10%);
            --shadow-md: 0 4px 6px rgb(0 0 0 / 10%);
            --shadow-lg: 0 10px 15px rgb(0 0 0 / 10%);
            /* Inputs */
            --input-border: #1A5E63;
            --input-focus: rgb(26 94 99 / 20%);
            /* Liens */
            --link-color: #2A8F96;
            --link-hover: #1A5E63;
        }

        /* Mode sombre */
        @media (prefers-color-scheme: dark) {
            :root {
                --primary-color: #1A5E63;
                --secondary-color: #FFC857;
                --background-primary: #1B1B1B;
                --background-secondary: #202020;
                --background-input: #2D2D2D;
                --text-primary: #EAEAEA;
                --text-secondary: #CFCFCF;
                --text-disabled: #7F8C8D;
                --button-primary: #1A5E63;
                --button-primary-hover: #15484B;
                --button-secondary: #FFC857;
                --button-secondary-hover: #F3BA44;
                --button-disabled: #4F5B5C;
                --success: rgb(39 174 96 / 40%);
                --warning: rgb(243 156 18 / 40%);
                --error: rgb(231 76 60 / 40%);
                --info: rgb(52 152 219 / 40%);
                --border-light: #2C3E50;
                --border-medium: #34495E;
                --border-dark: #1A252F;
                --gradient-hover: linear-gradient(to bottom,
                rgb(30 30 30 / 80%), rgb(15 15 15 / 90%)
                );
                --overlay: rgb(0 0 0 / 50%);
                --shadow: rgb(0 0 0 / 10%) 0px 1px 2px 0px;
                --shadow-sm: 0 1px 3px rgb(0 0 0 / 30%);
                --shadow-md: 0 4px 6px rgb(0 0 0 / 30%);
                --shadow-lg: 0 10px 15px rgb(0 0 0 / 30%);
                --input-border: #1A5E63;
                --input-focus: rgb(26 94 99 / 20%);
                --link-color: #1A5E63;
                --link-hover: #15484B;
            }
        }

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            display: flex;
            flex-direction: row;
            min-height: 100vh;
            max-width: 100vw;
            background: var(--background-secondary);
            transition: background-color 0.5s ease;
        }

        .main-container {
            display: flex;
            width: 100%;
        }

        /* Sidebar moderne */
        .sidebar {
            width: 240px;
            background: var(--background-primary);
            border-right: 1px solid #E5E7EB;
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            z-index: 1000;
            box-shadow: var(--shadow-sm);
        }

        .sidebar-header {
            padding: 24px 20px;
            border-bottom: 1px solid #E5E7EB;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 20px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 24px;
        }

        .logo::before {
            content: "📦";
            font-size: 24px;
        }

        .sidebar-search {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            background: var(--background-secondary);
            color: var(--text-primary);
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .sidebar-search:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px var(--input-focus);
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 0;
            overflow-y: auto;
        }

        .nav-section {
            margin-bottom: 32px;
        }

        .nav-section-title {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--text-disabled);
            margin: 0 20px 12px;
            letter-spacing: 0.5px;
        }

        .nav-list {
            list-style: none;
        }

        .nav-item {
            margin: 0 12px 4px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            background: var(--background-secondary);
            color: var(--text-primary);
        }

        .nav-link.active {
            background: var(--primary-color);
            color: white;
        }

        .nav-icon {
            font-size: 16px;
            width: 20px;
            text-align: center;
        }

        .nav-badge {
            background: #EF4444;
            color: white;
            font-size: 11px;
            padding: 2px 6px;
            border-radius: 10px;
            margin-left: auto;
        }

        .user-section {
            border-top: 1px solid #E5E7EB;
            padding: 16px 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border-radius: 8px;
            transition: background 0.2s ease;
            cursor: pointer;
        }

        .user-info:hover {
            background: var(--background-secondary);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 12px;
        }

        .user-details {
            flex: 1;
            min-width: 0;
        }

        .username {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            font-size: 12px;
            color: var(--text-disabled);
        }

        .user-menu {
            color: var(--text-disabled);
            font-size: 16px;
        }

        /* Main Content */
        .main-content {
            margin-left: 240px;
            flex: 1;
            padding: 24px 32px;
            background: var(--background-secondary);
            min-height: 100vh;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }

        .header-left h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .header-subtitle {
            color: var(--text-disabled);
            font-size: 14px;
        }

        .header-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .search-container {
            position: relative;
            width: 320px;
        }

        .search-input {
            width: 100%;
            padding: 12px 16px 12px 40px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            background: var(--background-primary);
            color: var(--text-primary);
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px var(--input-focus);
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-disabled);
            font-size: 16px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 16px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;

        }

        .btn-primary {
            background: var(--button-primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--button-primary-hover);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-secondary {
            background: var(--background-primary);
            color: var(--text-primary);
            border: 1px solid #E5E7EB;
        }

        .btn-secondary:hover {
            background: var(--background-secondary);
            border-color: var(--border-medium);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--background-primary);
            padding: 24px;
            border-radius: 12px;
            border: 1px solid #E5E7EB;
            box-shadow: var(--shadow-sm);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .stat-title {
            font-size: 14px;
            color: var(--text-disabled);
            font-weight: 500;
        }

        .stat-icon {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .stat-icon.blue { background: #3B82F6; }
        .stat-icon.orange { background: #F59E0B; }
        .stat-icon.green { background: #10B981; }
        .stat-icon.red { background: #EF4444; }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-primary);
        }

        /* Form Sections */
        .form-section {
            background: var(--background-primary);
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            margin-bottom: 24px;
            box-shadow: var(--shadow-sm);
        }

        .section-header {
            padding: 20px 24px;
            border-bottom: 1px solid #E5E7EB;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .add-btn {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 16px;
        }

        .add-btn:hover {
            background: var(--button-primary-hover);
            transform: scale(1.1);
        }

        .section-content {
            padding: 24px;
        }

        .form-grid {
            display: grid; /* Changed from flex to grid for better control of multiple columns */
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); /* Responsive columns */
            gap: 20px;
        }

        /* Moyenne semestre */
        .double-width {
            grid-column: span 2; /* occupe deux colonnes */
        }


        /* Réduit la largeur du champ */
        .small-width {
            width: 200px; /* tu peux ajuster la valeur selon ton besoin */
        }

        /* Aligne le champ à droite */
        .right-align {
            margin-left: auto; /* pousse l’élément vers la droite */
        }

        .mb-20 {
            margin-bottom: 20px; /* Ajuste la valeur selon l’espacement souhaité */
        }



        .form-group {
            position: relative;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            background: var(--background-primary);
            color: var(--text-primary);
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px var(--input-focus);
        }

        .form-label {
            position: absolute;
            left: 16px;
            top: 12px;
            font-size: 14px;
            color: var(--text-disabled);
            pointer-events: none;
            transition: all 0.2s ease;
            background: var(--background-primary);
            padding: 0 4px;
            z-index: 1; /* Ensure label is above input when transitioning */
        }

        .form-input:focus ~ .form-label,
        .form-input:not(:placeholder-shown) ~ .form-label {
            top: -8px;
            left: 12px;
            font-size: 12px;
            color: var(--primary-color);
        }

        /* --- Styles pour les Listes Déroulantes (Select) --- */
        .form-group select.form-input {
            appearance: none; /* Supprime l'apparence native du select */
            -webkit-appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg fill="%23BDC3C7" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/></svg>'); /* Flèche personnalisée SVG */
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 40px; /* Espace pour la flèche */
        }

        /* --- Styles pour les Boutons Radio --- */
        .radio-group {
            display: flex;
            align-items: center;
            gap: 20px; /* Espace entre les options radio */
            margin-top: 15px; /* Marge pour séparer des champs précédents */
        }

        .radio-option {
            display: flex;
            align-items: center;
            position: relative; /* Pour le positionnement des labels internes */
        }

        .radio-option input[type="radio"] {
            /* Cacher l'input radio natif */
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
            pointer-events: none;
        }

        .radio-option label {
            display: flex;
            align-items: center;
            cursor: pointer;
            font-size: 14px;
            color: var(--text-primary);
            position: relative; /* Pour le pseudo-élément */
            padding-left: 28px; /* Espace pour le cercle personnalisé */
            transition: color 0.2s ease;
        }

        .radio-option label::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            border: 2px solid var(--text-disabled); /* Couleur de la bordure du cercle */
            border-radius: 50%;
            background: var(--background-primary);
            transition: all 0.2s ease;
        }

        .radio-option input[type="radio"]:checked + label::before {
            border-color: var(--primary-color);
            background: var(--primary-color); /* Couleur de fond quand coché */
        }

        .radio-option label::after {
            content: '';
            position: absolute;
            left: 5px;
            top: 50%;
            transform: translateY(-50%) scale(0);
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--background-primary); /* Couleur du point intérieur */
            transition: transform 0.2s ease;
        }

        .radio-option input[type="radio"]:checked + label::after {
            transform: translateY(-50%) scale(1);
        }

        .radio-option label:hover::before {
            border-color: var(--primary-color); /* Bordure au survol */
        }


        /* Table moderne */
        .table-container {
            background: var(--background-primary);
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .table-header {
            padding: 20px 24px;
            border-bottom: 1px solid #E5E7EB;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .table-tabs {
            display: flex;
            gap: 24px;
        }

        .tab {
            padding: 8px 0;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-disabled);
            border-bottom: 2px solid transparent;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .tab.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background: var(--background-secondary);
            padding: 16px 24px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-disabled);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #E5E7EB;
        }

        .table td {
            padding: 16px 24px;
            border-bottom: 1px solid #F3F4F6;
            font-size: 14px;
            color: var(--text-primary);
        }

        .table tbody tr:hover {
            background: var(--background-secondary);
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .order-id {
            font-weight: 600;
            color: var(--text-primary);
        }

        .customer-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .customer-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 12px;
            color: white;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 16px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-badge.pending {
            background: #FEF3C7;
            color: #D97706;
        }

        .status-badge.completed {
            background: #D1FAE5;
            color: #059669;
        }

        .status-badge.refunded {
            background: #FEE2E2;
            color: #DC2626;
        }

        .status-badge.paid {
            background: #D1FAE5;
            color: #059669;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .status-badge.pending .status-dot { background: #D97706; }
        .status-badge.completed .status-dot { background: #059669; }
        .status-badge.refunded .status-dot { background: #DC2626; }
        .status-badge.paid .status-dot { background: #059669; }

        .table-actions {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 6px;
            background: transparent;
            color: var(--text-disabled);
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .action-btn:hover {
            background: var(--background-secondary);
            color: var(--text-primary);
        }

        .action-btn.delete:hover {
            background: #FEE2E2;
            color: #DC2626;
        }

        .checkbox {
            width: 16px;
            height: 16px;
            accent-color: var(--primary-color);
        }

        /* Pagination */
        .table-footer {
            padding: 16px 24px;
            border-top: 1px solid #E5E7EB;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .results-info {
            font-size: 14px;
            color: var(--text-disabled);
        }

        .pagination {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .pagination-btn {
            width: 32px;
            height: 32px;
            border: 1px solid #E5E7EB;
            border-radius: 6px;
            background: var(--background-primary);
            color: var(--text-primary);
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .pagination-btn:hover {
            background: var(--background-secondary);
        }

        .pagination-btn.active {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        /* Note Cards */
        .note-cards {
            display: grid;
            gap: 16px;
        }

        .note-card {
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            padding: 16px;
            background: var(--background-secondary);
            position: relative;
        }

        .note-card .remove-note {
            position: absolute;
            top: 25px;
            right: 30px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #FEE2E2;
            color: #DC2626;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        /* Bouton + - */
        .field-row {
            display: flex;
            align-items: flex-end;
            gap: 8px;
            margin-bottom: 10px;
        }

        .form-group {
            flex: 1;
        }

        .small-round-btn {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            font-size: 16px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s ease;
        }

        .small-round-btn:hover {
            background-color: #1b5fbd;
        }



        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 60px;
            }

            .main-content {
                margin-left: 60px;
                padding: 16px;
            }

            .sidebar-search,
            .nav-section-title,
            .nav-link span,
            .user-details {
                display: none;
            }

            .nav-link {
                justify-content: center;
                padding: 12px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .page-header {
                flex-direction: column;
                gap: 16px;
                align-items: stretch;
            }

            .search-container {
                width: 100%;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<div class="main-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo">Projet XXX</div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">Main</div>
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <span class="nav-icon">📊</span>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <span class="nav-icon">📦</span>
                            <span>Products</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link active">
                            <span class="nav-icon">🛍️</span>
                            <span>Orders</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <span class="nav-icon">👥</span>
                            <span>Customers</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <span class="nav-icon">📊</span>
                            <span>Reports</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Settings</div>
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <span class="nav-icon">🔗</span>
                            <span>Marketplace Sync</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <span class="nav-icon">💳</span>
                            <span>Payment Gateways</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <span class="nav-icon">⚙️</span>
                            <span>Settings</span>
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

    <!-- Main Content -->
    <main class="main-content">
        <div class="page-header">
            <div class="header-left">
                <h1>Reglement Frais D'Inscription</h1>
            </div>
        </div>


        <div class="form-group small-width right-align  mb-20">
            <select class="form-input" id="annee-academique" name="annee-academique">
                <option value="">Année-Académique</option>
                <option value=""></option>
                <option value=""></option>
            </select>
        </div>
        <!-- Informations de l'etudiant -->
        <div class="form-section">
            <div class="section-header">
                <h3 class="section-title">Information Etudiant</h3>
            </div>
            <div class="section-content">
                <div class="form-grid">
                    <div class="form-group">
                        <input type="text" name="studentNumber" class="form-input" placeholder=" " id="student-number">
                        <label class="form-label" for="student-number">Numero Carte d'Etudiant</label>
                    </div>
                    <div class="form-group">
                        <input type="text" name="studentLastname" class="form-input" placeholder=" " id="student-lastname">
                        <label class="form-label" for="student-lastname">Nom</label>
                    </div>
                    <div class="form-group">
                        <input type="text" name="studentFirstname" class="form-input" placeholder=" " id="student-firstname">
                        <label class="form-label" for="student-firstname">Prénoms</label>
                    </div>
                    <div class="form-group">
                        <input type="text" name="niveauEtude" class="form-input" placeholder=" " id="niveauEtude">
                        <label class="form-label" for="niveauEtude">Niveau d'Etude</label>
                    </div>
                </div>

            </div>
        </div>

        <!-- Reglements des frais d'inscription -->
        <div class="form-section">
            <div class="section-header">
                <h3 class="section-title">Reglements Frais D'Inscription</h3>
            </div>
            <div class="section-content">
                <div class="form-grid">
                    <div class="form-group">
                        <input type="montantpaye" name="montantpaye" class="form-input" placeholder=" " id="montantpaye">
                        <label class="form-label" for="montantpaye">Montant Payé</label>
                    </div>

                    <div class="form-group">
                        <input type="date" name="datereglement" class="form-input" placeholder=" " id="datereglement">
                        <label class="form-label" for="datereglement">Date de reglement</label>
                    </div>
                    <div class="form-group">
                        <input type="text" name="totalpaye" class="form-input" placeholder=" " id="totalpaye">
                        <label class="form-label" for="totalpaye">Total Payé</label>
                    </div>
                    <div class="form-group">
                        <input type="text" name="restepaye" class="form-input" placeholder=" " id="restepaye">
                        <label class="form-label" for="restepaye">Reste à Payé</label>
                    </div>
                    <div class="form-group">
                        <input type="text" name="montantapaye" class="form-input" placeholder=" " id="montantapaye">
                        <label class="form-label" for="montantapaye">Montant à Payé</label>
                    </div>
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; padding: 20px 0;">
            <button class="btn btn-primary" id="btnValider">Valider</button>
        </div>


        <!-- Orders Table -->
        <div class="table-container">
            <div class="table-header">
                <h3 class="table-title">Historique</h3>
                <div class="header-actions">
                    <div class="search-container">
                        <span class="search-icon">🔍</span>
                        <input type="text" id="searchInput" class="search-input" placeholder="Rechercher par ...">
                    </div>


                </div>
                <div class="header-actions">
                    <button id="btnExportPDF" class="btn btn-secondary">🕐 Exporter en PDF</button>
                    <button id="btnExportExcel" class="btn btn-secondary">📤 Exporter sur Excel</button>
                    <button id="btnPrint" class="btn btn-secondary">📊 Imprimer</button>
                    <button class="btn btn-primary" id="btnSupprimerSelection">Supprimer</button>
                </div>
            </div>

            <div style="padding: 0 24px; border-bottom: 1px solid #E5E7EB;">
                <div class="table-tabs">
                    <div class="tab active">Tout selectioner</div>
                    <div class="tab"></div>
                    <div class="tab"></div>
                    <div class="tab"></div>
                    <div class="tab"></div>
                </div>
            </div>

            <table class="table">
                <thead>
                <tr>
                    <th><input type="checkbox" class="checkbox"></th>
                    <th>Numero Carte d'Etudiant</th>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Niveau D'Etude</th>
                    <th>Montant Payé</th>
                    <th>Date de Règlement</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>

            <div class="table-footer">
                <div class="results-info">
                    Showing 1-9 of 240 entries
                </div>
                <div class="pagination">
                    <button class="pagination-btn">‹</button>
                    <button class="pagination-btn active">1</button>
                    <button class="pagination-btn">2</button>
                    <button class="pagination-btn">3</button>
                    <span>...</span>
                    <button class="pagination-btn">12</button>
                    <button class="pagination-btn">›</button>
                </div>
            </div>
        </div>
    </main>
</div>


<script>
    let rowToEdit = null;

    document.getElementById('btnValider').addEventListener('click', function () {
        const numerocarte = document.getElementById('student-number').value.trim();
        const nom = document.getElementById('student-lastname').value.trim();
        const prenom = document.getElementById('student-firstname').value.trim();
        const niveauetude = document.getElementById('niveauEtude').value.trim();
        const montantpaye = document.getElementById('montantpaye').value;
        const datereglement = document.getElementById('datereglement').value.trim();

        // === Vérifications ===

        if (!numerocarte) {
            alert("Veuillez remplir ce champs !");
            return;
        }



        // Vérification de l’unicité du matricule si on ajoute
        if (!rowToEdit) {
            const lignes = document.querySelectorAll('.table tbody tr');
            for (let ligne of lignes) {
                const cellulenumerocarte = ligne.children[1]?.textContent;
                if (cellulenumerocarte === numerocarte) {
                    alert("Ce numero de carte existe déjà !");
                    return;
                }
            }
        }

        if (rowToEdit) {
            // Modification
            rowToEdit.cells[1].textContent = numerocarte;
            rowToEdit.cells[2].textContent = nom;
            rowToEdit.cells[3].textContent = prenom;
            rowToEdit.cells[4].textContent = niveauetude;
            rowToEdit.cells[5].textContent = montantpaye;
            rowToEdit.cells[6].textContent = datereglement;

            rowToEdit = null;
            document.getElementById('btnValider').textContent = 'Valider';
        } else {
            // Ajout d'une nouvelle ligne
            const tbody = document.querySelector('.table tbody');
            const newRow = document.createElement('tr');

            newRow.innerHTML = `
            <td><input type="checkbox" class="checkbox"></td>
            <td>${numerocarte}</td>
            <td>${nom}</td>
            <td>${prenom}</td>
            <td>${niveauetude}</td>
            <td>${montantpaye}</td>
            <td>${datereglement}</td>
            <td>
                <div class="table-actions">
                    <button class="action-btn edit-btn">✏️</button>
                    <button class="action-btn delete-btn">🗑️</button>
                </div>
            </td>
        `;

            tbody.appendChild(newRow);
        }

        // Nettoyage
        document.querySelectorAll('.form-input').forEach(input => input.value = '');
    });

    // === Supprimer une ligne ===
    document.querySelector('.table tbody').addEventListener('click', function (e) {
        if (e.target.classList.contains('delete-btn')) {
            const row = e.target.closest('tr');
            row.remove();
        }
    });

    // === Modifier une ligne ===
    document.querySelector('.table tbody').addEventListener('click', function (e) {
        if (e.target.classList.contains('edit-btn')) {
            const row = e.target.closest('tr');
            rowToEdit = row;

            document.getElementById('student-number').value = row.cells[1].textContent;
            document.getElementById('student-lastname').value = row.cells[2].textContent;
            document.getElementById('student-firstname').value = row.cells[3].textContent;
            document.getElementById('niveauEtude').value = row.cells[4].textContent;
            document.getElementById('montantpaye').value = row.cells[5].textContent;
            document.getElementById('datereglement').value = row.cells[6].textContent;

            document.getElementById('btnValider').textContent = 'Mettre à jour';
        }
    });

    // === Suppression multiple ===
    document.getElementById('btnSupprimerSelection').addEventListener('click', function () {
        const checkboxes = document.querySelectorAll('.table tbody .checkbox:checked');
        if (checkboxes.length === 0) {
            alert("Veuillez cocher au moins une ligne !");
            return;
        }

        if (confirm("Confirmez-vous la suppression des lignes sélectionnées ?")) {
            checkboxes.forEach(checkbox => {
                const row = checkbox.closest('tr');
                row.remove();
            });
        }
    });

    //Barre de recherche
    document.getElementById('searchInput').addEventListener('keyup', function () {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('.table tbody tr');

        rows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            if (rowText.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });


    function addField(containerId, fieldName, label) {
        const container = document.getElementById(containerId);
        const newRow = document.createElement('div');
        newRow.className = 'field-row';

        newRow.innerHTML = `
    <div class="form-group">
      <input type="text" name="${fieldName}" class="form-input" placeholder=" ">
      <label class="form-label">${label}</label>
    </div>
    <button type="button" class="small-round-btn" onclick="addField('${containerId}', '${fieldName}', '${label}')">+</button>
    <button type="button" class="small-round-btn" onclick="removeField(this)">−</button>
  `;

        container.appendChild(newRow);
    }

    function removeField(button) {
        const row = button.parentElement;
        row.remove();
    }

</script>


<!-- Bibliothèque pour Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<!-- Bibliothèque pour PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
    // 🕐 Exporter en PDF
    document.getElementById("btnExportPDF").addEventListener("click", async function () {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        const table = document.querySelector(".table");
        const rows = table.querySelectorAll("tr");

        let y = 10;
        doc.setFontSize(12);
        doc.text("Liste du personnel", 10, y);
        y += 10;

        rows.forEach(row => {
            let x = 10;
            row.querySelectorAll("th, td").forEach(cell => {
                const text = cell.innerText || cell.textContent;
                doc.text(text.trim(), x, y);
                x += 30; // Espace entre les colonnes
            });
            y += 10;
        });

        doc.save("personnel.pdf");
    });

    // 📤 Exporter sur Excel
    document.getElementById("btnExportExcel").addEventListener("click", function () {
        const table = document.querySelector(".table");
        const wb = XLSX.utils.table_to_book(table, { sheet: "Personnel" });
        XLSX.writeFile(wb, "personnel.xlsx");
    });

    // 📊 Imprimer
    document.getElementById("btnPrint").addEventListener("click", function () {
        const tableHTML = document.querySelector(".table").outerHTML;
        const newWindow = window.open("", "", "width=900,height=700");
        newWindow.document.write(`
            <html>
            <head>
                <title>Impression</title>
                <style>
                    table { width: 100%; border-collapse: collapse; }
                    th, td { border: 1px solid #000; padding: 8px; text-align: left; }
                </style>
            </head>
            <body>
                <h2>Historique</h2>
                ${tableHTML}
            </body>
            </html>
        `);
        newWindow.document.close();
        newWindow.print();
    });
</script>

</body>
</html>
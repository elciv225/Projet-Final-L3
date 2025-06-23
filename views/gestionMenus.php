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
            flex: 1;
            height: 100vh;
            padding: 20px;
            overflow: hidden;
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



        h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 20px;
            text-align: center;
        }

        .form-group-inline {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .form-group-inline label {
            font-size: 16px;
            font-weight: 500;
            color: var(--text-primary);
            white-space: nowrap; /* Prevent label from wrapping */
        }

        .select-wrapper {
            position: relative;
            flex-grow: 1;
            width: 20px; /* Réduction de la taille visible du champ */

        }

        .custom-select {
            width: 50%; /* ou 150px ou auto selon ton besoin */
            padding: 10px 15px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            background: var(--background-primary);
            color: var(--text-primary);
            font-size: 14px;
            appearance: none; /* Hide default arrow */
            -webkit-appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg fill="%23BDC3C7" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/></svg>');
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 40px; /* Space for custom arrow */
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .custom-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px var(--input-focus);
        }

        /* Table styles */
        .permissions-table-container {
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            overflow: hidden;
            margin-top: 30px;
            box-shadow: var(--shadow-md);
            margin: 30px auto; /* Centrage horizontal + espace vertical */
            width: 95%; /* ou max-width: 1200px; pour contrôler la largeur */
        }

        .permissions-table {
            width: 100%;
            border-collapse: collapse;
            text-align: center; /* Center content in table cells */

        }

        .permissions-table thead th {
            background: var(--background-secondary);
            padding: 12px 15px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-disabled);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #E5E7EB;
        }

        .permissions-table tbody td {
            border-bottom: 1px solid #F3F4F6;
            font-size: 14px;
            color: var(--text-primary);
            padding: 20px 15px; /* augmente la hauteur des lignes */
        }

        .permissions-table tbody tr:hover {
            background: var(--background-secondary);
        }

        .permissions-table tbody tr:last-child td {
            border-bottom: none;
        }

        .permissions-table th:first-child,
        .permissions-table td:first-child {
            text-align: left; /* Align first column to the left */
            padding-left: 20px;
        }

        .checkbox-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .checkbox {
            width: 18px;
            height: 18px;
            accent-color: var(--primary-color);
            cursor: pointer;
            margin: 0; /* Remove default margin */
        }

        .total-select-header {
            text-align: right; /* "Tout Sélectionner" on the right */
            padding-bottom: 10px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-primary);
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 8px;
            padding-right: 20px; /* Align with table padding */
        }

        .footer-buttons {
            display: flex;
            justify-content: flex-end;
            margin-top: 30px;
            gap: 15px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px; /* Adjust padding for bigger buttons */
            border: none;
            border-radius: 8px;
            font-size: 15px; /* Slightly larger font */
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

        h1 {
            margin-bottom: clamp(30px, 5vw, 80px);
        }



        /* Responsive adjustments */
        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }

            .form-group-inline {
                flex-direction: column;
                align-items: flex-start;
            }

            .custom-select {
                width: 100%;
            }

            .permissions-table thead th,
            .permissions-table tbody td {
                padding: 10px;
                font-size: 12px;
            }

            .permissions-table th:first-child,
            .permissions-table td:first-child {
                padding-left: 10px;
            }

            .total-select-header {
                padding-right: 10px;
            }

            .footer-buttons {
                flex-direction: column;
                gap: 10px;
            }



            .btn {
                width: 100%;
                justify-content: center;
            }
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

            <h1>Gestion des menus</h1>

            <div class="container">
                <div class="form-group-inline">
                    <label for="userGroupSelect">Groupe utilisateur:</label>
                    <div class="select-wrapper">
                        <select id="userGroupSelect" class="custom-select">
                            <option value="">Sélectionner un groupe</option>
                            <option value="admin">Administrateur</option>
                            <option value="editor">Éditeur</option>
                            <option value="viewer">Lecteur</option>
                            <option value="guest">Invité</option>
                        </select>
                    </div>
                </div>
                <div class="permissions-table-container">
                    <div class="total-select-header">
                        <span>Tout Sélectionner</span>
                        <input type="checkbox" id="masterPermissionCheckbox" class="checkbox">
                    </div>
                    <table class="permissions-table">
                        <thead>
                        <tr>
                            <th>Traitement</th>
                            <th>Ajouter</th>
                            <th>Modifier</th>
                            <th>Supprimer</th>
                            <th>Imprimer</th>
                        </tr>
                        </thead>
                        <tbody id="permissionsTableBody">
                        <!-- Data will be dynamically loaded here by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>



            <div class="footer-buttons">
                <button class="btn btn-primary" id="validateButton">Valider</button>
            </div>
    </main>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userGroupSelect = document.getElementById('userGroupSelect');
        const masterPermissionCheckbox = document.getElementById('masterPermissionCheckbox');
        const permissionsTableBody = document.getElementById('permissionsTableBody');
        const validateButton = document.getElementById('validateButton');

        // Sample data for treatments and their default permissions (can be loaded from API)
        const treatments = [
            { id: 'trait1', name: 'Traitement 1', permissions: { add: false, mod: true, del: true, imp: true } },
            { id: 'trait2', name: 'Traitement 2', permissions: { add: true, mod: false, del: true, imp: true } },
            { id: 'trait3', name: 'Traitement 3', permissions: { add: false, mod: false, del: true, imp: true } },
            { id: 'trait4', name: 'Traitement 4', permissions: { add: false, mod: false, del: false, imp: true } },
            // Add more treatments as needed
        ];

        /**
         * Renders the permission table rows based on the current treatments data.
         */
        function renderPermissionsTable() {
            permissionsTableBody.innerHTML = ''; // Clear existing rows

            treatments.forEach(treatment => {
                const row = document.createElement('tr');
                row.innerHTML = `
                        <td>${treatment.name}</td>
                        <td><div class="checkbox-container"><input type="checkbox" class="checkbox permission-checkbox" data-treatment-id="${treatment.id}" data-permission-type="add" ${treatment.permissions.add ? 'checked' : ''}></div></td>
                        <td><div class="checkbox-container"><input type="checkbox" class="checkbox permission-checkbox" data-treatment-id="${treatment.id}" data-permission-type="mod" ${treatment.permissions.mod ? 'checked' : ''}></div></td>
                        <td><div class="checkbox-container"><input type="checkbox" class="checkbox permission-checkbox" data-treatment-id="${treatment.id}" data-permission-type="del" ${treatment.permissions.del ? 'checked' : ''}></div></td>
                        <td><div class="checkbox-container"><input type="checkbox" class="checkbox permission-checkbox" data-treatment-id="${treatment.id}" data-permission-type="imp" ${treatment.permissions.imp ? 'checked' : ''}></div></td>
                    `;
                permissionsTableBody.appendChild(row);
            });
            updateMasterPermissionCheckboxState(); // Update master checkbox after rendering
            addPermissionCheckboxListeners(); // Add listeners to new checkboxes
        }

        /**
         * Adds change event listeners to all individual permission checkboxes.
         */
        function addPermissionCheckboxListeners() {
            document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
                // Remove existing listeners to prevent duplicates if renderPermissionsTable is called multiple times
                checkbox.removeEventListener('change', updateMasterPermissionCheckboxState);
                checkbox.addEventListener('change', updateMasterPermissionCheckboxState);
            });
        }

        /**
         * Updates the state of the master permission checkbox based on individual checkboxes.
         */
        function updateMasterPermissionCheckboxState() {
            const allCheckboxes = document.querySelectorAll('.permission-checkbox');
            if (allCheckboxes.length === 0) {
                masterPermissionCheckbox.checked = false;
                masterPermissionCheckbox.indeterminate = false;
                return;
            }
            const checkedCheckboxes = document.querySelectorAll('.permission-checkbox:checked');
            masterPermissionCheckbox.checked = checkedCheckboxes.length === allCheckboxes.length;
            masterPermissionCheckbox.indeterminate = checkedCheckboxes.length > 0 && checkedCheckboxes.length < allCheckboxes.length;
        }

        // Event listener for the "Tout Sélectionner" checkbox
        masterPermissionCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
                checkbox.checked = isChecked;
                // Update the internal data model as well
                const treatmentId = checkbox.dataset.treatmentId;
                const permissionType = checkbox.dataset.permissionType;
                const treatment = treatments.find(t => t.id === treatmentId);
                if (treatment) {
                    treatment.permissions[permissionType] = isChecked;
                }
            });
        });

        // Event listener for the "Valider" button
        validateButton.addEventListener('click', function() {
            const selectedGroup = userGroupSelect.value;
            if (!selectedGroup) {
                alert("Veuillez sélectionner un groupe utilisateur."); // Replace with custom modal later
                return;
            }

            // Gather current permissions from the UI
            const currentPermissions = {};
            document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
                const treatmentId = checkbox.dataset.treatmentId;
                const permissionType = checkbox.dataset.permissionType;
                if (!currentPermissions[treatmentId]) {
                    currentPermissions[treatmentId] = {};
                }
                currentPermissions[treatmentId][permissionType] = checkbox.checked;
            });

            console.log('Groupe sélectionné:', selectedGroup);
            console.log('Permissions actuelles:', currentPermissions);
            alert("Permissions mises à jour pour le groupe: " + selectedGroup + "\n(Vérifiez la console pour les détails)");
            // In a real application, you would send this data to your backend
            // e.g., fetch('/api/update-permissions', {
            //     method: 'POST',
            //     headers: { 'Content-Type': 'application/json' },
            //     body: JSON.stringify({ group: selectedGroup, permissions: currentPermissions })
            // }).then(response => response.json()).then(data => console.log(data));
        });

        // Initial render of the table on page load
        renderPermissionsTable();
    });
</script>

</body>
</html>
-- Script pour ajouter les permissions de menu pour chaque groupe d'utilisateurs
-- Ce script ajoute des actions pour chaque menu, les lie aux traitements, et définit les autorisations

-- 1. Ajouter des actions pour chaque menu (visualisation)
INSERT INTO action (id, libelle)
VALUES 
('ACT_VIEW_ETUDIANTS', 'Visualiser les étudiants'),
('ACT_VIEW_ENSEIGNANTS', 'Visualiser les enseignants'),
('ACT_VIEW_PERSONNEL', 'Visualiser le personnel administratif'),
('ACT_VIEW_PARAMETRES', 'Visualiser les paramètres'),
('ACT_VIEW_HISTORIQUE', 'Visualiser l''historique du personnel'),
('ACT_VIEW_EVALUATION', 'Visualiser les évaluations'),
('ACT_VIEW_ATTRIBUTION', 'Visualiser les attributions de menu'),
('ACT_VIEW_REGLEMENT', 'Visualiser les règlements'),
('ACT_VIEW_AUDITS', 'Visualiser les audits'),
('ACT_VIEW_CONFIRMATION', 'Visualiser les confirmations de rapports'),
('ACT_VIEW_MESSAGERIE', 'Visualiser la messagerie'),
('ACT_VIEW_HIST_APPROBATION', 'Visualiser l''historique des approbations'),
('ACT_VIEW_ACCUEIL', 'Visualiser l''accueil'),
('ACT_VIEW_ESPACE_UTILISATEUR', 'Visualiser l''espace utilisateur'),
('ACT_VIEW_INSCRIPTION', 'Visualiser l''inscription'),
('ACT_VIEW_SOUMISSION', 'Visualiser la soumission de rapport');

-- 2. Lier les actions aux traitements
INSERT INTO traitement_action (traitement_id, action_id)
VALUES 
('TRT_GESTION_ETUDIANTS', 'ACT_VIEW_ETUDIANTS'),
('TRT_GESTION_ENSEIGNANTS', 'ACT_VIEW_ENSEIGNANTS'),
('TRT_GESTION_PERSONNEL', 'ACT_VIEW_PERSONNEL'),
('TRT_PARAMETRES', 'ACT_VIEW_PARAMETRES'),
('TRT_HISTORIQUE_PERSONNEL', 'ACT_VIEW_HISTORIQUE'),
('TRT_EVALUATION', 'ACT_VIEW_EVALUATION'),
('TRT_ATTRIBUTION_MENU', 'ACT_VIEW_ATTRIBUTION'),
('TRT_REGLEMENT', 'ACT_VIEW_REGLEMENT'),
('TRT_AUDITS', 'ACT_VIEW_AUDITS'),
('TRT_CONFIRMATION_RAPPORTS', 'ACT_VIEW_CONFIRMATION'),
('TRT_MESSAGERIE', 'ACT_VIEW_MESSAGERIE'),
('TRT_HISTORIQUE_APPROBATION', 'ACT_VIEW_HIST_APPROBATION'),
('TRT_ACCUEIL', 'ACT_VIEW_ACCUEIL'),
('TRT_ESPACE_UTILISATEUR', 'ACT_VIEW_ESPACE_UTILISATEUR'),
('TRT_INSCRIPTION_PUBLIC', 'ACT_VIEW_INSCRIPTION'),
('TRT_SOUMISSION_RAPPORT', 'ACT_VIEW_SOUMISSION');

-- 3. Définir les autorisations pour chaque groupe d'utilisateurs

-- Groupe des étudiants (GRP_ETUDIANTS)
INSERT INTO autorisation_action (groupe_utilisateur_id, traitement_id, action_id)
VALUES 
-- Menus publics accessibles à tous
('GRP_ETUDIANTS', 'TRT_ACCUEIL', 'ACT_VIEW_ACCUEIL'),
('GRP_ETUDIANTS', 'TRT_ESPACE_UTILISATEUR', 'ACT_VIEW_ESPACE_UTILISATEUR'),
('GRP_ETUDIANTS', 'TRT_INSCRIPTION_PUBLIC', 'ACT_VIEW_INSCRIPTION'),
('GRP_ETUDIANTS', 'TRT_SOUMISSION_RAPPORT', 'ACT_VIEW_SOUMISSION');

-- Groupe des validateurs de rapports (GRP_VALID_RAPPORT)
INSERT INTO autorisation_action (groupe_utilisateur_id, traitement_id, action_id)
VALUES 
-- Menus publics
('GRP_VALID_RAPPORT', 'TRT_ACCUEIL', 'ACT_VIEW_ACCUEIL'),
('GRP_VALID_RAPPORT', 'TRT_ESPACE_UTILISATEUR', 'ACT_VIEW_ESPACE_UTILISATEUR'),
-- Menus spécifiques aux enseignants
('GRP_VALID_RAPPORT', 'TRT_EVALUATION', 'ACT_VIEW_EVALUATION'),
('GRP_VALID_RAPPORT', 'TRT_CONFIRMATION_RAPPORTS', 'ACT_VIEW_CONFIRMATION'),
('GRP_VALID_RAPPORT', 'TRT_MESSAGERIE', 'ACT_VIEW_MESSAGERIE'),
('GRP_VALID_RAPPORT', 'TRT_HISTORIQUE_APPROBATION', 'ACT_VIEW_HIST_APPROBATION');

-- Groupe de l'administration pédagogique (GRP_ADMIN_PEDAGO)
INSERT INTO autorisation_action (groupe_utilisateur_id, traitement_id, action_id)
VALUES 
-- Menus publics
('GRP_ADMIN_PEDAGO', 'TRT_ACCUEIL', 'ACT_VIEW_ACCUEIL'),
('GRP_ADMIN_PEDAGO', 'TRT_ESPACE_UTILISATEUR', 'ACT_VIEW_ESPACE_UTILISATEUR'),
-- Menus de gestion
('GRP_ADMIN_PEDAGO', 'TRT_GESTION_ETUDIANTS', 'ACT_VIEW_ETUDIANTS'),
('GRP_ADMIN_PEDAGO', 'TRT_GESTION_ENSEIGNANTS', 'ACT_VIEW_ENSEIGNANTS'),
('GRP_ADMIN_PEDAGO', 'TRT_GESTION_PERSONNEL', 'ACT_VIEW_PERSONNEL'),
('GRP_ADMIN_PEDAGO', 'TRT_PARAMETRES', 'ACT_VIEW_PARAMETRES'),
('GRP_ADMIN_PEDAGO', 'TRT_HISTORIQUE_PERSONNEL', 'ACT_VIEW_HISTORIQUE'),
-- Menus autres
('GRP_ADMIN_PEDAGO', 'TRT_EVALUATION', 'ACT_VIEW_EVALUATION'),
('GRP_ADMIN_PEDAGO', 'TRT_ATTRIBUTION_MENU', 'ACT_VIEW_ATTRIBUTION'),
('GRP_ADMIN_PEDAGO', 'TRT_REGLEMENT', 'ACT_VIEW_REGLEMENT'),
('GRP_ADMIN_PEDAGO', 'TRT_AUDITS', 'ACT_VIEW_AUDITS'),
('GRP_ADMIN_PEDAGO', 'TRT_CONFIRMATION_RAPPORTS', 'ACT_VIEW_CONFIRMATION'),
-- Menus commission
('GRP_ADMIN_PEDAGO', 'TRT_MESSAGERIE', 'ACT_VIEW_MESSAGERIE'),
('GRP_ADMIN_PEDAGO', 'TRT_HISTORIQUE_APPROBATION', 'ACT_VIEW_HIST_APPROBATION');

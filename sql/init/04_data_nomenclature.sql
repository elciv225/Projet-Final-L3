-- Nomenclature de base pour l'application

-- Années Académiques
INSERT INTO annee_academique (id, date_debut, date_fin) VALUES
('2022-2023', '2022-10-01', '2023-09-30'),
('2023-2024', '2023-10-01', '2024-09-30'),
('2024-2025', '2024-10-01', '2025-09-30');

-- Entreprises (Exemples)
INSERT INTO entreprise (id, libelle) VALUES
('ENT_SONATEL', 'Sonatel'),
('ENT_ORANGE', 'Orange Digital Center'),
('ENT_SGBS', 'Société Générale Sénégal');

-- Niveaux d'Étude
INSERT INTO niveau_etude (id, libelle) VALUES
('L1', 'Licence 1'),
('L2', 'Licence 2'),
('L3', 'Licence 3'),
('M1', 'Master 1'),
('M2', 'Master 2');

-- Grades Enseignants
INSERT INTO grade (id, libelle) VALUES
('GRD_ASSIST', 'Assistant'),
('GRD_MA', 'Maître-Assistant'),
('GRD_MCF', 'Maître de Conférences'),
('GRD_PROF', 'Professeur Titulaire');

-- Spécialités Enseignants
INSERT INTO specialite (id, libelle) VALUES
('SPE_INFO_GL', 'Génie Logiciel'),
('SPE_INFO_RESEAU', 'Réseaux et Systèmes'),
('SPE_INFO_DATA', 'Science des Données'),
('SPE_MATH_STATS', 'Statistiques'),
('SPE_GESTION_PROJ', 'Gestion de Projets');

-- Fonctions Administratives/Académiques
INSERT INTO fonction (id, libelle) VALUES
('FCT_CHEF_DEPT_INFO', 'Chef du Département Informatique'),
('FCT_COORD_L3_INFO', 'Coordonnateur Licence 3 Informatique'),
('FCT_SECRETAIRE_PED', 'Secrétaire Pédagogique'),
('FCT_DIR_UFR_SAT', 'Directeur UFR SAT');

-- Catégories d'Utilisateur
INSERT INTO categorie_utilisateur (id, libelle) VALUES
('CAT_ETUD', 'Étudiant'),
('CAT_ENS', 'Enseignant'),
('CAT_ADMIN', 'Personnel Administratif'),
('CAT_SYS', 'Administrateur Système');

-- Types d'Utilisateur (dépend de categorie_utilisateur)
INSERT INTO type_utilisateur (id, libelle, categorie_utilisateur_id) VALUES
('TYPE_ETUD_L1_INFO', 'Étudiant Licence 1 Informatique', 'CAT_ETUD'),
('TYPE_ETUD_L3_INFO', 'Étudiant Licence 3 Informatique', 'CAT_ETUD'),
('TYPE_ETUD_M2_GL', 'Étudiant Master 2 Génie Logiciel', 'CAT_ETUD'),
('TYPE_ENS_PERM', 'Enseignant Permanent', 'CAT_ENS'),
('TYPE_ENS_VAC', 'Enseignant Vacataire', 'CAT_ENS'),
('TYPE_ADMIN_SCOL', 'Agent de Scolarité', 'CAT_ADMIN'),
('TYPE_ADMIN_SYS', 'Administrateur Système Principal', 'CAT_SYS');

-- Groupes d'Utilisateur
INSERT INTO groupe_utilisateur (id, libelle) VALUES
('GRP_ETUDIANTS', 'Étudiants'),
('GRP_ENSEIGNANTS', 'Enseignants'),
('GRP_ADMIN_SCOLARITE', 'Administration Scolarité'),
('GRP_ADMIN_SYSTEME', 'Administrateurs Système'),
('GRP_COMM_VALID_RAPPORT', 'Commission Validation Rapports'),
('GRP_ENSEIGNANT_CHERCHEUR', 'Enseignant Chercheur');


-- Niveaux d'Accès aux Données
INSERT INTO niveau_acces_donnees (id, libelle) VALUES
('ACCES_PUBLIC', 'Accès Public'),
('ACCES_ETUDIANT', 'Accès Étudiant'),
('ACCES_ENSEIGNANT', 'Accès Enseignant'),
('ACCES_DEPT_INFO', 'Accès Département Informatique'),
('ACCES_ADMIN', 'Accès Administratif Total');

-- Statuts Jury
INSERT INTO statut_jury (id, libelle) VALUES
('JURY_PRESIDENT', 'Président du Jury'),
('JURY_MEMBRE', 'Membre du Jury'),
('JURY_RAPPORTEUR', 'Rapporteur'),
('JURY_ENCADRANT', 'Encadrant Professionnel');

-- Niveaux d'Approbation
INSERT INTO niveau_approbation (id, libelle) VALUES
('APPROB_N1_ENCADRANT', 'Niveau 1 - Encadrant Pédagogique'),
('APPROB_N2_CHEF_DEPT', 'Niveau 2 - Chef de Département'),
('APPROB_N3_DIR_UFR', 'Niveau 3 - Directeur UFR');

-- Unités d'Enseignement (UE)
INSERT INTO ue (id, libelle, credit) VALUES
('UE_ALGO1', 'Algorithmique et Structures de Données 1', 6),
('UE_PROG_WEB', 'Programmation Web Fondamentale', 5),
('UE_BDD1', 'Bases de Données Relationnelles', 5),
('UE_ANGLAIS_TECH', 'Anglais Technique', 2),
('UE_PROJET_TUT', 'Projet Tutoré L3', 10),
('UE_STAGE_M2', 'Stage Master 2', 30);

-- Éléments Constitutifs d'UE (ECUE) (dépend de ue)
INSERT INTO ecue (id, libelle, credit, ue_id) VALUES
('ECUE_ALGO1_CM', 'Algorithmique 1 CM', 3, 'UE_ALGO1'),
('ECUE_ALGO1_TDTP', 'Algorithmique 1 TD/TP', 3, 'UE_ALGO1'),
('ECUE_HTML_CSS', 'HTML5 & CSS3', 2, 'UE_PROG_WEB'),
('ECUE_PHP_INTRO', 'Introduction PHP & MySQL', 3, 'UE_PROG_WEB'),
('ECUE_SQL', 'Langage SQL', 3, 'UE_BDD1'),
('ECUE_MERISE', 'Modélisation Merise', 2, 'UE_BDD1');

-- Traitements
INSERT INTO traitement (id, libelle) VALUES
('TRT_INSCRIPTION_ADMIN', 'Gestion des Inscriptions Administratives'),
('TRT_INSCRIPTION_PEDAG', 'Gestion des Inscriptions Pédagogiques'),
('TRT_GESTION_NOTES', 'Gestion des Notes et Évaluations'),
('TRT_SUIVI_RAPPORTS', 'Suivi des Rapports de Stage'),
('TRT_GESTION_UTILIS', 'Gestion des Utilisateurs'),
('TRT_PARAM_GEN', 'Paramètres Généraux');

-- Actions (pour les traitements)
INSERT INTO action (id, libelle) VALUES
('ACT_CREER_USER', 'Créer Utilisateur'),
('ACT_MODIF_USER', 'Modifier Utilisateur'),
('ACT_SUPPR_USER', 'Supprimer Utilisateur'),
('ACT_VALID_INSCR', 'Valider Inscription'),
('ACT_SAISIR_NOTE', 'Saisir Note'),
('ACT_APPROUV_RAPPORT', 'Approuver Rapport'),
('ACT_CONSULT_HISTO', 'Consulter Historique');

-- Catégories de Menu
INSERT INTO categorie_menu (id, libelle) VALUES
('CATMENU_GESTION', 'Gestion Administrative'),
('CATMENU_PEDAGOGIE', 'Pédagogie'),
('CATMENU_COMMISSION', 'Commission'),
('CATMENU_PARAM', 'Paramètres');

-- Menus (dépend de categorie_menu)
INSERT INTO menu (id, categorie_menu_id, libelle, vue) VALUES
('MENU_UTILISATEURS', 'CATMENU_GESTION', 'Utilisateurs', 'menu_views/utilisateurs'),
('MENU_PARAM_GEN', 'CATMENU_PARAM', 'Paramètres Généraux', 'menu_views/parametres-generaux'),
('MENU_HISTO_PERSO', 'CATMENU_GESTION', 'Historique Personnel', 'menu_views/historique-personnel'),
('MENU_EVAL_ETUD', 'CATMENU_PEDAGOGIE', 'Évaluation Étudiants', 'menu_views/evaluation-etudiant'),
('MENU_REG_INSCR', 'CATMENU_GESTION', 'Règlement Inscriptions', 'menu_views/reglement-inscription'),
('MENU_ATTR_MENU', 'CATMENU_PARAM', 'Attribution des Menus', 'menu_views/attribution-menu'),
('MENU_COMM_DISCUSS', 'CATMENU_COMMISSION', 'Discussions Commission', 'commission/discussions'),
('MENU_COMM_HISTO_APPROB', 'CATMENU_COMMISSION', 'Historique Approbations', 'commission/historique-approbation');

-- Association Menu-Traitement (Exemples)
INSERT INTO menu_traitement (menu_id, traitement_id) VALUES
('MENU_UTILISATEURS', 'TRT_GESTION_UTILIS'),
('MENU_PARAM_GEN', 'TRT_PARAM_GEN'),
('MENU_EVAL_ETUD', 'TRT_GESTION_NOTES'),
('MENU_REG_INSCR', 'TRT_INSCRIPTION_ADMIN');
-- Ajoutez d'autres associations au besoin

-- Association Traitement-Action (Exemples)
INSERT INTO traitement_action (traitement_id, action_id) VALUES
('TRT_GESTION_UTILIS', 'ACT_CREER_USER'),
('TRT_GESTION_UTILIS', 'ACT_MODIF_USER'),
('TRT_GESTION_UTILIS', 'ACT_SUPPR_USER'),
('TRT_INSCRIPTION_ADMIN', 'ACT_VALID_INSCR'),
('TRT_GESTION_NOTES', 'ACT_SAISIR_NOTE'),
('TRT_SUIVI_RAPPORTS', 'ACT_APPROUV_RAPPORT');
-- Ajoutez d'autres associations au besoin

-- Autorisation_Action (Exemples de permissions pour groupes)
-- Donner toutes les actions du traitement 'TRT_GESTION_UTILIS' au groupe 'GRP_ADMIN_SYSTEME'
INSERT INTO autorisation_action (groupe_utilisateur_id, traitement_id, action_id)
SELECT 'GRP_ADMIN_SYSTEME', ta.traitement_id, ta.action_id
FROM traitement_action ta
WHERE ta.traitement_id = 'TRT_GESTION_UTILIS';

-- Donner l'action 'ACT_APPROUV_RAPPORT' du traitement 'TRT_SUIVI_RAPPORTS' au groupe 'GRP_COMM_VALID_RAPPORT'
INSERT INTO autorisation_action (groupe_utilisateur_id, traitement_id, action_id) VALUES
('GRP_COMM_VALID_RAPPORT', 'TRT_SUIVI_RAPPORTS', 'ACT_APPROUV_RAPPORT');

-- Fins des insertions de nomenclature de base.
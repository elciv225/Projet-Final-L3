-- =================================================================
-- Fichier: 04_data_nomenclature.sql
-- Description: Insertion des données de référence et de test.
-- CORRECTION: Ajout de la désactivation/réactivation des contraintes
-- pour permettre la modification de la table utilisateur.
-- =================================================================

-- Définit l'encodage pour la session pour garantir la bonne gestion des caractères français
SET NAMES 'utf8mb4';
SET CHARACTER SET utf8mb4;

-- On désactive temporairement la vérification des clés étrangères
SET FOREIGN_KEY_CHECKS = 0;

-- =================================================================
--                  POPULATION DES TABLES DE REFERENCE
-- =================================================================

-- annee_academique
INSERT INTO annee_academique (id, date_debut, date_fin)
VALUES ('2023-2024', '2023-10-01', '2024-07-31'),
       ('2024-2025', '2024-10-01', '2025-07-31');

-- entreprise
INSERT INTO entreprise (id, libelle)
VALUES ('ENT_GOOGLE', 'Google'),
       ('ENT_MICROSOFT', 'Microsoft'),
       ('ENT_CAPGEMINI', 'Capgemini Côte d''Ivoire');

-- niveau_etude
INSERT INTO niveau_etude (id, libelle)
VALUES ('NIVEAU_LICENCE1', 'Licence 1'),
       ('NIVEAU_LICENCE2', 'Licence 2'),
       ('NIVEAU_LICENCE3', 'Licence 3'),
       ('NIVEAU_MASTER1', 'Master 1'),
       ('NIVEAU_MASTER2', 'Master 2');

-- grade
INSERT INTO grade (id, libelle)
VALUES ('GRD_ASSISTANT', 'Assistant'),
       ('GRD_MAITRE_ASSIST', 'Maître-Assistant'),
       ('GRD_MAITRE_CONF', 'Maître de Conférences'),
       ('GRD_PROFESSEUR', 'Professeur Titulaire');

-- specialite
INSERT INTO specialite (id, libelle)
VALUES ('SPE_INFO_GL', 'Génie Logiciel'),
       ('SPE_INFO_SI', 'Sécurité Informatique'),
       ('SPE_INFO_BD', 'Bases de Données'),
       ('SPE_MATHS_PURES', 'Mathématiques Pures');

-- fonction
INSERT INTO fonction (id, libelle)
VALUES ('FCT_CHEF_DEPT', 'Chef de Département'),
       ('FCT_DIR_ETUDES', 'Directeur des Études'),
       ('FCT_RESP_PEDAGO', 'Responsable Pédagogique');

-- ue (Unité d'Enseignement)
INSERT INTO ue (id, libelle, credit)
VALUES ('UE_PROG_C', 'Programmation C', 5),
       ('UE_ALGO_S2', 'Algorithmique S2', 6),
       ('UE_BD_AV', 'Bases de Données Avancées', 6);

-- ecue (Élément Constitutif d'une UE)
INSERT INTO ecue (id, libelle, credit, ue_id)
VALUES ('ECUEPROGC1', 'Structures de données en C', 3, 'UE_PROG_C'),
       ('ECUE_PROG_C_2', 'Pointeurs et mémoire', 2, 'UE_PROG_C'),
       ('ECUE_BD_AV', 'SQL Avancé et Optimisation', 4, 'UE_BD_AV'),
       ('ECUE_BD_AV_NOSQL', 'Introduction au NoSQL', 2, 'UE_BD_AV');

-- categorie_utilisateur
INSERT INTO categorie_utilisateur (id, libelle)
VALUES ('CAT_ETUDIANT', 'Étudiant'),
       ('CAT_ENSEIGNANT', 'Enseignant'),
       ('CAT_ADMIN', 'Personnel Administratif');

-- type_utilisateur
INSERT INTO type_utilisateur (id, libelle, categorie_utilisateur_id)
VALUES ('TYPE_ETUDIANT_L1', 'Étudiant en Licence 1', 'CAT_ETUDIANT'),
       ('TYPE_ETUDIANT_L2', 'Étudiant en Licence 2', 'CAT_ETUDIANT'),
       ('TYPE_ETUDIANT_L3', 'Étudiant en Licence 3', 'CAT_ETUDIANT'),
       ('TYPE_ETUDIANT_M1', 'Étudiant en Master 1', 'CAT_ETUDIANT'),
       ('TYPE_ETUDIANT_M2', 'Étudiant en Master 2', 'CAT_ETUDIANT'),
       ('TYPE_ENSEIGNANT_PERM', 'Enseignant Permanent', 'CAT_ENSEIGNANT'),
       ('TYPE_ADMIN_SCOLARITE', 'Agent de Scolarité', 'CAT_ADMIN'),
       ('TYPE_UNDEFINED', 'Indéfinie', 'CAT_ADMIN');

-- groupe_utilisateur
INSERT INTO groupe_utilisateur (id, libelle)
VALUES ('GRP_ETUDIANTS', 'Groupe des étudiants'),
       ('GRP_VALID_RAPPORT', 'Validateur de rapports'),
       ('GRP_COMMISsION', 'Membre de la Commission'),
       ('GRP_ADMIN_PEDAGO', 'Administration Pédagogique');


-- statut_jury
INSERT INTO statut_jury (id, libelle)
VALUES ('STATUT_JURY_PRESIDENT', 'Président du Jury'),
       ('STATUT_JURY_MEMBRE', 'Membre du Jury'),
       ('STATUT_JURY_ENCADRANT', 'Encadrant');

-- niveau_approbation
INSERT INTO niveau_approbation (id, libelle)
VALUES ('APPROB_CHEF_DEPT', 'Approbation Chef de Département'),
       ('APPROB_DIR_ETUDES', 'Approbation Directeur des Études');

-- traitement
INSERT INTO traitement (id, libelle)
VALUES ('TRT_INSCRIPTION', 'Processus d''inscription'),
       ('TRT_VALID_RAPPORT', 'Processus de validation de rapport');

-- action
INSERT INTO action (id, libelle)
VALUES ('ACT_VERIF_PAIE', 'Vérifier paiement'),
       ('ACT_VALID_INSCR', 'Valider inscription'),
       ('ACT_DEPOT_NOTE', 'Déposer une note'),
       ('ACT_VALID_NOTE', 'Valider une note');

-- traitement_action
INSERT INTO traitement_action (traitement_id, action_id)
VALUES ('TRT_INSCRIPTION', 'ACT_VERIF_PAIE'),
       ('TRT_INSCRIPTION', 'ACT_VALID_INSCR'),
       ('TRT_VALID_RAPPORT', 'ACT_DEPOT_NOTE'),
       ('TRT_VALID_RAPPORT', 'ACT_VALID_NOTE');


-- =================================================================
--                  POPULATION DES UTILISATEURS
-- =================================================================

-- Création des utilisateurs
INSERT INTO utilisateur (id, nom, prenoms, email, login, mot_de_passe, date_naissance, groupe_utilisateur_id,
                         type_utilisateur_id)
VALUES ('21INF001', 'KOUASSI', 'Jean-Luc', 'jean.kouassi@email.com', 'jkouassi', 'hashed_password_etudiant1',
        '2002-05-15', 'GRP_ETUDIANTS', 'TYPE_ETUDIANT_L3'),
       ('20MAS002', 'DIALLO', 'Aïssatou', 'aissatou.diallo@email.com', 'adiallo', 'hashed_password_etudiant2',
        '2000-11-20', 'GRP_ETUDIANTS', 'TYPE_ETUDIANT_M2'),
       ('ENS001', 'TRAORE', 'Moussa', 'moussa.traore@email.com', 'mtraore', 'hashed_password_enseignant1', '1980-03-10',
        'GRP_VALID_RAPPORT', 'TYPE_ENSEIGNANT_PERM'),
       ('ENS002', 'N''GUESSAN', 'Amenan', 'amenan.nguessan@email.com', 'anguessan', 'hashed_password_enseignant2',
        '1985-08-25', 'GRP_VALID_RAPPORT', 'TYPE_ENSEIGNANT_PERM'),
       ('ADM001', 'KONATE', 'Fatoumata', 'fatou.konate@email.com', 'fkonate', 'hashed_password_admin1', '1990-01-30',
        'GRP_ADMIN_PEDAGO', 'TYPE_ADMIN_SCOLARITE');

-- Spécialisation des utilisateurs
INSERT INTO etudiant (utilisateur_id, numero_carte)
VALUES ('21INF001', 'CARTE2021001'),
       ('20MAS002', 'CARTE2020002');

INSERT INTO enseignant (utilisateur_id)
VALUES ('ENS001'),
       ('ENS002');

INSERT INTO personnel_administratif (utilisateur_id)
VALUES ('ADM001');


-- =================================================================
--                  POPULATION DES TABLES DE PROCESSUS
-- =================================================================

-- inscription_etudiant
INSERT INTO inscription_etudiant (utilisateur_id, niveau_etude_id, annee_academique_id, date_inscription, montant)
VALUES ('21INF001', 'NIVEAU_LICENCE3', '2023-2024', '2023-10-15', 250000),
       ('20MAS002', 'NIVEAU_MASTER2', '2023-2024', '2023-10-20', 350000);

-- rapport_etudiant
INSERT INTO rapport_etudiant (id, titre, date_rapport, lien_rapport)
VALUES ('RAP202421INF001', 'Développement d''une application de gestion de stock', '2024-06-15',
        '/rapports/2024/rapport_21INF001.pdf'),
       ('RAP202420MAS002', 'Analyse de la sécurité des réseaux sans fil', '2024-07-01',
        '/rapports/2024/rapport_20MAS002.pdf');

-- depot_rapport
INSERT INTO depot_rapport (utilisateur_id, rapport_etudiant_id, date_depot)
VALUES ('21INF001', 'RAP202421INF001', '2024-06-16'),
       ('20MAS002', 'RAP202420MAS002', '2024-07-02');

-- affectation_encadrant (Jury)
INSERT INTO affectation_encadrant (utilisateur_id, rapport_etudiant_id, statut_jury_id, date_affectation)
VALUES ('ENS001', 'RAP202421INF001', 'STATUT_JURY_ENCADRANT', '2024-03-01'),
       ('ENS002', 'RAP202421INF001', 'STATUT_JURY_PRESIDENT', '2024-06-20'),
       ('ENS002', 'RAP202420MAS002', 'STATUT_JURY_ENCADRANT', '2024-04-05');

-- validation_rapport
INSERT INTO validation_rapport (utilisateur_id, rapport_etudiant_id, date_validation, commentaire)
VALUES ('ENS001', 'RAP202421INF001', '2024-06-25',
        'Bon travail de recherche, quelques corrections mineures à apporter sur le chapitre 3.');

-- approbation_rapport
INSERT INTO approbation_rapport (utilisateur_id, rapport_etudiant_id, date_approbation)
VALUES ('ADM001', 'RAP202421INF001', '2024-06-28');

-- evaluation
INSERT INTO evaluation (enseignant_id, etudiant_id, ecue_id, date_evaluation, note)
VALUES ('ENS001', '21INF001', 'ECUE_BD_AV', '2024-01-20', 15),
       ('ENS002', '21INF001', 'ECUEPROGC1', '2023-12-18', 13);

-- historique_grade, specialite, fonction
INSERT INTO historique_grade (utilisateur_id, grade_id, date_grade)
VALUES ('ENS001', 'GRD_MAITRE_CONF', '2020-07-10'),
       ('ENS002', 'GRD_MAITRE_ASSIST', '2018-07-15');

INSERT INTO historique_specialite (utilisateur_id, specialite_id, date_occupation)
VALUES ('ENS001', 'SPE_INFO_BD', '2015-09-01'),
       ('ENS002', 'SPE_INFO_GL', '2016-09-01');

INSERT INTO historique_fonction (utilisateur_id, fonction_id, date_occupation)
VALUES ('ENS001', 'FCT_CHEF_DEPT', '2022-09-01');

-- stage_effectue
INSERT INTO stage_effectue (utilisateur_id, entreprise_id, date_debut, date_fin)
VALUES ('21INF001', 'ENT_CAPGEMINI', '2024-03-01', '2024-05-31');

-- notification
INSERT INTO notification (emetteur_id, recepteur_id, message, date_notification, lu)
VALUES ('ENS001', '21INF001', 'Votre rapport a été validé avec quelques commentaires. Veuillez les consulter.',
        '2024-06-25 10:00:00', FALSE),
       ('ADM001', 'ENS001', 'Rappel : La réunion pédagogique aura lieu demain à 14h.', '2024-06-30 16:30:00', TRUE);

-- messagerie
INSERT INTO discussion (id, date_discussion)
VALUES ('DISC_ENS002_21INF001', '2024-05-10 09:00:00');

INSERT INTO messagerie (membre_commission_id, etudiant_concerne_id, discussion_id, message, date_message)
VALUES ('ENS002', '21INF001', 'DISC_ENS002_21INF001',
        'Bonjour Jean-Luc, pouvez-vous me faire un point sur l''avancement de votre chapitre 2 ?',
        '2024-05-10 09:00:15');

-- =================================================================
--      AJOUT DES MENUS ET TRAITEMENTS POUR L'APPLICATION
-- =================================================================

-- Catégories de menu
INSERT INTO categorie_menu (id, libelle)
VALUES ('CAT_MENU_GESTION', 'Gestion'),
       ('CAT_MENU_AUTRES', 'Autres'),
       ('CAT_MENU_COMMISSION', 'Commission'),
       ('CAT_MENU_PUBLIC', 'Public');

-- Menus
INSERT INTO menu (id, categorie_menu_id, libelle, vue)
VALUES ('MENU_ETUDIANTS', 'CAT_MENU_GESTION', 'Étudiants', 'etudiants'),
       ('MENU_ENSEIGNANTS', 'CAT_MENU_GESTION', 'Enseignants', 'enseignants'),
       ('MENU_PERSONNEL_ADMIN', 'CAT_MENU_GESTION', 'Personnel Administratif', 'personnel-administratif'),
       ('MENU_PARAMETRES', 'CAT_MENU_GESTION', 'Paramètres Généraux', 'parametres-generaux'),
       ('MENU_HISTORIQUE_PERSONNEL', 'CAT_MENU_GESTION', 'Historique du Personnel', 'historique-personnel'),
       ('MENU_EVALUATION', 'CAT_MENU_AUTRES', 'Évaluation Étudiants', 'evaluation-etudiant'),
       ('MENU_ATTRIBUTION', 'CAT_MENU_AUTRES', 'Gestion des menus', 'attribution-menu'),
       ('MENU_REGLEMENT', 'CAT_MENU_AUTRES', 'Règlement Inscription', 'reglement-inscription'),
       ('MENU_AUDITS', 'CAT_MENU_AUTRES', 'Audits du Système', 'audits'),
       ('MENU_CONFIRMATION_RAPPORTS', 'CAT_MENU_AUTRES', 'Confirmation des Rapports', 'confirmation-rapports'),
       ('MENU_MESSAGERIE', 'CAT_MENU_COMMISSION', 'Discussion', 'messagerie-commission'),
       ('MENU_HISTORIQUE_APPROBATION', 'CAT_MENU_COMMISSION', 'Historique des approbations', 'historique-approbation'),
       ('MENU_ACCUEIL', 'CAT_MENU_PUBLIC', 'Accueil', 'accueil'),
       ('MENU_ESPACE_UTILISATEUR', 'CAT_MENU_PUBLIC', 'Espace Utilisateur', 'espace-utilisateur'),
       ('MENU_INSCRIPTION', 'CAT_MENU_PUBLIC', 'Inscription', 'inscription'),
       ('MENU_SOUMISSION_RAPPORT', 'CAT_MENU_PUBLIC', 'Soumission Rapport', 'soumission-rapport');

-- Traitements
INSERT INTO traitement (id, libelle)
VALUES ('TRT_GESTION_ETUDIANTS', 'Gestion des étudiants'),
       ('TRT_GESTION_ENSEIGNANTS', 'Gestion des enseignants'),
       ('TRT_GESTION_PERSONNEL', 'Gestion du personnel administratif'),
       ('TRT_PARAMETRES', 'Gestion des paramètres'),
       ('TRT_HISTORIQUE_PERSONNEL', 'Consultation historique personnel'),
       ('TRT_EVALUATION', 'Évaluation des étudiants'),
       ('TRT_ATTRIBUTION_MENU', 'Attribution des menus'),
       ('TRT_REGLEMENT', 'Gestion des règlements'),
       ('TRT_AUDITS', 'Consultation des audits'),
       ('TRT_CONFIRMATION_RAPPORTS', 'Confirmation des rapports'),
       ('TRT_MESSAGERIE', 'Messagerie commission'),
       ('TRT_HISTORIQUE_APPROBATION', 'Historique des approbations'),
       ('TRT_ACCUEIL', 'Accueil public'),
       ('TRT_ESPACE_UTILISATEUR', 'Espace utilisateur'),
       ('TRT_INSCRIPTION_PUBLIC', 'Inscription publique'),
       ('TRT_SOUMISSION_RAPPORT', 'Soumission de rapport');

-- Menu-Traitement
INSERT INTO menu_traitement (menu_id, traitement_id)
VALUES ('MENU_ETUDIANTS', 'TRT_GESTION_ETUDIANTS'),
       ('MENU_ENSEIGNANTS', 'TRT_GESTION_ENSEIGNANTS'),
       ('MENU_PERSONNEL_ADMIN', 'TRT_GESTION_PERSONNEL'),
       ('MENU_PARAMETRES', 'TRT_PARAMETRES'),
       ('MENU_HISTORIQUE_PERSONNEL', 'TRT_HISTORIQUE_PERSONNEL'),
       ('MENU_EVALUATION', 'TRT_EVALUATION'),
       ('MENU_ATTRIBUTION', 'TRT_ATTRIBUTION_MENU'),
       ('MENU_REGLEMENT', 'TRT_REGLEMENT'),
       ('MENU_AUDITS', 'TRT_AUDITS'),
       ('MENU_CONFIRMATION_RAPPORTS', 'TRT_CONFIRMATION_RAPPORTS'),
       ('MENU_MESSAGERIE', 'TRT_MESSAGERIE'),
       ('MENU_HISTORIQUE_APPROBATION', 'TRT_HISTORIQUE_APPROBATION'),
       ('MENU_ACCUEIL', 'TRT_ACCUEIL'),
       ('MENU_ESPACE_UTILISATEUR', 'TRT_ESPACE_UTILISATEUR'),
       ('MENU_INSCRIPTION', 'TRT_INSCRIPTION_PUBLIC'),
       ('MENU_SOUMISSION_RAPPORT', 'TRT_SOUMISSION_RAPPORT');

-- =================================================================
--      CRÉATION DES UTILISATEURS DE TEST
-- =================================================================

-- Création des utilisateurs de test avec les procédures stockées

-- 1. Assy Eliel (Super Admin avec accès à toutes les fonctions)
CALL sp_creer_compte_utilisateur(
    'ASSY', 'Eliel', 'assy.eliel@email.com', 'eassy', 'rappliq', '1985-03-15', 'CAT_ADMIN', @user_id
);
-- Attribution du groupe Super Admin
UPDATE utilisateur SET groupe_utilisateur_id = 'GRP_ADMIN_PEDAGO' WHERE id = @user_id;

-- 2. Ouattara Katie (Prof, membre de la commission)
CALL sp_creer_compte_utilisateur(
    'OUATTARA', 'Katie', 'ouattara.katie@email.com', 'kouattara', 'rappliq', '1980-07-22', 'CAT_ENSEIGNANT', @user_id
);
-- Attribution du groupe Validateur de rapports
UPDATE utilisateur SET groupe_utilisateur_id = 'GRP_VALID_RAPPORT' WHERE id = @user_id;

-- 3. Bahou Stéphane (Étudiant de M2 pour tester la partie publique)
CALL sp_inscrire_etudiant(
    'BAHOU', 'Stéphane', 'bahou.stephane@email.com', 'rappliq', '1998-11-05',
    'NIVEAU_MASTER2', '2023-2024', 350000, @user_id
);

-- 4. Akahoua Syntiche (Personnel Administratif, valider rapport, inscription,...)
CALL sp_creer_compte_utilisateur(
    'AKAHOUA', 'Syntiche', 'akahoua.syntiche@email.com', 'sakahoua', 'rappliq', '1990-04-18', 'CAT_ADMIN', @user_id
);
-- Attribution du groupe Admin Pédago
UPDATE utilisateur SET groupe_utilisateur_id = 'GRP_ADMIN_PEDAGO' WHERE id = @user_id;

-- Attribution des autorisations pour les traitements
INSERT INTO autorisation_action (groupe_utilisateur_id, traitement_id, action_id)
VALUES 
-- Super Admin (Assy Eliel) a accès à tous les traitements et actions
('GRP_ADMIN_PEDAGO', 'TRT_GESTION_ETUDIANTS', 'ACT_VERIF_PAIE'),
('GRP_ADMIN_PEDAGO', 'TRT_GESTION_ETUDIANTS', 'ACT_VALID_INSCR'),
('GRP_ADMIN_PEDAGO', 'TRT_VALID_RAPPORT', 'ACT_DEPOT_NOTE'),
('GRP_ADMIN_PEDAGO', 'TRT_VALID_RAPPORT', 'ACT_VALID_NOTE'),

-- Validateur de rapports (Ouattara Katie) a accès aux traitements de validation
('GRP_VALID_RAPPORT', 'TRT_VALID_RAPPORT', 'ACT_DEPOT_NOTE'),
('GRP_VALID_RAPPORT', 'TRT_VALID_RAPPORT', 'ACT_VALID_NOTE');

-- =================================================================
--      AJOUT DE VALEURS PAR DÉFAUT POUR LA ROBUSTESSE
-- =================================================================

-- 1. Créer une catégorie "Système" pour les entrées par défaut, si elle n'existe pas.
INSERT INTO categorie_utilisateur (id, libelle)
SELECT 'CAT_SYSTEM', 'Système'
WHERE NOT EXISTS (SELECT 1 FROM categorie_utilisateur WHERE id = 'CAT_SYSTEM');

-- 2. Créer un type d'utilisateur par défaut.
INSERT INTO type_utilisateur (id, libelle, categorie_utilisateur_id)
SELECT 'TYPE_UNDEFINED', 'Non Défini', 'CAT_SYSTEM'
WHERE NOT EXISTS (SELECT 1 FROM type_utilisateur WHERE id = 'TYPE_UNDEFINED');

-- 3. Créer un groupe d'utilisateurs par défaut.
INSERT INTO groupe_utilisateur (id, libelle)
SELECT 'GRP_DEFAULT', 'Groupe par Défaut'
WHERE NOT EXISTS (SELECT 1 FROM groupe_utilisateur WHERE id = 'GRP_DEFAULT');

-- 4. Modifier la table 'utilisateur' pour utiliser ces nouvelles valeurs par défaut.
-- Cette opération est maintenant possible grâce à la désactivation des contraintes.
ALTER TABLE utilisateur
    MODIFY COLUMN type_utilisateur_id VARCHAR(25) NOT NULL DEFAULT 'TYPE_UNDEFINED',
    MODIFY COLUMN groupe_utilisateur_id VARCHAR(30) NOT NULL DEFAULT 'GRP_DEFAULT';

-- On réactive la vérification des clés étrangères à la fin du script
SET FOREIGN_KEY_CHECKS = 1;

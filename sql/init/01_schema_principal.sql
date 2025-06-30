-- =================================================================
--                  TABLES DU SCHEMA PRINCIPALE
-- =================================================================

-- Table: annee_academique
-- Description: Stocke les informations sur les années académiques.
CREATE TABLE annee_academique
(
    id         VARCHAR(10), -- ex: '2023-2024'
    date_debut DATE,
    date_fin   DATE,
    PRIMARY KEY (id)
);

-- Table: entreprise
-- Description: Liste des entreprises où les étudiants peuvent effectuer des stages.
CREATE TABLE entreprise
(
    id      VARCHAR(20), -- ex: 'ENT_GOOGLE'
    libelle VARCHAR(50),
    PRIMARY KEY (id)
);

-- Table: niveau_etude
-- Description: Définit les différents niveaux d'étude (Licence, Master, etc.).
CREATE TABLE niveau_etude
(
    id      VARCHAR(20), -- ex: 'NIVEAU_LICENCE3'
    libelle VARCHAR(50),
    PRIMARY KEY (id)
);

-- Table: grade
-- Description: Répertorie les grades académiques des enseignants.
CREATE TABLE grade
(
    id      VARCHAR(20), -- ex: 'GRD_MAITRE_CONF'
    libelle VARCHAR(50),
    PRIMARY KEY (id)
);

-- Table: spécialité
-- Description: Répertorie les spécialités des enseignants.
CREATE TABLE specialite
(
    id      VARCHAR(20), -- ex: 'SPE_INFO'
    libelle VARCHAR(50),
    PRIMARY KEY (id)
);

-- Table: fonction
-- Description: Liste les fonctions administratives ou académiques possibles.
CREATE TABLE fonction
(
    id      VARCHAR(20), -- ex: 'FCT_CHEF_DEPT'
    libelle VARCHAR(50),
    PRIMARY KEY (id)
);

-- Table: ue (Unité d'Enseignement)
-- Description: Définit les unités d'enseignement.
CREATE TABLE ue
(
    id      VARCHAR(15), -- ex: 'UE_MATHS1'
    libelle VARCHAR(50),
    credit  SMALLINT,
    PRIMARY KEY (id)
);

-- Table: categorie_utilisateur
-- Description: Catégorise les utilisateurs en grands groupes (Étudiant, Enseignant, etc.).
CREATE TABLE categorie_utilisateur
(
    id      VARCHAR(20), -- ex: 'CAT_ETUDIANT'
    libelle VARCHAR(50),
    PRIMARY KEY (id)
);

-- Table: groupe_utilisateur
-- Description: Définit les groupes de permissions ou de travail.
CREATE TABLE groupe_utilisateur
(
    id      VARCHAR(20), -- ex: 'GRP_VALID_RAPPORT'
    libelle VARCHAR(50),
    PRIMARY KEY (id)
);

-- Table: niveau_acces_donnees
-- Description: Définit le niveau de confidentialité des données accessibles.
CREATE TABLE niveau_acces_donnees
(
    id      VARCHAR(20), -- ex: 'ACCES_DEPT_INFO'
    libelle VARCHAR(50),
    PRIMARY KEY (id)
);

-- Table: statut_jury
-- Description: Définit les statuts possibles au sein d'un jury (Président, Membre, etc.).
CREATE TABLE statut_jury
(
    id      VARCHAR(25), -- ex: 'STATUT_JURY_PRESIDENT'
    libelle VARCHAR(50),
    PRIMARY KEY (id)
);

-- Table: niveau_approbation
-- Description: Définit les différents niveaux d'approbation pour un document.
CREATE TABLE niveau_approbation
(
    id      VARCHAR(25), -- ex: 'APPROB_CHEF_DEPT'
    libelle VARCHAR(50),
    PRIMARY KEY (id)
);

-- =================================================================
--                  TABLES DE PROCESSUS & LOGS
-- =================================================================

-- Table: traitement
-- Description: Définit un processus métier (ex: Inscription, Validation de rapport).
CREATE TABLE traitement
(
    id      VARCHAR(20), -- ex: 'TRT_INSCRIPTION'
    libelle VARCHAR(50),
    PRIMARY KEY (id)
);

-- Table: action
-- Description: Définit une étape atomique d'un traitement (ex: Vérifier paiement).
CREATE TABLE action
(
    id      VARCHAR(20), -- ex: 'ACT_VERIF_PAIE'
    libelle VARCHAR(50),
    PRIMARY KEY (id)
);

-- Table: categorie_menu
-- Description: Définit les lib des bouton des menu ainsi la vue associé, son icone
CREATE TABLE categorie_menu
(
    id      VARCHAR(20),
    libelle VARCHAR(50),
    PRIMARY KEY (id)
);

-- Table: menu
-- Description: Définit les lib des bouton des menu ainsi la vue associé, son icone
CREATE TABLE menu
(
    id                VARCHAR(20),
    categorie_menu_id VARCHAR(20),
    libelle           VARCHAR(50),
    vue               VARCHAR(50),
    PRIMARY KEY (id),
    FOREIGN KEY (categorie_menu_id) REFERENCES categorie_menu (id)
);

-- Table: menu_traitement
-- Description: Associe un menu à son traitement
CREATE TABLE menu_traitement
(
    menu_id       VARCHAR(20),
    traitement_id VARCHAR(20),
    PRIMARY KEY (menu_id, traitement_id),
    FOREIGN KEY (menu_id) REFERENCES menu (id),
    FOREIGN KEY (traitement_id) REFERENCES traitement (id)
);

-- Table: traitement_action
-- Description: Associe des actions à un traitement.
CREATE TABLE traitement_action
(
    traitement_id VARCHAR(20) NOT NULL, -- ex: 'TRT_INSCRIPTION'
    action_id     VARCHAR(20) NOT NULL, -- ex: 'ACT_VERIF_PAIE'
    PRIMARY KEY (traitement_id, action_id),
    FOREIGN KEY (traitement_id) REFERENCES traitement (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (action_id) REFERENCES action (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- =================================================================
--                  TABLES PRINCIPALES & RELATIONS
-- =================================================================

-- Table: ecue (Élément Constitutif d'une UE)
-- Description: Définit les matières (ECUE) qui composent une UE.
CREATE TABLE ecue
(
    id      VARCHAR(15),          -- ex: 'ECUE_ALGEBRE1'
    libelle VARCHAR(50),
    credit  SMALLINT,
    ue_id   VARCHAR(15) NOT NULL, -- ex: 'UE_MATHS1'
    PRIMARY KEY (id),
    FOREIGN KEY (ue_id) REFERENCES ue (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Table: type_utilisateur
-- Description: Spécifie plus finement la catégorie d'un utilisateur.
CREATE TABLE type_utilisateur
(
    id                       VARCHAR(25),          -- ex: 'TYPE_ETUDIANT_L1'
    libelle                  VARCHAR(50),
    categorie_utilisateur_id VARCHAR(20) NOT NULL, -- ex: 'CAT_ETUDIANT'
    PRIMARY KEY (id),
    FOREIGN KEY (categorie_utilisateur_id) REFERENCES categorie_utilisateur (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Table: utilisateur
-- Description: Table centrale contenant les informations de tous les utilisateurs.
CREATE TABLE utilisateur
(
    id                      VARCHAR(30),          -- ex: '24INF001' (matricule)
    nom                     VARCHAR(50),
    prenoms                 VARCHAR(100),
    email                   VARCHAR(255) UNIQUE,
    login                   VARCHAR(50) UNIQUE,
    mot_de_passe            VARCHAR(255),
    date_naissance          DATE,
    groupe_utilisateur_id   VARCHAR(20) NOT NULL, -- ex: 'GRP_VALID_RAPPORT'
    type_utilisateur_id     VARCHAR(25) NOT NULL, -- ex: 'TYPE_ETUDIANT_L1'
    niveau_acces_donnees_id VARCHAR(20) NOT NULL, -- ex: 'ACCES_DEPT_INFO'
    PRIMARY KEY (id),
    FOREIGN KEY (groupe_utilisateur_id) REFERENCES groupe_utilisateur (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (type_utilisateur_id) REFERENCES type_utilisateur (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (niveau_acces_donnees_id) REFERENCES niveau_acces_donnees (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Table: personnel_administratif
-- Description: Spécialisation de la table utilisateur pour le personnel administratif.
CREATE TABLE personnel_administratif
(
    utilisateur_id VARCHAR(30), -- ex: '21ADM005'
    PRIMARY KEY (utilisateur_id),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Table: enseignant
-- Description: Spécialisation de la table utilisateur pour les enseignants.
CREATE TABLE enseignant
(
    utilisateur_id VARCHAR(30), -- ex: 'ENS012'
    PRIMARY KEY (utilisateur_id),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Table: etudiant
-- Description: Spécialisation de la table utilisateur pour les étudiants.
CREATE TABLE etudiant
(
    utilisateur_id VARCHAR(30), -- ex: '24INF001'
    numero_carte VARCHAR(20),
    PRIMARY KEY (utilisateur_id),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Table: inscription_etudiant
-- Description: Lie un étudiant à un niveau d'étude pour une année académique.
CREATE TABLE inscription_etudiant
(
    utilisateur_id      VARCHAR(30), -- ex: '24INF001'
    niveau_etude_id     VARCHAR(20), -- ex: 'NIVEAU_LICENCE3'
    annee_academique_id VARCHAR(10), -- ex: '2023-2024'
    date_inscription    DATE,
    montant             INT,
    PRIMARY KEY (utilisateur_id, niveau_etude_id, annee_academique_id),
    FOREIGN KEY (utilisateur_id) REFERENCES etudiant (utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (niveau_etude_id) REFERENCES niveau_etude (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (annee_academique_id) REFERENCES annee_academique (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Table: rapport_etudiant
-- Description: Stocke les métadonnées des rapports soumis par les étudiants.
CREATE TABLE rapport_etudiant
(
    id           VARCHAR(40), -- ex: 'RAPPORT_2024_24INF001'
    titre        VARCHAR(255),
    date_rapport DATE,
    lien_rapport VARCHAR(255),
    PRIMARY KEY (id)
);

-- Table: depot_rapport
-- Description: Journalise le dépôt d'un rapport par un étudiant.
CREATE TABLE depot_rapport
(
    utilisateur_id      VARCHAR(30), -- ex: '24INF001'
    rapport_etudiant_id VARCHAR(40), -- ex: 'RAPPORT_2024_24INF001'
    date_depot          DATE,
    PRIMARY KEY (utilisateur_id, rapport_etudiant_id),
    FOREIGN KEY (utilisateur_id) REFERENCES etudiant (utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (rapport_etudiant_id) REFERENCES rapport_etudiant (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Table: validation_rapport
-- Description: Trace la validation d'un rapport par un enseignant.
CREATE TABLE validation_rapport
(
    utilisateur_id      VARCHAR(30), -- ex: 'ENS012'
    rapport_etudiant_id VARCHAR(40), -- ex: 'RAPPORT_2024_24INF001'
    date_validation     DATE,
    commentaire         TEXT,
    PRIMARY KEY (utilisateur_id, rapport_etudiant_id),
    FOREIGN KEY (utilisateur_id) REFERENCES enseignant (utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (rapport_etudiant_id) REFERENCES rapport_etudiant (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Table: approbation_rapport
-- Description: Trace l'approbation d'un rapport par le personnel administratif.
CREATE TABLE approbation_rapport
(
    utilisateur_id      VARCHAR(30), -- ex: '21ADM005'
    rapport_etudiant_id VARCHAR(40), -- ex: 'RAPPORT_2024_24INF001'
    date_approbation    DATE,
    PRIMARY KEY (utilisateur_id, rapport_etudiant_id),
    FOREIGN KEY (utilisateur_id) REFERENCES personnel_administratif (utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (rapport_etudiant_id) REFERENCES rapport_etudiant (id) ON DELETE RESTRICT ON UPDATE CASCADE
);


-- Table: affectation_encadrant
-- Description: Assigne un enseignant à un rapport avec un statut dans le jury.
CREATE TABLE affectation_encadrant
(
    utilisateur_id      VARCHAR(30), -- ex: 'ENS012'
    rapport_etudiant_id VARCHAR(40), -- ex: 'RAPPORT_2024_24INF001'
    statut_jury_id      VARCHAR(25), -- ex: 'STATUT_JURY_PRESIDENT'
    date_affectation    DATE,
    PRIMARY KEY (utilisateur_id, rapport_etudiant_id, statut_jury_id),
    FOREIGN KEY (utilisateur_id) REFERENCES enseignant (utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (rapport_etudiant_id) REFERENCES rapport_etudiant (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (statut_jury_id) REFERENCES statut_jury (id) ON DELETE RESTRICT ON UPDATE CASCADE
);


-- Table: compte_rendu
-- Description: Stocke les informations des comptes rendus.
CREATE TABLE compte_rendu
(
    id           VARCHAR(40), -- ex: 'CR_REUNION_20240520'
    titre        VARCHAR(255),
    date_rapport DATE,
    PRIMARY KEY (id)
);

-- Table: remise_compte_rendu
-- Description: Journalise la remise d'un compte rendu par un enseignant.
CREATE TABLE remise_compte_rendu
(
    utilisateur_id  VARCHAR(30), -- ex: 'ENS012'
    compte_rendu_id VARCHAR(40), -- ex: 'CR_REUNION_20240520'
    date_rendu      DATE,
    PRIMARY KEY (utilisateur_id, compte_rendu_id),
    FOREIGN KEY (utilisateur_id) REFERENCES enseignant (utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (compte_rendu_id) REFERENCES compte_rendu (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Table: historique_approbation
-- Description: Historique des approbations sur les comptes rendus.
CREATE TABLE historique_approbation
(
    niveau_approbation_id VARCHAR(25), -- ex: 'APPROB_CHEF_DEPT'
    compte_rendu_id       VARCHAR(40), -- ex: 'CR_REUNION_20240520'
    date_approbation      TIMESTAMP,
    PRIMARY KEY (niveau_approbation_id, compte_rendu_id),
    FOREIGN KEY (niveau_approbation_id) REFERENCES niveau_approbation (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (compte_rendu_id) REFERENCES compte_rendu (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Table: evaluation
-- Description: Enregistre la note donnée par un enseignant à un étudiant pour une matière.
CREATE TABLE evaluation
(
    enseignant_id   VARCHAR(30), -- ex: 'ENS012'
    etudiant_id     VARCHAR(30), -- ex: '24INF001'
    ecue_id         VARCHAR(15), -- ex: 'ECUE_ALGEBRE1'
    date_evaluation DATE,
    note            SMALLINT,
    PRIMARY KEY (enseignant_id, etudiant_id, ecue_id),
    FOREIGN KEY (enseignant_id) REFERENCES enseignant (utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (etudiant_id) REFERENCES etudiant (utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (ecue_id) REFERENCES ecue (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Table: historique_fonction
-- Description: Historise les fonctions occupées par les enseignants.
CREATE TABLE historique_fonction
(
    utilisateur_id  VARCHAR(30), -- ex: 'ENS012'
    fonction_id     VARCHAR(20), -- ex: 'FCT_CHEF_DEPT'
    date_occupation DATE,
    PRIMARY KEY (utilisateur_id, fonction_id),
    FOREIGN KEY (utilisateur_id) REFERENCES enseignant (utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (fonction_id) REFERENCES fonction (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Table: historique_spécialité
-- Description: Historise les spécialités occupées par les enseignants.
CREATE TABLE historique_specialite
(
    utilisateur_id  VARCHAR(30), -- ex: 'ENS012'
    specialite_id     VARCHAR(20), -- ex: 'FCT_CHEF_DEPT'
    date_occupation DATE,
    PRIMARY KEY (utilisateur_id, specialite_id),
    FOREIGN KEY (utilisateur_id) REFERENCES enseignant (utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (specialite_id) REFERENCES specialite (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Table: historique_grade
-- Description: Historise les grades obtenus par les enseignants.
CREATE TABLE historique_grade
(
    utilisateur_id VARCHAR(30), -- ex: 'ENS012'
    grade_id       VARCHAR(20), -- ex: 'GRD_MAITRE_CONF'
    date_grade     DATE,
    PRIMARY KEY (utilisateur_id, grade_id),
    FOREIGN KEY (utilisateur_id) REFERENCES enseignant (utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (grade_id) REFERENCES grade (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Table: stage_effectue
-- Description: Associe un étudiant à une entreprise pour un stage.
CREATE TABLE stage_effectue
(
    utilisateur_id VARCHAR(30), -- ex: '24INF001'
    entreprise_id  VARCHAR(20), -- ex: 'ENT_GOOGLE'
    date_debut     DATE,
    date_fin       DATE,
    PRIMARY KEY (utilisateur_id, entreprise_id),
    FOREIGN KEY (utilisateur_id) REFERENCES etudiant (utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Table: autorisation_action
-- Description: Gère les permissions sur les actions des menu_views pour les groupes.
CREATE TABLE autorisation_action
(
    groupe_utilisateur_id VARCHAR(20), -- ex: 'GRP_VALID_RAPPORT'
    traitement_id         VARCHAR(20), -- ex: 'TRT_INSCRIPTION'
    action_id             VARCHAR(20), -- ex: 'ACT_VERIF_PAIE'
    PRIMARY KEY (groupe_utilisateur_id, traitement_id, action_id),
    FOREIGN KEY (groupe_utilisateur_id) REFERENCES groupe_utilisateur (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (traitement_id) REFERENCES traitement (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (action_id) REFERENCES action (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Table: notification
-- Description: Table pour stocker les notifications entre utilisateurs.
CREATE TABLE notification
(
    emetteur_id       VARCHAR(30), -- ex: 'ENS012'
    recepteur_id      VARCHAR(30), -- ex: '24INF001'
    message           TEXT,
    date_notification TIMESTAMP,
    lu                BOOLEAN DEFAULT FALSE,
    PRIMARY KEY (emetteur_id, recepteur_id, date_notification),
    FOREIGN KEY (emetteur_id) REFERENCES utilisateur (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (recepteur_id) REFERENCES utilisateur (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Table: discussion
-- Description: Entête d'une discussion pour la messagerie.
CREATE TABLE discussion
(
    id              VARCHAR(40), -- ex: 'DISC_ENS012_24INF001'
    date_discussion TIMESTAMP,
    PRIMARY KEY (id)
);

-- Table: messagerie
-- Description: Gère les messages des membres de la commission
CREATE TABLE messagerie
(
    membre_commission_id VARCHAR(30),
    etudiant_concerne_id VARCHAR(30),
    discussion_id        VARCHAR(40),
    message              TEXT,
    date_message         DATETIME,
    PRIMARY KEY (membre_commission_id, etudiant_concerne_id, discussion_id, date_message),
    FOREIGN KEY (membre_commission_id) REFERENCES enseignant (utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (etudiant_concerne_id) REFERENCES etudiant (utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (discussion_id) REFERENCES discussion (id) ON DELETE RESTRICT ON UPDATE CASCADE
);
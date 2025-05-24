CREATE TABLE rapport_etudiant
(
    id            VARCHAR(15),
    titre         VARCHAR(255),
    date_rapport  DATE,
    theme_memoire VARCHAR(50),
    PRIMARY KEY (id)
);

CREATE TABLE niveau_approbation
(
    id      VARCHAR(15),
    libelle VARCHAR(50),
    PRIMARY KEY (id)
);

CREATE TABLE ecue
(
    id      VARCHAR(5),
    libelle VARCHAR(50),
    credit  TINYINT UNSIGNED,
    PRIMARY KEY (id)
);

CREATE TABLE fonction
(
    id      VARCHAR(5),
    libelle VARCHAR(50),
    PRIMARY KEY (id)
);

CREATE TABLE grade
(
    id      VARCHAR(5),
    libelle VARCHAR(50),
    PRIMARY KEY (id)
);

CREATE TABLE entreprise
(
    id      VARCHAR(5),
    libelle VARCHAR(50),
    PRIMARY KEY (id)
);

CREATE TABLE niveau_etude
(
    id      VARCHAR(5),
    libelle VARCHAR(50),
    PRIMARY KEY (id)
);

CREATE TABLE annee_academique
(
    id         VARCHAR(5),
    date_debut DATE,
    date_fin   DATE,
    PRIMARY KEY (id)
);

CREATE TABLE groupe_utilisateur
(
    id      VARCHAR(15),
    libelle VARCHAR(50),
    PRIMARY KEY (id)
);

CREATE TABLE traitement
(
    id      VARCHAR(5),
    libelle VARCHAR(50),
    PRIMARY KEY (id)
);

CREATE TABLE compte_rendu
(
    id           VARCHAR(15),
    titre        VARCHAR(255),
    date_rapport DATE,
    PRIMARY KEY (id)
);

CREATE TABLE statut_jury
(
    id      VARCHAR(15),
    libelle VARCHAR(50),
    PRIMARY KEY (id)
);

CREATE TABLE niveau_acces_donnees
(
    id      VARCHAR(5),
    libelle VARCHAR(50),
    PRIMARY KEY (id)
);

CREATE TABLE action
(
    id      VARCHAR(5),
    libelle VARCHAR(50),
    PRIMARY KEY (id)
);

CREATE TABLE categorie_utilisateur
(
    id      VARCHAR(20),
    libelle VARCHAR(50),
    PRIMARY KEY (id)
);

CREATE TABLE discussion
(
    id              VARCHAR(20),
    date_discussion DATETIME,
    PRIMARY KEY (id)
);

CREATE TABLE ue
(
    id      VARCHAR(5),
    libelle VARCHAR(50),
    credit  TINYINT UNSIGNED,
    ecue_id VARCHAR(5) NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (ecue_id) REFERENCES ecue (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE type_utilisateur
(
    id                       VARCHAR(15),
    libelle                  VARCHAR(50),
    categorie_utilisateur_id VARCHAR(20) NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (categorie_utilisateur_id) REFERENCES categorie_utilisateur (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE utilisateur
(
    id                      VARCHAR(15),
    nom                     VARCHAR(15),
    prenoms                 VARCHAR(50),
    email                   VARCHAR(255),
    login                   VARCHAR(50),
    mot_de_passe            VARCHAR(255), -- Should store hashed passwords
    photo                   VARCHAR(255), -- Path or URL to the photo file
    date_naissance          DATE,
    groupe_utilisateur_id   VARCHAR(15) NOT NULL,
    type_utilisateur_id     VARCHAR(15) NOT NULL,
    niveau_acces_donnees_id VARCHAR(5)  NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (groupe_utilisateur_id) REFERENCES groupe_utilisateur (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (type_utilisateur_id) REFERENCES type_utilisateur (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (niveau_acces_donnees_id) REFERENCES niveau_acces_donnees (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE audit
(
    id             BIGINT UNSIGNED AUTO_INCREMENT,
    description    TEXT,
    date_action    DATETIME,
    action_id      VARCHAR(5)  NOT NULL,
    traitement_id  VARCHAR(5)  NOT NULL,
    utilisateur_id VARCHAR(15) NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (action_id) REFERENCES action (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (traitement_id) REFERENCES traitement (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE personnel_administratif
(
    utilisateur_id VARCHAR(15),
    PRIMARY KEY (utilisateur_id),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE enseignant
(
    utilisateur_id VARCHAR(15),
    PRIMARY KEY (utilisateur_id),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE etudiant
(
    utilisateur_id VARCHAR(15),
    PRIMARY KEY (utilisateur_id),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE inscription_etudiant
(
    utilisateur_id      VARCHAR(15),
    niveau_etude_id     VARCHAR(5),
    annee_academique_id VARCHAR(5),
    date_inscription    DATE,
    montant             INT,
    PRIMARY KEY (utilisateur_id, niveau_etude_id, annee_academique_id),
    FOREIGN KEY (utilisateur_id) REFERENCES etudiant (utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (niveau_etude_id) REFERENCES niveau_etude (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (annee_academique_id) REFERENCES annee_academique (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE stage_effectue
(
    utilisateur_id VARCHAR(15),
    entreprise_id  VARCHAR(5),
    date_debut     DATE,
    date_fin       DATE,
    PRIMARY KEY (utilisateur_id, entreprise_id),
    FOREIGN KEY (utilisateur_id) REFERENCES etudiant (utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE evaluation
(
    enseignant_id   VARCHAR(15), -- This refers to Utilisateur.id via Enseignant table
    etudiant_id     VARCHAR(15), -- This refers to Utilisateur.id via Etudiant table
    ecue_id         VARCHAR(5),
    date_evaluation DATE,
    note            TINYINT UNSIGNED,
    PRIMARY KEY (enseignant_id, etudiant_id, ecue_id),
    FOREIGN KEY (enseignant_id) REFERENCES enseignant (utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (etudiant_id) REFERENCES etudiant (utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (ecue_id) REFERENCES ecue (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE depot_rapport
(
    utilisateur_id      VARCHAR(15), -- This refers to Etudiant.utilisateur_id
    rapport_etudiant_id VARCHAR(15),
    date_depot          DATE,
    PRIMARY KEY (utilisateur_id, rapport_etudiant_id),
    FOREIGN KEY (utilisateur_id) REFERENCES etudiant (utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (rapport_etudiant_id) REFERENCES rapport_etudiant (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE approbation_rapport
(
    utilisateur_id      VARCHAR(15), -- This refers to Personnel_Administratif.utilisateur_id
    rapport_etudiant_id VARCHAR(15),
    date_approbation    DATE,
    PRIMARY KEY (utilisateur_id, rapport_etudiant_id),
    FOREIGN KEY (utilisateur_id) REFERENCES personnel_administratif (utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (rapport_etudiant_id) REFERENCES rapport_etudiant (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE validation_rapport
(
    utilisateur_id      VARCHAR(15), -- This refers to Enseignant.utilisateur_id
    rapport_etudiant_id VARCHAR(15),
    date_validation     DATE,
    commentaire         TEXT,
    PRIMARY KEY (utilisateur_id, rapport_etudiant_id),
    FOREIGN KEY (utilisateur_id) REFERENCES enseignant (utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (rapport_etudiant_id) REFERENCES rapport_etudiant (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE affectation_encadrant
(
    utilisateur_id      VARCHAR(15), -- This refers to Enseignant.utilisateur_id
    rapport_etudiant_id VARCHAR(15),
    statut_jury_id      VARCHAR(15),
    date_affectation    DATE,
    PRIMARY KEY (utilisateur_id, rapport_etudiant_id, statut_jury_id),
    FOREIGN KEY (utilisateur_id) REFERENCES enseignant (utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (rapport_etudiant_id) REFERENCES rapport_etudiant (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (statut_jury_id) REFERENCES statut_jury (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE historique_fonction
(
    utilisateur_id  VARCHAR(15), -- This refers to Enseignant.utilisateur_id
    fonction_id     VARCHAR(5),
    date_occupation DATE,
    PRIMARY KEY (utilisateur_id, fonction_id),
    FOREIGN KEY (utilisateur_id) REFERENCES enseignant (utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (fonction_id) REFERENCES fonction (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE historique_grade
(
    utilisateur_id VARCHAR(15), -- This refers to Enseignant.utilisateur_id
    grade_id       VARCHAR(5),
    date_grade     DATE,
    PRIMARY KEY (utilisateur_id, grade_id),
    FOREIGN KEY (utilisateur_id) REFERENCES enseignant (utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (grade_id) REFERENCES grade (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE remise_compte_rendu
(
    utilisateur_id  VARCHAR(15), -- This refers to Enseignant.utilisateur_id
    compte_rendu_id VARCHAR(15),
    date_rendu      DATE,
    PRIMARY KEY (utilisateur_id, compte_rendu_id),
    FOREIGN KEY (utilisateur_id) REFERENCES enseignant (utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (compte_rendu_id) REFERENCES compte_rendu (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE acces_traitement
(
    traitement_id  VARCHAR(5),
    utilisateur_id VARCHAR(15),
    date_accesion  DATE,
    heure_accesion TIME,
    PRIMARY KEY (traitement_id, utilisateur_id),
    FOREIGN KEY (traitement_id) REFERENCES traitement (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE autorisation_action
(
    groupe_utilisateur_id VARCHAR(15),
    traitement_id         VARCHAR(5),
    action_id             VARCHAR(5),
    PRIMARY KEY (groupe_utilisateur_id, traitement_id, action_id),
    FOREIGN KEY (groupe_utilisateur_id) REFERENCES groupe_utilisateur (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (traitement_id) REFERENCES traitement (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (action_id) REFERENCES action (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE notification
(
    emetteur_id       VARCHAR(15),                              -- This refers to Utilisateur.id
    recepteur_id      VARCHAR(15),                              -- This refers to Utilisateur.id
    message           TEXT,
    date_notification DATETIME,
    PRIMARY KEY (emetteur_id, recepteur_id, date_notification), -- Added date_notification to PK for uniqueness
    FOREIGN KEY (emetteur_id) REFERENCES utilisateur (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (recepteur_id) REFERENCES utilisateur (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE historique_approbation
(
    niveau_approbation_id VARCHAR(15),
    compte_rendu_id       VARCHAR(15),
    date_approbation      DATETIME,
    PRIMARY KEY (niveau_approbation_id, compte_rendu_id),
    FOREIGN KEY (niveau_approbation_id) REFERENCES niveau_approbation (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (compte_rendu_id) REFERENCES compte_rendu (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE messagerie
(
    membre_commission_id VARCHAR(15), -- This refers to Enseignant.utilisateur_id
    etudiant_concerne_id VARCHAR(15), -- This refers to Etudiant.utilisateur_id
    discussion_id        VARCHAR(20),
    message              TEXT,
    date_message         DATETIME,
    PRIMARY KEY (membre_commission_id, etudiant_concerne_id, discussion_id,
                 date_message),       -- Added date_message to PK for uniqueness
    FOREIGN KEY (membre_commission_id) REFERENCES enseignant (utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (etudiant_concerne_id) REFERENCES etudiant (utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (discussion_id) REFERENCES discussion (id) ON DELETE RESTRICT ON UPDATE CASCADE
);


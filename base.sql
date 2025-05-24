<<<<<<< HEAD
CREATE TABLE Rapport_Etudiant
(
    Identifiant   VARCHAR(15),
    Titre         VARCHAR(15),
    Date_Rapport  DATE,
    Theme_Memoire VARCHAR(50),
    PRIMARY KEY (Identifiant)
);

CREATE TABLE Niveau_Approbation
(
    Identifiant VARCHAR(15),
    Libelle     VARCHAR(50),
    PRIMARY KEY (Identifiant)
);

CREATE TABLE ECUE
(
    Identifiant VARCHAR(5),
    Libelle     VARCHAR(50),
    Credit      TINYINT UNSIGNED,
    PRIMARY KEY (Identifiant)
);

CREATE TABLE Fonction
(
    Identifiant VARCHAR(5),
    Libelle     VARCHAR(50),
    PRIMARY KEY (Identifiant)
);

CREATE TABLE Grade
(
    Identifiant VARCHAR(5),
    Libelle     VARCHAR(50),
    PRIMARY KEY (Identifiant)
);

CREATE TABLE Entreprise
(
    Identifiant VARCHAR(5),
    Libelle     VARCHAR(50),
    PRIMARY KEY (Identifiant)
);

CREATE TABLE Niveau_Etude
(
    Identifiant VARCHAR(5),
    Libelle     VARCHAR(50),
    PRIMARY KEY (Identifiant)
);

CREATE TABLE Annee_Academique
(
    Identifiant VARCHAR(5),
    Date_Debut  DATE,
    Date_Fin    DATE,
    PRIMARY KEY (Identifiant)
);

CREATE TABLE Groupe_Utilisateur
(
    Identifiant VARCHAR(15),
    Libelle     VARCHAR(50),
    PRIMARY KEY (Identifiant)
);

CREATE TABLE Traitement
(
    Identifiant VARCHAR(5),
    Libelle     VARCHAR(50),
    PRIMARY KEY (Identifiant)
);

CREATE TABLE Compte_Rendu
(
    Identifiant  VARCHAR(15),
    Titre        VARCHAR(15),
    Date_Rapport DATE,
    PRIMARY KEY (Identifiant)
);

CREATE TABLE Statut_Jury
(
    Identifiant VARCHAR(15),
    Libelle     VARCHAR(50),
    PRIMARY KEY (Identifiant)
);

CREATE TABLE Niveau_Acces_Donnees
(
    Identifiant VARCHAR(5),
    Libelle     VARCHAR(50),
    PRIMARY KEY (Identifiant)
);

CREATE TABLE Action
(
    Identifiant VARCHAR(5),
    Libelle     VARCHAR(50),
    PRIMARY KEY (Identifiant)
);

CREATE TABLE Categorie_Utilisateur
(
    Identifiant VARCHAR(20),
    Libelle     VARCHAR(50),
    PRIMARY KEY (Identifiant)
);

CREATE TABLE Discussion
(
    identifiant     VARCHAR(20),
    Date_Discussion DATETIME,
    PRIMARY KEY (identifiant)
);

CREATE TABLE UE
(
    Identifiant      VARCHAR(5),
    Libelle          VARCHAR(50),
    Credit           TINYINT UNSIGNED,
    Identifiant_ECUE VARCHAR(5) NOT NULL,
    PRIMARY KEY (Identifiant),
    FOREIGN KEY (Identifiant_ECUE) REFERENCES ECUE (Identifiant)
);

CREATE TABLE Type_Utilisateur
(
    Identifiant                       VARCHAR(15),
    Libelle                           VARCHAR(50),
    Identifiant_Categorie_Utilisateur VARCHAR(20) NOT NULL,
    PRIMARY KEY (Identifiant),
    FOREIGN KEY (Identifiant_Categorie_Utilisateur) REFERENCES Categorie_Utilisateur (Identifiant)
);

CREATE TABLE Utilisateur
(
    Identifiant                      VARCHAR(15),
    Nom                              VARCHAR(15),
    Prenoms                          VARCHAR(50),
    Email                            TEXT,
    Login                            VARCHAR(15),
    Mot_De_Passe                     TEXT,
    Photo                            TEXT,
    Date_Naissance                   DATE,
    Identifiant_Groupe_Utilisateur   VARCHAR(15) NOT NULL,
    Identifiant_Type_Utilisateur     VARCHAR(15) NOT NULL,
    Identifiant_Niveau_Acces_Donnees VARCHAR(5)  NOT NULL,
    PRIMARY KEY (Identifiant),
    FOREIGN KEY (Identifiant_Groupe_Utilisateur) REFERENCES Groupe_Utilisateur (Identifiant),
    FOREIGN KEY (Identifiant_Type_Utilisateur) REFERENCES Type_Utilisateur (Identifiant),
    FOREIGN KEY (Identifiant_Niveau_Acces_Donnees) REFERENCES Niveau_Acces_Donnees (Identifiant)
);

CREATE TABLE Audit
(
    Identifiant             BIGINT UNSIGNED AUTO_INCREMENT,
    Description             TEXT,
    Date_Action             DATETIME,
    Identifiant_Action      VARCHAR(5)  NOT NULL,
    Identifiant_Traitement  VARCHAR(5)  NOT NULL,
    Identifiant_Utilisateur VARCHAR(15) NOT NULL,
    PRIMARY KEY (Identifiant),
    FOREIGN KEY (Identifiant_Action) REFERENCES Action (Identifiant),
    FOREIGN KEY (Identifiant_Traitement) REFERENCES Traitement (Identifiant),
    FOREIGN KEY (Identifiant_Utilisateur) REFERENCES Utilisateur (Identifiant)
);

CREATE TABLE Personnel_Administratif
(
    Identifiant_Utilisateur VARCHAR(15),
    PRIMARY KEY (Identifiant_Utilisateur),
    FOREIGN KEY (Identifiant_Utilisateur) REFERENCES Utilisateur (Identifiant)
);

CREATE TABLE Enseignant
(
    Identifiant_Utilisateur VARCHAR(15),
    PRIMARY KEY (Identifiant_Utilisateur),
    FOREIGN KEY (Identifiant_Utilisateur) REFERENCES Utilisateur (Identifiant)
);

CREATE TABLE Etudiant
(
    Identifiant_Utilisateur VARCHAR(15),
    PRIMARY KEY (Identifiant_Utilisateur),
    FOREIGN KEY (Identifiant_Utilisateur) REFERENCES Utilisateur (Identifiant)
);

CREATE TABLE Inscription_Etudiant
(
    Identifiant_Utilisateur      VARCHAR(15),
    Identifiant_Niveau_Etude     VARCHAR(5),
    Identifiant_Annee_Academique VARCHAR(5),
    Date_Inscription             DATE,
    Montant                      INT,
    PRIMARY KEY (Identifiant_Utilisateur, Identifiant_Niveau_Etude, Identifiant_Annee_Academique),
    FOREIGN KEY (Identifiant_Utilisateur) REFERENCES Etudiant (Identifiant_Utilisateur),
    FOREIGN KEY (Identifiant_Niveau_Etude) REFERENCES Niveau_Etude (Identifiant),
    FOREIGN KEY (Identifiant_Annee_Academique) REFERENCES Annee_Academique (Identifiant)
);

CREATE TABLE Stage_Effectue
(
    Identifiant_Utilisateur VARCHAR(15),
    Identifiant_Entreprise  VARCHAR(5),
    Date_Debut              DATE,
    Date_Fin                DATE,
    PRIMARY KEY (Identifiant_Utilisateur, Identifiant_Entreprise),
    FOREIGN KEY (Identifiant_Utilisateur) REFERENCES Etudiant (Identifiant_Utilisateur),
    FOREIGN KEY (Identifiant_Entreprise) REFERENCES Entreprise (Identifiant)
);

CREATE TABLE Evaluation
(
    Identifiant_Enseignant VARCHAR(15),
    Identifiant_Etudiant   VARCHAR(15),
    Identifiant_ECUE       VARCHAR(5),
    Date_Evaluation        DATE,
    Note                   TINYINT UNSIGNED,
    PRIMARY KEY (Identifiant_Enseignant, Identifiant_Etudiant, Identifiant_ECUE),
    FOREIGN KEY (Identifiant_Enseignant) REFERENCES Enseignant (Identifiant_Utilisateur),
    FOREIGN KEY (Identifiant_Etudiant) REFERENCES Etudiant (Identifiant_Utilisateur),
    FOREIGN KEY (Identifiant_ECUE) REFERENCES ECUE (Identifiant)
);

CREATE TABLE Depot_Rapport
(
    Identifiant_Utilisateur      VARCHAR(15),
    Identifiant_Rapport_Etudiant VARCHAR(15),
    Date_Depot                   DATE,
    PRIMARY KEY (Identifiant_Utilisateur, Identifiant_Rapport_Etudiant),
    FOREIGN KEY (Identifiant_Utilisateur) REFERENCES Etudiant (Identifiant_Utilisateur),
    FOREIGN KEY (Identifiant_Rapport_Etudiant) REFERENCES Rapport_Etudiant (Identifiant)
);

CREATE TABLE Approbation_Rapport
(
    Identifiant_Utilisateur      VARCHAR(15),
    Identifiant_Rapport_Etudiant VARCHAR(15),
    Date_Approbation             DATE,
    PRIMARY KEY (Identifiant_Utilisateur, Identifiant_Rapport_Etudiant),
    FOREIGN KEY (Identifiant_Utilisateur) REFERENCES Personnel_Administratif (Identifiant_Utilisateur),
    FOREIGN KEY (Identifiant_Rapport_Etudiant) REFERENCES Rapport_Etudiant (Identifiant)
);

CREATE TABLE Validation_Rapport
(
    Identifiant_Utilisateur      VARCHAR(15),
    Identifiant_Rapport_Etudiant VARCHAR(15),
    Date_Validation              DATE,
    Commentaire                  TEXT,
    PRIMARY KEY (Identifiant_Utilisateur, Identifiant_Rapport_Etudiant),
    FOREIGN KEY (Identifiant_Utilisateur) REFERENCES Enseignant (Identifiant_Utilisateur),
    FOREIGN KEY (Identifiant_Rapport_Etudiant) REFERENCES Rapport_Etudiant (Identifiant)
);

CREATE TABLE Affectation_Encadrant
(
    Identifiant_Utilisateur      VARCHAR(15),
    Identifiant_Rapport_Etudiant VARCHAR(15),
    Identifiant_Statut_Jury      VARCHAR(15),
    Date_Affectation             DATE,
    PRIMARY KEY (Identifiant_Utilisateur, Identifiant_Rapport_Etudiant, Identifiant_Statut_Jury),
    FOREIGN KEY (Identifiant_Utilisateur) REFERENCES Enseignant (Identifiant_Utilisateur),
    FOREIGN KEY (Identifiant_Rapport_Etudiant) REFERENCES Rapport_Etudiant (Identifiant),
    FOREIGN KEY (Identifiant_Statut_Jury) REFERENCES Statut_Jury (Identifiant)
);

CREATE TABLE Historique_Fonction
(
    Identifiant_Utilisateur VARCHAR(15),
    Identifiant_Fonction    VARCHAR(5),
    Date_Occupation         DATE,
    PRIMARY KEY (Identifiant_Utilisateur, Identifiant_Fonction),
    FOREIGN KEY (Identifiant_Utilisateur) REFERENCES Enseignant (Identifiant_Utilisateur),
    FOREIGN KEY (Identifiant_Fonction) REFERENCES Fonction (Identifiant)
);

CREATE TABLE Historique_Grade
(
    Identifiant_Utilisateur VARCHAR(15),
    Identifiant_Grade       VARCHAR(5),
    Date_Grade              DATE,
    PRIMARY KEY (Identifiant_Utilisateur, Identifiant_Grade),
    FOREIGN KEY (Identifiant_Utilisateur) REFERENCES Enseignant (Identifiant_Utilisateur),
    FOREIGN KEY (Identifiant_Grade) REFERENCES Grade (Identifiant)
);

CREATE TABLE Remise_Compte_Rendu
(
    Identifiant_Utilisateur  VARCHAR(15),
    Identifiant_Compte_Rendu VARCHAR(15),
    Date_Rendu               DATE,
    PRIMARY KEY (Identifiant_Utilisateur, Identifiant_Compte_Rendu),
    FOREIGN KEY (Identifiant_Utilisateur) REFERENCES Enseignant (Identifiant_Utilisateur),
    FOREIGN KEY (Identifiant_Compte_Rendu) REFERENCES Compte_Rendu (Identifiant)
);

CREATE TABLE Acces_Traitement
(
    Identifiant_Traitement  VARCHAR(5),
    Identifiant_Utilisateur VARCHAR(15),
    Date_Accesion           DATE,
    Heure_Accesion          TIME,
    PRIMARY KEY (Identifiant_Traitement, Identifiant_Utilisateur),
    FOREIGN KEY (Identifiant_Traitement) REFERENCES Traitement (Identifiant),
    FOREIGN KEY (Identifiant_Utilisateur) REFERENCES Utilisateur (Identifiant)
);

CREATE TABLE Autorisation_Action
(
    Identifiant_Groupe_Utilisateur VARCHAR(15),
    Identifiant_Traitement         VARCHAR(5),
    Identifiant_Action             VARCHAR(5),
    PRIMARY KEY (Identifiant_Groupe_Utilisateur, Identifiant_Traitement, Identifiant_Action),
    FOREIGN KEY (Identifiant_Groupe_Utilisateur) REFERENCES Groupe_Utilisateur (Identifiant),
    FOREIGN KEY (Identifiant_Traitement) REFERENCES Traitement (Identifiant),
    FOREIGN KEY (Identifiant_Action) REFERENCES Action (Identifiant)
);

CREATE TABLE Notification
(
    Identifiant_Emetteur  VARCHAR(15),
    Identifiant_Recepteur VARCHAR(15),
    Message               TEXT,
    Date_Notification     DATETIME,
    PRIMARY KEY (Identifiant_Emetteur, Identifiant_Recepteur),
    FOREIGN KEY (Identifiant_Emetteur) REFERENCES Utilisateur (Identifiant),
    FOREIGN KEY (Identifiant_Recepteur) REFERENCES Utilisateur (Identifiant)
);

CREATE TABLE Historique_Approbation
(
    Identifiant_Niveau_Approbation VARCHAR(15),
    Identifiant_Compte_Rendu       VARCHAR(15),
    Date_Approbation               DATETIME,
    PRIMARY KEY (Identifiant_Niveau_Approbation, Identifiant_Compte_Rendu),
    FOREIGN KEY (Identifiant_Niveau_Approbation) REFERENCES Niveau_Approbation (Identifiant),
    FOREIGN KEY (Identifiant_Compte_Rendu) REFERENCES Compte_Rendu (Identifiant)
);

CREATE TABLE Messagerie
(
    Identifiant_Membre_Commission VARCHAR(15),
    Identifiant_Etudiant_Concerne VARCHAR(15),
    identifiant_Discussion        VARCHAR(20),
    Message                       TEXT,
    Date_Message                  DATETIME,
    PRIMARY KEY (Identifiant_Membre_Commission, Identifiant_Etudiant_Concerne, identifiant_Discussion),
    FOREIGN KEY (Identifiant_Membre_Commission) REFERENCES Enseignant (Identifiant_Utilisateur),
    FOREIGN KEY (Identifiant_Etudiant_Concerne) REFERENCES Etudiant (Identifiant_Utilisateur),
    FOREIGN KEY (identifiant_Discussion) REFERENCES Discussion (identifiant)
=======
CREATE TABLE rapport_etudiant(
                                 id VARCHAR(15),
                                 titre VARCHAR(255),
                                 date_rapport DATE,
                                 theme_memoire VARCHAR(50),
                                 PRIMARY KEY(id)
);

CREATE TABLE niveau_approbation(
                                   id VARCHAR(15),
                                   libelle VARCHAR(50),
                                   PRIMARY KEY(id)
);

CREATE TABLE ecue(
                     id VARCHAR(5),
                     libelle VARCHAR(50),
                     credit TINYINT UNSIGNED,
                     PRIMARY KEY(id)
);

CREATE TABLE fonction(
                         id VARCHAR(5),
                         libelle VARCHAR(50),
                         PRIMARY KEY(id)
);

CREATE TABLE grade(
                      id VARCHAR(5),
                      libelle VARCHAR(50),
                      PRIMARY KEY(id)
);

CREATE TABLE entreprise(
                           id VARCHAR(5),
                           libelle VARCHAR(50),
                           PRIMARY KEY(id)
);

CREATE TABLE niveau_etude(
                             id VARCHAR(5),
                             libelle VARCHAR(50),
                             PRIMARY KEY(id)
);

CREATE TABLE annee_academique(
                                 id VARCHAR(5),
                                 date_debut DATE,
                                 date_fin DATE,
                                 PRIMARY KEY(id)
);

CREATE TABLE groupe_utilisateur(
                                   id VARCHAR(15),
                                   libelle VARCHAR(50),
                                   PRIMARY KEY(id)
);

CREATE TABLE traitement(
                           id VARCHAR(5),
                           libelle VARCHAR(50),
                           PRIMARY KEY(id)
);

CREATE TABLE compte_rendu(
                             id VARCHAR(15),
                             titre VARCHAR(255),
                             date_rapport DATE,
                             PRIMARY KEY(id)
);

CREATE TABLE statut_jury(
                            id VARCHAR(15),
                            libelle VARCHAR(50),
                            PRIMARY KEY(id)
);

CREATE TABLE niveau_acces_donnees(
                                     id VARCHAR(5),
                                     libelle VARCHAR(50),
                                     PRIMARY KEY(id)
);

CREATE TABLE action(
                       id VARCHAR(5),
                       libelle VARCHAR(50),
                       PRIMARY KEY(id)
);

CREATE TABLE categorie_utilisateur(
                                      id VARCHAR(20),
                                      libelle VARCHAR(50),
                                      PRIMARY KEY(id)
);

CREATE TABLE discussion(
                           id VARCHAR(20),
                           date_discussion DATETIME,
                           PRIMARY KEY(id)
);

CREATE TABLE ue(
                   id VARCHAR(5),
                   libelle VARCHAR(50),
                   credit TINYINT UNSIGNED,
                   ecue_id VARCHAR(5) NOT NULL,
                   PRIMARY KEY(id),
                   FOREIGN KEY(ecue_id) REFERENCES ecue(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE type_utilisateur(
                                 id VARCHAR(15),
                                 libelle VARCHAR(50),
                                 categorie_utilisateur_id VARCHAR(20) NOT NULL,
                                 PRIMARY KEY(id),
                                 FOREIGN KEY(categorie_utilisateur_id) REFERENCES categorie_utilisateur(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE utilisateur(
                            id VARCHAR(15),
                            nom VARCHAR(15),
                            prenoms VARCHAR(50),
                            email VARCHAR(255),
                            login VARCHAR(50),
                            mot_de_passe VARCHAR(255), -- Should store hashed passwords
                            photo VARCHAR(255), -- Path or URL to the photo file
                            date_naissance DATE,
                            groupe_utilisateur_id VARCHAR(15) NOT NULL,
                            type_utilisateur_id VARCHAR(15) NOT NULL,
                            niveau_acces_donnees_id VARCHAR(5) NOT NULL,
                            PRIMARY KEY(id),
                            FOREIGN KEY(groupe_utilisateur_id) REFERENCES groupe_utilisateur(id) ON DELETE RESTRICT ON UPDATE CASCADE,
                            FOREIGN KEY(type_utilisateur_id) REFERENCES type_utilisateur(id) ON DELETE RESTRICT ON UPDATE CASCADE,
                            FOREIGN KEY(niveau_acces_donnees_id) REFERENCES niveau_acces_donnees(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE audit(
                      id BIGINT UNSIGNED AUTO_INCREMENT,
                      description TEXT,
                      date_action DATETIME,
                      action_id VARCHAR(5) NOT NULL,
                      traitement_id VARCHAR(5) NOT NULL,
                      utilisateur_id VARCHAR(15) NOT NULL,
                      PRIMARY KEY(id),
                      FOREIGN KEY(action_id) REFERENCES action(id) ON DELETE RESTRICT ON UPDATE CASCADE,
                      FOREIGN KEY(traitement_id) REFERENCES traitement(id) ON DELETE RESTRICT ON UPDATE CASCADE,
                      FOREIGN KEY(utilisateur_id) REFERENCES utilisateur(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE personnel_administratif(
                                        utilisateur_id VARCHAR(15),
                                        PRIMARY KEY(utilisateur_id),
                                        FOREIGN KEY(utilisateur_id) REFERENCES utilisateur(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE enseignant(
                           utilisateur_id VARCHAR(15),
                           PRIMARY KEY(utilisateur_id),
                           FOREIGN KEY(utilisateur_id) REFERENCES utilisateur(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE etudiant(
                         utilisateur_id VARCHAR(15),
                         PRIMARY KEY(utilisateur_id),
                         FOREIGN KEY(utilisateur_id) REFERENCES utilisateur(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE inscription_etudiant(
                                     utilisateur_id VARCHAR(15),
                                     niveau_etude_id VARCHAR(5),
                                     annee_academique_id VARCHAR(5),
                                     date_inscription DATE,
                                     montant INT,
                                     PRIMARY KEY(utilisateur_id, niveau_etude_id, annee_academique_id),
                                     FOREIGN KEY(utilisateur_id) REFERENCES etudiant(utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
                                     FOREIGN KEY(niveau_etude_id) REFERENCES niveau_etude(id) ON DELETE RESTRICT ON UPDATE CASCADE,
                                     FOREIGN KEY(annee_academique_id) REFERENCES annee_academique(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE stage_effectue(
                               utilisateur_id VARCHAR(15),
                               entreprise_id VARCHAR(5),
                               date_debut DATE,
                               date_fin DATE,
                               PRIMARY KEY(utilisateur_id, entreprise_id),
                               FOREIGN KEY(utilisateur_id) REFERENCES etudiant(utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
                               FOREIGN KEY(entreprise_id) REFERENCES entreprise(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE evaluation(
                           enseignant_id VARCHAR(15), -- This refers to Utilisateur.id via Enseignant table
                           etudiant_id VARCHAR(15),   -- This refers to Utilisateur.id via Etudiant table
                           ecue_id VARCHAR(5),
                           date_evaluation DATE,
                           note TINYINT UNSIGNED,
                           PRIMARY KEY(enseignant_id, etudiant_id, ecue_id),
                           FOREIGN KEY(enseignant_id) REFERENCES enseignant(utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
                           FOREIGN KEY(etudiant_id) REFERENCES etudiant(utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
                           FOREIGN KEY(ecue_id) REFERENCES ecue(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE depot_rapport(
                              utilisateur_id VARCHAR(15), -- This refers to Etudiant.utilisateur_id
                              rapport_etudiant_id VARCHAR(15),
                              date_depot DATE,
                              PRIMARY KEY(utilisateur_id, rapport_etudiant_id),
                              FOREIGN KEY(utilisateur_id) REFERENCES etudiant(utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
                              FOREIGN KEY(rapport_etudiant_id) REFERENCES rapport_etudiant(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE approbation_rapport(
                                    utilisateur_id VARCHAR(15), -- This refers to Personnel_Administratif.utilisateur_id
                                    rapport_etudiant_id VARCHAR(15),
                                    date_approbation DATE,
                                    PRIMARY KEY(utilisateur_id, rapport_etudiant_id),
                                    FOREIGN KEY(utilisateur_id) REFERENCES personnel_administratif(utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
                                    FOREIGN KEY(rapport_etudiant_id) REFERENCES rapport_etudiant(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE validation_rapport(
                                   utilisateur_id VARCHAR(15), -- This refers to Enseignant.utilisateur_id
                                   rapport_etudiant_id VARCHAR(15),
                                   date_validation DATE,
                                   commentaire TEXT,
                                   PRIMARY KEY(utilisateur_id, rapport_etudiant_id),
                                   FOREIGN KEY(utilisateur_id) REFERENCES enseignant(utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
                                   FOREIGN KEY(rapport_etudiant_id) REFERENCES rapport_etudiant(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE affectation_encadrant(
                                      utilisateur_id VARCHAR(15), -- This refers to Enseignant.utilisateur_id
                                      rapport_etudiant_id VARCHAR(15),
                                      statut_jury_id VARCHAR(15),
                                      date_affectation DATE,
                                      PRIMARY KEY(utilisateur_id, rapport_etudiant_id, statut_jury_id),
                                      FOREIGN KEY(utilisateur_id) REFERENCES enseignant(utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
                                      FOREIGN KEY(rapport_etudiant_id) REFERENCES rapport_etudiant(id) ON DELETE RESTRICT ON UPDATE CASCADE,
                                      FOREIGN KEY(statut_jury_id) REFERENCES statut_jury(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE historique_fonction(
                                    utilisateur_id VARCHAR(15), -- This refers to Enseignant.utilisateur_id
                                    fonction_id VARCHAR(5),
                                    date_occupation DATE,
                                    PRIMARY KEY(utilisateur_id, fonction_id),
                                    FOREIGN KEY(utilisateur_id) REFERENCES enseignant(utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
                                    FOREIGN KEY(fonction_id) REFERENCES fonction(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE historique_grade(
                                 utilisateur_id VARCHAR(15), -- This refers to Enseignant.utilisateur_id
                                 grade_id VARCHAR(5),
                                 date_grade DATE,
                                 PRIMARY KEY(utilisateur_id, grade_id),
                                 FOREIGN KEY(utilisateur_id) REFERENCES enseignant(utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
                                 FOREIGN KEY(grade_id) REFERENCES grade(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE remise_compte_rendu(
                                    utilisateur_id VARCHAR(15), -- This refers to Enseignant.utilisateur_id
                                    compte_rendu_id VARCHAR(15),
                                    date_rendu DATE,
                                    PRIMARY KEY(utilisateur_id, compte_rendu_id),
                                    FOREIGN KEY(utilisateur_id) REFERENCES enseignant(utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
                                    FOREIGN KEY(compte_rendu_id) REFERENCES compte_rendu(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE acces_traitement(
                                 traitement_id VARCHAR(5),
                                 utilisateur_id VARCHAR(15),
                                 date_accesion DATE,
                                 heure_accesion TIME,
                                 PRIMARY KEY(traitement_id, utilisateur_id),
                                 FOREIGN KEY(traitement_id) REFERENCES traitement(id) ON DELETE RESTRICT ON UPDATE CASCADE,
                                 FOREIGN KEY(utilisateur_id) REFERENCES utilisateur(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE autorisation_action(
                                    groupe_utilisateur_id VARCHAR(15),
                                    traitement_id VARCHAR(5),
                                    action_id VARCHAR(5),
                                    PRIMARY KEY(groupe_utilisateur_id, traitement_id, action_id),
                                    FOREIGN KEY(groupe_utilisateur_id) REFERENCES groupe_utilisateur(id) ON DELETE RESTRICT ON UPDATE CASCADE,
                                    FOREIGN KEY(traitement_id) REFERENCES traitement(id) ON DELETE RESTRICT ON UPDATE CASCADE,
                                    FOREIGN KEY(action_id) REFERENCES action(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE notification(
                             emetteur_id VARCHAR(15),      -- This refers to Utilisateur.id
                             recepteur_id VARCHAR(15),     -- This refers to Utilisateur.id
                             message TEXT,
                             date_notification DATETIME,
                             PRIMARY KEY(emetteur_id, recepteur_id, date_notification), -- Added date_notification to PK for uniqueness
                             FOREIGN KEY(emetteur_id) REFERENCES utilisateur(id) ON DELETE RESTRICT ON UPDATE CASCADE,
                             FOREIGN KEY(recepteur_id) REFERENCES utilisateur(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE historique_approbation(
                                       niveau_approbation_id VARCHAR(15),
                                       compte_rendu_id VARCHAR(15),
                                       date_approbation DATETIME,
                                       PRIMARY KEY(niveau_approbation_id, compte_rendu_id),
                                       FOREIGN KEY(niveau_approbation_id) REFERENCES niveau_approbation(id) ON DELETE RESTRICT ON UPDATE CASCADE,
                                       FOREIGN KEY(compte_rendu_id) REFERENCES compte_rendu(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE messagerie(
                           membre_commission_id VARCHAR(15),    -- This refers to Enseignant.utilisateur_id
                           etudiant_concerne_id VARCHAR(15),  -- This refers to Etudiant.utilisateur_id
                           discussion_id VARCHAR(20),
                           message TEXT,
                           date_message DATETIME,
                           PRIMARY KEY(membre_commission_id, etudiant_concerne_id, discussion_id, date_message), -- Added date_message to PK for uniqueness
                           FOREIGN KEY(membre_commission_id) REFERENCES enseignant(utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
                           FOREIGN KEY(etudiant_concerne_id) REFERENCES etudiant(utilisateur_id) ON DELETE RESTRICT ON UPDATE CASCADE,
                           FOREIGN KEY(discussion_id) REFERENCES discussion(id) ON DELETE RESTRICT ON UPDATE CASCADE
>>>>>>> github/finish-database-script
);

[end of base.sql]

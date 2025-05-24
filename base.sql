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
);

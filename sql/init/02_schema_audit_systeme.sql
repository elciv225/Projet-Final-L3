-- =================================================================
--                  TABLES D'AUDIT
-- =================================================================

-- TABLE audit
-- Description: Log de l'exécution d'un traitement par un utilisateur.
CREATE TABLE audit
(
    id              BIGINT PRIMARY KEY AUTO_INCREMENT,
    description     VARCHAR(255),                 -- Texte généré avec le libellé du traitement et les libellés des actions
    date_traitement TIMESTAMP,
    traitement_id   VARCHAR(20) NOT NULL, -- ex: 'TRT_INSCRIPTION'
    utilisateur_id  VARCHAR(30) NOT NULL, -- ex: '21ADM005'
    FOREIGN KEY (traitement_id) REFERENCES traitement (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- TABLE audit_action
-- Description: Log de chaque action d'un traitement audité.
CREATE TABLE audit_action
(
    id              BIGINT PRIMARY KEY AUTO_INCREMENT,
    audit_id        BIGINT                                            NOT NULL, -- Référence l'audit parent
    action_id       VARCHAR(20)                                       NOT NULL, -- ex: 'ACT_VERIF_PAIE'
    ordre           SMALLINT,
    heure_execution TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    statut          VARCHAR(10) CHECK (statut IN ('SUCCES', 'ECHEC')) NOT NULL,
    message         VARCHAR(255),
    FOREIGN KEY (audit_id) REFERENCES audit (id) ON DELETE CASCADE,
    FOREIGN KEY (action_id) REFERENCES action (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- =================================================================
--                  TABLES SYSTEME
-- =================================================================

-- TABLE etat_sauvegarde
-- Description: Permet de sauvegarder et de restaurer les états précédents des enregistrements en cas de besoin.
CREATE TABLE etat_sauvegarde
(
    id                BIGINT primary key AUTO_INCREMENT,
    nom_table         VARCHAR(255) NOT NULL,
    enregistrement_id VARCHAR(255) NOT NULL,
    donnees           JSON NOT NULL,
    date_sauvegarde   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    traitement_id     VARCHAR(255),
    utilisateur_id    VARCHAR(255),
    FOREIGN KEY (traitement_id) REFERENCES traitement (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- =================================================================
--            SYSTÈME DE GESTION UNIVERSITAIRE COMPLET
-- =================================================================
-- Fichier: 03_procedures_fonctions_triggers.sql
-- Description: Contient les procédures stockées, fonctions, triggers et indexes.
-- CORRECTIONS:
-- 1. Alignement des types de données des paramètres des procédures avec le schéma.
-- 2. Correction des valeurs codées en dur (IDs de groupe/type) pour correspondre aux données.
-- 3. Correction de la logique du trigger d'audit pour l'insertion.
-- =================================================================


-- =================================================================
-- 1. PROCÉDURES STOCKÉES
-- =================================================================

DELIMITER //

-- Procédure d'inscription complète d'un étudiant
CREATE PROCEDURE sp_inscrire_etudiant(
    IN p_nom VARCHAR(50),
    IN p_prenoms VARCHAR(100),
    IN p_email VARCHAR(255),
    IN p_mot_de_passe VARCHAR(255),
    IN p_date_naissance DATE,
    IN p_niveau_etude_id VARCHAR(30),
    IN p_annee_academique_id VARCHAR(10),
    IN p_montant INT,
    OUT p_matricule VARCHAR(30)
)
BEGIN
    DECLARE v_last_num INT DEFAULT 0;
    DECLARE v_matricule VARCHAR(30);
    DECLARE v_numero_carte VARCHAR(30);
    DECLARE v_prefix_nom VARCHAR(4);
    DECLARE v_prefix_date VARCHAR(6);
    DECLARE v_full_prefix VARCHAR(10);
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            ROLLBACK;
            RESIGNAL;
        END;

    START TRANSACTION;

    -- *** CORRECTION: Nouvelle logique de génération d'ID robuste ***
-- 1. Construire le préfixe basé sur le nom et la date de naissance
    SET v_prefix_nom = UPPER(SUBSTRING(REPLACE(p_nom, ' ', ''), 1, 4)); -- 4 premières lettres du nom
    SET v_prefix_date = DATE_FORMAT(p_date_naissance, '%d%m%y'); -- Format JJMMAA
    SET v_full_prefix = CONCAT(v_prefix_nom, v_prefix_date);
    -- Ex: 'ASSE120605'

-- 2. Trouver le plus grand numéro séquentiel pour ce préfixe et l'incrémenter
    SELECT IFNULL(MAX(CAST(SUBSTRING(id, LENGTH(v_full_prefix) + 1) AS UNSIGNED)), 0)
    INTO v_last_num
    FROM utilisateur
    WHERE id LIKE CONCAT(v_full_prefix, '%');

-- 3. Construire le matricule final
    SET v_matricule = CONCAT(v_full_prefix, LPAD(v_last_num + 1, 4, '0'));
    -- Ex: 'ASSE1206050001'

-- 4. Générer le numéro de carte basé sur la même logique
    SET v_numero_carte = CONCAT('CE', v_prefix_date, LPAD(v_last_num + 1, 4, '0'));
    -- Ex: 'CE1206050001'

-- 5. Insérer l'utilisateur (le login est maintenant le matricule)
    INSERT INTO utilisateur (id, nom, prenoms, email, login, mot_de_passe, date_naissance,
                             groupe_utilisateur_id, type_utilisateur_id)
    VALUES (v_matricule, p_nom, p_prenoms, p_email, v_matricule, SHA2(p_mot_de_passe, 256), p_date_naissance,
            'GRP_ETUDIANTS',
            CASE
                WHEN p_niveau_etude_id = 'NIVEAU_LICENCE1' THEN 'TYPE_ETUDIANT_L1'
                WHEN p_niveau_etude_id = 'NIVEAU_LICENCE2' THEN 'TYPE_ETUDIANT_L2'
                WHEN p_niveau_etude_id = 'NIVEAU_LICENCE3' THEN 'TYPE_ETUDIANT_L3'
                WHEN p_niveau_etude_id = 'NIVEAU_MASTER1' THEN 'TYPE_ETUDIANT_M1'
                WHEN p_niveau_etude_id = 'NIVEAU_MASTER2' THEN 'TYPE_ETUDIANT_M2'
                ELSE 'TYPE_UNDEFINED'
                END);

-- 6. Insérer dans la table étudiant
    INSERT INTO etudiant (utilisateur_id, numero_carte)
    VALUES (v_matricule, v_numero_carte);

-- 7. Insérer l'inscription
    INSERT INTO inscription_etudiant (utilisateur_id, niveau_etude_id, annee_academique_id, date_inscription, montant)
    VALUES (v_matricule, p_niveau_etude_id, p_annee_academique_id, CURDATE(), p_montant);

    SET p_matricule = v_matricule;
    COMMIT;
END//

-- Procédure de suppression complète d'un étudiant
CREATE PROCEDURE sp_supprimer_etudiant(IN p_utilisateur_id VARCHAR(30))
BEGIN
    -- Déclaration d'un curseur pour itérer sur les rapports de l'étudiant
    DECLARE v_rapport_id VARCHAR(40);
    DECLARE v_done INT DEFAULT FALSE;
    DECLARE cur_rapports CURSOR FOR
        SELECT rapport_etudiant_id FROM depot_rapport WHERE utilisateur_id = p_utilisateur_id;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_done = TRUE;

-- Gestionnaire d'erreurs pour la transaction
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            ROLLBACK;
            RESIGNAL; -- Propage l'erreur
        END;

    START TRANSACTION;

-- 1. Supprimer les dépendances simples
    DELETE FROM inscription_etudiant WHERE utilisateur_id = p_utilisateur_id;
    DELETE FROM evaluation WHERE etudiant_id = p_utilisateur_id;
    DELETE FROM stage_effectue WHERE utilisateur_id = p_utilisateur_id;
    DELETE FROM messagerie WHERE etudiant_concerne_id = p_utilisateur_id;

-- 2. Gérer la suppression complexe des rapports en cascade
    OPEN cur_rapports;
    read_loop:
    LOOP
        FETCH cur_rapports INTO v_rapport_id;
        IF v_done THEN
            LEAVE read_loop;
        END IF;

        -- Supprimer les dépendances d'un rapport avant de le supprimer lui-même
        DELETE FROM affectation_encadrant WHERE rapport_etudiant_id = v_rapport_id;
        DELETE FROM validation_rapport WHERE rapport_etudiant_id = v_rapport_id;
        DELETE FROM approbation_rapport WHERE rapport_etudiant_id = v_rapport_id;
        DELETE FROM depot_rapport WHERE rapport_etudiant_id = v_rapport_id;
        DELETE FROM rapport_etudiant WHERE id = v_rapport_id;
    END LOOP;
    CLOSE cur_rapports;

    -- 3. Supprimer les notifications où l'étudiant est émetteur ou récepteur
    DELETE FROM notification WHERE emetteur_id = p_utilisateur_id OR recepteur_id = p_utilisateur_id;

-- 4. Supprimer l'entrée de la table de spécialisation 'etudiant'
    DELETE FROM etudiant WHERE utilisateur_id = p_utilisateur_id;

-- 5. Finalement, supprimer l'utilisateur de la table principale
    DELETE FROM utilisateur WHERE id = p_utilisateur_id;
    COMMIT;
END//

-- Procédure de création d'un enseignant
CREATE PROCEDURE sp_creer_enseignant(
    IN p_nom VARCHAR(50),
    IN p_prenoms VARCHAR(100),
    IN p_email VARCHAR(255),
    IN p_mot_de_passe VARCHAR(255),
    IN p_date_naissance DATE,
    IN p_grade_id VARCHAR(30),
    IN p_specialite_id VARCHAR(30),
    IN p_fonction_id VARCHAR(30),
    OUT p_user_id VARCHAR(30)
)
BEGIN
    DECLARE v_last_num INT DEFAULT 0;
    DECLARE v_login VARCHAR(50);
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            ROLLBACK;
            RESIGNAL;
        END;

    START TRANSACTION;

    SELECT IFNULL(MAX(CAST(SUBSTRING(id, 4) AS UNSIGNED)), 0) INTO v_last_num FROM utilisateur WHERE id LIKE 'ENS%';
    SET p_user_id = CONCAT('ENS', LPAD(v_last_num + 1, 3, '0'));
    SET v_login = CONCAT(LOWER(SUBSTRING(p_prenoms, 1, 1)), LOWER(REPLACE(p_nom, ' ', '')));

    INSERT INTO utilisateur (id, nom, prenoms, email, login, mot_de_passe, date_naissance, groupe_utilisateur_id,
                             type_utilisateur_id)
    VALUES (p_user_id, p_nom, p_prenoms, p_email, v_login, SHA2(p_mot_de_passe, 256), p_date_naissance,
            'GRP_VALID_RAPPORT', 'TYPE_ENSEIGNANT_PERM');

    INSERT INTO enseignant (utilisateur_id) VALUES (p_user_id);

    IF p_grade_id IS NOT NULL AND p_grade_id != '' THEN
        INSERT INTO historique_grade (utilisateur_id, grade_id, date_grade) VALUES (p_user_id, p_grade_id, CURDATE());
    END IF;
    IF p_specialite_id IS NOT NULL AND p_specialite_id != '' THEN
        INSERT INTO historique_specialite (utilisateur_id, specialite_id, date_occupation)
        VALUES (p_user_id, p_specialite_id, CURDATE());
    END IF;
    IF p_fonction_id IS NOT NULL AND p_fonction_id != '' THEN
        INSERT INTO historique_fonction (utilisateur_id, fonction_id, date_occupation)
        VALUES (p_user_id, p_fonction_id, CURDATE());
    END IF;
    COMMIT;
END//


-- Procédure de création d'un membre du personnel administratif
CREATE PROCEDURE sp_creer_personnel_admin(
    IN p_nom VARCHAR(50),
    IN p_prenoms VARCHAR(100),
    IN p_email VARCHAR(255),
    IN p_mot_de_passe VARCHAR(255),
    IN p_date_naissance DATE,
    OUT p_user_id VARCHAR(30)
)
BEGIN
    DECLARE v_last_num INT DEFAULT 0;
    DECLARE v_login VARCHAR(50);
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            ROLLBACK;
            RESIGNAL;
        END;

    START TRANSACTION;

    SELECT IFNULL(MAX(CAST(SUBSTRING(id, 4) AS UNSIGNED)), 0) INTO v_last_num FROM utilisateur WHERE id LIKE 'ADM%';
    SET p_user_id = CONCAT('ADM', LPAD(v_last_num + 1, 3, '0'));
    SET v_login = CONCAT(LOWER(SUBSTRING(p_prenoms, 1, 1)), LOWER(REPLACE(p_nom, ' ', '')));

    INSERT INTO utilisateur (id, nom, prenoms, email, login, mot_de_passe, date_naissance, groupe_utilisateur_id,
                             type_utilisateur_id)
    VALUES (p_user_id, p_nom, p_prenoms, p_email, v_login, SHA2(p_mot_de_passe, 256), p_date_naissance,
            'GRP_ADMIN_PEDAGO', 'TYPE_ADMIN_SCOLARITE');

    INSERT INTO personnel_administratif (utilisateur_id) VALUES (p_user_id);
    COMMIT;
END//

-- =================================================================
-- 📌 PROCÉDURES DE MODIFICATION
-- =================================================================

-- Procédure de modification d'un enseignant
CREATE PROCEDURE sp_modifier_enseignant(
    IN p_user_id VARCHAR(30),
    IN p_nom VARCHAR(50),
    IN p_prenoms VARCHAR(100),
    IN p_email VARCHAR(255),
    IN p_date_naissance DATE,
    IN p_grade_id VARCHAR(30),
    IN p_specialite_id VARCHAR(30),
    IN p_fonction_id VARCHAR(30)
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            ROLLBACK;
            RESIGNAL;
        END;

    START TRANSACTION;

-- Mettre à jour les informations de base de l'utilisateur
    UPDATE utilisateur
    SET nom            = p_nom,
        prenoms        = p_prenoms,
        email          = p_email,
        date_naissance = p_date_naissance
    WHERE id = p_user_id;

-- Mettre à jour les informations historiques (crée une nouvelle entrée si elle n'existe pas)
    IF p_grade_id IS NOT NULL AND p_grade_id != '' THEN
        INSERT INTO historique_grade (utilisateur_id, grade_id, date_grade)
        VALUES (p_user_id, p_grade_id, CURDATE())
        ON DUPLICATE KEY UPDATE date_grade = CURDATE();
    END IF;
    IF p_specialite_id IS NOT NULL AND p_specialite_id != '' THEN
        INSERT INTO historique_specialite (utilisateur_id, specialite_id, date_occupation)
        VALUES (p_user_id, p_specialite_id, CURDATE())
        ON DUPLICATE KEY UPDATE date_occupation = CURDATE();
    END IF;
    IF p_fonction_id IS NOT NULL AND p_fonction_id != '' THEN
        INSERT INTO historique_fonction (utilisateur_id, fonction_id, date_occupation)
        VALUES (p_user_id, p_fonction_id, CURDATE())
        ON DUPLICATE KEY UPDATE date_occupation = CURDATE();
    END IF;
    COMMIT;
END//


-- Procédure de modification d'un membre du personnel administratif
CREATE PROCEDURE sp_modifier_personnel_admin(
    IN p_user_id VARCHAR(30),
    IN p_nom VARCHAR(50),
    IN p_prenoms VARCHAR(100),
    IN p_email VARCHAR(255),
    IN p_date_naissance DATE
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            ROLLBACK;
            RESIGNAL;
        END;

    START TRANSACTION;

-- Mettre à jour les informations de base de l'utilisateur
    UPDATE utilisateur
    SET nom            = p_nom,
        prenoms        = p_prenoms,
        email          = p_email,
        date_naissance = p_date_naissance
    WHERE id = p_user_id;
    COMMIT;
END//


-- =================================================================
-- 📌 PROCÉDURE DE SUPPRESSION
-- =================================================================

-- Procédure de suppression sécurisée d'un membre du personnel (enseignant ou admin)
CREATE PROCEDURE sp_supprimer_personnel(IN p_utilisateur_id VARCHAR(30))
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            ROLLBACK;
            RESIGNAL;
        END;

    START TRANSACTION;

-- Supprimer les dépendances (enseignant ou admin)
    DELETE FROM historique_grade WHERE utilisateur_id = p_utilisateur_id;
    DELETE FROM historique_specialite WHERE utilisateur_id = p_utilisateur_id;
    DELETE FROM historique_fonction WHERE utilisateur_id = p_utilisateur_id;
    DELETE FROM affectation_encadrant WHERE utilisateur_id = p_utilisateur_id;
    DELETE FROM validation_rapport WHERE utilisateur_id = p_utilisateur_id;
    DELETE FROM evaluation WHERE enseignant_id = p_utilisateur_id;
    DELETE FROM approbation_rapport WHERE utilisateur_id = p_utilisateur_id;
    DELETE FROM remise_compte_rendu WHERE utilisateur_id = p_utilisateur_id;

-- Supprimer les spécialisations
    DELETE FROM enseignant WHERE utilisateur_id = p_utilisateur_id;
    DELETE FROM personnel_administratif WHERE utilisateur_id = p_utilisateur_id;

-- Supprimer les notifications
    DELETE FROM notification WHERE emetteur_id = p_utilisateur_id OR recepteur_id = p_utilisateur_id;

-- Finalement, supprimer l'utilisateur
    DELETE FROM utilisateur WHERE id = p_utilisateur_id;
    COMMIT;
END//

-- Procédure pour modifier un étudiant
CREATE PROCEDURE sp_modifier_etudiant(
    IN p_etudiant_id VARCHAR(30),
    IN p_nom VARCHAR(50),
    IN p_prenoms VARCHAR(100),
    IN p_email VARCHAR(255),
    IN p_date_naissance DATE,
    IN p_niveau_etude_id VARCHAR(30),
    IN p_annee_academique_id VARCHAR(10),
    IN p_montant INT
)
BEGIN
    DECLARE v_type_etudiant_id VARCHAR(30);
    DECLARE v_type_etudiant_libelle VARCHAR(50);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            ROLLBACK;
            RESIGNAL; -- Propage l'erreur
        END;

    START TRANSACTION;

-- 1. Déterminer le type d'étudiant basé sur le niveau d'étude
    CASE p_niveau_etude_id
        WHEN 'NIVEAU_LICENCE1'
            THEN SET v_type_etudiant_id = 'TYPE_ETUDIANT_L1'; SET v_type_etudiant_libelle = 'Étudiant en Licence 1';
        WHEN 'NIVEAU_LICENCE2'
            THEN SET v_type_etudiant_id = 'TYPE_ETUDIANT_L2'; SET v_type_etudiant_libelle = 'Étudiant en Licence 2';
        WHEN 'NIVEAU_LICENCE3'
            THEN SET v_type_etudiant_id = 'TYPE_ETUDIANT_L3'; SET v_type_etudiant_libelle = 'Étudiant en Licence 3';
        WHEN 'NIVEAU_MASTER1'
            THEN SET v_type_etudiant_id = 'TYPE_ETUDIANT_M1'; SET v_type_etudiant_libelle = 'Étudiant en Master 1';
        WHEN 'NIVEAU_MASTER2'
            THEN SET v_type_etudiant_id = 'TYPE_ETUDIANT_M2'; SET v_type_etudiant_libelle = 'Étudiant en Master 2';
        ELSE SET v_type_etudiant_id = 'TYPE_UNDEFINED'; SET v_type_etudiant_libelle = 'Non Défini';
        END CASE;

    -- Créer le type d'utilisateur s'il n'existe pas pour éviter les erreurs de clé étrangère
    INSERT IGNORE INTO type_utilisateur (id, libelle, categorie_utilisateur_id)
    VALUES (v_type_etudiant_id, v_type_etudiant_libelle, 'CAT_ETUDIANT');

-- 2. Mettre à jour la table utilisateur
    UPDATE utilisateur
    SET nom                 = p_nom,
        prenoms             = p_prenoms,
        email               = p_email,
        date_naissance      = p_date_naissance,
        type_utilisateur_id = v_type_etudiant_id
    WHERE id = p_etudiant_id;

-- 3. Mettre à jour l'inscription correspondante
    UPDATE inscription_etudiant
    SET niveau_etude_id = p_niveau_etude_id,
        montant         = p_montant
    WHERE utilisateur_id = p_etudiant_id
      AND annee_academique_id = p_annee_academique_id;

-- La condition IF ROW_COUNT() a été supprimée ici.

    COMMIT;
END//

-- Procédure de validation de rapport
CREATE PROCEDURE sp_valider_rapport(
    IN p_enseignant_id VARCHAR(30),
    IN p_rapport_id VARCHAR(40),
    IN p_commentaire TEXT
)
BEGIN
    DECLARE v_existe_depot INT DEFAULT 0;
    DECLARE v_deja_valide INT DEFAULT 0;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            ROLLBACK;
            RESIGNAL;
        END;

    START TRANSACTION;

    SELECT COUNT(*) INTO v_existe_depot FROM depot_rapport WHERE rapport_etudiant_id = p_rapport_id;
    IF v_existe_depot = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Le rapport n\'a pas été déposé';
    END IF;

    SELECT COUNT(*) INTO v_deja_valide FROM validation_rapport WHERE rapport_etudiant_id = p_rapport_id;
    IF v_deja_valide > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Le rapport est déjà validé';
    END IF;

    INSERT INTO validation_rapport (utilisateur_id, rapport_etudiant_id, date_validation, commentaire)
    VALUES (p_enseignant_id, p_rapport_id, CURDATE(), p_commentaire);

    INSERT INTO notification (emetteur_id, recepteur_id, message, date_notification, lu)
    SELECT p_enseignant_id,
           dr.utilisateur_id,
           CONCAT('Votre rapport "', re.titre, '" a été validé avec le commentaire: ', p_commentaire),
           NOW(),
           FALSE
    FROM depot_rapport dr
             JOIN rapport_etudiant re ON dr.rapport_etudiant_id = re.id
    WHERE dr.rapport_etudiant_id = p_rapport_id;
    COMMIT;
END//

-- Procédure d'évaluation d'un étudiant
CREATE PROCEDURE sp_evaluer_etudiant(
    IN p_enseignant_id VARCHAR(30),
    IN p_etudiant_id VARCHAR(30),
    IN p_ecue_id VARCHAR(30), -- CORRIGÉ: VARCHAR(15) -> VARCHAR(30)
    IN p_note SMALLINT
)
BEGIN
    DECLARE v_note_existe INT DEFAULT 0;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            ROLLBACK;
            RESIGNAL;
        END;

    START TRANSACTION;

    IF p_note < 0 OR p_note > 20 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La note doit être comprise entre 0 et 20';
    END IF;

    SELECT COUNT(*)
    INTO v_note_existe
    FROM evaluation
    WHERE enseignant_id = p_enseignant_id
      AND etudiant_id = p_etudiant_id
      AND ecue_id = p_ecue_id;

    IF v_note_existe > 0 THEN
        UPDATE evaluation
        SET note            = p_note,
            date_evaluation = CURDATE()
        WHERE enseignant_id = p_enseignant_id
          AND etudiant_id = p_etudiant_id
          AND ecue_id = p_ecue_id;
    ELSE
        INSERT INTO evaluation (enseignant_id, etudiant_id, ecue_id, date_evaluation, note)
        VALUES (p_enseignant_id, p_etudiant_id, p_ecue_id, CURDATE(), p_note);
    END IF;

    INSERT INTO notification (emetteur_id, recepteur_id, message, date_notification, lu)
    SELECT p_enseignant_id,
           p_etudiant_id,
           CONCAT('Nouvelle note attribuée pour ', ec.libelle, ': ', p_note, '/20'),
           NOW(),
           FALSE
    FROM ecue ec
    WHERE ec.id = p_ecue_id;
    COMMIT;
END//

-- Procédure de création de compte utilisateur générique
CREATE PROCEDURE sp_creer_compte_utilisateur(
    IN p_nom VARCHAR(50),
    IN p_prenoms VARCHAR(100),
    IN p_email VARCHAR(255),
    IN p_login VARCHAR(50),
    IN p_mot_de_passe VARCHAR(255),
    IN p_date_naissance DATE,
    IN p_categorie_user VARCHAR(30), -- CORRIGÉ: VARCHAR(20) -> VARCHAR(30)
    OUT p_user_id VARCHAR(30)
)
BEGIN
    DECLARE v_last_num INT DEFAULT 0;
    DECLARE v_user_id VARCHAR(30);
    DECLARE v_prefix VARCHAR(5);
    DECLARE v_type_user VARCHAR(30);
    DECLARE v_groupe_user VARCHAR(30);
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            ROLLBACK;
            RESIGNAL;
        END;

    START TRANSACTION;

    CASE p_categorie_user
        WHEN 'CAT_ETUDIANT'
            THEN SET v_prefix = 'ETU'; SET v_type_user = 'TYPE_UNDEFINED'; SET v_groupe_user = 'GRP_ETUDIANTS';
        WHEN 'CAT_ENSEIGNANT' THEN SET v_prefix = 'ENS'; SET v_type_user = 'TYPE_ENSEIGNANT_PERM';
                                   SET v_groupe_user = 'GRP_VALID_RAPPORT';
        WHEN 'CAT_ADMIN'
            THEN SET v_prefix = 'ADM'; SET v_type_user = 'TYPE_ADMIN_SCOLARITE'; SET v_groupe_user = 'GRP_ADMIN_PEDAGO';
        ELSE SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Catégorie utilisateur invalide';
        END CASE;

    SELECT IFNULL(MAX(CAST(SUBSTRING(id, LENGTH(v_prefix) + 1) AS UNSIGNED)), 0)
    INTO v_last_num
    FROM utilisateur
    WHERE id LIKE CONCAT(v_prefix, '%');

    SET v_user_id = CONCAT(v_prefix, LPAD(v_last_num + 1, 3, '0'));

    INSERT INTO utilisateur (id, nom, prenoms, email, login, mot_de_passe, date_naissance, groupe_utilisateur_id,
                             type_utilisateur_id)
    VALUES (v_user_id, p_nom, p_prenoms, p_email, p_login, SHA2(p_mot_de_passe, 256), p_date_naissance, v_groupe_user,
            v_type_user);

    CASE p_categorie_user
        WHEN 'CAT_ETUDIANT'
            THEN INSERT INTO etudiant (utilisateur_id, numero_carte) VALUES (v_user_id, CONCAT('CARTE_', v_user_id));
        WHEN 'CAT_ENSEIGNANT' THEN INSERT INTO enseignant (utilisateur_id) VALUES (v_user_id);
        WHEN 'CAT_ADMIN' THEN INSERT INTO personnel_administratif (utilisateur_id) VALUES (v_user_id);
        END CASE;

    SET p_user_id = v_user_id;
    COMMIT;
END//

-- Procédures de maintenance
CREATE PROCEDURE sp_backup_donnees_critiques()
BEGIN
    SET @backup_suffix = DATE_FORMAT(NOW(), '%Y%m%d_%H%i%s');
    SET @sql_user = CONCAT('CREATE TABLE backup_utilisateur_', @backup_suffix, ' AS SELECT * FROM utilisateur');
    PREPARE stmt1 FROM @sql_user; EXECUTE stmt1; DEALLOCATE PREPARE stmt1;
    SET @sql_eval = CONCAT('CREATE TABLE backup_evaluation_', @backup_suffix, ' AS SELECT * FROM evaluation');
    PREPARE stmt2 FROM @sql_eval; EXECUTE stmt2; DEALLOCATE PREPARE stmt2;
    SET @sql_depot = CONCAT('CREATE TABLE backup_depot_rapport_', @backup_suffix, ' AS SELECT * FROM depot_rapport');
    PREPARE stmt3 FROM @sql_depot; EXECUTE stmt3; DEALLOCATE PREPARE stmt3;
    SELECT CONCAT('Sauvegarde créée avec le suffixe: ', @backup_suffix) as resultat;
END//

CREATE PROCEDURE sp_nettoyer_notifications_anciennes(IN jours_retention INT)
BEGIN
    DECLARE nb_supprimees INT DEFAULT 0;
    DELETE FROM notification WHERE lu = TRUE AND DATEDIFF(CURDATE(), date_notification) > jours_retention;
    SET nb_supprimees = ROW_COUNT();
    SELECT CONCAT('Nettoyage terminé: ', nb_supprimees, ' notifications supprimées') as resultat;
END//

DELIMITER ;

-- =================================================================
-- 2. FONCTIONS
-- =================================================================

DELIMITER //

CREATE FUNCTION fn_calculer_age(p_date_naissance DATE)
    RETURNS INT
    READS SQL DATA
    DETERMINISTIC
BEGIN
    RETURN TIMESTAMPDIFF(YEAR, p_date_naissance, CURDATE());
END//

CREATE FUNCTION fn_rapport_complet(p_rapport_id VARCHAR(40))
    RETURNS BOOLEAN
    READS SQL DATA
    DETERMINISTIC
BEGIN
    DECLARE v_depose, v_valide, v_approuve BOOLEAN DEFAULT FALSE;
    SELECT COUNT(*) > 0 INTO v_depose FROM depot_rapport WHERE rapport_etudiant_id = p_rapport_id;
    SELECT COUNT(*) > 0 INTO v_valide FROM validation_rapport WHERE rapport_etudiant_id = p_rapport_id;
    SELECT COUNT(*) > 0 INTO v_approuve FROM approbation_rapport WHERE rapport_etudiant_id = p_rapport_id;
    RETURN (v_depose AND v_valide AND v_approuve);
END//

CREATE FUNCTION fn_duree_stage(p_utilisateur_id VARCHAR(30), p_entreprise_id VARCHAR(30)) -- CORRIGÉ: VARCHAR(20) -> VARCHAR(30)
    RETURNS INT
    READS SQL DATA
    DETERMINISTIC
BEGIN
    DECLARE v_duree INT DEFAULT 0;
    SELECT DATEDIFF(date_fin, date_debut)
    INTO v_duree
    FROM stage_effectue
    WHERE utilisateur_id = p_utilisateur_id
      AND entreprise_id = p_entreprise_id;
    RETURN COALESCE(v_duree, 0);
END//

CREATE FUNCTION fn_moyenne_etudiant(p_etudiant_id VARCHAR(30))
    RETURNS DECIMAL(4, 2)
    READS SQL DATA
    DETERMINISTIC
BEGIN
    DECLARE v_moyenne DECIMAL(4, 2) DEFAULT 0.00;
    SELECT AVG(note) INTO v_moyenne FROM evaluation WHERE etudiant_id = p_etudiant_id;
    RETURN COALESCE(v_moyenne, 0.00);
END//

CREATE FUNCTION fn_obtenir_mention(p_moyenne DECIMAL(4, 2))
    RETURNS VARCHAR(20)
    DETERMINISTIC
BEGIN
    CASE
        WHEN p_moyenne >= 16 THEN RETURN 'Très Bien';
        WHEN p_moyenne >= 14 THEN RETURN 'Bien';
        WHEN p_moyenne >= 12 THEN RETURN 'Assez Bien';
        WHEN p_moyenne >= 10 THEN RETURN 'Passable';
        ELSE RETURN 'Insuffisant';
        END CASE;
END//

CREATE FUNCTION fn_notifications_non_lues(p_utilisateur_id VARCHAR(30))
    RETURNS INT
    READS SQL DATA
    DETERMINISTIC
BEGIN
    DECLARE v_nb_non_lues INT DEFAULT 0;
    SELECT COUNT(*) INTO v_nb_non_lues FROM notification WHERE recepteur_id = p_utilisateur_id AND lu = FALSE;
    RETURN v_nb_non_lues;
END//

DELIMITER ;

-- =================================================================
-- 3. TRIGGERS
-- =================================================================

DELIMITER //

CREATE TRIGGER trg_audit_utilisateur_update
    AFTER UPDATE
    ON utilisateur
    FOR EACH ROW
BEGIN
    INSERT INTO audit_utilisateur (utilisateur_id, action, ancienne_valeur, nouvelle_valeur, date_modification,
                                   utilisateur_modificateur)
    VALUES (NEW.id, 'UPDATE',
            CONCAT('Email: ', OLD.email, ', Groupe: ', OLD.groupe_utilisateur_id),
            CONCAT('Email: ', NEW.email, ', Groupe: ', NEW.groupe_utilisateur_id),
            NOW(), USER());
END//

CREATE TRIGGER trg_notification_validation_rapport
    AFTER INSERT
    ON validation_rapport
    FOR EACH ROW
BEGIN
    -- Notifier l'étudiant
    INSERT INTO notification (emetteur_id, recepteur_id, message, date_notification, lu)
    SELECT NEW.utilisateur_id,
           dr.utilisateur_id,
           CONCAT('Votre rapport "', re.titre, '" a été validé par ', u.prenoms, ' ', u.nom),
           NOW(),
           FALSE
    FROM depot_rapport dr
             JOIN rapport_etudiant re ON dr.rapport_etudiant_id = re.id
             JOIN utilisateur u ON NEW.utilisateur_id = u.id
    WHERE dr.rapport_etudiant_id = NEW.rapport_etudiant_id;

    -- Notifier le personnel administratif pour approbation
-- NOTE: Notifie TOUS les admins. Pourrait être optimisé pour cibler un groupe.
    INSERT INTO notification (emetteur_id, recepteur_id, message, date_notification, lu)
    SELECT NEW.utilisateur_id,
           pa.utilisateur_id,
           CONCAT('Le rapport "', re.titre, '" est prêt pour approbation'),
           NOW(),
           FALSE
    FROM personnel_administratif pa
             JOIN rapport_etudiant re ON re.id = NEW.rapport_etudiant_id
             CROSS JOIN utilisateur u ON NEW.utilisateur_id = u.id;
END//

CREATE TRIGGER trg_prevent_user_delete
    BEFORE DELETE
    ON utilisateur
    FOR EACH ROW
BEGIN
    DECLARE v_count_eval, v_count_depot INT DEFAULT 0;
    SELECT COUNT(*) INTO v_count_eval FROM evaluation WHERE enseignant_id = OLD.id OR etudiant_id = OLD.id;
    SELECT COUNT(*) INTO v_count_depot FROM depot_rapport WHERE utilisateur_id = OLD.id;
    IF v_count_eval > 0 OR v_count_depot > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Impossible de supprimer cet utilisateur: il a des données liées';
    END IF;
END//

CREATE TRIGGER trg_audit_evaluation
    AFTER INSERT
    ON evaluation
    FOR EACH ROW
BEGIN
    -- CORRIGÉ: Le nombre de colonnes correspond maintenant au nombre de valeurs.
    INSERT INTO audit_evaluation (enseignant_id, etudiant_id, ecue_id, note, date_audit, action)
    VALUES (NEW.enseignant_id, NEW.etudiant_id, NEW.ecue_id, NEW.note, NOW(), 'INSERT');
END//

CREATE TRIGGER trg_audit_evaluation_update
    AFTER UPDATE
    ON evaluation
    FOR EACH ROW
BEGIN
    INSERT INTO audit_evaluation (enseignant_id, etudiant_id, ecue_id, note, ancienne_note, date_audit, action)
    VALUES (NEW.enseignant_id, NEW.etudiant_id, NEW.ecue_id, NEW.note, OLD.note, NOW(), 'UPDATE');
END//

CREATE TRIGGER trg_validate_note
    BEFORE INSERT
    ON evaluation
    FOR EACH ROW
BEGIN
    IF NEW.note < 0 OR NEW.note > 20 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La note doit être comprise entre 0 et 20';
    END IF;
END//

CREATE TRIGGER trg_validate_note_update
    BEFORE UPDATE
    ON evaluation
    FOR EACH ROW
BEGIN
    IF NEW.note < 0 OR NEW.note > 20 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La note doit être comprise entre 0 et 20';
    END IF;
END//

DELIMITER ;

-- =================================================================
--  4. INDEXES
-- =================================================================

-- Création des indexes
CREATE INDEX idx_utilisateur_email ON utilisateur (email);
CREATE INDEX idx_utilisateur_login ON utilisateur (login);
CREATE INDEX idx_evaluation_date ON evaluation (date_evaluation);
CREATE INDEX idx_evaluation_etudiant ON evaluation (etudiant_id);
CREATE INDEX idx_evaluation_enseignant ON evaluation (enseignant_id);
CREATE INDEX idx_depot_rapport_date ON depot_rapport (date_depot);
CREATE INDEX idx_validation_rapport_date ON validation_rapport (date_validation);
CREATE INDEX idx_inscription_annee ON inscription_etudiant (annee_academique_id);
CREATE INDEX idx_notification_recepteur_lu ON notification (recepteur_id, lu);
CREATE INDEX idx_stage_dates ON stage_effectue (date_debut, date_fin);
CREATE INDEX idx_evaluation_composite ON evaluation (etudiant_id, ecue_id, date_evaluation);
CREATE INDEX idx_inscription_composite ON inscription_etudiant (utilisateur_id, annee_academique_id, niveau_etude_id);
CREATE INDEX idx_affectation_rapport ON affectation_encadrant (rapport_etudiant_id, statut_jury_id);

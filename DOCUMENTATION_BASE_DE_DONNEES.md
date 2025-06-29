# Documentation de la Base de Données - Système de Gestion Académique

## Table des Matières

1. [Vue d'ensemble](#vue-densemble)
2. [Architecture de la base de données](#architecture-de-la-base-de-données)
3. [Tables de nomenclature](#tables-de-nomenclature)
4. [Tables principales](#tables-principales)
5. [Tables système](#tables-système)
6. [Tables de processus et audit](#tables-de-processus-et-audit)
7. [Relations et contraintes](#relations-et-contraintes)
8. [Diagramme entité-relation](#diagramme-entité-relation)
9. [Exemples d'utilisation](#exemples-dutilisation)

## Vue d'ensemble

Cette base de données est conçue pour gérer un système académique complet incluant :
- Gestion des utilisateurs (étudiants, enseignants, personnel administratif)
- Suivi des inscriptions et des évaluations
- Gestion des rapports et leur processus de validation
- Système de messagerie et notifications
- Audit et traçabilité des actions

## Architecture de la base de données

La base de données est organisée en plusieurs catégories de tables :

### 1. Tables de Nomenclature
Tables de référence contenant les données de base du système.

### 2. Tables Principales
Tables centrales gérant les entités métier principales.

### 3. Tables Système
Tables techniques pour la gestion des sauvegardes et la maintenance.

### 4. Tables de Processus et Audit
Tables gérant les workflows et la traçabilité.

## Tables de nomenclature

### `annee_academique`
Stocke les informations sur les années académiques.

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | VARCHAR(10) | Identifiant unique (ex: '2023-2024') |
| `date_debut` | DATE | Date de début de l'année académique |
| `date_fin` | DATE | Date de fin de l'année académique |

**Clé primaire :** `id`

### `entreprise`
Liste des entreprises partenaires pour les stages.

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | VARCHAR(20) | Identifiant unique (ex: 'ENT_GOOGLE') |
| `libelle` | VARCHAR(50) | Nom de l'entreprise |

**Clé primaire :** `id`

### `niveau_etude`
Définit les différents niveaux d'étude.

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | VARCHAR(20) | Identifiant unique (ex: 'NIVEAU_LICENCE3') |
| `libelle` | VARCHAR(50) | Libellé du niveau |

**Clé primaire :** `id`

### `grade`
Répertorie les grades académiques des enseignants.

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | VARCHAR(20) | Identifiant unique (ex: 'GRD_MAITRE_CONF') |
| `libelle` | VARCHAR(50) | Libellé du grade |

**Clé primaire :** `id`

### `fonction`
Liste les fonctions administratives ou académiques.

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | VARCHAR(20) | Identifiant unique (ex: 'FCT_CHEF_DEPT') |
| `libelle` | VARCHAR(50) | Libellé de la fonction |

**Clé primaire :** `id`

### `ue` (Unité d'Enseignement)
Définit les unités d'enseignement.

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | VARCHAR(15) | Identifiant unique (ex: 'UE_MATHS1') |
| `libelle` | VARCHAR(50) | Nom de l'UE |
| `credit` | SMALLINT | Nombre de crédits |

**Clé primaire :** `id`

### `categorie_utilisateur`
Catégorise les utilisateurs en grands groupes.

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | VARCHAR(20) | Identifiant unique (ex: 'CAT_ETUDIANT') |
| `libelle` | VARCHAR(50) | Libellé de la catégorie |

**Clé primaire :** `id`

### `groupe_utilisateur`
Définit les groupes de permissions.

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | VARCHAR(20) | Identifiant unique (ex: 'GRP_VALID_RAPPORT') |
| `libelle` | VARCHAR(50) | Libellé du groupe |

**Clé primaire :** `id`

### `niveau_acces_donnees`
Définit les niveaux de confidentialité.

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | VARCHAR(20) | Identifiant unique (ex: 'ACCES_DEPT_INFO') |
| `libelle` | VARCHAR(50) | Libellé du niveau d'accès |

**Clé primaire :** `id`

### `statut_jury`
Définit les statuts au sein d'un jury.

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | VARCHAR(25) | Identifiant unique (ex: 'STATUT_JURY_PRESIDENT') |
| `libelle` | VARCHAR(50) | Libellé du statut |

**Clé primaire :** `id`

### `niveau_approbation`
Définit les niveaux d'approbation pour les documents.

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | VARCHAR(25) | Identifiant unique (ex: 'APPROB_CHEF_DEPT') |
| `libelle` | VARCHAR(50) | Libellé du niveau |

**Clé primaire :** `id`

## Tables principales

### `utilisateur`
Table centrale contenant tous les utilisateurs du système.

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | VARCHAR(30) | Matricule unique (ex: '24INF001') |
| `nom` | VARCHAR(50) | Nom de famille |
| `prenoms` | VARCHAR(100) | Prénoms |
| `email` | VARCHAR(255) | Adresse email (unique) |
| `login` | VARCHAR(50) | Nom d'utilisateur (unique) |
| `mot_de_passe` | VARCHAR(255) | Mot de passe chiffré |
| `photo` | VARCHAR(255) | Chemin vers la photo |
| `date_naissance` | DATE | Date de naissance |
| `groupe_utilisateur_id` | VARCHAR(20) | Référence vers le groupe |
| `type_utilisateur_id` | VARCHAR(25) | Référence vers le type |
| `niveau_acces_donnees_id` | VARCHAR(20) | Référence vers le niveau d'accès |

**Clé primaire :** `id`
**Clés étrangères :**
- `groupe_utilisateur_id` → `groupe_utilisateur(id)`
- `type_utilisateur_id` → `type_utilisateur(id)`
- `niveau_acces_donnees_id` → `niveau_acces_donnees(id)`

### `type_utilisateur`
Spécifie la catégorie détaillée d'un utilisateur.

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | VARCHAR(25) | Identifiant unique (ex: 'TYPE_ETUDIANT_L1') |
| `libelle` | VARCHAR(50) | Libellé du type |
| `categorie_utilisateur_id` | VARCHAR(20) | Référence vers la catégorie |

**Clé primaire :** `id`
**Clé étrangère :** `categorie_utilisateur_id` → `categorie_utilisateur(id)`

### `ecue` (Élément Constitutif d'une UE)
Définit les matières composant une UE.

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | VARCHAR(15) | Identifiant unique (ex: 'ECUE_ALGEBRE1') |
| `libelle` | VARCHAR(50) | Nom de la matière |
| `credit` | SMALLINT | Nombre de crédits |
| `ue_id` | VARCHAR(15) | Référence vers l'UE |

**Clé primaire :** `id`
**Clé étrangère :** `ue_id` → `ue(id)`

### Tables de spécialisation des utilisateurs

#### `etudiant`
Spécialisation pour les étudiants.

| Colonne | Type | Description |
|---------|------|-------------|
| `utilisateur_id` | VARCHAR(30) | Référence vers utilisateur |

**Clé primaire :** `utilisateur_id`
**Clé étrangère :** `utilisateur_id` → `utilisateur(id)`

#### `enseignant`
Spécialisation pour les enseignants.

| Colonne | Type | Description |
|---------|------|-------------|
| `utilisateur_id` | VARCHAR(30) | Référence vers utilisateur |

**Clé primaire :** `utilisateur_id`
**Clé étrangère :** `utilisateur_id` → `utilisateur(id)`

#### `personnel_administratif`
Spécialisation pour le personnel administratif.

| Colonne | Type | Description |
|---------|------|-------------|
| `utilisateur_id` | VARCHAR(30) | Référence vers utilisateur |

**Clé primaire :** `utilisateur_id`
**Clé étrangère :** `utilisateur_id` → `utilisateur(id)`

### Tables de gestion académique

#### `inscription_etudiant`
Lie un étudiant à un niveau d'étude pour une année.

| Colonne | Type | Description |
|---------|------|-------------|
| `utilisateur_id` | VARCHAR(30) | Référence vers l'étudiant |
| `niveau_etude_id` | VARCHAR(20) | Référence vers le niveau |
| `annee_academique_id` | VARCHAR(10) | Référence vers l'année |
| `date_inscription` | DATE | Date d'inscription |
| `montant` | INT | Montant payé |

**Clé primaire :** `(utilisateur_id, niveau_etude_id, annee_academique_id)`

#### `evaluation`
Enregistre les notes des étudiants.

| Colonne | Type | Description |
|---------|------|-------------|
| `enseignant_id` | VARCHAR(30) | Référence vers l'enseignant |
| `etudiant_id` | VARCHAR(30) | Référence vers l'étudiant |
| `ecue_id` | VARCHAR(15) | Référence vers la matière |
| `date_evaluation` | DATE | Date de l'évaluation |
| `note` | SMALLINT | Note obtenue |

**Clé primaire :** `(enseignant_id, etudiant_id, ecue_id)`

### Tables de gestion des rapports

#### `rapport_etudiant`
Stocke les métadonnées des rapports.

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | VARCHAR(40) | Identifiant unique |
| `titre` | VARCHAR(255) | Titre du rapport |
| `date_rapport` | DATE | Date du rapport |
| `lien_rapport` | VARCHAR(255) | Lien vers le fichier |

**Clé primaire :** `id`

#### `depot_rapport`
Journalise le dépôt des rapports.

| Colonne | Type | Description |
|---------|------|-------------|
| `utilisateur_id` | VARCHAR(30) | Référence vers l'étudiant |
| `rapport_etudiant_id` | VARCHAR(40) | Référence vers le rapport |
| `date_depot` | DATE | Date de dépôt |

**Clé primaire :** `(utilisateur_id, rapport_etudiant_id)`

#### `validation_rapport`
Trace la validation par les enseignants.

| Colonne | Type | Description |
|---------|------|-------------|
| `utilisateur_id` | VARCHAR(30) | Référence vers l'enseignant |
| `rapport_etudiant_id` | VARCHAR(40) | Référence vers le rapport |
| `date_validation` | DATE | Date de validation |
| `commentaire` | TEXT | Commentaires |

**Clé primaire :** `(utilisateur_id, rapport_etudiant_id)`

#### `approbation_rapport`
Trace l'approbation par l'administration.

| Colonne | Type | Description |
|---------|------|-------------|
| `utilisateur_id` | VARCHAR(30) | Référence vers l'administrateur |
| `rapport_etudiant_id` | VARCHAR(40) | Référence vers le rapport |
| `date_approbation` | DATE | Date d'approbation |

**Clé primaire :** `(utilisateur_id, rapport_etudiant_id)`

#### `affectation_encadrant`
Assigne les enseignants aux jurys.

| Colonne | Type | Description |
|---------|------|-------------|
| `utilisateur_id` | VARCHAR(30) | Référence vers l'enseignant |
| `rapport_etudiant_id` | VARCHAR(40) | Référence vers le rapport |
| `statut_jury_id` | VARCHAR(25) | Statut dans le jury |
| `date_affectation` | DATE | Date d'affectation |

**Clé primaire :** `(utilisateur_id, rapport_etudiant_id, statut_jury_id)`

### Tables de messagerie

#### `discussion`
Entête des discussions.

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | VARCHAR(40) | Identifiant unique |
| `date_discussion` | TIMESTAMP | Date de création |

**Clé primaire :** `id`

#### `messagerie`
Messages des discussions.

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | BIGINT | Identifiant auto-généré |
| `discussion_id` | VARCHAR(40) | Référence vers la discussion |
| `auteur_id` | VARCHAR(30) | Référence vers l'auteur |
| `message` | TEXT | Contenu du message |
| `date_message` | TIMESTAMP | Date du message |

**Clé primaire :** `id`

#### `notification`
Système de notifications.

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | BIGINT | Identifiant auto-généré |
| `emetteur_id` | VARCHAR(30) | Référence vers l'émetteur |
| `recepteur_id` | VARCHAR(30) | Référence vers le récepteur |
| `message` | TEXT | Contenu de la notification |
| `date_notification` | TIMESTAMP | Date de création |
| `lu` | BOOLEAN | Statut de lecture |

**Clé primaire :** `id`

## Tables système

### `etat_sauvegarde`
Permet de sauvegarder et restaurer les états précédents des enregistrements.

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | BIGINT | Identifiant auto-généré |
| `nom_table` | TEXT | Nom de la table concernée |
| `id_enregistrement` | TEXT | Identifiant de l'enregistrement sauvegardé |
| `donnees` | JSONB | Données complètes au format JSON |
| `date_sauvegarde` | TIMESTAMP | Date de la sauvegarde (par défaut : maintenant) |
| `id_traitement` | TEXT | Référence vers le traitement (optionnel) |
| `id_utilisateur` | TEXT | Référence vers l'utilisateur (optionnel) |

**Clé primaire :** `id`
**Clés étrangères :**
- `id_traitement` → `traitement(id)` (optionnel)
- `id_utilisateur` → `utilisateur(id)` (optionnel)

**Utilisation :**
Cette table permet de conserver un historique des modifications en stockant l'état complet des enregistrements avant leur modification ou suppression. Le format JSONB permet une recherche et une indexation efficaces des données sauvegardées.

## Tables de processus et audit

### Tables de définition des processus

#### `traitement`
Définit les processus métier.

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | VARCHAR(20) | Identifiant unique |
| `libelle` | VARCHAR(50) | Nom du traitement |

**Clé primaire :** `id`

#### `action`
Définit les étapes atomiques.

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | VARCHAR(20) | Identifiant unique |
| `libelle` | VARCHAR(50) | Nom de l'action |

**Clé primaire :** `id`

#### `traitement_action`
Associe les actions aux traitements.

| Colonne | Type | Description |
|---------|------|-------------|
| `traitement_id` | VARCHAR(20) | Référence vers le traitement |
| `action_id` | VARCHAR(20) | Référence vers l'action |
| `ordre` | SMALLINT | Ordre d'exécution |

**Clé primaire :** `(traitement_id, action_id)`

#### `autorisation_action`
Gère les permissions sur les actions.

| Colonne | Type | Description |
|---------|------|-------------|
| `groupe_utilisateur_id` | VARCHAR(20) | Référence vers le groupe |
| `traitement_id` | VARCHAR(20) | Référence vers le traitement |
| `action_id` | VARCHAR(20) | Référence vers l'action |

**Clé primaire :** `(groupe_utilisateur_id, traitement_id, action_id)`

### Tables d'audit

#### `audit`
Log des exécutions de traitements.

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | BIGINT | Identifiant auto-généré |
| `description` | TEXT | Description du traitement |
| `date_traitement` | TIMESTAMP | Date d'exécution |
| `traitement_id` | VARCHAR(20) | Référence vers le traitement |
| `utilisateur_id` | VARCHAR(30) | Référence vers l'utilisateur |

**Clé primaire :** `id`

#### `audit_action`
Log détaillé des actions.

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | BIGINT | Identifiant auto-généré |
| `audit_id` | BIGINT | Référence vers l'audit parent |
| `action_id` | VARCHAR(20) | Référence vers l'action |
| `ordre` | SMALLINT | Ordre d'exécution |
| `heure_execution` | TIMESTAMP | Heure d'exécution |
| `statut` | VARCHAR(10) | Résultat (SUCCES/ECHEC) |
| `message` | TEXT | Message de résultat |

**Clé primaire :** `id`

## Relations et contraintes

### Contraintes de référence
- Toutes les clés étrangères utilisent `ON DELETE RESTRICT ON UPDATE CASCADE`
- Exception : `audit_action.audit_id` utilise `ON DELETE CASCADE`

### Contraintes d'unicité
- `utilisateur.email` : UNIQUE
- `utilisateur.login` : UNIQUE

### Contraintes de vérification
- `audit_action.statut` : CHECK (statut IN ('SUCCES', 'ECHEC'))

## Exemples d'utilisation

### Inscription d'un étudiant
```sql
-- Insérer un nouvel étudiant
INSERT INTO utilisateur (id, nom, prenoms, email, login, mot_de_passe, ...)
VALUES ('24INF001', 'Dupont', 'Jean', 'jean.dupont@email.com', 'jdupont', ...);

INSERT INTO etudiant (utilisateur_id) VALUES ('24INF001');

-- L'inscrire à un niveau d'étude
INSERT INTO inscription_etudiant (utilisateur_id, niveau_etude_id, annee_academique_id, date_inscription, montant)
VALUES ('24INF001', 'NIVEAU_LICENCE3', '2023-2024', CURRENT_DATE, 150000);
```

### Dépôt et validation d'un rapport
```sql
-- Dépôt du rapport
INSERT INTO rapport_etudiant (id, titre, date_rapport, lien_rapport)
VALUES ('RAPPORT_2024_24INF001', 'Rapport de stage', CURRENT_DATE, '/uploads/rapport_24INF001.pdf');

INSERT INTO depot_rapport (utilisateur_id, rapport_etudiant_id, date_depot)
VALUES ('24INF001', 'RAPPORT_2024_24INF001', CURRENT_DATE);

-- Validation par un enseignant
INSERT INTO validation_rapport (utilisateur_id, rapport_etudiant_id, date_validation, commentaire)
VALUES ('ENS012', 'RAPPORT_2024_24INF001', CURRENT_DATE, 'Rapport conforme aux exigences');
```

### Consultation des évaluations d'un étudiant
```sql
SELECT 
    e.libelle as matiere,
    ue.libelle as unite_enseignement,
    ev.note,
    ev.date_evaluation,
    u.nom as enseignant_nom,
    u.prenoms as enseignant_prenoms
FROM evaluation ev
JOIN ecue e ON ev.ecue_id = e.id
JOIN ue ON e.ue_id = ue.id
JOIN utilisateur u ON ev.enseignant_id = u.id
WHERE ev.etudiant_id = '24INF001'
ORDER BY ev.date_evaluation DESC;
```

### Sauvegarde d'un état avant modification
```sql
-- Avant de modifier un utilisateur, sauvegarder son état actuel
INSERT INTO etat_sauvegarde (nom_table, id_enregistrement, donnees, id_traitement, id_utilisateur)
SELECT 
    'utilisateur',
    '24INF001',
    to_jsonb(u.*),
    'TRT_MODIF_UTILISATEUR',
    'ADM001'
FROM utilisateur u 
WHERE u.id = '24INF001';

-- Puis effectuer la modification
UPDATE utilisateur 
SET email = 'nouveau.email@domain.com' 
WHERE id = '24INF001';
```

### Restauration d'un état précédent
```sql
-- Rechercher les sauvegardes d'un enregistrement
SELECT 
    id,
    date_sauvegarde,
    donnees->>'email' as ancien_email,
    donnees->>'nom' as ancien_nom
FROM etat_sauvegarde 
WHERE nom_table = 'utilisateur' 
  AND id_enregistrement = '24INF001'
ORDER BY date_sauvegarde DESC;

-- Restaurer à partir d'une sauvegarde spécifique
-- (nécessiterait une procédure stockée pour reconstruire l'UPDATE automatiquement)
```

Cette documentation fournit une vue complète de la structure de la base de données et de son utilisation dans le système de gestion académique.
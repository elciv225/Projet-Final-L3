<?php
// $utilisateur is the base user object (optional, for modification)
// $detailsUtilisateur is the Etudiant object (optional, for modification)
// $niveaux_etude is the list of all NiveauEtude objects
// $categorieUtilisateur is the label like "Étudiant"
// $categorieId is the ID like 'CAT_ETUD'

$num_matricule_val = isset($detailsUtilisateur) ? htmlspecialchars($detailsUtilisateur->getNumMatricule()) : '';
// date_naissance is in the main form, but if needed here:
// $date_naissance_val = isset($utilisateur) ? htmlspecialchars($utilisateur->getDateNaissance()) : '';
$lieu_naissance_val = isset($detailsUtilisateur) ? htmlspecialchars($detailsUtilisateur->getLieuNaissance()) : '';
$id_niveau_etude_val = isset($detailsUtilisateur) ? htmlspecialchars($detailsUtilisateur->getIdNiveauEtude()) : '';

?>
<div class="form-section specific-fields-etudiant">
    <div class="section-header">
        <h3 class="section-title">Informations Spécifiques <?= htmlspecialchars($categorieUtilisateur ?? "Étudiant") ?></h3>
    </div>
    <div class="section-content">
        <div class="form-grid">
            <div class="form-group">
                <input type="text" name="num_matricule" class="form-input" placeholder=" " id="num_matricule" value="<?= $num_matricule_val ?>" required>
                <label class="form-label" for="num_matricule">Numéro Matricule</label>
            </div>
            <div class="form-group">
                <input type="text" name="lieu_naissance" class="form-input" placeholder=" " id="lieu_naissance" value="<?= $lieu_naissance_val ?>">
                <label class="form-label" for="lieu_naissance">Lieu de Naissance</label>
            </div>
            <div class="form-group">
                <select class="form-input" id="id_niveau_etude" name="id_niveau_etude" required>
                    <option value="">Sélectionnez Niveau d'Étude</option>
                    <?php if (isset($niveaux_etude) && !empty($niveaux_etude)): ?>
                        <?php foreach ($niveaux_etude as $niveau): ?>
                            <option value="<?= htmlspecialchars($niveau->getId()) ?>" <?= ($id_niveau_etude_val == $niveau->getId()) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($niveau->getLibelle()) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <label class="form-label" for="id_niveau_etude">Niveau d'Étude Actuel</label>
            </div>
            <!-- Note: Date de naissance is typically in the main user form -->
            <!-- Année Académique Concernée is part of inscription, not the student entity itself usually -->
        </div>
    </div>
</div>

<!-- The table for listing users is usually part of the main view (utilisateurs.php) -->
<!-- or loaded separately. This partial should focus on the form fields. -->
<!-- If a list of students needs to be displayed here based on the selected category, -->
<!-- that logic would be separate from this form. -->
<!-- For now, removing the static table from this partial form view. -->

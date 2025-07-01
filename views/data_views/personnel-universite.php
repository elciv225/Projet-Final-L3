<?php
// Variables available:
// $categorieUtilisateur (label e.g. "Enseignant")
// $categorieSlug (ID e.g. "CAT_ENS") - this comes from $viewData['categorieSlug'] in controller
// $grades (list of Grade objects, only if $categorieSlug corresponds to 'enseignant' type)
// $fonctions (list of Fonction objects)
// $utilisateur (base User object for modification, optional)
// $detailsUtilisateur (Enseignant or PersonnelAdministratif object for modification, optional)

$id_grade_val = '';
$id_fonction_val = '';

if (isset($detailsUtilisateur)) {
    if ($categorieSlug === 'CAT_ENS' && method_exists($detailsUtilisateur, 'getIdGrade')) { // Check if it's an Enseignant object
        $id_grade_val = htmlspecialchars($detailsUtilisateur->getIdGrade());
    }
    // Both Enseignant and PersonnelAdministratif might have a fonction.
    // The `personnel_administratif` table itself doesn't have id_fonction.
    // The `enseignant` table also doesn't.
    // This implies id_fonction might be a general attribute or part of a more complex setup not directly on these detail tables.
    // For UtilisateursController, 'id_fonction_personnel' is expected for 'administratif'
    // and 'id_grade_enseignant' for 'enseignant'.
    // Let's assume for 'administratif', the fonction is linked via $detailsUtilisateur->getIdFonction() if such a method exists.
    // And for 'enseignant', if they also have a primary fonction, it might be on $detailsUtilisateur.
    // The controller expects `id_fonction_personnel` for 'administratif' and `id_grade_enseignant` for 'enseignant'.
    // This means these fields are mutually exclusive in the specific part of the form.

    if ($categorieSlug === 'CAT_ADMIN' && method_exists($detailsUtilisateur, 'getIdFonction')) { // Example for admin
         $id_fonction_val = htmlspecialchars($detailsUtilisateur->getIdFonction());
    }
    // If an enseignant can also have a primary fonction stored on their details:
    // if ($categorieSlug === 'CAT_ENS' && method_exists($detailsUtilisateur, 'getIdFonction')) {
    //     $id_fonction_val = htmlspecialchars($detailsUtilisateur->getIdFonction());
    // }
}
?>
<div class="form-section specific-fields-personnel">
    <div class="section-header">
        <h3 class="section-title">Informations Spécifiques <?= htmlspecialchars($categorieUtilisateur ?? "Personnel") ?></h3>
    </div>
    <div class="section-content">
        <div class="form-grid">
            <?php
            // The controller uses $categorieSlug to determine which fields to expect.
            // 'CAT_ENS' for enseignant, 'CAT_ADMIN' for administratif (assuming these are the IDs/slugs)
            if ($categorieSlug === 'CAT_ENS'): ?>
                <div class="form-group">
                    <select name="id_grade_enseignant" class="form-input" id="id_grade_enseignant">
                        <option value="">Sélectionnez un Grade</option>
                        <?php if (isset($grades) && !empty($grades)): ?>
                            <?php foreach ($grades as $grade): ?>
                                <option value="<?= htmlspecialchars($grade->getId()) ?>" <?= ($id_grade_val == $grade->getId()) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($grade->getLibelle()) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <label class="form-label" for="id_grade_enseignant">Grade</label>
                </div>
            <?php elseif ($categorieSlug === 'CAT_ADMIN'): ?>
                <div class="form-group">
                    <select name="id_fonction_personnel" class="form-input" id="id_fonction_personnel">
                        <option value="">Sélectionnez une Fonction</option>
                        <?php if (isset($fonctions) && !empty($fonctions)): ?>
                            <?php foreach ($fonctions as $fonction): ?>
                                <option value="<?= htmlspecialchars($fonction->getId()) ?>" <?= ($id_fonction_val == $fonction->getId()) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($fonction->getLibelle()) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <label class="form-label" for="id_fonction_personnel">Fonction</label>
                </div>
            <?php else: ?>
                <!-- Potentially other categories or a generic message if no specific fields -->
                 <p>Aucun champ spécifique pour la catégorie: <?= htmlspecialchars($categorieUtilisateur ?? "") ?></p>
            <?php endif; ?>

            <!-- Removed Date du Grade, Date de la Fonction, Spécialités as they are not directly part of this form's submission logic in UtilisateursController -->
        </div>
    </div>
</div>

<!-- Static table removed from this partial view -->


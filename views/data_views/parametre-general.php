<div class="section-content">
    <h2><?= $parametre ?></h2>
    
    <!-- Formulaire d'ajout de paramètre -->
    <div class="form-section">
        <div class="section-header">
            <h3 class="section-title">Ajouter un nouveau <?= strtolower($parametre) ?></h3>
        </div>
        <form method="post" action="/charger-formulaire-paramatre-specifique" class="ajax-form" data-target=".section-content">
            <input type="hidden" name="parametre-specifique" value="<?= strtolower($parametre) ?>">
            <input type="hidden" name="action" value="ajouter">
            
            <div class="form-group">
                <input type="text" id="nouveauParametre" name="nouveau-parametre" class="form-input" required>
                <label for="nouveauParametre" class="form-label">Libellé</label>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </div>
        </form>
    </div>
    
    <!-- Liste des paramètres existants -->
    <div class="table-container">
        <div class="table-header">
            <h3>Liste des <?= strtolower($parametre) ?>s</h3>
        </div>
        <div class="table-scroll-wrapper scroll-custom">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Libellé</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($donnees)): ?>
                        <?php foreach ($donnees as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['id']) ?></td>
                                <td><?= htmlspecialchars($item['libelle']) ?></td>
                                <td>
                                    <form method="post" action="/charger-formulaire-paramatre-specifique" class="ajax-form" data-target=".section-content" style="display: inline;">
                                        <input type="hidden" name="parametre-specifique" value="<?= strtolower($parametre) ?>">
                                        <input type="hidden" name="action" value="supprimer">
                                        <input type="hidden" name="parametre-id" value="<?= $item['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce paramètre ?');">
                                            Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">Aucun paramètre trouvé</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Réinitialiser le formulaire après soumission réussie
    const form = document.querySelector('form[action="/charger-formulaire-paramatre-specifique"][data-target=".section-content"]');
    if (form) {
        form.addEventListener('ajax:success', function() {
            form.reset();
        });
    }
});
</script>
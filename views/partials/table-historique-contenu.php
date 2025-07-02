<?php
// Données passées :
// $enteteTable (array des titres de colonnes)
// $lignesTable (array de lignes de données, chaque ligne est un tableau associatif)
// $typeHistoriqueDemandee (string: 'fonction', 'grade', 'specialite')
// $nomPersonnel (string: nom et prénom du personnel concerné)
// $utilisateurId (string: ID du personnel)
// $donneesChargees (bool)
?>

<div class="table-header">
    <h3 class="table-title">
        Historique des <?= htmlspecialchars(ucfirst($typeHistoriqueDemandee)) ?>s
        pour <?= htmlspecialchars($nomPersonnel) ?> (<?= htmlspecialchars($utilisateurId) ?>)
    </h3>
    <!-- Potentiels boutons d'action globaux pour la table (export, etc.) -->
</div>
<div class="table-scroll-wrapper scroll-custom">
    <table class="table">
        <thead>
            <tr>
                <?php if (!empty($enteteTable)): ?>
                    <?php foreach ($enteteTable as $titre): ?>
                        <th><?= htmlspecialchars($titre) ?></th>
                    <?php endforeach; ?>
                    <!-- Colonne Actions si des actions par ligne sont prévues -->
                    <!-- <th>Actions</th> -->
                <?php else: ?>
                    <th>Informations</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($lignesTable) && !empty($lignesTable)): ?>
                <?php foreach ($lignesTable as $ligne): ?>
                    <tr>
                        <?php foreach ($ligne as $key => $valeur):
                            // Ne pas afficher les IDs bruts si on a déjà les libellés
                            if (str_ends_with($key, '_id') && isset($ligne[str_replace('_id', '_libelle', $key)])) {
                                continue;
                            }
                            // Formater les dates si nécessaire (supposons qu'elles sont déjà au format souhaité)
                            $displayValue = htmlspecialchars($valeur);
                            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $valeur)) { // Simple check for YYYY-MM-DD
                                try {
                                    $date = new DateTime($valeur);
                                    $displayValue = $date->format('d/m/Y');
                                } catch (Exception $e) {
                                    // Keep original value if date parsing fails
                                }
                            }
                        ?>
                            <td><?= $displayValue ?></td>
                        <?php endforeach; ?>
                        <!-- Cellule pour les actions par ligne (ex: modifier, supprimer une entrée d'historique) -->
                        <!-- <td>
                            <button class="btn-action btn-edit-hist" title="Modifier cette entrée">✏️</button>
                            <button class="btn-action btn-delete-hist" title="Supprimer cette entrée">🗑️</button>
                        </td> -->
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= (!empty($enteteTable)) ? count($enteteTable) + 0 : 1 ?>" style="text-align: center; padding: 20px;">
                        Aucun historique de type "<?= htmlspecialchars($typeHistoriqueDemandee) ?>" trouvé pour ce personnel.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<div class="table-footer">
    <div class="results-info">
        Affichage de <?= count($lignesTable ?? []) ?> entrée(s).
    </div>
    <!-- Pagination si nécessaire (non implémentée pour cette vue partielle simple) -->
</div>

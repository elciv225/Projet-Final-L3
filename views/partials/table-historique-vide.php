<?php
// Données passées : $message (optionnel)
$messageAffiche = $message ?? "Veuillez sélectionner les filtres pour afficher l'historique.";
?>
<div class="table-scroll-wrapper scroll-custom">
    <table class="table">
        <thead>
            <tr>
                <th>Informations sur l'Historique</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center; padding: 40px; color: var(--text-disabled);">
                    <?= htmlspecialchars($messageAffiche) ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div class="table-footer">
    <div class="results-info">Aucune donnée à afficher.</div>
</div>

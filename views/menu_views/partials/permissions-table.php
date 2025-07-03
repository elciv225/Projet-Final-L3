<?php 
$actionsCount = isset($actionsUniques) ? count($actionsUniques) : 0;
?>
<?php if (isset($menus) && !empty($menus)): ?>
    <?php foreach ($menus as $menuLibelle => $traitements): ?>
        <tr class="menu-header-row">
            <td colspan="<?= $actionsCount + 1 ?>"><?= htmlspecialchars($menuLibelle) ?></td>
        </tr>
        <?php foreach ($traitements as $traitementId => $traitementDetails): ?>
            <tr data-traitement-id="<?= htmlspecialchars($traitementId) ?>">
                <td class="traitement-label"><?= htmlspecialchars($traitementDetails['libelle']) ?></td>
                <?php if (isset($actionsUniques) && !empty($actionsUniques)): ?>
                    <?php foreach ($actionsUniques as $actionId => $actionLibelle): ?>
                        <td>
                            <?php if (isset($traitementDetails['actions'][$actionId])): ?>
                                <div class="checkbox-container">
                                    <input type="checkbox" class="checkbox permission-checkbox"
                                           data-action-id="<?= htmlspecialchars($actionId) ?>"
                                           <?= isset($permissions[$traitementId][$actionId]) ? 'checked' : '' ?>>
                                </div>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="<?= $actionsCount + 1 ?>" style="text-align: center; padding: 20px;">
            Aucun menu ou traitement n'a été configuré dans la base de données.
        </td>
    </tr>
<?php endif; ?>

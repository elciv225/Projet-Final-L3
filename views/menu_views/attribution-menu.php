<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Gestion des menus et permissions</h1>
        </div>
    </div>

    <div class="container">
        <div class="form-section">
            <div class="section-header">
                <h3 class="section-title">Configuration des Permissions par Groupe</h3>
            </div>
            <div class="section-content">
                <div class="form-group-inline" style="margin-bottom: 32px;">
                    <label for="userGroupSelect">Groupe utilisateur:</label>
                    <div class="select-wrapper">
                        <select id="userGroupSelect" class="custom-select">
                            <option value="">Sélectionner un groupe...</option>
                            <?php if (isset($groupes)): foreach ($groupes as $groupe): ?>
                                <option value="<?= htmlspecialchars($groupe->getId()) ?>">
                                    <?= htmlspecialchars($groupe->getLibelle()) ?>
                                </option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                </div>

                <div class="permissions-table-container">
                    <div class="total-select-header">
                        <span>Tout Sélectionner</span>
                        <input type="checkbox" id="masterPermissionCheckbox" class="checkbox">
                    </div>
                    <table class="permissions-table">
                        <thead>
                        <tr>
                            <th>Menu / Traitement</th>
                            <?php
                            $actionsUniques = [];
                            if (isset($menus)) {
                                foreach ($menus as $traitements) {
                                    foreach ($traitements as $details) {
                                        foreach ($details['actions'] as $actionId => $actionLibelle) {
                                            $actionsUniques[$actionId] = $actionLibelle;
                                        }
                                    }
                                }
                            }
                            ksort($actionsUniques); // Trier les actions pour un ordre cohérent
                            foreach ($actionsUniques as $actionLibelle): ?>
                                <th><?= htmlspecialchars($actionLibelle) ?></th>
                            <?php endforeach; ?>
                        </tr>
                        </thead>
                        <tbody id="permissionsTableBody">
                        <?php if (isset($menus) && !empty($menus)): ?>
                            <?php foreach ($menus as $menuLibelle => $traitements): ?>
                                <tr class="menu-header-row">
                                    <td colspan="<?= count($actionsUniques) + 1 ?>"><?= htmlspecialchars($menuLibelle) ?></td>
                                </tr>
                                <?php foreach ($traitements as $traitementId => $traitementDetails): ?>
                                    <tr data-traitement-id="<?= htmlspecialchars($traitementId) ?>">
                                        <td class="traitement-label"><?= htmlspecialchars($traitementDetails['libelle']) ?></td>
                                        <?php foreach ($actionsUniques as $actionId => $actionLibelle): ?>
                                            <td>
                                                <?php if (isset($traitementDetails['actions'][$actionId])): ?>
                                                    <div class="checkbox-container">
                                                        <input type="checkbox" class="checkbox permission-checkbox"
                                                               data-action-id="<?= htmlspecialchars($actionId) ?>">
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 20px;">
                                    Aucun menu ou traitement n'a été configuré dans la base de données.
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-buttons">
        <button class="btn btn-primary" id="validateButton">Valider les modifications</button>
    </div>
</main>

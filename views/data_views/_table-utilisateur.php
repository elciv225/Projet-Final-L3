<table class="table" id="userTable">
    <thead>
    <tr>
        <th><input type="checkbox" id="selectAll-userTable" class="checkbox"></th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Email</th>
        <th>Type d'utilisateur</th>
        <th>Groupe utilisateur</th>
        <th>Login</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php if (isset($utilisateurs) && !empty($utilisateurs)): ?>
        <?php foreach ($utilisateurs as $utilisateur): ?>
            <tr>
                <td><input type="checkbox" class="checkbox" value="<?= $utilisateur['id'] ?>"></td>
                <td><?= htmlspecialchars($utilisateur['nom'] ?? '') ?></td>
                <td><?= htmlspecialchars($utilisateur['prenoms'] ?? '') ?></td>
                <td><?= htmlspecialchars($utilisateur['email'] ?? '') ?></td>
                <td><?= htmlspecialchars($utilisateur['type_user'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($utilisateur['groupe'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($utilisateur['id'] ?? '') ?></td>
                <td>
                    <button class="btn-action">✏️</button>
                    <button class="btn-action">🗑️</button>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="8" style="text-align: center;">Aucun utilisateur trouvé.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
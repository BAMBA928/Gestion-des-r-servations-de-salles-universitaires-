<h1>Formulaire salle</h1>
<?php if (!empty($errors)): ?>
    <ul class="errors">
        <?php foreach ($errors as $champ => $messages): ?>
            <?php foreach ($messages as $message): ?>
                <li><?= htmlspecialchars($champ) ?> : <?= htmlspecialchars($message) ?></li>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
<form method="post">
    <label>Nom
        <input type="text" name="nom" value="<?= htmlspecialchars($old['nom'] ?? '') ?>">
    </label>
    <label>Bâtiment
        <input type="text" name="batiment" value="<?= htmlspecialchars($old['batiment'] ?? '') ?>">
    </label>
    <label>Capacité
        <input type="number" name="capacite" value="<?= htmlspecialchars((string) ($old['capacite'] ?? '')) ?>">
    </label>
    <label>Type
        <select name="type">
            <?php foreach (['cours', 'informatique', 'laboratoire', 'amphitheatre', 'reunion'] as $type): ?>
                <option value="<?= $type ?>" <?= ($old['type'] ?? '') === $type ? 'selected' : '' ?>><?= $type ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>Active
        <input type="checkbox" name="active" value="1" <?= ($old['active'] ?? true) ? 'checked' : '' ?>>
    </label>
    <button type="submit">Enregistrer</button>
</form>
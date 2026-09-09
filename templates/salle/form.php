<h1><?= isset($salle) ? 'Modifier la salle' : 'Nouvelle salle' ?></h1>
<?php if (!empty($errors)): ?>
    <ul class="errors">
        <?php foreach ($errors as $champ => $messages): ?>
            <?php foreach ($messages as $message): ?>
                <li><strong><?= htmlspecialchars($champ) ?></strong> : <?= htmlspecialchars($message) ?></li>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
<form method="post" action="/salles" class="card-form">
    <label>Nom
        <input type="text" name="nom" value="<?= htmlspecialchars($old['nom'] ?? $salle->nom ?? '') ?>">
    </label>
    <label>Bâtiment
        <input type="text" name="batiment" value="<?= htmlspecialchars($old['batiment'] ?? $salle->batiment ?? '') ?>">
    </label>
    <label>Capacité
        <input type="number" name="capacite" value="<?= htmlspecialchars((string) ($old['capacite'] ?? $salle->capacite ?? '')) ?>">
    </label>
    <label>Type
        <select name="type">
            <?php $typeActuel = $old['type'] ?? $salle->type ?? ''; ?>
            <?php foreach (['cours', 'informatique', 'laboratoire', 'amphitheatre', 'reunion'] as $type): ?>
                <option value="<?= $type ?>" <?= $typeActuel === $type ? 'selected' : '' ?>><?= ucfirst($type) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label class="checkbox-row">
        <input type="checkbox" name="active" value="1" <?= ($old['active'] ?? $salle->active ?? true) ? 'checked' : '' ?>>
        Salle active
    </label>
    <button type="submit">Enregistrer</button>
</form>
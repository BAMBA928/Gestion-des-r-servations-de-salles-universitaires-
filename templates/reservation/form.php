
<?php $salles = $salles ?? [] ?>

<h1>Nouvelle réservation</h1>
<?php if (!empty($errors)): ?>
    <ul class="errors">
        <?php foreach ($errors as $champ => $messages): ?>
            <?php foreach ($messages as $message): ?>
                <li><strong><?= htmlspecialchars($champ) ?></strong> : <?= htmlspecialchars($message) ?></li>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
<form method="post" action="/reservations" class="card-form">
    <label>Salle
        <select name="salle_id">
            <?php foreach ($salles as $salle): ?>
                <option value="<?= $salle->id ?>" <?= ($old['salle_id'] ?? '') == $salle->id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($salle->nom) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>Responsable
        <input type="text" name="responsable" value="<?= htmlspecialchars($old['responsable'] ?? '') ?>">
    </label>
    <label>Email
        <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
    </label>
    <label>Motif
        <textarea name="motif"><?= htmlspecialchars($old['motif'] ?? '') ?></textarea>
    </label>
    <label>Début
        <input type="datetime-local" name="date_debut" value="<?= htmlspecialchars($old['date_debut'] ?? '') ?>">
    </label>
    <label>Fin
        <input type="datetime-local" name="date_fin" value="<?= htmlspecialchars($old['date_fin'] ?? '') ?>">
    </label>
    <button type="submit">Réserver</button>
</form>
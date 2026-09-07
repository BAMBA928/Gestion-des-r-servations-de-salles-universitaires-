<?php $salle = $salle ?? null ?>
<?php if ($salle === null): ?>
    <p>Salle introuvable.</p>
<?php else: ?>
    <h1><?= htmlspecialchars($salle->nom) ?></h1>
    <p>Bâtiment : <?= htmlspecialchars($salle->batiment) ?></p>
    <p>Capacité : <?= htmlspecialchars((string) $salle->capacite) ?></p>
    <p>Type : <?= htmlspecialchars($salle->type) ?></p>
    <p>Statut : <?= $salle->active ? 'Active' : 'Inactive' ?></p>
    <a href="/salles/<?= htmlspecialchars((string) $salle->id) ?>/edit">Modifier</a>
<?php endif; ?>
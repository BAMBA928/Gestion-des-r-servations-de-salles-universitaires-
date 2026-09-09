<?php $salle = $salle ?? null ?>
<?php if ($salle === null): ?>
    <div class="empty-state">Salle introuvable.</div>
<?php else: ?>
    <h1><?= htmlspecialchars($salle->nom) ?></h1>
    <div class="detail-card">
        <p><strong>Bâtiment :</strong> <?= htmlspecialchars($salle->batiment) ?></p>
        <p><strong>Capacité :</strong> <?= htmlspecialchars((string) $salle->capacite) ?> places</p>
        <p><strong>Type :</strong> <?= htmlspecialchars($salle->type) ?></p>
        <p><strong>Statut :</strong>
            <span class="badge <?= $salle->active ? 'active' : 'inactive' ?>">
                <?= $salle->active ? 'Active' : 'Inactive' ?>
            </span>
        </p>
        <div class="detail-actions">
            <a href="/salles/<?= htmlspecialchars((string) $salle->id) ?>/edit" class="btn">Modifier</a>
        </div>
    </div>
<?php endif; ?>
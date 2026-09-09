<?php $reservations = $reservations ?? [] ?>
<h1>Réservations</h1>
<?php if (empty($reservations)): ?>
    <div class="empty-state">Aucune réservation pour le moment.</div>
<?php else: ?>
    <ul class="card-list">
        <?php foreach ($reservations as $reservation): ?>
            <li>
                <a href="/reservations/<?= htmlspecialchars((string) $reservation->id) ?>">
                    <?= htmlspecialchars($reservation->responsable) ?>
                    <span class="badge <?= $reservation->statut === 'confirmée' ? 'confirmee' : 'annulee' ?>">
                        <?= htmlspecialchars($reservation->statut) ?>
                    </span>
                    <span class="meta"><?= htmlspecialchars($reservation->date_debut->format('d/m/Y H:i')) ?> → <?= htmlspecialchars($reservation->date_fin->format('H:i')) ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
<a href="/reservations/create" class="btn-add">+ Nouvelle réservation</a>
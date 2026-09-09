
<?php $reservation = $reservation ?? null ?>

<?php if ($reservation === null): ?>
    <div class="empty-state">Réservation introuvable.</div>
<?php else: ?>
    <h1>Réservation de <?= htmlspecialchars($reservation->responsable) ?></h1>
    <div class="detail-card">
        <p><strong>Email :</strong> <?= htmlspecialchars($reservation->email) ?></p>
        <p><strong>Motif :</strong> <?= htmlspecialchars($reservation->motif) ?></p>
        <p><strong>Créneau :</strong> <?= htmlspecialchars($reservation->date_debut->format('d/m/Y H:i')) ?> → <?= htmlspecialchars($reservation->date_fin->format('H:i')) ?></p>
        <p><strong>Statut :</strong>
            <span class="badge <?= $reservation->statut === 'confirmée' ? 'confirmee' : 'annulee' ?>">
                <?= htmlspecialchars($reservation->statut) ?>
            </span>
        </p>
        <?php if ($reservation->statut === 'confirmée'): ?>
            <div class="detail-actions">
                <form method="post" action="/reservations/<?= $reservation->id ?>/cancel">
                    <button type="submit" class="btn-danger">Annuler la réservation</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php $reservation = $reservation ?? null ?>

<?php if ($reservation === null): ?>
    <p>Réservation introuvable.</p>
<?php else: ?>
    <h1>Réservation de <?= htmlspecialchars($reservation->responsable) ?></h1>
    <p>Email : <?= htmlspecialchars($reservation->email) ?></p>
    <p>Motif : <?= htmlspecialchars($reservation->motif) ?></p>
    <p>Du <?= htmlspecialchars($reservation->date_debut->format('d/m/Y H:i')) ?>
       au <?= htmlspecialchars($reservation->date_fin->format('d/m/Y H:i')) ?></p>
    <p>Statut : <?= htmlspecialchars($reservation->statut) ?></p>
    <?php if ($reservation->statut === 'confirmée'): ?>
        <form method="post" action="/reservations/<?= $reservation->id ?>/cancel">
            <button type="submit">Annuler</button>
        </form>
    <?php endif; ?>
<?php endif; ?>
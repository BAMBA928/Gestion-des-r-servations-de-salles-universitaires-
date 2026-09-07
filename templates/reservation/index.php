<?php $reservations = $reservations ?? [] ?>

<h1>Réservations</h1>
<ul>
    <?php foreach ($reservations as $reservation): ?>
        <li>
            <a href="/reservations/<?= htmlspecialchars((string) $reservation->id) ?>">
                <?= htmlspecialchars($reservation->responsable) ?> —
                <?= htmlspecialchars($reservation->date_debut->format('d/m/Y H:i')) ?>
                (<?= htmlspecialchars($reservation->statut) ?>)
            </a>
        </li>
    <?php endforeach; ?>
</ul>
<a href="/reservations/create">Nouvelle réservation</a>
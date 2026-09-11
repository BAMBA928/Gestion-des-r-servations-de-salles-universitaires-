<?php $reservations = $reservations ?? [] ?>
<h1>Réservations</h1>
<?php if ($reservations instanceof \Illuminate\Pagination\LengthAwarePaginator && $reservations->isEmpty()): ?>
    <div class="empty-state">Aucune réservation pour le moment.</div>
<?php elseif (empty($reservations)): ?>
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

    <?php if ($reservations instanceof \Illuminate\Pagination\LengthAwarePaginator): ?>
        <nav class="pagination">
            <?php if ($reservations->currentPage() > 1): ?>
                <a href="?page=<?= $reservations->currentPage() - 1 ?>">&laquo; Précédent</a>
            <?php endif; ?>
            <span>Page <?= $reservations->currentPage() ?> / <?= $reservations->lastPage() ?></span>
            <?php if ($reservations->hasMorePages()): ?>
                <a href="?page=<?= $reservations->currentPage() + 1 ?>">Suivant &raquo;</a>
            <?php endif; ?>
        </nav>
    <?php endif; ?>
<?php endif; ?>
<a href="/reservations/create" class="btn-add">+ Nouvelle réservation</a>
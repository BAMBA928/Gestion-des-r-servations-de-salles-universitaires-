
<h1>Liste des salles</h1>
<?php if ($salles->isEmpty()): ?>
    <div class="empty-state">Aucune salle enregistrée pour le moment.</div>
<?php else: ?>
    <ul class="card-list">
        <?php foreach ($salles as $salle): ?>
            <li>
                <a href="/salles/<?= htmlspecialchars((string) $salle->id) ?>">
                    <?= htmlspecialchars($salle->nom) ?>
                    <span class="badge <?= $salle->active ? 'active' : 'inactive' ?>">
                        <?= $salle->active ? 'Active' : 'Inactive' ?>
                    </span>
                    <span class="meta"><?= htmlspecialchars((string) $salle->capacite) ?> places — <?= htmlspecialchars($salle->batiment) ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <nav class="pagination">
        <?php if ($salles->currentPage() > 1): ?>
            <a href="?page=<?= $salles->currentPage() - 1 ?>">&laquo; Précédent</a>
        <?php endif; ?>
        <span>Page <?= $salles->currentPage() ?> / <?= $salles->lastPage() ?></span>
        <?php if ($salles->hasMorePages()): ?>
            <a href="?page=<?= $salles->currentPage() + 1 ?>">Suivant &raquo;</a>
        <?php endif; ?>
    </nav>
<?php endif; ?>
<a href="/salles/create" class="btn-add">+ Ajouter une salle</a>
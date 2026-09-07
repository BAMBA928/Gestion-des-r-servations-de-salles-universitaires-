
<?php $salles = $salles ?? [] ?>
<h1>Liste des salles</h1>
<ul>
    <?php foreach ($salles as $salle): ?>
        <li>
            <a href="/salles/<?= htmlspecialchars((string) $salle->id) ?>">
                <?= htmlspecialchars($salle->nom) ?> (<?= htmlspecialchars((string) $salle->capacite) ?> places)
            </a>
        </li>
    <?php endforeach; ?>
</ul>
<a href="/salles/create">Ajouter une salle</a>
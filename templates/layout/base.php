<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réservation de salles</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <span class="brand">🏫 Salles Universitaires</span>
            <nav>
                <a href="/salles">Salles</a>
                <a href="/reservations">Réservations</a>
            </nav>
        </div>
    </header>
    <main class="container">
        <?= $content ?? '' ?>
    </main>
</body>
</html>
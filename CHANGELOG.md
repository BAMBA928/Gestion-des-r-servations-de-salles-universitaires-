# Changelog

les versions du projet sont documentées dans ce fichier.

## [v1.0.0] - Finalisation
- Ajout du CSS, gestion des messages, README et CHANGELOG complets
- Diagramme de classes et vérification de l'installation depuis un dépôt cloné

## [v0.12.0] - Tests
- Tests unitaires du service de création de réservation (8 scénarios) avec repositories en mémoire
- Tests unitaires des validateurs Salle et Reservation
- Tests d'intégration Eloquent avec SQLite en mémoire (création, relations, chevauchement, annulation)

## [v0.11.0] - Conteneur d'injection de dépendances
- Configuration de PHP-DI (`config/container.php`)
- `Capsule` et `Dispatcher` configurés via factories
- Simplification de `public/index.php`
- Ajout des commandes CLI `bin/bamba` (`bamba:migrate`, `bamba:seed`)

## [v0.10.0] - Routeur
- Configuration de FastRoute (`routes/web.php`)
- Gestion des réponses 404 et 405 (avec en-tête `Allow`)
- Dispatch des requêtes dans `App\Application`

## [v0.9.0] - Interface web
- Contrôleurs `SalleController` et `ReservationController`
- Vues (layout, salle, réservation, erreurs), sorties échappées

## [v0.8.0] - Services métier
- `CreerReservationService` et `AnnulerReservationService`
- Exceptions `SalleIndisponibleException` et `ReservationIntrouvableException`

## [v0.7.0] - Repositories
- Interfaces `SalleRepositoryInterface` et `ReservationRepositoryInterface`
- Implémentations Eloquent

## [v0.6.0] - DTO
- `CreerSalleDTO` et `CreerReservationDTO`, constructeurs privés
- Ajout de `CreerReservationDTOBuilder` et `CreerSalleDTOBuilder`

## [v0.5.0] - Validation
- `ValidatorInterface`, `ValidationResult`
- `SalleValidator` et `ReservationValidator` avec Respect\Validation

## [v0.4.0] - Données initiales
- Script de seed pour 5 salles, idempotent (`firstOrCreate`)

## [v0.3.0] - Modèles
- Modèles Eloquent `Salle` et `Reservation` avec relation hasMany/belongsTo

## [v0.2.0] - Eloquent
- Configuration de Capsule Manager et connexion MySQL
- Migrations pour les tables `salles` et `reservations`

## [v0.1.0] - Initialisation Composer
- `composer.json`, autoload PSR-4, arborescence du projet

## [v0.0.0] - Initialisation du dépôt
- Dépôt Git, `.gitignore`, README et CHANGELOG initiaux
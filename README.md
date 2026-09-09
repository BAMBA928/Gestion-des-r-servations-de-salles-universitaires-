# Gestion des réservations de salles universitaires

Application web permettant de consulter les salles universitaires et de gérer leurs réservations, développée en PHP orienté objet sans framework complet, avec des composants Composer spécialisés (FastRoute, Eloquent, PHP-DI, Respect\Validation).

## Prérequis

- PHP 8.2 ou 8.3, avec les extensions : `pdo_mysql`, `dom`, `sqlite3` (pour les tests)
- MySQL
- Composer

## Installation

### 1. Cloner le dépôt

```bash
git clone <url-du-depot>
cd reservation-salles
```

### 2. Installer les dépendances

```bash
composer install
```

### 3. Configurer l'environnement

```bash
cp .env.example .env
```

Modifiez `.env` avec vos informations de connexion MySQL :
APP_ENV=development
APP_DEBUG=true
DB_DRIVER=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=reservation_salles
DB_USERNAME=root
DB_PASSWORD=


### 4. Créer la base de données

```bash
mysql -u root -p -e "CREATE DATABASE reservation_salles;"
```

### 5. Exécuter les migrations

```bash
php bin/bamba bamba:migrate
```

### 6. Ajouter les données initiales

```bash
php bin/bamba bamba:seed
```

Ce script insère 5 salles de départ (Amphithéâtre A, Salle B12, Laboratoire Chimie, Salle Informatique 1, Salle de réunion). Il peut être exécuté plusieurs fois sans créer de doublons.

## Lancer le serveur

```bash
php -S localhost:8000 -t public
```

L'application est accessible sur `http://localhost:8000`.

## Exécuter les tests

```bash
vendor/bin/phpunit
```

Les tests unitaires (`tests/Unit`) utilisent des repositories en mémoire et ne nécessitent pas MySQL. Les tests d'intégration (`tests/Integration`) utilisent une base SQLite en mémoire.

## Commandes disponibles

| Commande | Description |
|---|---|
| `php bin/bamba bamba:migrate` | Crée les tables `salles` et `reservations` |
| `php bin/bamba bamba:seed` | Insère les 5 salles initiales |

## Structure du projet

Voir `ARCHITECTURE.md` pour le détail des choix architecturaux (MVC, injection de dépendances, Repository, DTO, etc.).

## Fonctionnalités

- Gestion des salles : liste, détail, ajout, modification, activation/désactivation
- Gestion des réservations : liste, filtre par salle, détail, création, annulation
- Règles métier : salle active obligatoire, durée maximale de 4h, pas de chevauchement entre réservations confirmées, réservation dans le futur uniquement
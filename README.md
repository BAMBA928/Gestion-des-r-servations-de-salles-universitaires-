# Gestion des réservations de salles universitaires

Application web permettant de consulter les salles universitaires et de gérer leurs réservations, développée en PHP orienté objet sans framework complet, avec des composants Composer spécialisés (FastRoute, Eloquent, PHP-DI, Respect\Validation).

## Prérequis

- PHP 8.2 ou 8.3, avec les extensions : `pdo_mysql`, `dom`, `sqlite3` (pour les tests)
- MySQL
- Composer
- Docker et Docker Compose (optionnel, pour un lancement conteneurisé)

## Installation classique (sans Docker)

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

### 7. Lancer le serveur

```bash
php -S localhost:8000 -t public
```

L'application est accessible sur `http://localhost:8000`.

---

## Lancement avec Docker

### 1. Configurer les secrets

```bash
cp .env.docker.exemple .env.docker
```

Modifiez `.env.docker` avec vos propres valeurs (ce fichier n'est **jamais versionné**, voir `.gitignore`) :

MYSQL_ROOT_PASSWORD=votre_mot_de_passe
MYSQL_DATABASE=reservation_salles
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe


### 2. Démarrer les conteneurs

⚠️ Le fichier de secrets s'appelle `.env.docker` (et non `.env`), il faut donc **toujours préciser `--env-file`** :

```bash
docker compose --env-file .env.docker up --build -d
```

### 3. Vérifier que tout est démarré

```bash
docker compose ps
```

Les deux services (`app` et `db`) doivent apparaître avec le statut `Up` (et `db` idéalement `healthy`).

### 4. Exécuter les migrations et le seed dans le conteneur

```bash
docker compose exec app php bin/bamba bamba:migrate
docker compose exec app php bin/bamba bamba:seed
```

### 5. Accéder à l'application

http://localhost:8000


### Commandes utiles

| Commande | Description |
|---|---|
| `docker compose --env-file .env.docker up --build -d` | Démarre les conteneurs (reconstruit l'image si besoin) |
| `docker compose ps` | Liste l'état des conteneurs |
| `docker compose logs app` / `logs db` | Affiche les logs d'un service |
| `docker compose exec app <commande>` | Exécute une commande dans le conteneur `app` |
| `docker compose down` | Arrête les conteneurs (garde les données) |
| `docker compose down -v` | Arrête les conteneurs et supprime les données de la base |

Voir `DOCKER.md` pour le détail de chaque ligne du `Dockerfile` et du `docker-compose.yml`.

---

## Exécuter les tests

```bash
vendor/bin/phpunit
```

Les tests unitaires (`tests/Unit`) utilisent des repositories en mémoire et ne nécessitent pas MySQL. Les tests d'intégration (`tests/Integration`) utilisent une base SQLite en mémoire.

## Commandes disponibles (`bin/bamba`)

| Commande | Description |
|---|---|
| `php bin/bamba bamba:migrate` | Crée les tables `salles` et `reservations` |
| `php bin/bamba bamba:seed` | Insère les 5 salles initiales |

## Structure du projet

Voir `ARCHITECTURE.md` pour le détail des choix architecturaux (MVC, injection de dépendances, Repository, DTO, etc.) et `DOCKER.md` pour la dockerisation.

## Fonctionnalités

- Gestion des salles : liste, détail, ajout, modification, activation/désactivation
- Gestion des réservations : liste, filtre par salle, détail, création, annulation
- Règles métier : salle active obligatoire, durée maximale de 4h, pas de chevauchement entre réservations confirmées, réservation dans le futur uniquement
## Reponse
1. **Quel est le rôle de Composer ?**
    Ta réponse est juste sur l'essentiel : gérer les dépendances (installer/mettre à jour les librairies externes comme illuminate/database) et l'autoload (charger automatiquement les classes via les namespaces, sans require manuel). On peut ajouter un 3ᵉ rôle : il gère aussi les scripts (ex: lancer les tests) et les contraintes de version (ex: ^12.0 = accepte les mises à jour mineures mais pas les majeures, pour éviter de casser le projet) 
2. **Quelle différence existe entre require et require-dev ?**
    * require : dépendances nécessaires en production, pour que l'appli fonctionne (ex: illuminate/database, php-di/php-di). Sans elles, l'appli plante.
   * require-dev : dépendances utiles seulement pendant le développement (ex: phpunit/phpunit pour les tests). En production, on  peut les exclure avec composer install --no-dev, ce qui allège le déploiement. 
3. **Pourquoi faut-il versionner composer.lock ?** 
   composer.json dit "j'accepte les versions ^12.0", mais ça laisse une marge (12.0, 12.1, 12.5...). Le fichier composer.lock fige les numéros de version exacts qui ont été installés au moment du composer install. Si tu ne le versionnes pas, un collègue (ou toi, plus tard) qui fait composer install pourrait récupérer une version différente → comportement différent, bugs difficiles à reproduire. Versionner composer.lock garantit que tout le monde installe exactement les mêmes versions.
4. **Pourquoi ne versionne-t-on pas vendor/ ?**
    vendor/ contient le code source de toutes les dépendances installées — ça peut représenter des dizaines de Mo, voire plus. Comme composer.lock + composer.json permettent de régénérer vendor/ à l'identique n'importe où (composer install), le versionner serait redondant : ça alourdit le dépôt Git inutilement et complique les diffs (on ne veut pas voir le code de FastRoute dans l'historique de ton projet).

    ## reponse 2
    1. Quel rôle joue Capsule\Manager ? (précision)
Capsule\Manager est le point d'entrée qui connecte Eloquent à ta base de données sans avoir besoin de tout Laravel. Concrètement, il :

reçoit la configuration de connexion (driver, host, database, etc.) ;
établit la connexion PDO en interne ;
enregistre Eloquent comme "global" (setAsGlobal()) pour que tes futurs modèles (Salle::class, Reservation::class) puissent y accéder automatiquement, sans que tu aies à leur passer la connexion à la main à chaque fois.

C'est un peu comme un "chef d'orchestre" qui prépare la salle avant que les musiciens (tes modèles) n'arrivent — une fois que bootEloquent() est appelé, tout le reste de l'appli peut utiliser Salle::all() sans se soucier de la connexion.

2. Pourquoi Eloquent peut-il fonctionner sans Laravel ?
Eloquent est packagé dans le composant illuminate/database, qui est indépendant du framework Laravel complet. Laravel lui-même utilise ce composant en interne, mais comme c'est un package Composer séparé, n'importe quel projet PHP peut l'installer seul (composer require illuminate/database) sans installer tout Laravel (routing, sessions, etc.). C'est justement pour cette raison qu'on utilise Capsule\Manager : dans Laravel, la configuration/connexion est automatique via le conteneur du framework ; sans Laravel, il faut le faire "à la main" avec Capsule, qui simule ce rôle.

3. Où doit se trouver le démarrage de l'ORM ?
Il doit se trouver dans un seul endroit centralisé (config/database.php), appelé une seule fois, tôt dans le cycle de vie de l'application (au démarrage). Pourquoi un seul endroit ?

Si tu appelais addConnection() + bootEloquent() dans plusieurs fichiers, tu risquerais de créer plusieurs connexions ou de reconfigurer Eloquent plusieurs fois — gaspillage de ressources, comportement imprévisible.
Ça respecte aussi une contrainte du sujet : "la connexion doit être configurée une seule fois".

C'est pour ça que ce fichier est ensuite chargé une seule fois, typiquement depuis public/index.php (le point d'entrée unique) ou via le conteneur PHP-DI plus tard (Étape 11).

4. Différence entre ORM et SQL écrit à la main

	SQL à la main (PDO)	ORM (Eloquent)
Syntaxe	SELECT * FROM salles WHERE active = 1	Salle::where('active', true)->get()
Résultat	Tableau associatif brut	Objets PHP ($salle->nom, $salle->capacite)
Relations	Jointures manuelles à écrire	$salle->reservations (relation automatique)
Portabilité	Dépend du SGBD (syntaxe MySQL ≠ PostgreSQL parfois)	L'ORM adapte la requête selon le driver configuré
Risque d'erreur	Plus élevé (fautes de frappe SQL, injections si mal préparé)	Plus faible (requêtes construites par du code testé)
Contrôle fin	Total (tu maîtrises exactement la requête générée)	Un peu de "magie" — parfois moins performant si mal utilisé (ex: N+1 queries)

En résumé : le SQL à la main donne plus de contrôle mais plus de verbosité et de risques ; l'ORM accélère le développement et rend le code plus lisible/orienté objet, au prix d'un peu moins de contrôle direct sur la requête SQL générée.

## reponse 3
1. Quel type de relation Eloquent avez-vous utilisé ?
Deux relations complémentaires, qui décrivent la même relation réelle vue des deux côtés :

hasMany dans Salle : "une salle possède plusieurs réservations". Eloquent va chercher toutes les lignes de reservations où salle_id correspond à l'id de cette salle.
belongsTo dans Reservation : "une réservation appartient à une salle". Eloquent va chercher la ligne de salles dont l'id correspond au salle_id de cette réservation.

C'est une relation 1 → N (un-à-plusieurs) : une salle peut avoir zéro, une, ou plusieurs réservations, mais chaque réservation appartient à une seule salle. La règle simple pour se souvenir laquelle utiliser : la table qui contient la clé étrangère (ici reservations.salle_id) utilise belongsTo ; l'autre table utilise hasMany.

2. Pourquoi déclarer $fillable ou $guarded ?
C'est une protection contre une faille appelée mass assignment (assignation massive). Imagine que tu fasses :

php
Salle::create($_POST);

Si un utilisateur malveillant ajoute un champ inattendu dans le formulaire (ex: id pour écraser une autre salle, ou une colonne sensible que tu ajouterais plus tard comme est_premium), Eloquent l'insérerait sans distinction, sans que tu l'aies prévu.

$fillable est une liste blanche : seuls les champs listés (nom, batiment, capacite, type, active) peuvent être remplis via create() ou fill(). Tout le reste est ignoré automatiquement par Eloquent, même si présent dans le tableau de données. $guarded fait l'inverse (liste noire de champs interdits), mais $fillable est généralement préféré car plus explicite et plus sûr par défaut (tu dois lister volontairement ce qui est autorisé, plutôt que d'oublier de bloquer quelque chose).

4. Pourquoi convertir les dates en objets ?
Sans cast, $reservation->date_debut serait une simple chaîne de caractères ("2026-09-10 10:00:00"), sur laquelle tu ne peux rien faire de pratique — pas de comparaison propre, pas de calcul de durée. Avec 'date_debut' => 'datetime', Eloquent te retourne un objet Carbon (une extension de DateTimeImmutable/DateTime), qui permet :
## reponse 4
1. Migration vs Seeder (confirmé)
Exactement : la migration s'occupe de la structure (créer/modifier des tables, colonnes, contraintes) — c'est le "squelette" de la base. Le seeder s'occupe des données à l'intérieur de tables déjà existantes — c'est le "contenu". On pourrait résumer : migration = CREATE TABLE, seeder = INSERT INTO. Les deux sont complémentaires et souvent utilisées dans cet ordre (d'abord migrer, puis seeder).

3. Empêcher les doublons (confirmé)
Très bonne synthèse, et tu as raison de mentionner l'alternative : on pourrait aussi mettre une contrainte UNIQUE sur la colonne nom directement en base (ALTER TABLE salles ADD UNIQUE (nom)), ce qui empêcherait MySQL lui-même d'accepter un doublon (et lancerait une exception si on essayait). C'est une protection complémentaire, pas exclusive : firstOrCreate() évite déjà le problème au niveau applicatif, et une contrainte UNIQUE protégerait même si quelqu'un insérait des données autrement (script SQL direct, autre appli). Dans un vrai projet, on combine souvent les deux — mais pour ce projet pédagogique, firstOrCreate() suffit.

2. Pourquoi les données initiales doivent-elles être reproductibles ?
Voici l'explication : le script de seed sert à donner à l'application un état de départ cohérent et connu, exploitable par n'importe qui, n'importe quand. Concrètement :

Nouveau développeur / nouvelle machine : quand ton collègue (ou toi sur un autre PC) clone le dépôt, la base est vide. Le seed lui permet de retrouver immédiatement les mêmes 5 salles que toi, sans avoir à les saisir à la main.
Tests automatisés : à l'Étape 12, les tests d'intégration ont besoin de données prévisibles pour vérifier des comportements (ex: "la réservation de la Salle B12 doit être refusée si elle chevauche une réservation existante"). Si les données changent à chaque exécution, les tests deviendraient instables (parfois ils passent, parfois non).
Démonstration / recette : à l'Étape 11 (scénarios de recette), on teste avec "Salle B12" — il faut que cette salle existe de façon garantie dans n'importe quel environnement (dev, recette, prod de test).

Si le seed n'était pas reproductible (donc s'il créait des doublons à chaque exécution), relancer le script deviendrait dangereux — on ne pourrait le lancer qu'une seule fois, ce qui est fragile et source d'erreurs humaines (quelqu'un l'exécute deux fois par mégarde → base polluée).

## Reponse 5

4. Comment retourner plusieurs erreurs en une seule fois ? (précision)
Tu as raison sur l'usage d'assert(), mais précisons le mécanisme exact : dans notre code, chaque règle (nom, batiment, capacite...) est vérifiée séparément, dans une boucle foreach, avec son propre try/catch. Donc même si le champ nom échoue, la boucle continue vers batiment, capacite, etc. — chaque échec est capturé indépendamment et ajouté au tableau $errors[$champ].

La nuance assert() vs check() que tu mentionnes est juste, mais elle s'applique à l'intérieur d'une seule règle : si une règle combine plusieurs contraintes (ex: v::stringType()->length(2, 100)), assert() va lister toutes les sous-erreurs de cette règle composée (via getMessages() qui retourne un tableau), alors que check() s'arrêterait à la toute première sous-contrainte violée. C'est pour ça qu'on utilise assert() + catch plutôt que check(), combiné à notre boucle foreach qui elle gère le "plusieurs champs en erreur en même temps".

2. Pourquoi créer une interface de validation ? (bonne intuition, à élargir)
Ton raisonnement sur Open/Closed est valide dans l'esprit (on peut ajouter de nouveaux validateurs sans modifier le code existant), mais la raison principale ici, c'est plutôt le principe Liskov / Dependency Inversion (2 des 5 principes SOLID que tu devras documenter dans ARCHITECTURE.md) :

Substituabilité : n'importe quelle classe qui dépend d'un ValidatorInterface (ex: un contrôleur) peut recevoir indifféremment un SalleValidator ou un ReservationValidator — le code appelant n'a pas besoin de savoir lequel exactement, il appelle juste ->validate($data).
Testabilité : en test unitaire, tu pourrais créer un FauxValidator implements ValidatorInterface qui retourne toujours isValid() === true, pour tester ton contrôleur sans dépendre de la vraie logique de validation.
Découplage : le contrôleur dépend d'un contrat (l'interface), pas d'une implémentation concrète — si demain tu changes Respect\Validation pour une autre librairie, tu réécris seulement les classes concrètes, sans toucher aux contrôleurs.

3. Pourquoi le validateur ne doit-il pas enregistrer les données ? (à corriger)
Ta réponse mélange un peu deux étapes différentes (Validation à l'Étape 5, DTO à l'Étape 6) — c'est normal, elles sont liées, mais la vraie raison ici est le principe de responsabilité unique (Single Responsibility Principle, encore SOLID) :

Le rôle du validateur est de répondre à une seule question : "ces données respectent-elles le format attendu ?" — rien de plus.
S'il enregistrait aussi les données (Salle::create($data)), il porterait deux responsabilités mélangées : valider ET persister. Résultat : impossible de tester la validation seule sans toucher la base de données ; impossible de réutiliser le validateur dans un contexte où on ne veut pas encore sauvegarder (ex: validation en amont d'un DTO, comme tu l'as bien remarqué) ; et si demain la logique de sauvegarde change (ajout d'un log, d'une notification), il faudrait modifier une classe qui n'a rien à voir avec la validation.
Le sujet confirme cette séparation stricte des couches : Validator → DTO → Service → Repository, chacun avec une seule responsabilité claire.

1. Pourquoi séparer la validation syntaxique des règles métier ?
La différence entre les deux :

Validation syntaxique (ce qu'on fait à l'Étape 5) : "est-ce que email a la forme d'un email ?", "est-ce que motif fait entre 5 et 255 caractères ?" — des règles indépendantes du contexte métier, qui ne changent jamais selon la situation.
Règle métier (ce qu'on fera à l'Étape 8, dans CreerReservationService) : "est-ce que date_debut < date_fin ?", "est-ce que la salle est déjà réservée sur ce créneau ?" — des règles qui dépendent de l'état de l'application (d'autres réservations existantes, de la salle en question) et qui peuvent évoluer avec les besoins métier.

Pourquoi séparer ? Parce que ces deux types de vérifications ont des cycles de vie et des dépendances différents :

La validation syntaxique ne nécessite aucun accès à la base de données — elle peut être testée avec juste un tableau de données en entrée.
La règle métier nécessite d'interroger la base (chercher les réservations existantes via le Repository) — elle ne peut pas être testée sans ce contexte.

Si on mélangeait les deux dans le validateur, on rendrait le validateur dépendant du Repository/de la base de données — cassant sa simplicité et sa testabilité (rappel de la contrainte du sujet : "les tests unitaires des services ne doivent pas nécessiter MySQL", ce qui n'est possible que si la logique métier est bien isolée du reste).
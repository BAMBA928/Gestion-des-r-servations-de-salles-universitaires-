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
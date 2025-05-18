# Documentation de la classe FormatHelper

## Description générale

La classe `FormatHelper` est une classe utilitaire qui fournit des méthodes statiques pour formater des données provenant de la base de données dans un format standardisé pour les réponses API. Elle facilite la transformation des résultats bruts de requêtes SQL en structures JSON cohérentes et bien formatées.

## Méthodes publiques

### `commentaire(array $row): array`

Formate les données d'un commentaire pour l'API.

**Paramètres:**
- `$row` (array): Tableau associatif contenant les données brutes du commentaire

**Retourne:**
- Un tableau formaté avec la structure suivante:
  ```php
  [
    "id" => int,
    "commentaire" => string,
    "note" => int,
    "date" => [
      "ajout" => string,
      "modification" => string
    ],
    "user" => [
      "id" => int,
      "pseudo" => string
    ],
    "lieu" => [
      "id" => int,
      "nom" => string
    ]
  ]
  ```

**Exemple d'utilisation:**
```php
$commentaireData = $database->fetchRow("SELECT * FROM commentaires WHERE id_commentaire = 1");
$commentaireFormatte = FormatHelper::commentaire($commentaireData);
```

### `lieuLight(array $row): array`

Formate les données d'un lieu pour une réponse d'API légère (utilisation dans les listes ou aperçus).

**Paramètres:**
- `$row` (array): Tableau associatif contenant les données brutes du lieu

**Retourne:**
- Un tableau formaté avec la structure suivante:
  ```php
  [
    "id" => int,
    "nom" => string,
    "horaires" => string,
    "description" => string,
    "adresse" => [
      "adresse" => string,
      "code_postal" => string,
      "ville" => string,
      "telephone" => string,
      "site_web" => string
    ],
    "type" => array,
    "est_evenement" => bool,
    "date_evenement" => [
      "debut" => string|null,
      "fin" => string|null
    ],
    "position" => [
      "latitude" => float,
      "longitude" => float,
      "distance_km" => float
    ],
    "equipements" => array,
    "ages" => array
  ]
  ```

**Exemple d'utilisation:**
```php
$lieuData = $database->fetchRow("SELECT * FROM lieux WHERE id_lieu = 1");
$lieuFormatte = FormatHelper::lieuLight($lieuData);
```

### `lieuDetail(array $row): array`

Formate les données d'un lieu pour une réponse d'API détaillée (vue complète d'un lieu).

**Paramètres:**
- `$row` (array): Tableau associatif contenant les données détaillées du lieu

**Retourne:**
- Un tableau formaté avec la structure suivante:
  ```php
  [
    "id" => int,
    "nom" => string,
    "description" => string,
    "horaires" => string,
    "adresse" => [
      "adresse" => string,
      "ville" => string,
      "code_postal" => string,
      "telephone" => string,
      "site_web" => string
    ],
    "type" => array,
    "est_evenement" => bool,
    "date_evenement" => [
      "debut" => string|null,
      "fin" => string|null
    ],
    "position" => [
      "latitude" => float,
      "longitude" => float
    ],
    "equipements" => array,
    "ages" => array,
    "commentaires" => array,
    "note_moyenne" => float,
    "nombre_commentaires" => int
  ]
  ```

**Exemple d'utilisation:**
```php
$lieuDetailData = $database->fetchRow("SELECT * FROM vue_lieu_details WHERE id_lieu = 1");
$lieuDetailFormatte = FormatHelper::lieuDetail($lieuDetailData);
```

### `userPreference(array $row): array`

Formate les données d'un utilisateur et ses préférences.

**Paramètres:**
- `$row` (array): Tableau associatif contenant les données de l'utilisateur et ses préférences

**Retourne:**
- Un tableau formaté avec la structure suivante:
  ```php
  [
    "id" => int,
    "pseudo" => string,
    "mail" => string,
    "telephone" => string,
    "grade" => int,
    "dates" => [
      "creation" => string|null,
      "derniere_connexion" => string|null
    ],
    "recevoirMail" => bool,
    "preferences" => [
      "tranches_age" => array,
      "equipements" => array
    ]
  ]
  ```

**Exemple d'utilisation:**
```php
$userData = $database->fetchRow("SELECT * FROM vue_user_preference WHERE id_user = 1");
$userFormatte = FormatHelper::userPreference($userData);
```

## Méthodes privées

### `parseCommaSeparated(string $data): array`

Parse une chaîne de valeurs séparées par des virgules. Utile pour traiter les listes d'équipements, de types, etc.

- Si la chaîne commence par '{', elle traite chaque élément comme un objet JSON
- Sinon, elle traite la chaîne comme une simple liste de noms

**Retourne:**
- Un tableau d'objets avec `id` et `nom` pour chaque élément

### `decodeJsonArray(string $json): array`

Décode une chaîne JSON représentant un tableau, en ajoutant les crochets englobants si nécessaire.

### `safeJsonDecode(string $value): string`

Décode de manière sécurisée une chaîne potentiellement encodée en JSON. Si le décodage échoue, décode les entités HTML.

## Informations techniques supplémentaires

- La classe utilise des opérateurs de fusion null (`??`) pour gérer les clés manquantes
- Les identifiants sont toujours castés en entiers
- Les coordonnées géographiques sont arrondies à 5 décimales
- Les notes moyennes sont arrondies à 2 décimales
- Les booléens sont castés explicitement avec `(bool)`
- La classe gère le décodage des entités HTML pour assurer l'affichage correct des caractères spéciaux

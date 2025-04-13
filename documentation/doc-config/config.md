# Documentation KidsSpot API

## Configuration

### Structure des fichiers de configuration

L'API utilise une architecture de configuration séparée entre les environnements :

- `config.php` : Fichier principal qui détecte l'environnement actuel et charge la configuration appropriée
- `config.development.php` : Configuration spécifique à l'environnement de développement
- `config.production.php` : Configuration spécifique à l'environnement de production

### Sélection de l'environnement

Dans le fichier `config.php`, l'environnement est défini manuellement :

```php
// Pour le développement (localhost)
$env = 'development'; 
// OU
// Pour la production
//$env = 'production';
```

Pour passer d'un environnement à l'autre, commentez/décommentez la ligne appropriée.

### Paramètres de configuration

Chaque fichier de configuration contient les paramètres suivants :

| Paramètre | Description |
|-----------|-------------|
| `host` | L'adresse du serveur de base de données |
| `db_name` | Le nom de la base de données |
| `username` | L'identifiant de connexion à la base de données |
| `password` | Le mot de passe de connexion à la base de données |
| `jwt_secret` | La clé secrète utilisée pour signer les tokens JWT |

## Base de données

La classe `Database` gère la connexion à la base de données à l'aide de PDO.

### Utilisation

```php
// Création d'une instance de Database
$database = new Database();

// Obtention de la connexion
$conn = $database->getConnexion();

// Utilisation de la connexion pour des requêtes
$stmt = $conn->prepare("SELECT * FROM table");
```

La classe `Database` charge automatiquement la configuration appropriée depuis `config.php`.

## Authentication JWT

L'API utilise JSON Web Tokens (JWT) pour l'authentification, implémentée dans la classe `JWT`.

### Initialisation

```php
// Charger la configuration
$config = require 'config.php';

// Créer une instance JWT
$jwt = new JWT($config);
```

### Génération de token

```php
// Données à inclure dans le token
$donnees = [
    'user_id' => 123,
    'role' => 'admin'
];

// Génération du token
$token = $jwt->generer($donnees);
```

### Vérification de token

```php
// Token à vérifier
$token = "header.payload.signature";

// Vérification du token
$donnees = $jwt->verifier($token);

if ($donnees) {
    // Token valide, $donnees contient les informations du payload
    $user_id = $donnees['user_id'];
} else {
    // Token invalide ou expiré
}
```

## Sécurité

Les bonnes pratiques suivantes sont mises en place :

1. Séparation des configurations par environnement
2. Stockage sécurisé des clés JWT
3. Utilisation de PDO avec le mode d'erreur Exception
4. Paramètres de connexion isolés dans des fichiers de configuration

## Recommandations pour la production

1. Ajoutez `config.production.php` à votre `.gitignore` pour éviter d'exposer les informations sensibles
2. Utilisez des clés JWT différentes pour le développement et la production
3. Considérez l'utilisation de variables d'environnement pour les informations les plus sensibles
4. Vérifiez régulièrement que le bon environnement est activé avant un déploiement

## Débogage

Pour déboguer la configuration, vous pouvez modifier le fichier `config.php` pour afficher les informations de configuration (uniquement en développement). Pensez à supprimer ou à commenter ce code avant de passer en production.

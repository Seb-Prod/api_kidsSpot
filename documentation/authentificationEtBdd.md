# Documentation du système d'authentification et base de données

## Aperçu du système

Ce système fournit une infrastructure simple mais robuste pour la connexion à une base de données MySQL et la gestion de l'authentification via des tokens JWT (JSON Web Tokens). Le système est conçu pour fonctionner dans différents environnements (développement et production) avec des configurations spécifiques à chaque environnement.

## Structure des fichiers

- `Database.php` - Gestion de la connexion à la base de données MySQL via PDO
- `JWT.php` - Gestion de l'authentification par tokens JWT
- `config.php` - Point d'entrée pour charger la configuration appropriée
- `config.development.php` - Configuration pour l'environnement de développement
- `config.production.php` - Configuration pour l'environnement de production

## Configuration du système

### Gestion des environnements

Le système utilise un mécanisme de configuration basé sur l'environnement. Pour basculer entre les environnements, modifiez le fichier `config.php` :

```php
// Pour le développement (local)
$env = 'development'; // Activer cette ligne
//$env = 'production'; // Commenter cette ligne

// Pour la production
//$env = 'development'; // Commenter cette ligne
$env = 'production'; // Activer cette ligne
```

### Fichiers de configuration

Les fichiers de configuration contiennent les paramètres spécifiques à chaque environnement :

#### Configuration de développement (`config.development.php`)
- Base de données locale
- Paramètres simplifiés pour le développement
- Clé JWT spécifique à l'environnement de développement

#### Configuration de production (`config.production.php`)
- Paramètres de connexion à la base de données de production
- Clé JWT sécurisée pour l'environnement de production

## Classe Database

La classe `Database` gère la connexion à la base de données MySQL en utilisant PDO.

### Utilisation

```php
// Création d'une instance de la classe Database
$database = new Database();

// Obtention de la connexion
$connexion = $database->getConnexion();

// Utilisation de la connexion
$query = $connexion->prepare("SELECT * FROM users");
$query->execute();
$users = $query->fetchAll(PDO::FETCH_ASSOC);
```

### Méthodes

#### `__construct()`
Initialise une nouvelle instance de la classe Database en chargeant la configuration appropriée depuis le fichier `config.php`.

#### `getConnexion()`
Établit et retourne une connexion PDO à la base de données.

**Retour :** Objet PDO en cas de succès, null en cas d'erreur.

## Classe JWT

La classe `JWT` gère l'authentification par tokens JWT.

### Utilisation

```php
// Charger la configuration
$config = require(__DIR__ . '/config.php');

// Créer une instance de la classe JWT
$jwt = new JWT($config);

// Générer un token pour un utilisateur
$userData = [
    'id' => 123,
    'username' => 'john_doe',
    'role' => 'admin'
];
$token = $jwt->generer($userData);

// Vérifier un token reçu
$headerToken = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
$token = str_replace('Bearer ', '', $headerToken);
$userData = $jwt->verifier($token);

if ($userData) {
    // Token valide, $userData contient les informations de l'utilisateur
    echo "Utilisateur authentifié: " . $userData['username'];
} else {
    // Token invalide ou expiré
    header('HTTP/1.0 401 Unauthorized');
    echo json_encode(['error' => 'Accès non autorisé']);
    exit;
}
```

### Méthodes

#### `__construct($config)`
Initialise une nouvelle instance de la classe JWT avec la configuration fournie.

**Paramètres :**
- `$config` : Tableau associatif contenant la configuration, notamment la clé secrète JWT (`jwt_secret`).

#### `generer($donnees)`
Génère un token JWT contenant les données fournies.

**Paramètres :**
- `$donnees` : Tableau associatif contenant les données à inclure dans le token.

**Retour :** Chaîne représentant le token JWT généré.

#### `verifier($token)`
Vérifie et décode un token JWT.

**Paramètres :**
- `$token` : Chaîne représentant le token JWT à vérifier.

**Retour :** Tableau associatif contenant les données du token si valide, `false` sinon.

## Sécurité

### Protection des informations sensibles

- Les informations de connexion à la base de données et les clés JWT sont stockées dans des fichiers de configuration séparés.
- Ces fichiers ne doivent pas être versionnés dans Git (ajoutez-les à `.gitignore`).
- En production, utilisez des variables d'environnement ou des systèmes de gestion de secrets.

### Bonnes pratiques

1. **Rotation des clés JWT** : Changez régulièrement la clé secrète JWT.
2. **Durée de validité des tokens** : La durée par défaut est de 1 heure (3600 secondes). Ajustez-la selon vos besoins de sécurité.
3. **HTTPS** : Utilisez toujours HTTPS en production pour protéger les tokens en transit.
4. **Validation des entrées** : Validez toujours les données utilisateur avant de les utiliser.

## Gestion des erreurs

Le système dispose de mécanismes de gestion des erreurs :

- La connexion à la base de données affiche une erreur en cas d'échec.
- Le fichier de configuration génère une erreur JSON si le fichier correspondant à l'environnement n'est pas trouvé.
- La classe JWT génère une exception si la clé secrète n'est pas définie dans la configuration.

## Exemple d'intégration complète

Voici un exemple d'utilisation des deux classes ensemble pour une API d'authentification :

```php
<?php
// Inclure les fichiers nécessaires
require_once 'Database.php';
require_once 'JWT.php';

// Charger la configuration
$config = require(__DIR__ . '/config.php');

// Pour une route d'authentification (login)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/api/login') {
    // Récupérer les données d'identification
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';
    
    // Connexion à la base de données
    $database = new Database();
    $conn = $database->getConnexion();
    
    // Vérifier les identifiants
    $stmt = $conn->prepare("SELECT id, username, role, password_hash FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password_hash'])) {
        // Générer un token JWT
        $jwt = new JWT($config);
        $token = $jwt->generer([
            'id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role']
        ]);
        
        // Renvoyer le token
        header('Content-Type: application/json');
        echo json_encode(['token' => $token]);
    } else {
        // Échec de l'authentification
        header('HTTP/1.0 401 Unauthorized');
        echo json_encode(['error' => 'Identifiants incorrects']);
    }
    exit;
}

// Pour une route protégée par authentification
if (strpos($_SERVER['REQUEST_URI'], '/api/protected') === 0) {
    // Récupérer le token
    $headerToken = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    $token = str_replace('Bearer ', '', $headerToken);
    
    // Vérifier le token
    $jwt = new JWT($config);
    $userData = $jwt->verifier($token);
    
    if (!$userData) {
        header('HTTP/1.0 401 Unauthorized');
        echo json_encode(['error' => 'Accès non autorisé']);
        exit;
    }
    
    // L'utilisateur est authentifié, continuer avec les ressources protégées
    // ...
}
```

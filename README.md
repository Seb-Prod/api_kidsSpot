# API KidsSpot

API RESTful permettant d'accéder à des informations sur des lieux et événements pour enfants.

## Structure du projet

```
api_kidsSpot/
├── config/
│   ├── config.php          # Configuration de la base de données
│   └── Database.php        # Classe de connexion à la base de données
├── models/
│   └── Lieux.php           # Modèle pour les lieux
├── lieux/
│   ├── lire.php            # Point d'entrée pour obtenir un lieu spécifique
│   └── readAll.php         # Point d'entrée pour obtenir les lieux autour d'une position
└── .htaccess               # Configuration des URL amicales
```

## Configuration de la base de données

### `config/config.php`

Ce fichier contient les configurations de connexion à la base de données pour différents environnements.

**Fonctionnalités :**
- Définition de l'environnement courant (development ou production)
- Configuration des paramètres de connexion spécifiques à chaque environnement
- Vérification de la validité de l'environnement spécifié

**Exemple d'utilisation :**
```php
// Pour utiliser la configuration
$config = require_once('config/config.php');
$host = $config['host'];
$db_name = $config['db_name'];
```

### `config/Database.php`

Cette classe gère la connexion à la base de données en utilisant les paramètres définis dans `config.php`.

**Fonctionnalités :**
- Initialisation des paramètres de connexion
- Établissement de la connexion PDO à la base de données
- Gestion des erreurs de connexion

**Exemple d'utilisation :**
```php
// Création d'une instance de la base de données
$database = new Database();
$db = $database->getConnexion();
```

## Modèle des lieux

### `models/Lieux.php`

Cette classe gère les opérations liées aux lieux dans la base de données.

**Méthodes principales :**

1. **`obtenirLieu($id)`**
   - **Paramètre :** ID du lieu à récupérer
   - **Retourne :** Requête préparée contenant les informations du lieu spécifié
   - **Description :** Récupère les détails complets d'un lieu spécifique par son ID

2. **`obtenirLieuxAutour($latitude, $longitude)`**
   - **Paramètres :** Coordonnées géographiques (latitude et longitude)
   - **Retourne :** Requête préparée contenant les lieux à proximité des coordonnées
   - **Description :** Récupère tous les lieux proches des coordonnées fournies, triés par distance croissante

## Points d'entrée de l'API

### `lieux/read.php`

Point d'entrée pour récupérer les détails d'un lieu spécifique.

**Méthode HTTP :** GET

**Paramètres URL :**
- `id` : ID du lieu à récupérer (obligatoire)

**Format de réponse :**
```json
{
  "lieu": {
    "id": 1,
    "nom": "Parc des enfants",
    "description": "Un parc avec des jeux pour enfants",
    "type_lieu": "Parc",
    "est_evenement": false,
    "date_evenement": {
      "debut": null,
      "fin": null
    },
    "adresse": {
      "adresse": "123 rue des Jeux",
      "ville": "Lyon",
      "code_postal": "69000",
      "telephone": "0123456789",
      "site_web": "http://exemple.com"
    },
    "position": {
      "latitude": 45.75,
      "longitude": 4.85
    },
    "equipements": ["Toboggan", "Balançoires"]
  }
}
```

**Codes de réponse :**
- `200 OK` : Lieu trouvé
- `400 Bad Request` : ID manquant ou invalide
- `404 Not Found` : Lieu non trouvé
- `405 Method Not Allowed` : Méthode HTTP non autorisée

**Exemple d'utilisation :**
```
GET /lieux/1
```

### `lieux/readAll.php`

Point d'entrée pour récupérer les lieux autour d'une position géographique.

**Méthode HTTP :** GET

**Paramètres URL :**
- `lat` : Latitude (obligatoire)
- `lng` : Longitude (obligatoire)

**Format de réponse :**
```json
{
  "lieux": [
    {
      "id": 1,
      "nom": "Parc des enfants",
      "adresse": {
        "adresse": "123 rue des Jeux",
        "code_postal": "69000",
        "ville": "Lyon"
      },
      "type": "Parc",
      "est_evenement": false,
      "position": {
        "latitude": 45.75,
        "longitude": 4.85,
        "distance_km": 0.5
      },
      "equipements": ["Toboggan", "Balançoires"]
    },
    {
      "id": 2,
      "nom": "Jardin d'aventures",
      "adresse": {
        "adresse": "456 avenue des Enfants",
        "code_postal": "69001",
        "ville": "Lyon"
      },
      "type": "Jardin",
      "est_evenement": false,
      "position": {
        "latitude": 45.76,
        "longitude": 4.86,
        "distance_km": 1.2
      },
      "equipements": ["Tyrolienne", "Structure d'escalade"]
    }
  ]
}
```

**Codes de réponse :**
- `200 OK` : Lieux trouvés
- `400 Bad Request` : Coordonnées manquantes ou invalides
- `404 Not Found` : Aucun lieu trouvé
- `405 Method Not Allowed` : Méthode HTTP non autorisée

**Exemple d'utilisation :**
```
GET /lieux/autour/45.75/4.85
```

## URL amicales (via .htaccess)

L'API utilise des règles de réécriture pour simplifier les URL :

- `/lieux/{id}` redirige vers `read.php?id={id}`
- `/lieux/autour/{latitude}/{longitude}` redirige vers `readAll.php?lat={latitude}&lng={longitude}`

## Installation

### Prérequis
- PHP 7.4+
- MySQL/MariaDB
- Apache avec mod_rewrite activé

### Configuration

1. Clonez le dépôt :
```bash
git clone https://github.com/votre-utilisateur/api_kidsSpot.git
```

2. Importez la structure de la base de données (un fichier SQL sera fourni ultérieurement)

3. Configurez l'environnement dans `config/config.php` :
```php
$env = 'development'; // Changez pour 'production' en production
```

4. Assurez-vous que les chemins dans les fichiers d'inclusion sont corrects selon votre installation

## Remarques sur la sécurité

- L'API vérifie que les paramètres sont valides avant de les utiliser dans les requêtes
- L'API utilise des requêtes préparées pour éviter les injections SQL
- En production, il est recommandé de sécuriser les identifiants de base de données et de limiter les CORS
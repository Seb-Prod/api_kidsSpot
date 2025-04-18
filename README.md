# 🧒 KidsSpot API

Une API RESTful permettant d'accéder à des informations sur des lieux et événements adaptés aux enfants.

![Version](https://img.shields.io/badge/version-1.0.0-blue)
![PHP](https://img.shields.io/badge/PHP-8.0+-green)
![Licence](https://img.shields.io/badge/license-MIT-yellow)

## 📋 Sommaire

- [Description](#-description)
- [Prérequis](#-prérequis)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Structure du projet](#-structure-du-projet)
- [Points d'accès API](#-points-daccès-api)
- [Authentification](#-authentification)
- [Documentation technique](#-documentation-technique)
- [Contribuer](#-contribuer)
- [Licence](#-licence)

## 📝 Description

KidsSpot API est un service web qui permet aux développeurs et aux applications d'accéder à une base de données de lieux et d'événements adaptés aux enfants. L'API fournit des informations détaillées sur ces lieux, notamment leur emplacement, leurs équipement, ainsi que les commentaires et évaluations des utilisateurs.

## 🔧 Prérequis

- PHP 8.0 ou supérieur
- MySQL 5.7 ou supérieur
- Serveur web (Apache/Nginx)

## 🏗️ Installation

### 1. Récupération du code source

```bash
git clone https://github.com/Seb-Prod/api_kidsSpot.git
cd kidsSpot
```

### 2. Configuration de la base de données

- Importez le fichier `kids_spot.sql` dans votre serveur MySQL :

```bash
mysql -u username -p database_name < kids_spot.sql
```

- Configurez les informations de connexion dans le fichier approprié:

Pour l'environnement de développement, modifiez `config/config.development.php`:
```php
<?php
return [
    'host' => 'localhost',
    'db_name' => 'kids_spot',
    'username' => 'votre_nom_utilisateur',
    'password' => 'votre_mot_de_passe',
    'jwt_secret' => 'votre_clé_secrète_pour_jwt'
];
```

Pour l'environnement de production, modifiez `config/config.production.php` de manière similaire.

### 3. Configuration de l'environnement

Modifiez le fichier `config/config.php` pour définir l'environnement:

```php
// Pour le développement
$env = 'development';

// Pour la production (décommentez la ligne ci-dessous et commentez celle du dessus)
// $env = 'production';
```

### 4. Déploiement

- Pour un environnement de développement local:
  - Placez le projet dans le dossier `htdocs` (XAMPP) ou `www` (WAMP/MAMP)
  - Démarrez Apache et MySQL
  - Accédez à l'API via `http://localhost/kidsSpot/`

- Pour un environnement de production:
  - Déployez les fichiers sur votre serveur web
  - Assurez-vous que les permissions des fichiers sont correctement configurées
  - Configurez votre serveur web pour pointer sur le répertoire racine du projet

## ⚙️ Configuration

### Sécurité

- La clé secrète JWT (`jwt_secret`) doit être une chaîne aléatoire complexe pour assurer la sécurité des tokens
- En production, assurez-vous que les fichiers de configuration ne sont pas accessibles publiquement
- Utilisez HTTPS pour protéger les communications API

### CORS

Si vous devez configurer les CORS pour permettre l'accès depuis d'autres domaines, modifiez le fichier `.htaccess` ou ajoutez les en-têtes appropriés dans vos scripts PHP.

## 📂 Structure du projet
Le projet est organisé par logique métier, chaque dossier correspond à un module spécifique de l'API.
```
📦kidsSpot/
┣ 📂 commentaires/          # Contient les endpoints pour les commentaires.
┣ 📂 config/                # Contient les fichiers de configuration de l'application et la connexion à la base de données.
┣ 📂 documentation/         # Documentation technique détaillée par endpoint.
┣ 📂 errors/                # Contient les pages d'erreurs personnalisées pour certaines situations.
┣ 📂 favoris/               # Contient les endpoints pour les favoris.
┣ 📂 lieux/                 # Contient les endpoints pour les lieux.
┣ 📂 middleware/            # Contient les classes d'aide pour la validation, la sécurité, les autorisations et le formatage des données.
┣ 📂 models/                # Contient les classes PHP représentant les tables de la base de données.
┣ 📂 sql/                   # Contient les fichiers SQL nécessaires à la création de la base de données.
┗ 📂 users/                 # Contient les endpoints pour les users.

```

## 🌐 Points d'accès API

### ℹ️ Information
#### Grades utilisateurs
| ID | Valeur |
|----|--------|
| 1  | standart |
| 2  | superUser |
| 3  | spare |
| 4  | admin |

#### Types de lieux
| ID | Valeur |
|----|--------|
| 1  | Restaurant |
| 2  | Loisir |
| 3  | Culture |

#### Types d'équipement
| ID | Valeur |
|----|--------|
| 1  | Accès poussette |
| 2  | Aire de jeux |
| 3  | Micro-ondes |
| 4  | Chaise haute |
| 5  | Table à langer |

### Lieux

| Endpoint | Méthode | Description | 🔐 | Doc |
|----------|---------|-------------|------------------|-----|
| `/lieux/{id}` | GET | Détails d'un lieu spécifique | Non | [📖](documentation/endPoints/lieux/read.md) |
| `/lieux/autour/{lat}/{long}` | GET | Liste les lieux autour d'une position | Non | [📖](documentation/endPoints/lieux/readAll.md) |
| `/lieux/ajout` | POST | Ajoute un nouveau lieu/événement | Oui |[📖](documentation/endPoints/lieux/create.md) |
| `/lieux/modifier` | PUT | Modifie un lieu/événement existant | Oui |[📖](documentation/endPoints/lieux/update.md) |
| `/lieux/supprime` | DELETE | Supprime un lieu/événement | Oui |[📖](documentation/endPoints/lieux/delete.md) |

### Commentaires

| Endpoint | Méthode | Description | Authentification | Documentation |
|----------|---------|-------------|------------------|---------------|
| `/commentaires/ajouter` | POST | Ajoute un commentaire | Oui | [Documentation](documentation/endPoints/commentaires/create.md) |
| `/commentaires/lire/{id}` | GET | Détails d'un commentaire | Non | [Documentation](documentation/endPoints/commentaires/read.md) |
| `/commentaires/lire/lieu/{id}` | GET | Liste les commentaires d'un lieu | Non | [Documentation](documentation/endPoints/commentaires/readAll.md) |
| `/commentaires/modifier` | PUT | Modifie un commentaire | Oui | [Documentation](documentation/endPoints/commentaires/update.md) |
| `/commentaires/supprimer` | DELETE | Supprime un commentaire | Oui | [Documentation](documentation/endPoints/commentaires/delete.md) |

### Favoris

| Endpoint | Méthode | Description | Authentification | Documentation |
|----------|---------|-------------|------------------|---------------|
| `/favoris/ajouter` | POST | Ajoute un favoris | Oui | [Documentation](documentation/endPoints/favoris/create.md) |
| `/favoris` | GET | Liste tous les favoris | Oui | [Documentation](documentation/endPoints/favoris/read.md) |
| `/favoris/supprimer` | DELETE | Supprime un favoris | Oui | [Documentation](documentation/endPoints/favoris/delete.md) |

## 🔐 Authentification

L'API utilise l'authentification par token JWT (JSON Web Token). Pour les endpoints protégés:

1. Obtenez un token via `/auth/login` avec vos identifiants
2. Utilisez ce token dans l'en-tête HTTP pour les requêtes authentifiées:
   ```
   Authorization: Bearer votre_token_jwt
   ```

Le token a une durée de validité limitée et devra être renouvelé périodiquement.

## 📚 Documentation technique

### Models
| Models | Documentation |
|--------|---------------|
| Commentaires.php | [Documentation](documentation/models/Commentaires.md) |

Pour plus de détails sur chaque endpoint, consultez les documents spécifiques dans le dossier `documentation/`:

- [Configuration (connexion à la base de données)](documentation/doc-config/config.md)
- [Ajout d'un commentaire et d'une note](documentation/endPoints/commentaires/create.md)
- [Suppression de commentaire et de note](documentation/endPoints/commentaires/delete.md)

## 🤝 Contribuer

Les contributions sont les bienvenues! Pour contribuer:

1. Forkez le projet
2. Créez une branche pour votre fonctionnalité (`git checkout -b feature/ma-fonctionnalite`)
3. Committez vos changements (`git commit -m 'Ajout de ma fonctionnalité'`)
4. Poussez vers la branche (`git push origin feature/ma-fonctionnalite`)
5. Ouvrez une Pull Request

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier LICENSE pour plus de détails.

---

© 2025 KidsSpot API. Tous droits réservés.

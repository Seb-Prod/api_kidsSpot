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

KidsSpot API est un service web qui permet aux développeurs et aux applications d'accéder à une base de données de lieux et d'événements adaptés aux enfants. L'API fournit des informations détaillées sur ces lieux, notamment leur emplacement, leurs équipements, ainsi que les commentaires et évaluations des utilisateurs.

## 🔧 Prérequis

- PHP 8.0 ou supérieur
- MySQL 5.7 ou supérieur
- Serveur web (Apache/Nginx)

## 🏗️ Installation

### 1. Récupération du code source

```bash
git clone https://github.com/Seb-Prod/api_kidsSpot.git
cd api_kidsSpot
```

### 2. Configuration de la base de données

- #### Créez la base de données :
    Vous devez d'abord créer la base de données dans votre serveur MySQL avant d'importer les données. Vous pouvez le faire en utilisant un client MySQL (comme phpMyAdmin, MySQL Workbench, ou la ligne de commande).

    Par exemple, avec la ligne de commande MySQL :
```bash
mysql -u votre_nom_utilisateur -p
# Vous serez invité à entrer votre mot de passe
mysql> CREATE DATABASE IF NOT EXISTS `kids_spot`; 
mysql> USE `kids_spot`;
```

- #### Importez le fichier `kids_spot.sql` :
    Une fois la base de données créée et sélectionnée, importez le fichier SQL pour créer les tables et insérer les données.
```bash
mysql> SOURCE /sql/kids_spot.sql
```
(Remplacez `/sql/kids_spot.sql` par le chemin réel du fichier.)

## ⚙️ Configuration

Cette API utilise des fichiers `.env` pour gérer les variables d'environnement.

### Fichiers `.env`

L'application utilise les fichiers `.env` suivants pour la configuration :

* `.env.local` : Configuration spécifique à votre environnement de développement local. Ce fichier surcharge les configurations du `.env` principal.
* `.env.production` : Configuration pour l'environnement de production.
* `.env.example` : Un fichier modèle contenant toutes les variables d'environnement nécessaires avec des valeurs par défaut ou des espaces réservés. **Vous devez copier ce fichier vers `.env.local` et `.env.production` et y configurer les valeurs appropriées.**

### Configuration de la base de données

1. **Configurez les variables d'environnement :**
   Copiez le contenu du fichier `.env.example` vers `.env.local` (pour votre développement local) et `.env.production` (pour votre environnement de production). Ensuite, modifiez ces fichiers avec les informations de configuration appropriées pour chaque environnement.

   **Exemple de configuration dans `.env.local` :**

   ```
   APP_ENV=development

   # Base de données
   DB_HOST=localhost
   DB_NAME=kids_spot
   DB_USER=root
   DB_PASSWORD=

   # JWT
   JWT_SECRET=votre_clé_secrète_pour_jwt

   # Configuration Email
   MAIL_HOST=smtp.gmail.com
   MAIL_AUTH=true
   MAIL_USERNAME=kidspottp@gmail.com
   MAIL_PASSWORD=zcnw vpsy yobn ayis
   MAIL_SECURE=tls
   MAIL_PORT=587
   MAIL_FROM_EMAIL=kidspottp@gmail.com
   MAIL_FROM_NAME="Kids Spot"
   ```

   **Assurez-vous de configurer correctement les informations de connexion à la base de données, la clé secrète JWT et les paramètres d'email dans les fichiers `.env.local` et `.env.production`.**

### Déploiement

- Pour un environnement de développement local:
  - Placez le projet dans le dossier `htdocs` (XAMPP) ou `www` (WAMP/MAMP)
  - Démarrez Apache et MySQL
  - Accédez à l'API via `http://localhost/api_kidsSpot/`

- Pour un environnement de production:
  - Déployez les fichiers sur votre serveur web
  - Assurez-vous que les permissions des fichiers sont correctement configurées
  - Configurez votre serveur web pour pointer sur le répertoire racine du projet

### Sécurité

- La clé secrète JWT (`JWT_SECRET`) doit être une chaîne aléatoire complexe pour assurer la sécurité des tokens
- En production, assurez-vous que les fichiers de configuration ne sont pas accessibles publiquement
- Utilisez HTTPS pour protéger les communications API

### CORS

Si vous devez configurer les CORS pour permettre l'accès depuis d'autres domaines, modifiez le fichier `.htaccess` ou ajoutez les en-têtes appropriés dans vos scripts PHP.

## 📂 Structure du projet

Le projet est organisé par logique métier, chaque dossier correspond à un module spécifique de l'API.
```
📦api_kidsSpot/
┣ 📂 commentaires/          # Contient les endpoints pour les commentaires.
┣ 📂 config/                # Contient les fichiers de configuration de l'application et la connexion à la base de données.
┣ 📂 documentation/         # Documentation technique détaillée par endpoint.
┣ 📂 errors/                # Contient les pages d'erreurs personnalisées pour certaines situations.
┣ 📂 favoris/               # Contient les endpoints pour les favoris.
┣ 📂 lib/                   # Librairies et fonctions utilitaires.
┣ 📂 lieux/                 # Contient les endpoints pour les lieux.
┣ 📂 middleware/            # Contient les classes d'aide pour la validation, la sécurité, les autorisations et le formatage des données.
┣ 📂 models/                # Contient les classes PHP représentant les tables de la base de données.
┣ 📂 profil/                # Contient les endpoints pour les préférences utilisateur.
┣ 📂 sql/                   # Contient les fichiers SQL nécessaires à la création de la base de données.
┗ 📂 users/                 # Contient les endpoints pour les utilisateurs.
```

## ℹ️ Information

#### Grades utilisateurs
| ID | Valeur |
|----|--------|
| 1  | standard |
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
| 6  | Parking |

## 🌐 Points d'accès API

### Lieux

| Endpoint | Méthode | Description | 🔐 | 📖 |
|----------|---------|-------------|------------------|-----|
| `/lieux/{id}` | GET | Détails d'un lieu spécifique | Non | [📖](documentation/endPoints/lieux/read.md) |
| `/lieux/autour/{lat}/{long}` | GET | Liste les lieux autour d'une position | Non | [📖](documentation/endPoints/lieux/readAll.md) |
| `/lieux/ajout` | POST | Ajoute un nouveau lieu/événement | Oui | [📖](documentation/endPoints/lieux/create.md) |
| `/lieux/modifier` | PUT | Modifie un lieu/événement existant | Oui | [📖](documentation/endPoints/lieux/update.md) |
| `/lieux/supprimer` | DELETE | Supprime un lieu/événement | Oui | [📖](documentation/endPoints/lieux/delete.md) |

### Commentaires

| Endpoint | Méthode | Description | 🔐 | 📖 |
|----------|---------|-------------|----|-----|
| `/commentaires/{id}` | GET | Détails d'un commentaire | Non | [📖](documentation/endPoints/commentaires/read.md) |
| `/commentaires/lieu/{id}` | GET | Liste les commentaires d'un lieu | Non | [📖](documentation/endPoints/commentaires/readAll.md) |
| `/commentaires/ajouter` | POST | Ajoute un commentaire | Oui | [📖](documentation/endPoints/commentaires/create.md) |
| `/commentaires/modifier` | PUT | Modifie un commentaire | Oui | [📖](documentation/endPoints/commentaires/update.md) |
| `/commentaires/supprimer` | DELETE | Supprime un commentaire | Oui | [📖](documentation/endPoints/commentaires/delete.md) |

### Favoris

| Endpoint | Méthode | Description | 🔐 | 📖 |
|----------|---------|-------------|------------------|---------------|
| `/favoris/ajouter` | POST | Ajoute un favori | Oui | [📖](documentation/endPoints/favoris/create.md) |
| `/favoris/{lat}/{lng}` | GET | Liste tous les favoris | Oui | [📖](documentation/endPoints/favoris/read.md) |
| `/favoris/supprimer` | DELETE | Supprime un favori | Oui | [📖](documentation/endPoints/favoris/delete.md) |

### User
| Endpoint | Méthode | Description | 🔐 | 📖 |
|----------|---------|-------------|------------------|---------------|
| `/users/create.php` | POST | Ajoute un utilisateur | Non | [📖](documentation/endPoints/users/create_user.md) |
| `/users/forgot.php` | POST | Envoie un mail avec un token de récupération | Non | [📖](documentation/endPoints/users/reset_password_request.md) |
| `/users/login.php` | POST | Authentification des utilisateurs | Non | [📖](documentation/endPoints/users/login_user.md) |
| `/users/reset.php` | POST | Réinitialisation du mot de passe via un token | Non | [📖](documentation/endPoints/users/reset_password.md) |
| `/users/sendMail.php` | POST | Envoi d'emails groupés aux utilisateurs | Oui | [📖](documentation/endPoints/users/send_mail.md) |
| `/users/update.php` | PUT | Mise à jour du profil utilisateur | Oui | [📖](documentation/endPoints/users/update_user.md) |

### Profil (préférences de l'utilisateur)
| Endpoint | Méthode | Description | 🔐 | 📖 |
|----------|---------|-------------|------------------|---------------|
| `/profil/editer` | POST | Editer les préférences de l'utilisateur | Oui | [📖](documentation/endPoints/profil/edit.md) |
| `/profil/` | GET | Liste les préférences de l'utilisateur | Oui | [📖](documentation/endPoints/profil/read.md) |


## 🔐 Authentification

L'API utilise l'authentification par token JWT (JSON Web Token). Pour les endpoints protégés:

1. Obtenez un token via `/users/login.php` avec vos identifiants
2. Utilisez ce token dans l'en-tête HTTP pour les requêtes authentifiées:
   ```
   Authorization: Bearer votre_token_jwt
   ```

Le token a une durée de validité limitée et devra être renouvelé périodiquement.

## 📚 Documentation technique

### Models
| Model | 📖 |
|--------|---------------|
| Commentaires.php | [📖](documentation/models/Commentaires.md) |

### Classes
| Classe | 📖 |
|--------|----------------|
| /middleware/Validator.php | [📖](documentation/classes/validator.md) |

### Fonctions
| Fonctions | 📖 |
|--------|----------------|
| /middleware/UserAutorisation.php | [📖](documentation/fonctions/UserAutorisation.md) |

### Autres documentation

- [Système d'authentification et base de données](documentation/authentificationEtBdd.md)
- [Configuration des gestionnaires d'erreurs avec .htaccess](documentation/htaccess-error-documentation.md)

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
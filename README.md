# KidsSpot API

📝 Bienvenue sur la documentation de l'API **KidsSpot**, une API RESTful permettant d'accéder à des informations sur des lieux et événements adapté aux enfants.

## 🏗️ Installation
1️⃣Cloner le projet
```batch
git clone https://github.com/Seb-Prod/api_kidsSpot.git
cd kidsSpot
```
2️⃣**Configurer la base de données**
- Importer le fichier `kids_spot.sql` dans MySQL.
- Modifier le fichier `config.development.php` ou `config.production.php`(dans le répertoire `/config) pour renseigner les informations de connexion à la base de données.
    ```php
    <?php
    return [
        'host' => '',
        'db_name' => '',
        'username' => '',
        'password' => '',
        'jwt_secret' => ''
    ];  
    ```
- Modifier dans le fichier `config.php`(dans le répertoire `/config`) la variable `$env`selon si en production ou en dévelopement.
    ```php
    $env = 'development'; // À commenter si en production
    //$env = 'production'; // A commenter si en dévelopement    ```

3️⃣**Lancer le server local**
- Utiliser un serveur local comme XAMPP ou WAMP.
- Placer le projet dans le dossier `htdocs`(pour XAMPP).
- Démarrer Apache et MySQL

4️⃣**Accéder à l'API**
- Consulter le chapitre sur les endpoints dans la doucumentation si dessous.

## 📂 Structure du projet

```
📦kidsSpot/
┣ 📂 config/
┃   ┗ config.php                # Configuration de la base de données
┃   ┗ Database.php              # Classe de connexion à la base de données
┃   ┗ JWT.php                   # Classe pour gérer les tokens JWT
┃   ┗ config.development.php    # Configuration environement de dévelopement
┃   ┗ config.production.php     # Configuration environement de production
┣ 📂 documentation/
┃   ┗ 📂 endPoints
┃       ┗ 📂 commentaires
┃           ┗ create.md
┃           ┗ read.md
┃           ┗ readAll.md
┃           ┗ update.md
┃           ┗ delete.md
┃ 
┣ 📂 commentaires/
┃   ┗ create.php                # EndPoint pour ajouter
┃   ┗ read.php                  # EndPoind pour lire un
┃   ┗ readAll.php               # EndPoind pour tous lire
┃   ┗ update.php                # EndPoint pour la modification
┃   ┗ delete.php                # EndPoint pour la suppression


├── errors/
│   ├── 400.php
│   ├── 404.php 
├── middleware/
│   ├── auth_middleware.php # Middleware de vérification d'authentification.
├── models/
│   └── Lieux.php           # Modèle pour les lieux
│   └── Users.php           # Modèle pour les utilisateurs
├── lieux/
│   ├── read.php            # Point d'entrée pour obtenir un lieu spécifique
│   └── readAll.php         # Point d'entrée pour obtenir les lieux autour d'une position
│   └── create.php          # Point d'entrée pour ajouter un lieu
│   └── delete.php          # Point d'entrée pour suprimer un lieu
│   └── update.php          # Point d'entrée pour modifier un lieu
└── .htaccess               # Configuration des URL amicales
```

## 📚 Documentation Technique
- [Configuration (connexion à la base de données)](documentation/doc-config/config.md)
- [EndPoint - commentaires - Ajout d’un commentaire et d’une note](documentation/endPoints/commentaires/create.md)
- [EndPoint - commentaires - Suppression de commentaire et de note](documentation/endPoints/commentaires/delete.md)
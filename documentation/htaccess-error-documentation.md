# Configuration des gestionnaires d'erreurs avec .htaccess

## Introduction

Le fichier `.htaccess` permet de configurer facilement la gestion d'erreurs HTTP pour votre application PHP. Cette configuration permet de rediriger automatiquement les erreurs courantes (comme 400 Bad Request et 404 Not Found) vers vos scripts de gestion d'erreurs personnalisés.

## Configuration de base

Voici une configuration `.htaccess` qui utilise les gestionnaires d'erreurs `400.php` et `404.php` :

```apache
# Activer le moteur de réécriture
RewriteEngine On

# Définir les gestionnaires d'erreurs personnalisés
ErrorDocument 400 /400.php
ErrorDocument 404 /404.php

# Autres configurations possibles
# ErrorDocument 401 /401.php
# ErrorDocument 403 /403.php
# ErrorDocument 500 /500.php
```

## Configuration avancée avec paramètres

Pour passer des paramètres supplémentaires à vos gestionnaires d'erreurs, vous pouvez utiliser des redirections conditionnelles :

```apache
# Activer le moteur de réécriture
RewriteEngine On

# Configuration de base des erreurs
ErrorDocument 400 /400.php
ErrorDocument 404 /404.php

# Règles spécifiques pour les coordonnées géographiques (exemple)
# Détecter des coordonnées non numériques dans une URL comme /geo/{lat}/{lng}
RewriteCond %{REQUEST_URI} ^/geo/([^/]+)/([^/]+)/?$
RewriteCond %{REQUEST_URI} ^/geo/([^0-9.-]+)/([0-9.-]+)/?$ [OR]
RewriteCond %{REQUEST_URI} ^/geo/([0-9.-]+)/([^0-9.-]+)/?$
RewriteRule ^geo/.*$ /400.php?reason=coords [L]

# Redirection pour les coordonnées valides mais qui ne correspondent à aucune ressource
RewriteCond %{REQUEST_URI} ^/geo/([0-9.-]+)/([0-9.-]+)/?$
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^geo/.*$ /404.php?invalid_coords=1 [L]
```

## Gestion des API RESTful

Pour une API RESTful, vous pouvez configurer le `.htaccess` pour gérer automatiquement différents types de requêtes et d'erreurs :

```apache
# Activer le moteur de réécriture
RewriteEngine On

# Forcer le type de contenu JSON pour les erreurs
<Files ~ "^(400|404|500)\.php$">
    Header set Content-Type "application/json; charset=UTF-8"
</Files>

# Configuration de base des erreurs
ErrorDocument 400 /400.php
ErrorDocument 404 /404.php
ErrorDocument 500 /500.php

# Rediriger toutes les requêtes vers index.php sauf pour les fichiers existants
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?route=$1 [QSA,L]

# Règles spécifiques pour détecter certains types d'erreurs et les rediriger
# vers les gestionnaires appropriés avec des paramètres contextuels
```

## Compatibilité avec les environnements de développement et production

Vous pouvez adapter votre configuration `.htaccess` en fonction de l'environnement :

```apache
# Détection de l'environnement (exemple)
# Cette approche nécessite une configuration serveur appropriée
SetEnvIf Host "localhost|127\.0\.0\.1" ENVIRONMENT=development
SetEnvIf Host "^(www\.)?example\.com$" ENVIRONMENT=production

# Configuration des erreurs différente selon l'environnement
<IfDefine ENVIRONMENT=development>
    # En développement : afficher des erreurs détaillées
    php_flag display_errors on
    php_value error_reporting 32767
</IfDefine>

<IfDefine ENVIRONMENT=production>
    # En production : journaliser les erreurs mais ne pas les afficher
    php_flag display_errors off
    php_flag log_errors on
    php_value error_log /path/to/error.log
</IfDefine>

# Gestionnaires d'erreurs communs aux deux environnements
ErrorDocument 400 /400.php
ErrorDocument 404 /404.php
```

## Bonnes pratiques

1. **Vérifiez la compatibilité** : Certaines directives peuvent ne pas fonctionner selon votre hébergement.
2. **Testez vos configurations** : Assurez-vous que les redirections fonctionnent comme prévu avant la mise en production.
3. **Utilisez des chemins absolus** : Pour éviter les problèmes de chemin relatif, préférez les chemins absolus à partir de la racine du site.
4. **Cache-Control** : Ajoutez des en-têtes pour éviter la mise en cache des pages d'erreur.
5. **Journalisation** : Configurez une journalisation appropriée pour les erreurs, surtout en production.

## Exemple complet

Voici un exemple complet de fichier `.htaccess` qui intègre la gestion d'erreurs et d'autres configurations courantes :

```apache
# Activer le moteur de réécriture
RewriteEngine On

# Définir le répertoire de base
RewriteBase /

# Rediriger HTTP vers HTTPS (en production)
# RewriteCond %{HTTPS} off
# RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Prévenir l'accès aux fichiers de configuration
<FilesMatch "^(config\.|\.env|\.git)">
    Order allow,deny
    Deny from all
</FilesMatch>

# Définir les gestionnaires d'erreurs
ErrorDocument 400 /400.php
ErrorDocument 401 /401.php
ErrorDocument 403 /403.php
ErrorDocument 404 /404.php
ErrorDocument 500 /500.php

# Empêcher la mise en cache des pages d'erreur
<FilesMatch "^(400|401|403|404|500)\.php$">
    Header set Cache-Control "no-store, no-cache, must-revalidate, max-age=0"
    Header set Pragma "no-cache"
    Header set Expires "Mon, 1 Jan 1990 00:00:00 GMT"
    Header set Content-Type "application/json; charset=UTF-8"
</FilesMatch>

# Règles spécifiques pour les API
RewriteRule ^api/([^/]+)/([^/]+)/([^/]+)/?$ api/index.php?resource=$1&id=$2&action=$3 [QSA,L]
RewriteRule ^api/([^/]+)/([^/]+)/?$ api/index.php?resource=$1&id=$2 [QSA,L]
RewriteRule ^api/([^/]+)/?$ api/index.php?resource=$1 [QSA,L]

# Règle par défaut - rediriger tout le reste vers index.php
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?route=$1 [QSA,L]
```

Ce fichier `.htaccess` fournit une base solide pour gérer les erreurs HTTP dans votre application PHP tout en assurant la sécurité et la performance.

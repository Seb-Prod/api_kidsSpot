# Documentation de la fonction validateUserAutorisation

## Description

La fonction `validateUserAutorisation` est une fonction PHP qui permet de vérifier si un utilisateur est connecté et s'il possède les droits suffisants pour accéder à une ressource ou effectuer une action spécifique. Cette fonction est particulièrement utile dans les API REST ou les applications web nécessitant un contrôle d'accès basé sur les rôles.

## Syntaxe

```php
validateUserAutorisation($donnees_utilisateur, $niveau_requis)
```

## Paramètres

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$donnees_utilisateur` | `array\|bool` | Tableau associatif contenant les informations de l'utilisateur, ou `false` si l'utilisateur n'est pas authentifié. Ce tableau provient généralement d'une vérification de token ou de session. |
| `$niveau_requis` | `int` | Entier représentant le niveau d'autorisation minimal requis pour l'action ou la ressource. La structure et la signification des niveaux sont spécifiques à l'application. |

## Valeur de retour

La fonction ne retourne aucune valeur (`void`). Si l'autorisation échoue, elle termine l'exécution du script en envoyant une réponse HTTP appropriée.

## Comportement

1. Si `$donnees_utilisateur` est `false` (utilisateur non connecté) :
   - Envoie un code de statut HTTP 401 (Non autorisé)
   - Retourne un message JSON indiquant que l'utilisateur doit se connecter
   - Termine l'exécution du script avec `exit`

2. Si l'utilisateur est connecté mais ne possède pas le niveau d'autorisation requis :
   - Envoie un code de statut HTTP 403 (Interdit)
   - Retourne un message JSON indiquant que l'utilisateur n'a pas les droits suffisants
   - Termine l'exécution du script avec `exit`

3. Si l'utilisateur est connecté et possède le niveau d'autorisation requis :
   - La fonction se termine normalement sans interrompre l'exécution du script
   - Le code suivant l'appel à cette fonction sera exécuté

## Dépendances

La fonction dépend d'une fonction externe `verifierAutorisation()` qui n'est pas définie dans le fragment de code fourni. Cette fonction est supposée prendre les données de l'utilisateur et le niveau requis en entrée, et retourner un booléen indiquant si l'utilisateur est autorisé.

## Exemples d'utilisation

### Exemple 1: Protection d'un endpoint d'API

```php
<?php
// Point d'entrée d'API pour supprimer un article
function deleteArticle($articleId) {
    // Récupérer les informations de l'utilisateur depuis le token JWT
    $userData = validateJwtToken();
    
    // Vérifier que l'utilisateur est connecté et possède le niveau d'administrateur (niveau 3)
    validateUserAutorisation($userData, 3);
    
    // Si l'exécution continue, c'est que l'utilisateur est autorisé
    // Procéder à la suppression de l'article
    $result = ArticleModel::delete($articleId);
    
    // Retourner une réponse
    http_response_code(200);
    echo json_encode(["success" => true, "message" => "Article supprimé avec succès"]);
}
```

### Exemple 2: Protection d'une page d'administration

```php
<?php
// Page d'administration des utilisateurs
session_start();
$userData = $_SESSION['user'] ?? false;

// Vérifier que l'utilisateur est connecté et possède au moins le niveau de modérateur (niveau 2)
validateUserAutorisation($userData, 2);

// Si l'exécution continue, c'est que l'utilisateur est autorisé
// Afficher le contenu de la page d'administration
?>
<!DOCTYPE html>
<html>
<head>
    <title>Administration des utilisateurs</title>
</head>
<body>
    <h1>Gestion des utilisateurs</h1>
    <!-- Contenu de la page d'administration -->
</body>
</html>
```

### Exemple 3: Vérification des droits pour une API REST

```php
<?php
// API REST pour mettre à jour un profil utilisateur
$requestMethod = $_SERVER["REQUEST_METHOD"];
$pathInfo = $_SERVER["PATH_INFO"] ?? "";

if ($requestMethod === "PUT" && preg_match("#^/users/(\d+)$#", $pathInfo, $matches)) {
    $userId = $matches[1];
    
    // Récupérer les informations de l'utilisateur depuis le token
    $userData = getBearerToken();
    
    // Vérifier si l'utilisateur est connecté
    validateUserAutorisation($userData, 1);
    
    // Vérification supplémentaire : l'utilisateur peut-il modifier ce profil ?
    // (soit c'est son propre profil, soit il est administrateur)
    if ($userData['id'] != $userId && $userData['grade'] < 3) {
        http_response_code(403);
        echo json_encode(["message" => "Vous ne pouvez modifier que votre propre profil."]);
        exit;
    }
    
    // Procéder à la mise à jour du profil
    $requestData = json_decode(file_get_contents("php://input"), true);
    updateUserProfile($userId, $requestData);
    
    http_response_code(200);
    echo json_encode(["success" => true, "message" => "Profil mis à jour avec succès"]);
}
```

## Bonnes pratiques

1. Cette fonction doit être appelée au début de chaque script ou route nécessitant une autorisation.
2. La fonction `verifierAutorisation()` doit être implémentée de manière à vérifier correctement les niveaux d'autorisation en fonction des besoins spécifiques de l'application.
3. Les messages d'erreur peuvent être personnalisés en fonction du contexte.
4. Pour une meilleure sécurité, il est recommandé d'utiliser HTTPS lors de la transmission des informations d'authentification.

## Sécurité

- La fonction termine l'exécution du script en cas d'échec d'autorisation, ce qui empêche tout accès non autorisé au code suivant.
- Les messages d'erreur fournissent suffisamment d'informations pour guider l'utilisateur sans révéler de détails sensibles sur le système d'autorisation.
- Les codes de statut HTTP appropriés (401 et 403) sont utilisés conformément aux standards.

## Notes

- Cette fonction est conçue pour être utilisée dans un environnement où les réponses JSON sont attendues (comme une API REST).
- Pour les applications web traditionnelles, vous pourriez vouloir rediriger l'utilisateur vers une page de connexion plutôt que de renvoyer un message JSON.

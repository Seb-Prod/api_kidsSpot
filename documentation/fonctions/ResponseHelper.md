# Documentation des Fonctions de Réponse HTTP

## Description générale

Ce fichier contient un ensemble de fonctions utilitaires qui permettent d'envoyer des réponses HTTP standardisées au format JSON. Ces fonctions simplifient la création de réponses cohérentes à travers toute l'API, en gérant automatiquement les codes de statut HTTP et le formatage des données en JSON.

## Fonctions disponibles

### `sendSuccessResponse($data, $status = 200): void`

Envoie une réponse HTTP de succès contenant des données.

**Paramètres:**
- `$data` (mixed): Les données à inclure dans la réponse JSON
- `$status` (int, optionnel): Le code de statut HTTP à envoyer (défaut: 200 OK)

**Format de la réponse:**
```json
{
  "status": "success",
  "data": [Les données fournies]
}
```

**Exemple d'utilisation:**
```php
// Envoyer une liste d'éléments
$items = $database->fetchAll("SELECT * FROM items");
sendSuccessResponse($items);

// Envoyer une réponse de succès avec un code personnalisé
sendSuccessResponse($userData, 200);
```

### `sendUpdatedResponse(): void`

Envoie une réponse HTTP indiquant qu'une modification a été effectuée avec succès.

**Format de la réponse:**
```json
{
  "status": "success",
  "message": "La modification a été effectué"
}
```

**Exemple d'utilisation:**
```php
// Après une mise à jour réussie
$database->update("users", ["name" => "Nouveau Nom"], ["id" => 1]);
sendUpdatedResponse();
```

### `sendCreatedResponse($message = "Ressource créée avec succès."): void`

Envoie une réponse HTTP de création réussie (201 Created).

**Paramètres:**
- `$message` (string, optionnel): Message personnalisé à inclure dans la réponse (défaut: "Ressource créée avec succès.")

**Format de la réponse:**
```json
{
  "status": "success",
  "message": "Ressource créée avec succès."
}
```

**Exemple d'utilisation:**
```php
// Après la création d'un nouvel utilisateur
$userId = $database->insert("users", ["name" => "Nouvel Utilisateur", "email" => "email@example.com"]);
sendCreatedResponse("L'utilisateur a été créé avec succès.");
```

### `sendDeletedResponse(): void`

Envoie une réponse HTTP indiquant une suppression réussie (204 No Content).

**Note:** Cette fonction ne renvoie aucun contenu, conformément à la spécification HTTP 204.

**Exemple d'utilisation:**
```php
// Après la suppression d'une ressource
$database->delete("users", ["id" => 1]);
sendDeletedResponse();
```

### `sendErrorResponse($message, $status = 400): void`

Envoie une réponse HTTP d'erreur avec un message.

**Paramètres:**
- `$message` (string): Le message d'erreur à inclure dans la réponse
- `$status` (int, optionnel): Le code de statut HTTP à envoyer (défaut: 400 Bad Request)

**Format de la réponse:**
```json
{
  "status": "error",
  "message": "Le message d'erreur fourni"
}
```

**Exemple d'utilisation:**
```php
// Erreur simple
if (!$userId) {
    sendErrorResponse("Utilisateur non trouvé", 404);
}

// Erreur d'authentification
if (!$tokenValide) {
    sendErrorResponse("Token d'authentification invalide ou expiré", 401);
}
```

### `sendValidationErrorResponse($message, $erreurs, $status = 400): void`

Envoie une réponse HTTP d'erreur de validation avec un message général et des détails pour chaque champ.

**Paramètres:**
- `$message` (string): Message d'erreur général
- `$erreurs` (array): Tableau associatif des erreurs par champ
- `$status` (int, optionnel): Le code de statut HTTP à envoyer (défaut: 400 Bad Request)

**Format de la réponse:**
```json
{
  "status": "error",
  "message": "Le message d'erreur général",
  "errors": {
    "champ1": "Message d'erreur pour le champ 1",
    "champ2": "Message d'erreur pour le champ 2"
  }
}
```

**Exemple d'utilisation:**
```php
// Validation d'un formulaire
$erreurs = [];
if (empty($_POST['email'])) {
    $erreurs['email'] = "L'email est obligatoire";
}
if (strlen($_POST['password']) < 8) {
    $erreurs['password'] = "Le mot de passe doit contenir au moins 8 caractères";
}

if (!empty($erreurs)) {
    sendValidationErrorResponse("Le formulaire contient des erreurs", $erreurs);
}
```

## Remarques techniques

- Toutes ces fonctions terminent l'exécution du script avec `exit` après l'envoi de la réponse
- L'option `JSON_UNESCAPED_UNICODE` est utilisée pour garantir un encodage correct des caractères spéciaux
- Les codes HTTP utilisés respectent les conventions REST:
  - 200: OK (succès général)
  - 201: Created (création réussie)
  - 204: No Content (succès sans contenu, typiquement pour les suppressions)
  - 400: Bad Request (erreur de validation ou de requête)
  - Les autres codes peuvent être fournis en paramètre selon les besoins

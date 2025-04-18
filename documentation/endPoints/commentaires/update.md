# 📍 Endpoint : Modifier un commentaire sur un lieu
Permet à un utilisateur authentifié de modifier **son propre** commentaire sur un lieu via une requête HTTP `PUT`.

## Endpoint: PUT `/commentaires/modifier`

### 🌐 URL
```
PUT /kidsspot/commentaires/modifier
```

### 🔐 Authentification
✅ Requise — **Token JWT dans le Header `Authorization`.**  
Le rôle de l'utilisateur doit être **≥ 1** (autorisation nécessaire).

### 💡 Paramètres du Body (JSON)
| Paramètre         | Type             | Description                                                | Obligatoire | Contraintes                                        |
|-------------------|------------------|------------------------------------------------------------|-------------|---------------------------------------------------|
| `id`             | `int`         | Identifiant du commentaire à modifier                                                | ✅ Oui      | Entier strictement positif                            |
| `note`     | `int`         | Note associée                              | ✅ Oui      | Comprise entre 0 et 5                           |
| `commentaire`        | `string`         | Texte du commentaire                                       | ✅ Oui      | Maximum 1000 caractères                             |

### 💻 Exemple de Requête
```http
POST /api/lieux/modifier
Authorization: Bearer VOTRE_JWT_TOKEN
Content-Type: application/json

{
  "id": 17,
  "commentaire": "Endroit super sympa pour les enfants !",
  "note": 3
}
```

### ✅ Exemple de Réponse - Succès (200 OK)
```json
{
  "status": "success",
  "message": "La modification a été effectuée."
}
```

### ⚠️ Exemple de Réponse - Commentaire inexistant (404 Not Found)
```json
{
  "status": "error",
  "message": "Ce commentaire n'existe pas."
}
```

### ⛔ Exemple de Réponse - Non autorisé (403 Forbidden)
```json
{
  "status": "error",
  "message": "Vous n'avez pas les droits pour effectuer cette action."
}
```

### ❌ Exemple de Réponse - Erreur de Validation (400 Bad Request)
```json
{
  "status": "error",
  "message": "Les données fournies sont invalides.",
  "errors": {
    "id": "L'identifiant doit être un entier positif",
    "note": "Une note est obligatoire entre 0 et 5"
  }
}
```

### ⚠️ Codes d’erreur possibles
| Code HTTP | Message   | Explication                         |
|-----------|-----------|-------------------------------------|
| 200       | Modification réussie        | Le commentaire a été mis à jour avec succès. |
| 400       | Mauvaise Requête | Données invalide ou manquantes. |
| 401       | Non autorisé | Token JWT manquant ou invalide. |
| 403 | Accès refusé | Rôle insuffisant pour effectuer la modification. |
| 404 | Commentaire non trouvé | L'id du commentaire est introuvable. |
| 405 | La méthode n'est pas autorisée. | Une Autre méthode HTTP que PUT a été utilisée. |
| 503 | Erreur serveur | Echec de la modification en base de données |

### 💡 Remarques
- L’utilisateur ne peut modifier que ses propres commentaires.
- Les données sont validées pour éviter des mises à jour erronées ou malveillantes.
- Si l’ID de commentaire n’existe pas, ou que l’utilisateur n’en est pas l’auteur, la modification sera bloquée.

# 📍 Endpoint : Ajouter un lieu en favoris
Permet à un utilisateur authentifié d’ajouter un lieu à sa liste de favoris via une requête HTTP `POST`.

## Endpoint: GET `/favoris/ajouter`

### 🌐 URL
```
POST /kidsspot/favoris/ajouter
```

### 🔐 Authentification
✅ Requise — **Token JWT dans le Header `Authorization`.**  
Le rôle de l'utilisateur doit être **≥ 1** (autorisation nécessaire).

### 💡 Paramètres du Body (JSON)
| Paramètre      | Type      | Description                                | Obligatoire | Contraintes                       |
|----------------|-----------|--------------------------------------------|-------------|-----------------------------------|
| `id_lieu`      | `int`     | Identifiant du lieu à ajouter en favoris               | ✅ Oui      | Entier strictement positif        |

### 💻 Exemple de Requête
```http
POST /api/favoris/ajouter
Authorization: Bearer VOTRE_JWT_TOKEN
Content-Type: application/json

{
  "id_lieu": 7
}
```

### ✅ Exemple de Réponse - Succès (201 Created)
```json
{
  "status": "success",
  "message": "L'ajout a été effectué."
}
```

### ⚠️ Exemple de Réponse - Déjà commenté (409 Conflict)
```json
{
  "status": "error",
  "message": "Vous avez déjà ajouté ce lieu."
}
```

### ❌ Exemple de Réponse - Erreur de Validation (400 Bad Request)
```json
{
  "status": "error",
  "message": "Les données fournies sont invalides.",
  "errors": {
    "note": "L'id du lieu est obligatoire"
  }
}
```

### ⚠️ Codes d’erreur possibles
| Code HTTP | Message   | Explication                         |
|-----------|-----------|-------------------------------------|
| 201       | L'ajout a été effectué | Lieu ajouté avec succès. |
| 400       | Données invalides | Paramètres manquants ou invalides. |
| 401       | Non autotisé. | Token JWT manquant ou invalide. |
| 403 | Accès refusé | Utilisation authentifié, mais rôle insuffisant. |
| 404 | Ce lieu n'existe pas | L'identifiant du lieu n'existe pas en base de données. |
| 405 | La méthode n'est pas autorisée. | Une Autre méthode HTTP que POST a été utilisée. |
| 409 | Vous avez déjà ajouté ce lieu | Ce lieu est déjà dans les favoris de l'utilisateur. |
| 503 | Erreur serveur | Echec de l'insertion en base |

### 💡 Remarques
- Un utilisateur ne peut ajouter un même lieu en favoris qu’une seule fois.
- Le id_lieu doit correspondre à un lieu existant dans la base de données.
- L’ajout est effectif uniquement après validation des données et contrôle d’unicité.
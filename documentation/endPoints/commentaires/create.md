# 📍 Endpoint : Ajouter un commentaire sur un lieu
Permet à un utilisateur authentifié d’ajouter un commentaire et une note à un lieu donné via une requête HTTP `POST`.

## Endpoint: GET `/commentaires/ajouter`

### 🌐 URL
```
POST /kidsspot/commentaires/ajouter
```

### 🔐 Authentification
✅ Requise — **Token JWT dans le Header `Authorization`.**  
Le rôle de l'utilisateur doit être **≥ 1** (autorisation nécessaire).

### 💡 Paramètres du Body (JSON)
| Paramètre      | Type      | Description                                | Obligatoire | Contraintes                       |
|----------------|-----------|--------------------------------------------|-------------|-----------------------------------|
| `id_lieu`      | `int`     | Identifiant du lieu commenté               | ✅ Oui      | Entier strictement positif        |
| `note`         | `int`   | Note attribuée au lieu                     | ✅ Oui      | Comprise entre `0` et `5`         |
| `commentaire`  | `string`  | Texte du commentaire                       | ✅ Oui      | Chaîne non vide, max `1000` chars |

### 💻 Exemple de Requête
```http
POST /api/commentaires/create.php
Authorization: Bearer VOTRE_JWT_TOKEN
Content-Type: application/json

{
  "id_lieu": 7,
  "note": 4.5,
  "commentaire": "Super endroit pour les enfants, sécurisé et personnel très accueillant."
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
  "message": "Vous avez déjà commenté ce lieu."
}
```

### ❌ Exemple de Réponse - Erreur de Validation (400 Bad Request)
```json
{
  "status": "error",
  "message": "Les données fournies sont invalides.",
  "errors": {
    "note": "Une note est obligatoire entre 0 et 5"
  }
}
```

### ⚠️ Codes d’erreur possibles
| Code HTTP | Message   | Explication                         |
|-----------|-----------|-------------------------------------|
| 201       | Commentaire ajouté | Le commentaire a été créé avec succès. |
| 400       | Données invalides | Paramètres manquants ou invalides. |
| 401       | Non autotisé. | Token JWT manquant ou invalide. |
| 403 | Accès refusé | Utilisation authentifié, mais rôle insuffisant. |
| 405 | La méthode n'est pas autorisée. | Une Autre méthode HTTP que POST a été utilisée. |
| 409 | Commentaire déjà existant | L'utilisateur a déjà commenté ce lieu |
| 503 | Erreur serveur | Echec de l'insertion en base |

### 💡 Remarques
- Un utilisateur ne peut commenter un même lieu qu’une seule fois.
- La note doit être comprise entre 0 et 5, demi-points autorisés.
- Le commentaire est stocké immédiatement après validation et contrôle d’unicité.
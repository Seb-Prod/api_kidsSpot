# 📍 Endpoint : Supprimer un commentaire sur un lieu
Permet à un utilisateur authentifié de supprimer **son propre** commentaire d’un lieu via une requête HTTP `DELETE`.

## Endpoint: GET `/commentaires/supprimer`

### 🌐 URL
```
DELETE /kidsspot/commentaires/supprimer
```

### 🔐 Authentification
✅ Requise — **Token JWT dans le Header `Authorization`.**  
Le rôle de l'utilisateur doit être **≥ 4** (autorisation nécessaire).

### 💡 Paramètres du Body (JSON)
| Paramètre | Type | Description | Obligatoire | Contraintes |
|-----------|------|-------------|-------------|-------------|
| `id` | `int` | Identifiant du commentaire à supprimer | ✅ Oui | Entier strictement positif |

### 💻 Exemple de Requête
```http
DELETE /api/commentaires/supprimer
Authorization: Bearer VOTRE_JWT_TOKEN
Content-Type: application/json

{
  "id": 12
}
```

### ✅ Exemple de Réponse - Succès (200 OK)
```json
{
  "status": "success",
  "message": "La suppression a été effectuée."
}
```

### ⚠️ Exemple de Réponse - Lieu inexistant (404 Not Found)
```json
{
  "status": "error",
  "message": "Ce commentaire n'existe pas."
}
```

### ❌ Exemple de Réponse - Erreur de Validation (400 Bad Request)
```json
{
  "status": "error",
  "message": "Les données fournies sont invalides.",
  "errors": {
    "id": "L'identifiant doit être un entier strictement positif."
  }
}
```

### ⚠️ Codes d’erreur possibles
| Code HTTP | Message   | Explication                         |
|-----------|-----------|-------------------------------------|
| 200 | Commentaire supprimé | Suppression réussie. |
| 400 | Mauvaise Requête | Données invalides (par ex. id incorrect). |
| 401 | Non autotisé. | Token JWT manquant ou invalide. |
| 403 | Accès refusé | Utilisation authentifié, mais rôle insuffisant. |
| 404 | Introuvable | Le commentaire n'existe pas en base de données. |
| 405 | La méthode n'est pas autorisée. | Une Autre méthode HTTP que DELETE a été utilisée. |
| 503 | Erreur serveur | Echec de l'insertion en base |

### 💡 Remarques
- L’utilisateur peut uniquement supprimer son propre commentaire, sauf s’il possède un rôle supérieur (modérateur ou admin).
- Validation rigoureuse de l’ID pour éviter les suppressions accidentelles ou malveillantes.
- Si la ressource n’existe pas ou si elle appartient à un autre utilisateur, la suppression sera refusée.
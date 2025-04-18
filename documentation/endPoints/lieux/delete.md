# 📍 Endpoint : Supprimer un lieu
Permet de supprimer un lieu dans la base de données en envoyant son `id` via une requête HTTP `DELETE`.

## Endpoint: GET `/lieux/supprime`

### 🌐 URL
```
DELETE /kidsspot/lieux/supprime
```

### 🔐 Authentification
✅ Requise — **Token JWT dans le Header `Authorization`.**  
Le rôle de l'utilisateur doit être **≥ 4** (autorisation nécessaire).

### 💡 Paramètres du Body (JSON)
| Paramètre | Type | Description | Obligatoire | Contraintes |
|-----------|------|-------------|-------------|-------------|
| `ide` | `int` | Identifiant du lieu à supprimer | ✅ Oui | Entier strictement positif |

### 💻 Exemple de Requête
```http
DELETE /api/lieux/supprime
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
  "message": "Ce lieux n'existe pas."
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
| 200 | Suppression réussie | Le lieu a été supprimé avec succès. |
| 400 | Mauvaise Requête | Données invalides (par ex. id incorrect). |
| 401 | Non autotisé. | Token JWT manquant ou invalide. |
| 403 | Accès refusé | Utilisation authentifié, mais rôle insuffisant. |
| 404 | Introuvable | Le lieu n'existe pas en base de données. |
| 405 | La méthode n'est pas autorisée. | Une Autre méthode HTTP que DELETE a été utilisée. |
| 503 | Erreur serveur | Echec de l'insertion en base |

### 💡 Remarques
- La suppression est définitive, les relations associées sont supprimées (ou gérées en base via contraintes ON DELETE).
- L’opération est sécurisée par une validation d’existence avant suppression.
- Seuls les utilisateurs autorisés (rôle ≥ 4) peuvent exécuter cette action.
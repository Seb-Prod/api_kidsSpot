# 📍 Endpoint : Ajouter ou modifier les préférences d’un utilisateur
Permet d’ajouter ou de modifier les préférences d’un utilisateur authentifié.

## Endpoint: GET `/profil/editer`

### 🌐 URL
```
POST /kidsspot/profil/editer
```

### 🔐 Authentification
✅ Requise — **Token JWT dans le Header `Authorization`.**  
Le rôle de l'utilisateur doit être **≥ 1** (autorisation nécessaire).

### 💡 Paramètres du Body (JSON)
| Paramètre      | Type      | Description                                | Obligatoire | Contraintes                       |
|----------------|-----------|--------------------------------------------|-------------|-----------------------------------|
| `tranches_age`      | `array`     | Liste des tranches d'âge préférées de l'utilisateur.               | ✅ Oui      | Entier uniques compris entre 1 et 3        |
| `equipements`      | `array`     | Liste des équipements préférés de l'utilisateur.               | ✅ Oui      | Entier uniques compris entre 1 et 5        |
### 💻 Exemple de Requête
```http
POST /api/profil/editer
Authorization: Bearer VOTRE_JWT_TOKEN
Content-Type: application/json

{
  "tranches_age": [1, 2],
  "equipements": [1, 3, 5]·
}
```

### ✅ Exemple de Réponse - Succès (201 Created)
```json
{
  "status": "success",
  "message": "L'ajout a été effectué."
}
```

### ⚠️ Exemple de Réponse - Données invalides (400 Bad Request)
```json
{
    "status": "error",
    "message": "Les données fournies sont invalides.",
    "errors": {
        "tranches_age": "Les tranches d'âge doivent être des identifiants uniques entre 1 et 3",
        "equipements": "Les équipements doivent être des identifiants uniques entre 1 et 5"
    }
}
```

### ❌ Exemple de Réponse - Méthode HTTP incorrecte (405 Not Allowed)
```json
{
  "status": "error",
  "message": "La méthode n'est pas autorisée."
}
```

### ⚠️ Codes d’erreur possibles
| Code HTTP | Message   | Explication                         |
|-----------|-----------|-------------------------------------|
| 200       | OK | Les préférences ont été ajoutées ou modifiées avec succès. |
| 400       | Données invalides | Paramètres manquants ou invalides. |
| 405 | La méthode n'est pas autorisée. | Une Autre méthode HTTP que POST a été utilisée. |
| 503 | Erreur serveur | Echec de l'insertion en base |

### 💡 Remarques
- L’utilisateur doit être authentifié pour effectuer cette action.
- Les tranches d’âge doivent être des entiers uniques compris entre 1 et 3, et les équipements doivent être des entiers uniques compris entre 1 et 5.
- La méthode HTTP utilisée doit être POST pour envoyer les données.
- Si la validation des données échoue, une réponse détaillant les erreurs est renvoyée.
- Si l’ajout ou la modification des préférences est réussi, un message de succès est renvoyé.
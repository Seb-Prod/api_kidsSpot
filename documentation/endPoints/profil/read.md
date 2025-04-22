# 📍 Endpoint : Lire les préférences d’un utilisateur
Permet de récupérer les préférences d’un utilisateur authentifié via son ID.

## Endpoint: GET `/profil`

### 🌐 URL
```
GET /kidsspot/profil
```

### 🔐 Authentification
✅ Requise — **Token JWT dans le Header `Authorization`.**  
Le rôle de l'utilisateur doit être **≥ 1** (autorisation nécessaire).

### 🧾 Paramètres URL
Aucun paramètre URL n’est requis. L’utilisateur est identifié par son token d’authentification.

### 💡 Exemple de requête
```http
GET /kidsspot/profil
```
### ✅ Exemple de réponse (succès)


```json
{
    "status": "success",
    "data": {
        "id": 5,
        "pseudo": "Seb-Prod2",
        "mail": "seb.prod@gmail.com",
        "telephone": "",
        "grade": 4,
        "dates": {
            "creation": "2025-04-13 16:50:42",
            "derniere_connexion": "2025-04-22 13:20:50"
        },
        "preferences": {
            "tranches_age": [
                {
                    "id": 2,
                    "nom": "3 - 6 ans"
                },
                {
                    "id": 3,
                    "nom": "6 ans et +"
                }
            ],
            "equipements": [
                {
                    "id": 1,
                    "nom": "Accès poussette"
                },
                {
                    "id": 3,
                    "nom": "Micro-ondes"
                }
            ]
        }
    }
}   
```

### ⚠️ Exemple de Réponse - Aucune préférence trouvée (404 Not Found)
```json
{
    "status": "error",
    "message": "Aucune préférence trouvée pour cet utilisateur."
}
```

### ❌ Exemple de Réponse - Méthode HTTP incorrecte (405 Method Not Allowed)
```json
{
    "status": "error",
    "message": "La méthode n'est pas autorisée."
}
```

### ⚠️ Codes d’erreur possibles
| Code HTTP | Message   | Explication                         |
|-----------|-----------|-------------------------------------|
| 200       | OK       | L'utilisateur et ses préférences ont été renvoyés avec succès. |
| 404 | Aucune préférence trouvé pour cet utilisateur | Aucune préférence n'a été trouvée pour l'utilisateur avec cet ID. |
| 405 | La méthode n'est pas autorisée. | Une Autre méthode HTTP que GET a été utilisée. |

### 💡 Remarques
- L’utilisateur doit être authentifié pour accéder à ses préférences.
- Ce endpoint ne permet que les requêtes de type GET.
- La réponse est standardisée avec status, data ou message.
- La méthode de vérification d’authentification et de permissions est implémentée via des middlewares.
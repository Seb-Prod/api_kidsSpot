# 📍 Endpoint : Récupérer la liste des lieux favoris d'un utilisateur
Permet à un utilisateur authentifié de récupérer la liste de ses lieux ajoutés en favoris, sous forme de tableau JSON.

## Endpoint: GET `/favoris/lire`

### 🌐 URL
```
GET /kidsspot/favoris/lire/{latitude}/{longitude}
```

### 🔐 Authentification
✅ Requise — **Token JWT dans le Header `Authorization`.**  
Le rôle de l'utilisateur doit être **≥ 1** (autorisation nécessaire).

### 🧾 Paramètres URL
| Paramètre | Type   | Description                          | Obligatoire | Contraintes                  |
|-----------|--------|--------------------------------------|-------------|------------------------------|
| latidute        | float    | Latitude actuelle de l'ustilisateur | ✅ Oui      | Doit être une latitude valide |
| longitude       | float    | Longitude actuelle de l'ustilisateur | ✅ Oui      | Doit être une laongitude valide |

### 💡 Exemple de requête
```http
GET /kidsspot/favoris/lire/48.85/2.35
```
### ✅ Exemple de réponse (succès)


```json
{
    "status": "success",
    "data": [
        {
            "id": 4,
            "nom": "Le P'tit Café Familial",
            "horaires": "09:00-18:00",
            "description": "Café cosy avec coin enfants et ateliers créatifs.",
            "adresse": {
                "adresse": "12 Rue de Belleville",
                "code_postal": "75020",
                "ville": "Paris"
            },
            "type": [
                {
                    "id": 1,
                    "nom": "Restaurant"
                }
            ],
            "est_evenement": false,
            "date_evenement": {
                "debut": null,
                "fin": null
            },
            "position": {
                "latitude": 48.87,
                "longitude": 2.38,
                "distance_km": 5.4
            },
            "equipements": [],
            "ages": []
        }
    ]
}    
```

### ⚠️ Exemple de Réponse - Aucun favori trouvé (404 Not Found)
```json
{
    "status": "error",
    "message": "Aucun lieu trouvé."
}
```

### ❌ Exemple de Réponse - Coordonéées invalides (400 Bad Request)
```json
{
    "status": "error",
    "message": "Les coordonnées fournies sont invalides.",
    "errors":{
        "latitude": "La latitude est obligatoire et doit être valide.",
        "longitude": "La longitude est obligatoire et doit être valide."
    }
}
```

### ⚠️ Codes d’erreur possibles
| Code HTTP | Message   | Explication                         |
|-----------|-----------|-------------------------------------|
| 200       | OSuccès       | Lieu des lieux renvofyée avec succès. |
| 400       | Données invalides. | Coordonnées absentes ou malformatées. |
| 401       | Non autorisé | Token JWT manquant ou invalide. |
| 403 | Accès refusé | Utilisateur authentifié, mais rôle insuffisant. |
| 404 | Aucun lieu touvé | Aucun favoris n'a été trouvé pour cet utilisateur. |
| 405 | La méthode n'est pas autorisée. | Une Autre méthode HTTP que GET a été utilisée. |

### 💡 Remarques
- L’utilisateur doit impérativement fournir ses coordonnées latitude et longitude pour que les distances soient calculées et retournées.
- Si aucun lieu favori n’est trouvé, une réponse 404 est retournée.
- La liste est triée ou enrichie en fonction de la distance grâce à FormatHelper::lieuLight.
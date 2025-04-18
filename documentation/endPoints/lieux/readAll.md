# 📍 Endpoint : Récupérer les lieux autour d'une position
Permet de récupérer une liste de lieux à proximité en fonction de coordonnées GPS spécifiées.

## Endpoint: GET `/lieux/autour/{latitude}/{longitude}`

### 🌐 URL
```
POST /kidsspot/lieux/autour/{latitude}/{longitude}
```

### 🔐 Authentification
Non requise.

### 🧾 Paramètres URL
| Paramètre | Type   | Description                          | Obligatoire | Contraintes                  |
|-----------|--------|--------------------------------------|-------------|------------------------------|
| latitude      | float    | Latitude du point de départ  | ✅ Oui      | Doit être compris entre `-90` et `90` |
| longitude       | float    | Longitude du point de départ  | ✅ Oui      | Doit être compris entre `-180` et `180` |
### 💡 Exemple de requête
```http
GET /kidsspot/lieux/autour/48.85/2.34
```
### ✅ Exemple de réponse (succès)


```json
{
    "status": "success",
    "data": [
        {
            "id": 3,
            "nom": "Aire de Jeux Parc Monceau",
            "horaires": "07:30-22:00",
            "description": "Aire de jeux sécurisée avec toboggans.",
            "adresse": {
                "adresse": "35 Boulevard de Courcelles",
                "code_postal": "75008",
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
                "longitude": 2.30,
                "distance_km": 2.43
            },
            "equipements": [
                {
                    "id": 1,
                    "nom": "Accès poussette"
                }
            ],
            "ages": [
                {
                    "id":1,
                    "nom": "0 - 3 ans"
                }
            ]
        },
        .....
    ]
}
```

### ⚠️ Codes d’erreur possibles
| Code HTTP | Message   | Explication                         |
|-----------|-----------|-------------------------------------|
| 200       | OK        | Lieu trouvés et retournés. |
| 400       | Mauvaise Requête | Coordonnées ou manquantes. |
| 404 | Aucun lieu trouvé | Aucun lieu à proximité de ces coordonnées. |
| 405 | La méthode n'est pas autorisée. | Une Autre méthode HTTP que GET a été utilisée. |

### 🧠 Validation des données
- `latitude` doit être un nombre compris entre -90 et 90.
- `longitude` doit être un nombre compris entre -180 et 180.
- En cas d’erreur de type, d’absence ou de valeur hors-borne, le serveur renvoie une réponse JSON d’erreur avec le code 400.

### 💼 Règle métier
- Seuls les lieux dans un rayon défini par la méthode getPlacesAround sont retournés.
- La distance (si présente) est calculée en km depuis la position fournie.
- Les résultats sont limités et formatés via FormatHelper::lieuLight().

### ✅ Note développeur
Cette route est sécurisée contre les injections SQL grâce à l’utilisation de requêtes préparées PDO côté modèle (Lieux.php).
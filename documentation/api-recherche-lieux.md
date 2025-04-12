# Documentation de l'API de recherche de lieux

## Endpoint: GET `/autour/{lat}/{lng}`

Cet endpoint permet de récupérer une liste de lieux situés à proximité de coordonnées géographiques spécifiées.

### URL

```
GET /kidsspot/lieux/autour/{lat}/{lng}
```

### Paramètres de requête

| Paramètre | Type    | Description                                 | Obligatoire | Contraintes                 |
|-----------|---------|---------------------------------------------|-------------|----------------------------|
| `lat`     | Float   | Latitude des coordonnées à rechercher       | Oui         | Entre -90 et 90            |
| `lng`     | Float   | Longitude des coordonnées à rechercher      | Oui         | Entre -180 et 180          |

### Exemples de requête

```
GET /kidsspot/lieux/autour/48.8566/2.3522
```

### Réponses

#### Succès (200 OK)

```json
{
  "lieux": [
    {
      "id": 1,
      "nom": "Parc des Enfants",
      "adresse": {
        "adresse": "123 rue des Jeux",
        "code_postal": "75001",
        "ville": "Paris"
      },
      "type": "loisir",
      "est_evenement": false,
      "position": {
        "latitude": 48.85661,
        "longitude": 2.35222,
        "distance_km": 0.25
      },
      "equipements": ["Aire de jeux", "Accès poussette"]
    },
    // ... autres lieux
  ]
}
```

#### Erreur - Paramètres manquants (400 Bad Request)

```json
{
  "message": "Les paramètres lat et lng sont requis."
}
```

#### Erreur - Valeurs non numériques (400 Bad Request)

```json
{
  "message": "Les coordonnées doivent être des nombres"
}
```

#### Erreur - Coordonnées hors limites (400 Bad Request)

```json
{
  "message": "Coordonnées hors limites. Latitude: -90 à 90, Longitude: -180 à 180."
}
```

#### Erreur - Aucun lieu trouvé (404 Not Found)

```json
{
  "message": "Aucun lieu trouvé."
}
```

#### Erreur - Méthode non autorisée (405 Method Not Allowed)

```json
{
  "message": "La méthode n'est pas autorisée"
}
```

### Structure des données renvoyées

| Champ                   | Type      | Description                                  |
|-------------------------|-----------|----------------------------------------------|
| `id`                    | Integer   | Identifiant unique du lieu                   |
| `nom`                   | String    | Nom du lieu                                  |
| `adresse.adresse`       | String    | Adresse postale du lieu                      |
| `adresse.code_postal`   | String    | Code postal                                  |
| `adresse.ville`         | String    | Ville                                        |
| `type`                  | String    | Type de lieu (Restaurant, Loisir, etc.)             |
| `est_evenement`         | Boolean   | Indique si le lieu est un événement temporaire |
| `position.latitude`     | Float     | Latitude (arrondie à 5 décimales)            |
| `position.longitude`    | Float     | Longitude (arrondie à 5 décimales)           |
| `position.distance_km`  | Float     | Distance en kilomètres (arrondie à 5 décimales) |
| `equipements`           | Array     | Liste des équipements disponibles            |

### Notes techniques

- Les coordonnées sont arrondies à 6 décimales lors du traitement de la requête
- Les résultats sont renvoyés au format JSON avec encodage UTF-8
- L'API prend en charge les requêtes CORS (Cross-Origin Resource Sharing)
- Les caractères spéciaux dans les noms et adresses sont correctement encodés

# Documentation de l'API affichage des détails d'un lieu

## Endpoint: GET `/autour/{lat}/{lng}`

Cet endpoint permet de récupérer les détails sur un lieu avec son id.

### URL

```
GET /kidsspot/lieux/{id}
```

### Paramètres de requête

| Paramètre | Type    | Description                                 | Obligatoire |
|-----------|---------|---------------------------------------------|-------------|
| `id`     | Integer   | Identifiant du lieu à lire       | Oui         |

### Exemples de requête

```
GET /kidsspot/lieux/1
```

### Réponses

#### Succès (200 OK)

```json
{
    "lieu": {
        "id": 1,
        "nom": "La Cabane des Petits Gourmets",
        "description": "Restaurant familial avec espace de jeux intérieur et menu adapté aux enfants.",
        "type_lieu": "Restaurant",
        "est_evenement": false,
        "date_evenement": {
            "debut": null,
            "fin": null
        },
        "adresse": {
            "adresse": "15 rue des Lilas",
            "ville": "Paris",
            "code_postal": "75011",
            "telephone": "0145789632",
            "site_web": "https://cabanepetitsgourmets.fr"
        },
        "position": {
            "latitude": 48.858609999999998763087205588817596435546875,
            "longitude": 2.359170000000000211315409615053795278072357177734375
        },
        "equipements": [
            "Accès poussette",
            "Aire de jeux",
            "Chaise haute",
            "Table à langer"
        ]
    }
}
```

#### Erreur - Paramètres manquants (400 Bad Request)

```json
{
  "message": "L'ID du lieu est manquant dans l'URL."
}
```

#### Erreur - Valeur non numériques ou négative (400 Bad Request)

```json
{
  "message": "L'ID fourni n'est pas valide."
}
```

#### Erreur - Aucun lieu trouvé (404 Not Found)

```json
{
  "message": "Le lieu n'existe pas."
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
| `description`           | String    | Description du lieu          |
| `adresse.adresse`       | String    | Adresse postale du lieu                      |
| `adresse.code_postal`   | String    | Code postal                                  |
| `adresse.ville`         | String    | Ville                                        |
| `adresse.code_telephone`| String    | Numéro de téléphone         |
| `adresse.site_web`      | String    | Adresse du site web         |
| `type`                  | String    | Type de lieu (Restaurant, Loisir, etc.)             |
| `est_evenement`         | Boolean   | Indique si le lieu est un événement temporaire |
| `date_evenement.debut` | Date | Date de début de l'évenement |
| `date_evenement.fin`   | Date | Date de fin de l'évenement |
| `position.latitude`     | Float     | Latitude (arrondie à 5 décimales)            |
| `position.longitude`    | Float     | Longitude (arrondie à 5 décimales)           |
| `position.distance_km`  | Float     | Distance en kilomètres (arrondie à 5 décimales) |
| `equipements`           | Array     | Liste des équipements disponibles            |

### Notes techniques

- Les résultats sont renvoyés au format JSON avec encodage UTF-8
- L'API prend en charge les requêtes CORS (Cross-Origin Resource Sharing)
- Les caractères spéciaux dans les noms et adresses sont correctement encodés

# Documentation de l'API de lecture d'un lieu

## Endpoint: GET `/{id}`

Cet endpoint permet de récupérer les informations détaillées d'un lieu spécifique à partir de son identifiant.

### URL

```
GET /kidsspot/lieux/{id}
```

Où `{id}` est l'identifiant numérique du lieu à consulter.

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
      "latitude": 48.85861,
      "longitude": 2.35917
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

#### Erreur - Valeur non numérique ou négative (400 Bad Request)

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

| Champ                     | Type      | Description                                  |
|---------------------------|-----------|----------------------------------------------|
| `id`                      | Integer   | Identifiant unique du lieu                   |
| `nom`                     | String    | Nom du lieu                                  |
| `description`             | String    | Description du lieu                          |
| `adresse.adresse`         | String    | Adresse postale du lieu                      |
| `adresse.code_postal`     | String    | Code postal                                  |
| `adresse.ville`           | String    | Ville                                        |
| `adresse.telephone`       | String    | Numéro de téléphone                          |
| `adresse.site_web`        | String    | Adresse du site web                          |
| `type_lieu`               | String    | Type de lieu (Restaurant, Loisir, etc.)      |
| `est_evenement`           | Boolean   | Indique si le lieu est un événement temporaire |
| `date_evenement.debut`    | Date      | Date de début de l'événement (ou null)        |
| `date_evenement.fin`      | Date      | Date de fin de l'événement (ou null)          |
| `position.latitude`       | Float     | Latitude (arrondie à 5 décimales)             |
| `position.longitude`      | Float     | Longitude (arrondie à 5 décimales)            |
| `equipements`             | Array     | Liste des équipements disponibles             |

### Validation des données

L'API effectue une validation de l'identifiant reçu selon les règles suivantes :
- `id` : Valeur numérique entière positive

### Notes techniques

- L'identifiant du lieu est intégré directement dans l'URL (format RESTful)
- Les coordonnées géographiques sont arrondies à 5 décimales
- Les équipements sont fournis sous forme de tableau, séparés par des virgules dans la base de données
- Les caractères spéciaux dans les champs textuels sont correctement décodés
- Les résultats sont renvoyés au format JSON avec encodage UTF-8
- L'API prend en charge les requêtes CORS (Cross-Origin Resource Sharing)
- Seules les requêtes GET sont acceptées sur cet endpoint
- En cas d'erreur 404, une page d'erreur personnalisée est affichée

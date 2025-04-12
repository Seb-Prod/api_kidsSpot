# Documentation de l'API de création de lieux

## Endpoint: POST `/lieux/ajout`

Cet endpoint permet de créer un nouveau lieu dans la base de données.

### URL

```
POST /kidsspot/lieux/ajout
```

### Corps de la requête

La requête doit contenir un objet JSON avec les informations du lieu à créer.

| Champ           | Type    | Description                           | Obligatoire | Contraintes                             |
|-----------------|---------|---------------------------------------|-------------|----------------------------------------|
| `nom`           | String  | Nom du lieu                           | Oui         | Non vide, max 150 caractères           |
| `description`   | String  | Description du lieu                   | Oui         | Non vide                               |
| `adresse`       | String  | Adresse postale du lieu               | Oui         | Non vide, max 100 caractères           |
| `ville`         | String  | Ville où se situe le lieu             | Oui         | Non vide, max 50 caractères            |
| `code_postal`   | String  | Code postal du lieu                   | Oui         | Format numérique à 5 chiffres          |
| `latitude`      | Float   | Latitude des coordonnées du lieu      | Oui         | Entre -90 et 90                        |
| `longitude`     | Float   | Longitude des coordonnées du lieu     | Oui         | Entre -180 et 180                      |
| `telephone`     | String  | Numéro de téléphone du lieu           | Oui         | Format numérique à 10 chiffres         |
| `site_web`      | String  | URL du site web du lieu               | Oui         | URL valide, max 255 caractères         |
| `id_type`       | Integer | Identifiant du type de lieu           | Oui         | Valeur entre 1 et 3                    |

### Exemple de requête

```json
{
  "nom": "Parc des Enfants",
  "description": "Un parc avec de nombreuses activités pour les enfants",
  "adresse": "123 rue des Jeux",
  "ville": "Paris",
  "code_postal": "75001",
  "latitude": 48.85661,
  "longitude": 2.35222,
  "telephone": "0123456789",
  "site_web": "https://www.parcdesenfants.fr",
  "id_type": 1
}
```

### Réponses

#### Succès (201 Created)

```json
{
  "message": "L'ajout a été effectué"
}
```

#### Erreur - Données invalides (400 Bad Request)

```json
{
  "message": "Les données fournies sont invalides.",
  "erreurs": ["nom", "code_postal"]
}
```

#### Erreur - Échec de création (503 Service Unavailable)

```json
{
  "message": "L'ajout n'a pas été effectué"
}
```

#### Erreur - Méthode non autorisée (405 Method Not Allowed)

```json
{
  "message": "La méthode n'est pas autorisée"
}
```

### Validation des données

L'API effectue une validation des données reçues selon les règles suivantes :
- `nom` : Non vide, chaîne de caractères, maximum 150 caractères
- `description` : Non vide, chaîne de caractères
- `adresse` : Non vide, chaîne de caractères, maximum 100 caractères
- `ville` : Non vide, chaîne de caractères, maximum 50 caractères
- `code_postal` : Format numérique à 5 chiffres
- `latitude` : Valeur numérique entre -90 et 90
- `longitude` : Valeur numérique entre -180 et 180
- `telephone` : Format numérique à 10 chiffres
- `site_web` : URL valide, maximum 255 caractères
- `id_type` : Valeur numérique entre 1 et 3

### Notes techniques

- Les dates de création et de modification sont automatiquement définies à la date actuelle
- Les résultats sont renvoyés au format JSON avec encodage UTF-8
- L'API prend en charge les requêtes CORS (Cross-Origin Resource Sharing)
- Seules les requêtes POST sont acceptées sur cet endpoint

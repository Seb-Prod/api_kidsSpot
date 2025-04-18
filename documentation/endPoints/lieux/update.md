# 📍 Endpoint : Modifier un lieu existant
Permet de modifier les informations d'un lieu déjà existant dans la base de données en envoyant ses informations via une requête HTTP `PUT`.

## Endpoint: PUT `/lieux/modifier`

### 🌐 URL
```
PUT /kidsspot/lieux/modifier
```

### 🔐 Authentification
✅ Requise — **Token JWT dans le Header `Authorization`.**  
Le rôle de l'utilisateur doit être **≥ 4** (autorisation nécessaire).

### 💡 Paramètres du Body (JSON)
| Paramètre         | Type             | Description                                                | Obligatoire | Contraintes                                        |
|-------------------|------------------|------------------------------------------------------------|-------------|---------------------------------------------------|
| `nom`             | `string`         | Nom du lieu                                                | ✅ Oui      | Maximum 150 caractères                            |
| `description`     | `string`         | Description détaillée du lieu                              | ✅ Oui      | Maximum 1000 caractères                           |
| `horaires`        | `string`         | Horaires d'ouverture                                       | ✅ Oui      | Maximum 50 caractères                             |
| `adresse`         | `string`         | Adresse postale                                            | ✅ Oui      | Maximum 100 caractères                            |
| `ville`           | `string`         | Ville où se situe le lieu                                  | ✅ Oui      | Maximum 50 caractères                             |
| `code_postal`     | `string`         | Code postal                                                | ✅ Oui      | Format français `XXXXX`                           |
| `latitude`        | `float`          | Latitude GPS                                               | ✅ Oui      | Doit être compris entre `-90` et `90`             |
| `longitude`       | `float`          | Longitude GPS                                              | ✅ Oui      | Doit être compris entre `-180` et `180`           |
| `telephone`       | `string`         | Numéro de téléphone                                        | ✅ Oui      | Format français / international valide            |
| `site_web`        | `string`         | URL vers le site officiel                                  | ✅ Oui      | URL valide                                        |
| `id_type`         | `int`            | Type de lieu (référence d'une table `types`)               | ✅ Oui      | Entier strictement positif                        |
| `tranches_age`    | `array<int>`      | Liste des ID de tranches d’âge associées                   | ✅ Oui      | Tableaux d'entiers, valeurs 1 à 3                 |
| `equipements`     | `array<int>`      | Liste des ID d'équipements associés                        | ✅ Oui      | Tableaux d'entiers, valeurs 1 à 5                 |
| `date_debut`      | `string`          | (optionnel) Date de début si événement                     | ❌ Optionnel| Format `dd/mm/yyyy`                               |
| `date_fin`        | `string`          | (optionnel) Date de fin si événement                       | ❌ Optionnel| Format `dd/mm/yyyy`                               |

⚠️ **Règle métier :**  
- Si `date_debut` est spécifiée, `date_fin` devient **obligatoire** (et inversement).  
- `tranches_age` et `equipements` doivent être des tableaux d'identifiants valides issus des tables référentielles.

### 💻 Exemple de Requête
```http
POST /api/lieux/modifier
Authorization: Bearer VOTRE_JWT_TOKEN
Content-Type: application/json

{
  "id": 53,
  "nom": "Le Parc Enchanté",
  "description": "Un parc sécurisé et agréable pour les enfants de tout âge.",
  "horaires": "10h00 - 19h00",
  "adresse": "12 Rue des Lilas",
  "ville": "Paris",
  "code_postal": "75020",
  "latitude": 48.8698,
  "longitude": 2.4009,
  "telephone": "0140000000",
  "site_web": "https://parc-enchante.fr",
  "id_type": 2,
  "tranches_age": [1, 2],
  "equipements": [1, 4],
  "date_debut": "15/04/2025",
  "date_fin": "30/05/2025
}
```

### ✅ Exemple de Réponse - Succès (200 OK)
```json
{
  "status": "success",
  "message": "La modification a été effectuée."
}
```

### ❌ Exemple de Réponse - Erreur de Validation (400 Bad Request)
```json
{
  "status": "error",
  "message": "Les données fournies sont invalides.",
  "errors": {
    "code_postal": "Le code postal n'est pas valide.",
    "tranches_age": "Les valeurs doivent être des identifiants valides (1 à 3)."
  }
}
```

### ⚠️ Codes d’erreur possibles
| Code HTTP | Message   | Explication                         |
|-----------|-----------|-------------------------------------|
| 200       | Modification réussie        | Le lieu a été mis à jour avec succès. |
| 400       | Mauvaise Requête | Données invalide ou manquantes. |
| 401       | Non autorisé | Token JWT manquant ou invalide. |
| 403 | Accès refusé | Rôle insuffisant pour effectuer la modification. |
| 405 | La méthode n'est pas autorisée. | Une Autre méthode HTTP que PUT a été utilisée. |
| 503 | Erreur serveur | Echec de la modification en base de données |

### 🧠 Validation des données
- Type et Format stricts : toutes les entrées sont validées via des règles.
- Vérification de cohérence des dates : date_debut et date_fin doivent être soit toutes deux définies, soit toutes deux absentes.
- Latitude/Longitude doivent être géographiquement valides.
- Contrôles spécifiques sur la structure des tableaux tranches_age et equipements.

### 💼 Règle métier
- Authentification obligatoire avec rôle ≥ 4 (ex : administrateur ou modérateur).
- L’utilisateur connecté est automatiquement enregistré comme créateur du lieu.
- En cas d’événement : les dates doivent être cohérentes.
- Les équipements et tranches d’âge sont associés via des tables pivot.

# API KidsSpot - Index des Endpoints

Ce document sert de référence centrale pour tous les endpoints disponibles dans l'API KidsSpot. Chaque endpoint est lié à sa documentation détaillée.

## Endpoints disponibles

### Lieux

| Méthode | Endpoint                             | Description                                                 | Documentation                                       |
| ------- | ------------------------------------ | ----------------------------------------------------------- | --------------------------------------------------- |
| GET     | `/kidsspot/lieux/autour/{lat}/{lng}` | Récupérer les lieux à proximité d'une position géographique | [Documentation détaillée](./api-lieu-autour.md) |
| GET     | `/kidsspot/lieux/{id}`               | Récupérer les détails d'un lieu spécifique                  | [Documentation détaillée](./api-lieu-details.md)    |
| DELETE  | `/kidsspot/lieux/delete`             | Supprime un lieu                                            | [Documentation détaillée](./api-lieu-supprime.md)   |
| POST    | `/kidsspot/lieux/ajout`              | Ajoute un lieu                                              | [Documentation détaillée](./api-lieu-ajout.md)      |

### Autres endpoints

_Note: Ajoutez ici les autres catégories d'endpoints de votre API (utilisateurs, authentification, événements, etc.) au fur et à mesure de leur développement._

## Utilisation générale

Tous les endpoints de l'API renvoient des données au format JSON et prennent en charge les requêtes CORS (Cross-Origin Resource Sharing).

### Headers communs

```
Content-Type: application/json; charset=UTF-8
```

### Codes de statut HTTP

- `200 OK` : La requête a réussi
- `201 Created` : 
- `400 Bad Request` : Requête mal formée ou paramètres invalides
- `404 Not Found` : La ressource demandée n'existe pas
- `405 Method Not Allowed` : La méthode HTTP utilisée n'est pas autorisée pour cet endpoint
- `503 Service Unavailable`: 
-

## Modèles de données

Pour une description détaillée des modèles de données manipulés par l'API, consultez la [documentation des modèles](./api-modeles-donnees.md).

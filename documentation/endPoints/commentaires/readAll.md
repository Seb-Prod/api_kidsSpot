# 📍 Endpoint : Lire tous les commentaires d'un lieu + moyenne des notes
Ce endpoint retourne la liste complète des commentaires pour un lieu spécifique, ainsi que la moyenne des notes.

## Endpoint: GET `/commentaires/lieu/{id}`

### 🌐 URL
```
POST /kidsspot/commentaires/lieu/{id}
```

### 🔐 Authentification
Non requise.

### 🧾 Paramètres URL
| Paramètre | Type   | Description                          | Obligatoire | Contraintes                  |
|-----------|--------|--------------------------------------|-------------|------------------------------|
| `id`      | `int`    | Identifiant du lieu  | ✅ Oui      | Entier strictement positif (> 0) |
### 💡 Exemple de requête
```http
GET /kidsspot/commentaire/lieu/2
```
### ✅ Exemple de réponse (succès)


```json
{
    "status": "success",
    "data": {
        "moyenne_notes": "4.0000",
        "commentaires": [
            {
                "id": 6,
                "commentaire": "Musée intéressant mais un peu cher pour une famille nombreuse.",
                "note": 3,
                "date": {
                    "ajout": "2025-04-14",
                    "modification": "2025-04-14"
                },
                "user": {
                    "id": 6,
                    "pseudo": "User1"
                },
                "lieu": {
                    "id": 2,
                    "nom": "Bibliothèque Louise bis"
                }
            },
            {
                "id": 11,
                "commentaire": "Expositions originales, mes enfants ont adoré.",
                "note": 5,
                "date": {
                    "ajout": "2025-04-14",
                    "modification": "2025-04-14"
                },
                "user": {
                    "id": 5,
                    "pseudo": "Seb-Prod2"
                },
                "lieu": {
                    "id": 2,
                    "nom": "Bibliothèque Louise bis"
                }
            }
        ]
    }
}
```

### ⚠️ Exemple de Réponse - Aucun commentaire (404 Not Found)
```json
{
    "status": "error",
    "message": "Aucun commentaire sur ce lieu."
}
```


### ⚠️ Codes d’erreur possibles
| Code HTTP | Message   | Explication                         |
|-----------|-----------|-------------------------------------|
| 200       | OK        | Commentaires trouvés et retournés. |
| 400       | Mauvaise Requête | L'ID fourni n'est pas valide. |
| 404 | Aucun commentaire trouvé | Aucun commentaire sur ce lieu. |
| 405 | La méthode n'est pas autorisée. | Une Autre méthode HTTP que GET a été utilisée. |

### 💡 Remarques
- Le paramètre id est requis et doit être un entier positif.
- Le tableau commentaires sera vide si aucun commentaire n’est trouvé, et une erreur 404 sera renvoyée.
- La moyenne_notes est toujours retournée même si aucun commentaire n’est disponible.
# 📍 Endpoint : Lire un commentaire par ID
Permet de récupérer les détails d'un commentaire et sa note via son `ID`.

## Endpoint: GET `/commentaires/`

### 🌐 URL
```
GET /kidsspot/commentaires/{id}
```

### 🔐 Authentification
Non requise.

### 🧾 Paramètres URL
| Paramètre | Type   | Description                          | Obligatoire | Contraintes                  |
|-----------|--------|--------------------------------------|-------------|------------------------------|
| id        | int    | Identifiant unique du commentaire à lire | ✅ Oui      | Entier strictement positif (> 0) |

### 💡 Exemple de requête
```http
GET /kidsspot/commentaires/4
```
### ✅ Exemple de réponse (succès)


```json
{
    "status": "success",
    "data": {
        "id": 5,
        "commentaire": "Super endroit pour les enfants, très sécurisé.",
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
            "id": 1,
            "nom": "Jardin des Plantes"
        }
    }
}     
```

### ⚠️ Exemple de Réponse - Commentaire inexistant (404 Not Found)
```json
{
    "status": "error",
    "message": "Le commentaire n'existe pas."
}
```

### ❌ Exemple de Réponse - ID manquant (400 Bad Request)
```json
{
    "status": "error",
    "message": "L'ID du commentaire est manquant dans l'URL."
}
```

### ⛔ Exemple de Réponse - ID invalide (400 Bad Request)
```json
{
    "status": "error",
    "message": "L'ID fourni n'est pas valide."
}
```

### ⚠️ Codes d’erreur possibles
| Code HTTP | Message   | Explication                         |
|-----------|-----------|-------------------------------------|
| 200       | OK        | Lieu trouvé et renvoyé correctement. |
| 400       | L'ID du lieu est manquant. | Le paramètre id est absent dans l'URL. |
| 400       | L'ID fournis n'est pas valide. | id est vide, nom numérique ou inférieur/égal à 0. |
| 404 | Aucun commentaire trouvé pur cet ID. | Commentaire inexistant avec cet identifiant dans la base. |
| 405 | La méthode n'est pas autorisée. | Une Autre méthode HTTP que GET a été utilisée. |

### 💡 Remarques
- L’identifiant id est requis et doit être un entier positif.
- Ce endpoint est accessible sans authentification (lecture publique) — sauf si tu souhaites le restreindre.
- Réponse standardisée avec success, data ou message.
# 📌 Documentation de l’API — Lecture des commentaires et moyenne d’un lieu

## Endpoint: POST `/commentaires/lire/lieu`

Cet endpoint permet de récupérer tous les commentaires associés à un lieu ainsi que la moyenne des notes attribuées.

### 🧭 URL

```
POST /kidsspot/commentaires/lire/lieu/{id}
```

### 🔐 Authentification requise

Aucune authentification requise.
Cet endpoint est public.

### 💡 Paramètres URL

La requête doit contenir un objet JSON avec les informations suivantes :

| Paramètre           | Type    | Description                           | Obligatoire | Contrainte |
|-----------------|---------|---------------------------------------|-------------|-----|
| `id`       | Integer | Identifiant du lieu pour rÃ©cupÃ©rer les commentaires.
 | Oui | Doit être un entier > 0 |

### 💡 Exemple de requête

```
GET /kidsspot/commentaires/lire/lieu/4
```

### 💾 Réponse en cas de succès — 200 OK

```json
{
  "commentaire": [
    {
      "id": 15,
      "commentaire": "Lieu très agréable pour les enfants.",
      "note": 4,
      "date": {
        "ajout": "2025-04-01 10:12:34",
        "modification": "2025-04-02 15:45:20"
      },
      "user": {
        "id": 7,
        "pseudo": "ParentCool"
      },
      "lieu": {
        "id": 4,
        "nom": "Espace Kids Paris"
      }
    },
    {
      "id": 16,
      "commentaire": "Très bon accueil et infrastructures top.",
      "note": 5,
      "date": {
        "ajout": "2025-04-05 09:21:11",
        "modification": "2025-04-05 10:00:00"
      },
      "user": {
        "id": 12,
        "pseudo": "Julie92"
      },
      "lieu": {
        "id": 4,
        "nom": "Espace Kids Paris"
      }
    }
  ],
  "moyenne_notes": 4.5
}
```

### ❌ Réponses d’erreur possibles

#### ❌ Erreur — 400 Bad Request (Paramètre manquant ou invalide)
- Si l’id est manquant dans l’URL :
```json
{
  "message": "L'ID du lieu est manquant dans l'URL.",
}
```
- Si l’id n’est pas valide (non numérique ou inférieur ou égal à 0) :
```json
{
  "message": "L'ID fourni n'est pas valide.",
}
```

#### ❌ Erreur — 404 Not Found (Aucun commentaire pour ce lieu)

```json
{
  "message": "Le commentaire n'existe pas."
}
```



#### ❌ Erreur — 405 Method Not Allowed (Mauvaise méthode HTTP)

```json
{
  "message": "La méthode n'est pas autorisée"
}
```

#### 🧪 Validation des données

- id : Doit être un entier strictement supérieur à 0.

#### 📜 Règles métier
- Le paramètre id est obligatoire.
- L’API retourne une liste de commentaires liés au lieu ainsi que la moyenne des notes.
- Si aucun commentaire n’est trouvé pour ce lieu, un message explicite est renvoyé.
- Les dates sont retournées au format YYYY-MM-DD HH:MM:SS.
- Tous les retours sont au format JSON encodé UTF-8.
- L’API supporte CORS.
- Seules les requêtes GET sont autorisées.

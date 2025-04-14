# 📌 Documentation de l’API — Lecture d’un commentaire et d’une note

## Endpoint: POST `/commentaires/lire`

Cet endpoint permet de récupérer un commentaire et sa note en fonction de son identifiant unique.

### 🧭 URL

```
POST /kidsspot/commentaires/lire/{id}
```

### 🔐 Authentification requise

Aucune authentification requise.
Cet endpoint est public.

### 💡 Paramètres URL

La requête doit contenir un objet JSON avec les informations suivantes :

| Paramètre           | Type    | Description                           | Obligatoire | Contrainte |
|-----------------|---------|---------------------------------------|-------------|-----|
| `id`       | Integer | Identifiant unique du commentaire à  consulter. | Oui | Doit être un entier > 0 |

### 💡 Exemple de requête

```
GET /kidsspot/commentaires/lire/4
```

### 💾 Réponse en cas de succès — 200 OK

```json
{
  "commentaire": {
    "id": 4,
    "commentaire": "Très bon accueil, espace super adapté aux enfants !",
    "note": 5,
    "date": {
      "ajout": "2025-04-13 14:25:32",
      "modification": "2025-04-13 14:35:02"
    },
    "user": {
      "id": 12,
      "pseudo": "MamanCool"
    },
    "lieu": {
      "id": 3,
      "nom": "Ludothèque Paris Centre"
    }
  }
}
```

### ❌ Réponses d’erreur possibles

#### ❌ Erreur — 400 Bad Request (Paramètre manquant ou invalide)
- Si l’id est manquant dans l’URL :
```json
{
  "message": "L'ID du commentaire est manquant dans l'URL.",
}
```
- Si l’id n’est pas valide (non numérique ou inférieur ou égal à 0) :
```json
{
  "message": "L'ID fourni n'est pas valide.",
}
```

#### ❌ Erreur — 404 Conflict (Commentaire introuvable)

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
- Le paramètre id est obligatoire pour cette requête.
- L’API retourne toujours un objet JSON structuré.
- Les dates sont retournées au format YYYY-MM-DD HH:MM:SS.
- L’API supporte CORS.
- Seules les requêtes GET sont autorisées.

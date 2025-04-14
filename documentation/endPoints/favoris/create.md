# 📌 Documentation de l’API — Ajout d'un lieu en favoris

## Endpoint: POST `/favoris/ajouter`

Cet endpoint permet à un utilisateur authentifié d’ajouter un lieu dans ses favoris.

### 🧭 URL

```
POST /kidsspot/favoris/ajouter
```

### 🔐 Authentification requise

Cet endpoint nécessite une authentification via Bearer Token.

L’utilisateur doit être connecté et transmettre le token dans l’en-tête HTTP suivant :

```
Authorization: Bearer VOTRE_TOKEN_ICI
```
Exemple :
```
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
```
👉 Si le token est manquant ou invalide, l’API renverra une réponse :
```json
{
  "message": "Accès non autorisé. Veuillez vous connecter."
}
```
👉 Si le grade de l'user n'est pas suffisant, l’API renverra une réponse :
```json
{
  "message": "Vous n'avez pas les droits suffisants pour effectuer cette action."
}
```

### 💾 Corps de la requête

La requête doit contenir un objet JSON avec les informations suivantes :

| Champ           | Type    | Description                           | Obligatoire | Contrainte |
|-----------------|---------|---------------------------------------|-------------|-----|
| `id_lieu`       | Integer | Identifiant du lieu concerné| Oui | Doit être un entier > 0 |

### 💡 Exemple de requête

```json
{
  {
    "id_lieu" : 1
}
}
```

### 💡 Réponses possibles

#### ✅ Succès - 201 Created

```json
{
  "message": "L'ajout a été effectué"
}
```

#### ⚠️ Erreur — 400 Bad Request (Données invalides)

```json
{
  "message": "Les données fournies sont invalides.",
  "erreurs": ["id_lieu"]
}
```

#### ⚠️ Erreur — 401 Unauthorized (utilisateur non authentifié)

```json
{
  "message": "Accès non autorisé. Veuillez vous connecter."
}
```

#### ⚠️ Erreur — 403 Forbidden (Droits insuffisants)

```json
{
  "message": "Vous n'avez pas les droits suffisants pour effectuer cette action."
}
```

#### ⚠️ Erreur — 404 Not Found (Lieu inexistant)

```json
{
  "message": "Ce lieu n'existe pas."
}
```

#### ⚠️ Erreur — 409 Conflict (Doublon)

```json
{
  "message": "Vous avez déjà commenté ce lieu."
}
```

#### ⚠️ Erreur — 503 Service Unavailable (Échec technique)

```json
{
  "message": "L'ajout n'a pas été effectué"
}
```

#### ❌ Erreur — 405 Method Not Allowed (Mauvaise méthode HTTP)

```json
{
  "message": "La méthode n'est pas autorisée"
}
```

#### 🧪 Validation des données
- id_lieu : Doit être un entier strictement positif.
- commentaire : Doit être une chaîne non vide.
- note : Doit être un nombre entre 0 et 5.

#### 📜 Règles métier
- L’utilisateur doit être connecté pour utiliser cet endpoint.
- Un utilisateur ne peut commenter et noter qu’une seule fois un même lieu.
- Les dates sont gérées automatiquement par la base via NOW().
- Tous les retours sont au format JSON encodé UTF-8.
- L’API supporte CORS.
- Seules les requêtes POST sont autorisées.

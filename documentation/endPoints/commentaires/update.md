# 📌 Documentation de l’API — Modification un Commentaire et une Note

## Endpoint: POST `/commentaires/modifier`

Cet endpoint permet à un utilisateur connecté de modifier un commentaire et une note liés à un lieu. Seul l'auteur du commentaire peut le modifier.

### 🧭 URL

```
POST /kidsspot/commentaires/modifier
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
| `id`            | Integer | Identifiant du commentaire à modifier | Oui     | Doit être un entier > 0 |
| `commentaire`   | String  | Nouveau contenu du commentaire  | Oui         | Non vide |
| `note`          | Integer | Nouvelle note           | Oui  | Valeur entre 0 et 5 |

### 💡 Exemple de requête

```json
{
  {
    "id_lieu" : 4,
    "commentaire" : "Lieu agréable, service sympathique.",
    "note" : 4
}
}
```

### 💡 Réponses possibles

#### ✅ Succès - 200 OK (OK)

```json
{
  "message": "La modification a été effectuée"
}
```

#### ⚠️ Erreur — 400 Bad Request (Données invalides)

```json
{
  "message": "Les données fournies sont invalides.",
  "erreurs": ["commentaire", "note"]
}
```

#### ⚠️ Erreur — 403 Forbidden (droit insufisant)

```json
{
  "message": "Vous n'avez pas les droits pour effectuer cette action."
}
```

#### ⚠️ Erreur — 404 Not Found (Commentaire introuvable)

```json
{
  "Ce commentaire n'existe pas."
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

	•	id_lieu : Doit être un entier strictement positif.
	•	commentaire : Doit être une chaîne non vide.
	•	note : Doit être un nombre entre 0 et 5.

#### 📜 Règles métier

	•	L’utilisateur doit être connecté pour utiliser cet endpoint.
	•	Un utilisateur ne peut commenter et noter qu’une seule fois un même lieu.
	•	Les dates sont gérées automatiquement par la base via NOW().
	•	Tous les retours sont au format JSON encodé UTF-8.
	•	L’API supporte CORS.
	•	Seules les requêtes POST sont autorisées.

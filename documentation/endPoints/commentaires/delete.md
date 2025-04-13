# 📌 Documentation de l’API — Suppression de commentaire et de note

## Endpoint: DELETE `/commentaires/supprimer`

Cet endpoint permet à un utilisateur authentifié de supprimer un commentaire qu’il a rédigé sur un lieu.
Seul l’auteur du commentaire ou un administrateur peut effectuer cette action.

### 🧭 URL

```
DELETE /kidsspot/commentaires/supprimer
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

La requête doit contenir un objet JSON avec l’ID du commentaire à supprimer.

| Champ           | Type    | Description                           | Obligatoire | Contrainte |
|-----------------|---------|---------------------------------------|-------------|-----|
| `id     `       | Integer | Identifiant du lieu à supprimer | Oui | Doit être un entier > 0 |

### 💡 Exemple de requête

```json
{
  {
    "id" : 5
}
}
```

### 💡 Réponses possibles

#### ✅ Succès - 200 (OK)

```json
{
  "message": "La suppression a été effectuée"
}
```

#### ⚠️ Erreur — 400 Bad Request (Données invalides)

```json
{
  "message": "Les données fournies sont invalides.",
  "erreurs": ["id"]
}
```

#### ⚠️ Erreur — 404 Not Found (Commentaire introuvable)

```json
{
  "Ce commentaire n'existe pas."
}
```

#### ⚠️ Erreur — 401 Unauthorized (Non autorisé)

```json
{
  "message": "Accès non autorisé. Veuillez vous connecter."
}
```

#### ❌ Erreur — 503 Service Unavailable (Échec de suppression)

```json
{
  "message": "La suppression n'a pas été effectuée"
}
```

#### ❌ Erreur — 405 Method Not Allowed (Méthode non autorisée)

```json
{
  "message": "La méthode n'est pas autorisée"
}
```

#### 

#### 🧪 Validation des données

	•	id : Doit être un entier strictement positif.

#### 🔒 Authentification & Règles de sécurité

	•	L’utilisateur doit être connecté via un token d’authentification.
	•	L’identifiant de l’utilisateur est automatiquement récupéré à partir du token.
	•	Un utilisateur ne peut commenter et noter qu’une seule fois un même lieu.
	•	Les utilisateurs non connectés ne peuvent pas accéder à cet endpoint.

#### 📜 Règles métier

	•	Seuls les utilisateurs connectés peuvent supprimer un commentaire.
	•	L’utilisateur doit être l’auteur du commentaire ou avoir un grade 4 (Administrateur).
	•	Les requêtes non authentifiées reçoivent un code HTTP 401 Unauthorized.
	•	Les résultats sont renvoyés au format JSON avec encodage UTF-8.
	•	L’API prend en charge les requêtes CORS.
	•	Seules les requêtes DELETE sont acceptées sur cet endpoint.

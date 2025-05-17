# 📌 Authentifier un utilisateur

Ce endpoint permet à un utilisateur de se connecter à l'application en fournissant son e-mail et son mot de passe. Si les identifiants sont valides, un token JWT est retourné.

---

### 🔗 URL

`/api/user/login.php`

---

### 📥 Méthode

`POST`

---

### 🧾 Headers requis

```http
Content-Type: application/json
Authorization: Bearer <votre_token> (si nécessaire)
```

---

### 🧸 Corps de la requête (JSON)

```json
{
  "mail": "utilisateur@example.com",
  "mot_de_passe": "MotDePasse123"
}
```

---

### ✅ Réponse (200 - Connexion réussie)

```json
{
  "message": "Connexion réussie",
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
  "grade": "parent",
  "pseudo": "SuperParent",
  "expiresIn": 3600
}
```

---

### ⚠️ Réponses d'erreurs

- `400 Bad Request` : Données incomplètes
```json
{
  "message": "Données incomplètes"
}
```

- `401 Unauthorized` : Email ou mot de passe incorrect, ou compte verrouillé
```json
{
  "message": "Email ou mot de passe incorrect"
}
```

```json
{
  "message": "Compte verrouillé. Veuillez utiliser la fonction de réinitialisation de mot de passe."
}
```

- `405 Method Not Allowed` : Méthode HTTP non autorisée
```json
{
  "message": "La méthode n'est pas autorisée"
}
```

---

### 🛡️ Remarques

- Après 5 tentatives de connexion échouées, le compte est verrouillé.
- En cas de compte verrouillé, l’utilisateur doit demander une réinitialisation de mot de passe.
- Le token JWT retourné est valable pendant 1 heure (3600 secondes).


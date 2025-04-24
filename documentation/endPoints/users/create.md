# 📍 Endpoint : Authentification Utilisateur
Permet à un utilisateur de se connecter en envoyant ses identifiants (email + mot de passe). En cas de succès, retourne un **token JWT** à utiliser dans les requêtes authentifiées.

## Endpoint: POST `/users/login.php`

### 🌐 URL
```
POST /users/login.php
```

### 🔐 Authentification
❌ **Non requise** pour accéder à cet endpoint (c’est lui qui génère le token).

### 💡 Paramètres du Body (JSON)
| Paramètre         | Type             | Description                                                | Obligatoire | Contraintes                                        |
|-------------------|------------------|------------------------------------------------------------|-------------|---------------------------------------------------|
| `mail`             | `string`         | Adresse email de l'utilisateur                                               | ✅ Oui      | Format d'email valide                           |
| `mot_de_passe`     | `string`         | Mot de passe                              | ✅ Oui      | Doit correspondes à l'utilisateur                           |

### 💻 Exemple de Requête
```http
POST /api/auth/login.php
Content-Type: application/json

{
  "mail": "utilisateur@example.com",
  "mot_de_passe": "MonMotDePasse123"
}
```

### ✅ Exemple de Réponse - Succès (200 OK)
```json
{
  "message": "Connexion réussie",
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
}
```

### ❌ Exemple de Réponse - Compte Verrouillé (401 Unauthorized)
```json
{
  "message": "Compte verrouillé. Veuillez utiliser la fonction de réinitialisation de mot de passe."
}
```

### ❌ Exemple de Réponse - Mauvais Identifiants (401 Unauthorized)
```json
{
  "message": "Email ou mot de passe incorrect"
}
```

### ❌ Exemple de Réponse - Données Incomplètes (400 Bad Request)
```json
{
  "message": "Données incomplètes"
}
```

### ⚠️ Codes d’erreur possibles
| Code HTTP | Message   | Explication                         |
|-----------|-----------|-------------------------------------|
| 200       | Connexion réussie        | Identifiants valide, token JWT retourné. |
| 400       | Données incomplètes | Mail ou mor_de_passe manquant. |
| 401       | Email ou mot de passe incorrect | Les identifiants sont érronés. |
| 401 | Compte verrouillé | Trop de tentative échouées, utilisateur bloqué. |
| 405 | La méthode n'est pas autorisée. | Une Autre méthode HTTP que POST a été utilisée. |

### 🧠 Logique Métier
- Si l’utilisateur existe et que le mot de passe est correct :
    - Réinitialisation des tentatives de connexion.
    - Sauvegarde de la date de dernière connexion.
    - Génération d’un token JWT contenant id, email et grade.
    - Si le mot de passe est incorrect :
    - Le compteur de tentatives est incrémenté.
    - Après 5 tentatives échouées, le compte est verrouillé (compte_verrouille = true).
    - Si le compte est verrouillé :
    - Aucun accès, message dédié envoyé.
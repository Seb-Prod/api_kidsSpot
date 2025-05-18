# 🔐 Réinitialisation du mot de passe

**Fichier** : `reset_password.php`  
**Méthode HTTP** : `POST`  
**Accès** : Public (utilisé lors d'une demande de réinitialisation de mot de passe via token)

---

## 📌 Description

Cet endpoint permet à un utilisateur de réinitialiser son mot de passe en fournissant :
- son adresse email,
- un nouveau mot de passe,
- un token de réinitialisation valide reçu par email.

---

## 📤 Données attendues (JSON dans le corps de la requête)

| Champ                    | Type     | Obligatoire | Validation                                                                 |
|--------------------------|----------|-------------|----------------------------------------------------------------------------|
| `mail`                   | string   | ✅           | Email valide                                                               |
| `mot_de_passe`           | string   | ✅           | Min. 8 caractères, 1 majuscule, 1 minuscule, 1 chiffre                     |
| `token_reinitialisation` | string   | ✅           | Chaîne non vide (envoyée par mail)                                         |

---

## ✅ Réponse — Succès : `201 Created`

```json
{
  "status": "success",
  "message": "Le mot de passe a bien été changé"
}
```

---

## ❌ Réponses — Erreurs possibles

### `400 Bad Request` — Données invalides

```json
{
  "status": "error",
  "message": "Les données fournies sont invalides.",
  "errors": {
    "mail": "Un email valide est obligatoire",
    "mot_de_passe": "Le mot de passe doit comporter au moins 8 caractères, une majuscule, une minuscule et un chiffre",
    "token_reinitialisation": "Code de réinitialisation reçu par email"
  }
}
```

### `400 Bad Request` — Token invalide ou expiré

```json
{
  "status": "error",
  "message": "Code de réinitialisation invalide ou périmé"
}
```

### `503 Service Unavailable` — Erreur côté serveur

```json
{
  "status": "error",
  "message": "Le mot de passe n'a pas été changé."
}
```

### `405 Method Not Allowed` — Mauvaise méthode HTTP

```json
{
  "status": "error",
  "message": "La méthode n'est pas autorisée."
}
```

---

## 🧪 Exemple de requête

```http
POST /api/reset_password.php
Content-Type: application/json

{
  "mail": "exemple@domaine.com",
  "mot_de_passe": "Motdepasse1",
  "token_reinitialisation": "abc123token"
}
```
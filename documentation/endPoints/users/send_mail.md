# 📧 Envoi groupé d'emails aux utilisateurs

**Fichier** : `send_group_email.php`  
**Méthode HTTP** : `POST`  
**Accès** : Réservé aux administrateurs (grade 4)

---

## 📌 Description

Cet endpoint permet à un administrateur d’envoyer un email personnalisé à tous les utilisateurs ayant activé l’option `opt_in_email`.

Le contenu peut inclure la variable `{PSEUDO}` qui sera automatiquement remplacée par le pseudo du destinataire.

---

## 📤 Données attendues (JSON dans le corps de la requête)

| Champ           | Type     | Obligatoire | Validation                                  |
|----------------|----------|-------------|---------------------------------------------|
| `sujet`        | string   | ✅           | Chaîne requise, max 50 caractères            |
| `contenueHTML` | string   | ✅           | Chaîne requise, max 500 caractères           |

---

## ✅ Réponse — Succès : `200 OK`

```json
{
  "status": "success",
  "message": "Emails envoyés avec succès à 25 utilisateurs. 0 échecs.",
  "total": 25,
  "success": 25,
  "failed": 0
}
```

Si des échecs ont lieu, un tableau `failed_emails` est retourné :

```json
{
  "status": "success",
  "message": "Emails envoyés avec succès à 23 utilisateurs. 2 échecs.",
  "total": 25,
  "success": 23,
  "failed": 2,
  "failed_emails": ["ex1@example.com", "ex2@example.com"]
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
    "sujet": "Le sujet du mail est obligatoire et ne doit pas dépasser 50 caractères",
    "contenueHTML": "Le contenu du mail est obligatoire et ne doit pas dépasser 500 caractères"
  }
}
```

### `405 Method Not Allowed` — Mauvaise méthode HTTP

```json
{
  "status": "error",
  "message": "La méthode n'est pas autorisée"
}
```

### `401 Unauthorized` ou `403 Forbidden` — Non authentifié ou autorisation insuffisante

> Géré par les middlewares `auth_middleware.php` et `UserAutorisation.php`

---

## 🧪 Exemple de requête

```http
POST /api/send_group_email.php
Content-Type: application/json
Authorization: Bearer <jeton_d_acces>

{
  "sujet": "Nouveautés KidsSpot !",
  "contenueHTML": "<p>Bonjour {PSEUDO},<br>Découvrez nos nouveaux lieux ajoutés ce mois-ci !</p>"
}
```
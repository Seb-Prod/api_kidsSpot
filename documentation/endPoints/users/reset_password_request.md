# Demande de réinitialisation de mot de passe

**URL** : `/users/forgot-password.php`

**Méthode HTTP** : `POST`

**Description** :  
Ce point de terminaison permet à un utilisateur de demander la réinitialisation de son mot de passe en recevant un code de vérification par e-mail.

---

## Requête

### Headers

- `Content-Type: application/json`

### Corps (`body`)

Les données doivent être envoyées au format JSON :

```json
{
  "mail": "exemple@email.com"
}
```

---

## Réponses

### ✅ 201 E-mail envoyé

```json
{
  "success": true,
  "message": "Un e-mail de réinitialisation a été envoyé."
}
```

### ⚠️ 400 Données manquantes

```json
{
  "success": false,
  "message": "L'e-mail est requis."
}
```

### ⚠️ 404 Utilisateur non trouvé

```json
{
  "success": false,
  "message": "Aucun utilisateur trouvé avec cet e-mail."
}
```

### ❌ 500 Erreur interne

```json
{
  "success": false,
  "message": "Impossible de générer le token."
}
```

ou

```json
{
  "success": false,
  "message": "Échec de l'envoi de l'e-mail."
}
```

### ❌ 405 Méthode non autorisée

```json
{
  "success": false,
  "message": "La méthode n'est pas autorisée."
}
```

---

## Notes

- Le token de réinitialisation est valide pendant 20 minutes.
- L'utilisateur doit utiliser le code reçu par e-mail pour finaliser la réinitialisation du mot de passe.
- L'adresse e-mail doit exister en base pour que le processus soit lancé.
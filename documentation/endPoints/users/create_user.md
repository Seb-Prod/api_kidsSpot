# Créer un utilisateur

**URL** : `/users/create.php`

**Méthode HTTP** : `POST`

**Description** :  
Ce point de terminaison permet de créer un nouvel utilisateur dans la base de données. Il vérifie que les données envoyées sont valides, que l'email et le pseudo sont uniques, puis crée l'utilisateur.

---

## Requête

### Headers

- `Content-Type: application/json`
- `Authorization: Bearer {token}` _(si requis dans d'autres contextes)_

### Corps (`body`)

Les données doivent être envoyées au format JSON :

```json
{
  "pseudo": "exemplePseudo",
  "mail": "exemple@email.com",
  "mot_de_passe": "Motdepasse123",
  "telephone": "0601020304"
}
```

### Règles de validation

| Champ          | Règle                                                                 |
|----------------|----------------------------------------------------------------------|
| `pseudo`       | Requis, chaîne de caractères, max 150 caractères                     |
| `mail`         | Requis, format email valide                                           |
| `mot_de_passe` | Requis, au moins 8 caractères, 1 majuscule, 1 minuscule, 1 chiffre    |
| `telephone`    | Requis, format français ou valide reconnu                            |

---

## Réponses

### ✅ 201 Créé

```json
{
  "success": true,
  "message": "L'ajout a été effectué."
}
```

### ⚠️ 400 Données invalides

```json
{
  "success": false,
  "message": "Les données fournies sont invalides.",
  "errors": {
    "mail": "Un email valide est obligatoire",
    "mot_de_passe": "Le mot de passe doit être de 8 caractères, une majuscule, une minuscile et un chiffre"
  }
}
```

### ⚠️ 409 Conflit (pseudo ou mail déjà existant)

```json
{
  "success": false,
  "message": "Ce pseudo existe déjà."
}
```

ou

```json
{
  "success": false,
  "message": "Cet email existe déjà."
}
```

### ❌ 503 Erreur serveur

```json
{
  "success": false,
  "message": "L'ajout n'a pas été effectué."
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

- Ce point de terminaison utilise une validation centralisée via la classe `Validator`.
- Les fonctions de réponse sont fournies par `ResponseHelper.php` pour garantir une structure uniforme des messages d'erreur ou de succès.
- Les collisions de pseudo ou d'email sont gérées avant toute tentative d'insertion en base de données.
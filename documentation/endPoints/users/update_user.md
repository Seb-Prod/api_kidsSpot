---
title: "Mettre à jour le profil utilisateur"
method: PUT
url: /update-user-profile.php
description: |
  Permet à un utilisateur connecté de modifier son profil (pseudo, email, téléphone, préférence email).
  Requiert une authentification (niveau d’autorisation ≥ 1).
headers:
  - "Authorization: Bearer {token}"
  - "Content-Type: application/json"
body:
  application/json:
    exemple:
      pseudo: "nouveauPseudo"
      mail: "nouveau@mail.com"
      telephone: "0612345678"
      opt_in_email: true
    description: |
      Données à mettre à jour. Tous les champs sont optionnels, mais au moins un doit être présent.
      Le champ `opt_in_email` doit être un booléen.
success_response:
  code: 201
  body:
    status: "success"
    message: "La modification a été effectuée."
error_responses:
  - code: 400
    body:
      status: "error"
      message: "Les données fournies sont invalides."
      errors: { pseudo: "Le pseudo est obligatoire..." }
  - code: 405
    body:
      status: "error"
      message: "La méthode n'est pas autorisée."
  - code: 409
    body:
      status: "error"
      message: "Ce pseudo existe déjà." | "Cet email existe déjà."
  - code: 503
    body:
      status: "error"
      message: "La modification n'a pas pu être effectuée."
---

### Exemple de requête

```http
PUT /update-user-profile.php HTTP/1.1
Host: api.kidsspot.fr
Authorization: Bearer {votre_token}
Content-Type: application/json

{
  "pseudo": "SuperParent78",
  "mail": "parent@example.com",
  "telephone": "0611223344",
  "opt_in_email": true
}
```

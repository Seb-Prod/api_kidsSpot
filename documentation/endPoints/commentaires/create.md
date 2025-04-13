# Documentation de l'API d'ajout de commentaires et de note'

## Endpoint: POST `/commentaires/ajout`

Cet endpoint permet d'ajouter un commentaire et une note par un user sur un lieu dans la base de données.

### URL

```
POST /kidsspot/commentaires/ajout
```

### Corps de la requête

La requête doit contenir un objet JSON avec les informations du lieu à créer.

| Champ           | Type    | Description                           | Obligatoire | Contrainte |
|-----------------|---------|---------------------------------------|-------------|-----|
| `id_lieu`       | Integer | Id du lieu                            | Oui         | Non vide |
| `commentaire`   | String  | Commentaire sur le lieu de l'user                 | Oui         | Non vide |
| `note`          | Integer | Note du lieu de l'user | Oui  | Valeur entre 0 et 5 |

### Exemple de requête

```json
{
  {
    "id_lieu" : 1,
    "commentaire" : "Super bien",
    "note" : 1
}
}
```

### Réponses

#### Succès (201 Created)

```json
{
  "message": "L'ajout a été effectué"
}
```

#### Erreur - Données invalides (400 Bad Request)

```json
{
  "message": "Les données fournies sont invalides.",
  "erreurs": ["commentaire", "note"]
}
```

#### Erreur - Doublon (409 Conflict)

```json
{
  "Vous avez déjà commenté ce lieu."
}
```

#### Erreur - Échec de création (503 Service Unavailable)

```json
{
  "message": "L'ajout n'a pas été effectué"
}
```

#### Erreur - Méthode non autorisée (405 Method Not Allowed)

```json
{
  "message": "La méthode n'est pas autorisée"
}
```

### Validation des données

L'API effectue une validation des données reçues selon les règles suivantes :
- `id_lieu` : Non vide, valeur numérique
- `commentaire` : Non vide, chaîne de caractères
- `note` : Valeur numérique entre 0 et 5

### Notes techniques

- Les dates de création et de modification sont automatiquement définies à la date actuelle
- L'id de l'user est récupérer lors de sa connection par un token qui doit etre envoyé
- Seul les users connecté peuvent ajouter et noter un lieu
- Un user ne peu commenter et noter q'une fois un lieu
- Les résultats sont renvoyés au format JSON avec encodage UTF-8
- L'API prend en charge les requêtes CORS (Cross-Origin Resource Sharing)
- Seules les requêtes POST sont acceptées sur cet endpoint
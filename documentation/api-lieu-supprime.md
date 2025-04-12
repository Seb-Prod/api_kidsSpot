# Documentation de l'API de suppression d'un lieu

## Endpoint: DELETE `/supprime`

Cet endpoint permet de supprimer un lieu de la base de données en utilisant son identifiant.

### URL

```
DELETE /kidsspot/lieux/supprime
```

### Corps de la requête

La requête doit contenir un objet JSON avec l'identifiant du lieu à supprimer.

| Champ | Type    | Description                    | Obligatoire |
|-------|---------|--------------------------------|-------------|
| `id`  | Integer | Identifiant du lieu à supprimer | Oui         |

### Exemple de requête

```json
{
  "id": 1
}
```

### Réponses

#### Succès (200 OK)

```json
{
  "message": "Le lieu a été supprimé avec succès."
}
```

#### Erreur - Paramètres manquants (400 Bad Request)

```json
{
  "message": "L'ID du lieu est manquant dans la requête."
}
```

#### Erreur - Valeur non numérique ou négative (400 Bad Request)

```json
{
  "message": "L'ID fourni n'est pas valide."
}
```

#### Erreur - Aucun lieu trouvé (404 Not Found)

```json
{
  "message": "Le lieu n'existe pas."
}
```

#### Erreur - Méthode non autorisée (405 Method Not Allowed)

```json
{
  "message": "La méthode n'est pas autorisée"
}
```

#### Erreur - Problème de serveur (503 Service Unavailable)

```json
{
  "message": "La suppression n'a pas pu être effectuée."
}
```

### Validation des données

L'API effectue une validation de l'identifiant reçu selon les règles suivantes :
- `id` : Valeur numérique entière positive

### Notes techniques

- L'ID du lieu à supprimer doit être fourni dans le corps de la requête au format JSON
- Les résultats sont renvoyés au format JSON avec encodage UTF-8
- L'API prend en charge les requêtes CORS (Cross-Origin Resource Sharing)
- La suppression est définitive et irréversible
- Un code de statut 404 est renvoyé si l'ID spécifié n'existe pas dans la base de données
- Un code de statut 400 est renvoyé si l'ID n'est pas valide ou est manquant
- Seules les requêtes DELETE sont acceptées sur cet endpoint

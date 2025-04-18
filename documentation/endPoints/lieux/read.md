# 📍 Endpoint : Récupérer les détails d'un lieu
Permet d'obtenir les informations détaillées d'un lieu spécifique à partir de son `ID`.

## Endpoint: GET `/lieux/`

### 🌐 URL
```
GET /kidsspot/lieux/{id}
```

### 🔐 Authentification
Non requise.

### 🧾 Paramètres URL
| Paramètre | Type   | Description                          | Obligatoire | Contraintes                  |
|-----------|--------|--------------------------------------|-------------|------------------------------|
| id        | int    | Identifiant unique du lieu à récupérer | ✅ Oui      | Entier strictement positif (> 0) |

### 💡 Exemple de requête
```http
GET /kidsspot/lieux/4
```
### ✅ Exemple de réponse (succès)


```json
{
    "status": "success",
    "data": {
        "id": 1,
        "nom": "Jardin des Plantes",
        "description": "Grand jardin botanique en plein cœur de Paris.",
        "horaires": "08:00-20:00",
        "adresse": {
            "adresse": "57 Rue Cuvier",
            "ville": "Paris",
            "code_postal": "75005",
            "telephone": "01 40 79 56 01",
            "site_web": "https://www.jardindesplantes.fr"
        },
        "type": [
            {
                "id": 2,
                "nom": "Loisir"
            }
        ],
        "est_evenement": true,
        "date_evenement": {
            "debut": "2025-04-20",
            "fin": "2025-04-20"
        },
        "position": {
            "latitude": 48.84,
            "longitude": 2.35
        },
        "equipements": [
            {
                "id": 1,
                "nom": "Accès poussette"
            },
            {
                "id": 2,
                "nom": "Aire de jeux"
            }
        ],
        "ages": [
            {
                "id": 1,
                "nom": "0 - 3 ans"
            },
            {
                "id": 2,
                "nom": "3 - 6 ans"
            }
        ],
        "commentaires": [
            {
                "pseudo": "Seb-Prod2",
                "commentaire": "Super endroit pour les enfants, très sécurisé.",
                "note": 5,
                "date_ajout": "2025-04-14"
            },
            {
                "pseudo": "User5",
                "commentaire": "Le jardin est magnifique au printemps.",
                "note": 4,
                "date_ajout": "2025-04-14"
            }
        ],
        "note_moyenne": 4.5,
        "nombre_commentaires": 2
    }
}
```

### ⚠️ Codes d’erreur possibles
| Code HTTP | Message   | Explication                         |
|-----------|-----------|-------------------------------------|
| 200       | OK        | Lieu trouvé et renvoyé correctement. |
| 400       | L'ID du lieu est manquant. | Le paramètre id est absent dans l'URL. |
| 400       | L'ID fournis n'est pas valide. | id est vide, nom numérique ou inférieur/égal à 0. |
| 404 | Aucun lieu trouvé oour cet ID. | Lieu inexistant avec cet identifiant dans la base. |
| 405 | La méthode n'est pas autorisée. | Une Autre méthode HTTP que GET a été utilisée. |

### 🧠 Validation des données
- `id` doit être présent dans l’URL (isset($_GET['id'])).
- `id` doit être un entier strictement positif.
- Validation via filter_var($_GET['id'], FILTER_VALIDATE_INT).

### 💼 Règle métier
- Seuls les lieux existants sont retournés.
- Si aucun lieu correspondant n’est trouvé en base, un message clair avec un code 404 est retourné.
- Les réponses sont uniformisées par sendSuccessResponse() ou sendErrorResponse() pour un comportement d’API standardisé.

### ✅ Note développeur
Cette route est sécurisée contre les injections SQL grâce à l’utilisation de requêtes préparées PDO côté modèle (Lieux.php).
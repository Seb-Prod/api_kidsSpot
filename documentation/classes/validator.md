# Documentation de la classe Validator

## Présentation générale

La classe `Validator` est une classe PHP statique qui permet de valider des données selon des règles définies. Elle est conçue pour faciliter la validation des entrées utilisateur, des paramètres de requête, ou toute autre donnée nécessitant une vérification avant traitement.

## Installation

La classe peut être intégrée dans n'importe quel projet PHP en incluant le fichier `Validator.php`.

```php
require_once 'path/to/Validator.php';
```

## Utilisation de base

```php
// Données à valider
$data = [
    'nom' => 'Dupont',
    'email' => 'dupont@example.com',
    'age' => 25
];

// Définition des règles de validation
$rules = [
    'nom' => Validator::requiredString(),
    'email' => Validator::email(),
    'age' => Validator::positiveInt()
];

// Validation des données
$errors = Validator::validate($data, $rules);

// Vérification des erreurs
if (empty($errors)) {
    // Les données sont valides
    echo "Toutes les données sont valides !";
} else {
    // Affichage des erreurs
    foreach ($errors as $field => $message) {
        echo "$field : $message<br>";
    }
}
```

## Personnalisation des messages d'erreur

```php
$rules = [
    'nom' => Validator::withMessage(
        Validator::requiredString(),
        "Le nom est obligatoire"
    ),
    'email' => Validator::withMessage(
        Validator::email(),
        "Veuillez fournir une adresse email valide"
    )
];
```

## Méthodes principales

### `validate(array $data, array $rules): array`

Valide un tableau de données en fonction des règles définies.

**Paramètres :**
- `$data` : Tableau associatif des données à valider
- `$rules` : Tableau associatif des règles de validation

**Retour :** Tableau associatif des erreurs de validation (vide si aucune erreur)

### `withMessage(callable $validator, string $message): array`

Permet d'associer un message d'erreur personnalisé à une règle de validation.

**Paramètres :**
- `$validator` : Fonction de validation à appliquer
- `$message` : Message d'erreur personnalisé

**Retour :** Tableau contenant la fonction de validation et le message d'erreur

## Règles de validation disponibles

| Méthode | Description | Exemple |
|---------|-------------|---------|
| `requiredString()` | Vérifie si la valeur est une chaîne non vide | `'nom' => Validator::requiredString()` |
| `requiredStringMax($maxLength)` | Vérifie si la valeur est une chaîne non vide et ne dépasse pas une longueur maximale | `'description' => Validator::requiredStringMax(200)` |
| `email()` | Vérifie si la valeur est une adresse email valide | `'email' => Validator::email()` |
| `password()` | Vérifie si la valeur est un mot de passe valide (min 8 caractères, au moins une majuscule, une minuscule et un chiffre) | `'password' => Validator::password()` |
| `optionalPhone()` | Vérifie si la valeur est vide ou un numéro de téléphone à 10 chiffres | `'telephone' => Validator::optionalPhone()` |
| `positiveInt()` | Vérifie si la valeur est un entier positif | `'age' => Validator::positiveInt()` |
| `range($min, $max)` | Vérifie si la valeur est un nombre compris dans une plage | `'note' => Validator::range(0, 20)` |
| `latitude()` | Vérifie si la valeur est une latitude valide (-90 à 90) | `'lat' => Validator::latitude()` |
| `longitude()` | Vérifie si la valeur est une longitude valide (-180 à 180) | `'lng' => Validator::longitude()` |
| `codePostal()` | Vérifie si la valeur est un code postal français valide | `'cp' => Validator::codePostal()` |
| `telephone()` | Vérifie si la valeur est un numéro de téléphone français valide | `'tel' => Validator::telephone()` |
| `url()` | Vérifie si la valeur est une URL valide ou vide | `'site' => Validator::url()` |
| `nonEmptyArray()` | Vérifie si la valeur est un tableau non vide | `'options' => Validator::nonEmptyArray()` |
| `array()` | Vérifie si la valeur est un tableau | `'data' => Validator::array()` |
| `arrayOfPositiveInts()` | Vérifie si la valeur est un tableau d'entiers positifs | `'ids' => Validator::arrayOfPositiveInts()` |
| `arrayOfUniqueIntsInRange($min, $max)` | Vérifie si la valeur est un tableau d'entiers uniques dans une plage | `'categories' => Validator::arrayOfUniqueIntsInRange(1, 10)` |
| `date($format)` | Vérifie si la valeur est une date valide dans un format spécifié | `'birthday' => Validator::date('Y-m-d')` |

## Exemples d'utilisation

### Validation d'un formulaire d'inscription

```php
$userData = [
    'username' => $_POST['username'] ?? '',
    'email' => $_POST['email'] ?? '',
    'password' => $_POST['password'] ?? '',
    'phone' => $_POST['phone'] ?? '',
    'age' => $_POST['age'] ?? 0,
    'website' => $_POST['website'] ?? ''
];

$rules = [
    'username' => Validator::withMessage(
        Validator::requiredStringMax(50),
        "Le nom d'utilisateur est obligatoire et ne doit pas dépasser 50 caractères"
    ),
    'email' => Validator::withMessage(
        Validator::email(),
        "Veuillez fournir une adresse email valide"
    ),
    'password' => Validator::withMessage(
        Validator::password(),
        "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre"
    ),
    'phone' => Validator::withMessage(
        Validator::optionalPhone(),
        "Le numéro de téléphone doit contenir 10 chiffres"
    ),
    'age' => Validator::withMessage(
        Validator::range(18, 120),
        "L'âge doit être compris entre 18 et 120 ans"
    ),
    'website' => Validator::withMessage(
        Validator::url(),
        "L'URL du site web n'est pas valide"
    )
];

$errors = Validator::validate($userData, $rules);
```

### Validation d'une adresse

```php
$addressData = [
    'street' => $_POST['street'] ?? '',
    'city' => $_POST['city'] ?? '',
    'postal_code' => $_POST['postal_code'] ?? '',
    'lat' => $_POST['lat'] ?? null,
    'lng' => $_POST['lng'] ?? null
];

$rules = [
    'street' => Validator::requiredString(),
    'city' => Validator::requiredString(),
    'postal_code' => Validator::codePostal(),
    'lat' => Validator::latitude(),
    'lng' => Validator::longitude()
];

$errors = Validator::validate($addressData, $rules);
```

### Validation de données pour une API

```php
$apiData = json_decode(file_get_contents('php://input'), true);

$rules = [
    'product_ids' => Validator::withMessage(
        Validator::arrayOfPositiveInts(),
        "La liste des produits doit contenir uniquement des identifiants positifs"
    ),
    'category_id' => Validator::withMessage(
        Validator::positiveInt(),
        "L'identifiant de catégorie doit être un nombre positif"
    ),
    'date_range' => Validator::withMessage(
        Validator::nonEmptyArray(),
        "La plage de dates est obligatoire"
    )
];

$errors = Validator::validate($apiData, $rules);
```

## Création de règles de validation personnalisées

Vous pouvez facilement créer vos propres règles de validation en définissant une fonction anonyme :

```php
// Vérification d'un numéro SIRET
$rules['siret'] = Validator::withMessage(
    function ($val) {
        return !empty($val) && is_string($val) && preg_match('/^[0-9]{14}$/', $val);
    },
    "Le numéro SIRET doit contenir 14 chiffres"
);

// Vérification d'une date de naissance (personne majeure)
$rules['birthdate'] = Validator::withMessage(
    function ($val) {
        if (empty($val) || !is_string($val)) return false;
        
        $date = DateTime::createFromFormat('Y-m-d', $val);
        if (!$date || $date->format('Y-m-d') !== $val) return false;
        
        $now = new DateTime();
        $age = $now->diff($date)->y;
        
        return $age >= 18;
    },
    "Vous devez être majeur pour vous inscrire"
);
```

## Bonnes pratiques

1. Toujours vérifier si le tableau d'erreurs est vide avant de traiter les données
2. Utiliser des messages d'erreur clairs et spécifiques
3. Regrouper les règles de validation dans un fichier séparé pour les réutiliser facilement
4. Adapter les règles de validation en fonction des besoins spécifiques de votre application

<?php
/**
 * @file
 * Endpoint API pour modifier un lieu.
 */

// --- Configuration des Headers HTTP ---
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// --- Middlewares ---
include_once '../middleware/auth_middleware.php';
include_once '../middleware/UserAutorisation.php';
include_once '../middleware/ResponseHelper.php';

// Authentification et autorisation
$donnees_utilisateur = verifierAuthentification();
validateUserAutorisation($donnees_utilisateur, 1);

// Vérification de la méthode HTTP
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // --- Dépendances ---
    include_once '../config/Database.php';
    include_once '../models/Lieux.php';
    include_once '../middleware/Validator.php';
    include_once '../middleware/Helpers.php';

    // Connexion à la base de données
    $database = new Database();
    $db = $database->getConnexion();

    // Instanciation du modèle Lieux
    $lieu = new Lieux($db);

    // Décodage des données JSON
    $donnees = (array) json_decode(file_get_contents("php://input"), true);

    // Règles de validation obligatoires
    $rules = [
        'id' => Validator::withMessage(
            Validator::positiveInt(),
            "L'identifiant doit être un entier positif"
        ),
        'nom' => Validator::withMessage(
            Validator::requiredStringMax(150),
            "Le nom est obligatoire et ne doit pas dépasser 150 caractères"
        ),
        'description' => Validator::withMessage(
            Validator::requiredStringMax(1000),
            "La description est obligatoire et ne doit pas dépasser 1000 caractères"
        ),
        'horaires' => Validator::withMessage(
            Validator::requiredStringMax(50),
            "Les horaires sont obligatoires et ne doivent pas dépasser 50 caractères"
        ),
        'adresse' => Validator::withMessage(
            Validator::requiredStringMax(100),
            "L'adresse est obligatoire et ne doit pas dépasser 100 caractères"
        ),
        'ville' => Validator::withMessage(
            Validator::requiredStringMax(50),
            "La ville est obligatoire et ne doit pas dépasser 50 caractères"
        ),
        'code_postal' => Validator::withMessage(
            Validator::codePostal(),
            "Le code postal doit être au format français (5 chiffres)"
        ),
        'latitude' => Validator::withMessage(
            Validator::latitude(),
            "La latitude doit être comprise entre -90 et 90"
        ),
        'longitude' => Validator::withMessage(
            Validator::longitude(),
            "La longitude doit être comprise entre -180 et 180"
        ),
        'id_type' => Validator::withMessage(
            Validator::positiveInt(),
            "Le type doit être un identifiant valide (entier positif)"
        ),
        'tranches_age' => Validator::withMessage(
            Validator::arrayOfUniqueIntsInRange(1, 3),
            "Les tranches d'âge doivent être des identifiants uniques entre 1 et 3"
        ),
        'equipements' => Validator::withMessage(
            Validator::arrayOfUniqueIntsInRange(1, 6),
            "Les équipements doivent être des identifiants uniques entre 1 et 6"
        ),
    ];

    // Règles facultatives
    $optionalRules = [
        'date_debut' => Validator::withMessage(
            Validator::date('d/m/Y'),
            "La date de début doit être au format jj/mm/aaaa"
        ),
        'date_fin' => Validator::withMessage(
            Validator::date('d/m/Y'),
            "La date de fin doit être au format jj/mm/aaaa"
        ),
        'site_web' => Validator::withMessage(
            Validator::url(),
            "Le site web doit être une URL valide"
        ),
        'telephone' => Validator::withMessage(
            Validator::telephone(),
            "Le numéro de téléphone doit être au format français (10 chiffres)"
        ),
    ];

    // Validation des champs obligatoires
    $errors = Validator::validate($donnees, $rules);
    if (!empty($errors)) {
        sendValidationErrorResponse("Les données fournies sont invalides.", $errors, 400);
    }

    // Validation des champs facultatifs
    foreach (['date_debut', 'date_fin', 'site_web', 'telephone'] as $champ) {
        if (!empty($donnees[$champ])) {
            $errors = Validator::validate([$champ => $donnees[$champ]], [$champ => $optionalRules[$champ]]);
            if (!empty($errors)) {
                sendValidationErrorResponse("Le champ {$champ} est invalide.", $errors, 400);
            }
        }
    }

    // Cohérence entre les dates
    if ((isset($donnees['date_debut']) && !isset($donnees['date_fin'])) ||
        (!isset($donnees['date_debut']) && isset($donnees['date_fin']))) {
        sendValidationErrorResponse(
            "Si une date est fournie, les dates de début et de fin doivent toutes deux être renseignées.",
            ['date_debut', 'date_fin'],
            400
        );
    }

    // Hydratation des champs
    foreach (array_keys($rules) as $champ) {
        if (isset($donnees[$champ])) {
            $lieu->$champ = $donnees[$champ];
        }
    }

    $lieu->id_user = $donnees_utilisateur['id'];
    $lieu->telephone = $donnees['telephone'] ?? null;
    $lieu->site_web = $donnees['site_web'] ?? null;

    $equipements = $donnees['equipements'] ?? [];
    $tranches_age = $donnees['tranches_age'] ?? [];

    $date_debut = isset($donnees['date_debut']) ? convertirDateFrancaisVersUs($donnees['date_debut']) : null;
    $date_fin = isset($donnees['date_fin']) ? convertirDateFrancaisVersUs($donnees['date_fin']) : null;

    // Vérification de l'existence du lieu
    if (!$lieu->exist()) {
        sendErrorResponse("Ce lieu n'existe pas.", 404);
    }

    // Mise à jour en base
    if ($lieu->update($equipements, $tranches_age, $date_debut, $date_fin)) {
        sendUpdatedResponse();
    } else {
        sendErrorResponse("La modification n'a pas été effectuée.", 503);
    }
} else {
    sendErrorResponse("La méthode n'est pas autorisée.", 405);
}
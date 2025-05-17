<?php
/**
 * @file
 * Endpoint de mise à jour du profil utilisateur (PUT).
 *
 * Permet à un utilisateur connecté de modifier ses informations personnelles :
 * pseudo, email, téléphone, et choix de recevoir des emails (opt_in_email).
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

// Authentification de l'utilisateur (niveau minimum requis : 1)
$donnees_utilisateur = verifierAuthentification();
validateUserAutorisation($donnees_utilisateur, 1);

// Vérifie que la requête est bien en méthode PUT
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {

    // Dépendances
    include_once '../config/Database.php';
    include_once '../models/Users.php';
    include_once '../middleware/Validator.php';

    // Connexion à la base de données
    $database = new Database();
    $db = $database->getConnexion();

    // Instanciation du modèle Users
    $users = new Users($db);

    // Récupération du corps JSON
    $donnees = (array) json_decode(file_get_contents("php://input"), true);

    // Règles de validation
    $rules = [
        'pseudo' => Validator::withMessage(
            Validator::requiredStringMax(50),
            "Le pseudo est obligatoire et ne doit pas dépasser 150 caractères"
        ),
        'mail' => Validator::withMessage(
            Validator::email(),
            "Un email valide est obligatoire"
        ),
        'telephone' => Validator::withMessage(
            Validator::telephone(),
            "Le numéro de téléphone doit être valide"
        ),
        'opt_in_email' => Validator::withMessage(
            Validator::boolean(),
            "opt_in_email doit être un booléen (true ou false)"
        ),
    ];

    // Affectation de l'ID utilisateur à l'objet Users
    $users->id = $donnees_utilisateur['id'];

    // Validation des données
    $errors = Validator::validate($donnees, $rules);

    if (!empty($errors)) {
        sendValidationErrorResponse("Les données fournies sont invalides.", $errors, 400);
    }

    // Vérification de l'unicité du pseudo
    if (isset($donnees['pseudo']) && $users->pseudoExists($donnees['pseudo'])) {
        sendErrorResponse("Ce pseudo existe déjà.", 409);
    }

    // Vérification de l'unicité de l'email
    if (isset($donnees['mail']) && $users->emailExists($donnees['mail'])) {
        sendErrorResponse("Cet email existe déjà.", 409);
    }

    // Assignation des valeurs reçues
    foreach (array_keys($rules) as $champ) {
        if (isset($donnees[$champ])) {
            $users->$champ = $donnees[$champ];
        }
    }

    // Exécution de la modification
    if ($users->modifier()) {
        sendCreatedResponse("La modification a été effectuée.");
    } else {
        sendErrorResponse("La modification n'a pas pu être effectuée.", 503);
    }

} else {
    sendErrorResponse("La méthode n'est pas autorisée.", 405);
}
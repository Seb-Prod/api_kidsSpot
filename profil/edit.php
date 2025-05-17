<?php
/**
 * @file
 * Endpoint API pour l'édition des préférences du profil utilisateur.
 *
 * Permet de mettre à jour les préférences d'âge et d'équipements de l'utilisateur connecté.
 */

// --- Configuration des Headers HTTP ---
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// --- Middlewares ---
include_once '../middleware/auth_middleware.php';
include_once '../middleware/UserAutorisation.php';
include_once '../middleware/ResponseHelper.php';

// Vérification de l'authentification
$donnees_utilisateur = verifierAuthentification();
validateUserAutorisation($donnees_utilisateur, 1);

// Vérification de la méthode HTTP
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // --- Dépendances ---
    include_once '../config/Database.php';
    include_once '../models/Profil.php';
    include_once '../middleware/Validator.php';
    include_once '../middleware/Helpers.php';

    // Connexion à la base de données
    $database = new Database();
    $db = $database->getConnexion();

    // Instanciation du modèle Profil
    $profil = new Profil($db);

    // Récupération et décodage des données JSON
    $donnees = (array) json_decode(file_get_contents("php://input"), true);

    // Règles de validation
    $rules = [
        'tranches_age' => Validator::withMessage(
            Validator::arrayOfUniqueIntsInRange(1, 3),
            "Les tranches d'âge doivent être des identifiants uniques entre 1 et 3."
        ),
        'equipements' => Validator::withMessage(
            Validator::arrayOfUniqueIntsInRange(1, 5),
            "Les équipements doivent être des identifiants uniques entre 1 et 5."
        ),
    ];

    // Validation des données
    $errors = Validator::validate($donnees, $rules);

    if (!empty($errors)) {
        sendValidationErrorResponse("Les données fournies sont invalides.", $errors, 400);
    }

    // Affectation de l'utilisateur et des préférences
    $profil->id = $donnees_utilisateur['id'];
    $profil->equipements = isset($donnees['equipements']) ? $donnees['equipements'] : [];
    $profil->ages = isset($donnees['tranches_age']) ? $donnees['tranches_age'] : [];

    // Sauvegarde en base
    if ($profil->save()) {
        sendCreatedResponse("Les préférences ont été mises à jour.");
    } else {
        sendErrorResponse("La mise à jour des préférences a échoué.", 503);
    }

} else {
    sendErrorResponse("La méthode n'est pas autorisée.", 405);
}

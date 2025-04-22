<?php

/**
 * @file
 * API Endpoint pour la éditer du profil utilisateur'.
 */

// Configuration des Headers HTTP
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Inclusion du middleware d'authentification
include_once '../middleware/auth_middleware.php';
include_once '../middleware/UserAutorisation.php';

// Inclusion du middleware des réponses
include_once '../middleware/ResponseHelper.php';

// Vérification de l'authentification
$donnees_utilisateur = verifierAuthentification();
validateUserAutorisation($donnees_utilisateur, 1);

// Vérification de la Méthode HTTP
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Inclusion des Fichiers Nécessaires
    include_once '../config/Database.php';
    include_once '../models/Profil.php';
    include_once '../middleware/Validator.php';
    include_once '../middleware/Helpers.php';

    // Crée une nouvelle instance de la classe Database pour établir une connexion à la base de données.
    $database = new Database();
    $db = $database->getConnexion();

    // Crée une nouvelle instance de la classe Lieux.
    $profil = new Profil($db);

    // Les données envoyées au format JSON dans le corps de la requête sont décodées en un objet PHP.
    $donnees = (array) json_decode(file_get_contents("php://input"), true);

    // Régles de validation des données
    $rules = [
        'tranches_age' => Validator::withMessage(
            Validator::arrayOfUniqueIntsInRange(1, 3),
            "Les tranches d'âge doivent être des identifiants uniques entre 1 et 3"
        ),
        'equipements' => Validator::withMessage(
            Validator::arrayOfUniqueIntsInRange(1, 5),
            "Les équipements doivent être des identifiants uniques entre 1 et 5"
        ),
    ];

    // Vérification des données
    $errors = Validator::validate($donnees, $rules);

    // Si des erreurs
    if (!empty($errors)) {
        sendValidationErrorResponse("Les données fournies sont invalides.", $errors, 400);
    }

    // Assignation de l'id de l'user
    $profil->id = $donnees_utilisateur['id'];

    // Assiggnation des équipement et tranche d'age
    $profil->equipements = isset($donnees['equipements']) ? $donnees['equipements'] : [];
    $profil->ages = isset($donnees['tranches_age']) ? $donnees['tranches_age'] : [];

    if ($profil->save()) {
        sendCreatedResponse("L'ajout a été effectué.");
    } else {
        sendErrorResponse("L'ajout n'a pas été effectué.", 503);
    }
} else {
    sendErrorResponse("La méthode n'est pas autorisée.", 405);
}

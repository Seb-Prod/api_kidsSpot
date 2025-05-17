<?php

/**
 * @file
 * Endpoint API pour supprimer un lieu spécifique en fonction de son ID.
 */

// Headers HTTP
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Vérifie que l'utilisateur est authentifié et autorisé (niveau 4 requis)
include_once '../middleware/auth_middleware.php';
include_once '../middleware/UserAutorisation.php';
include_once '../middleware/ResponseHelper.php';

$donnees_utilisateur = verifierAuthentification();
validateUserAutorisation($donnees_utilisateur, 4);

// Vérifie que la méthode HTTP est DELETE
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Connexion à la base de données et inclusion des fichiers nécessaires
    include_once '../config/Database.php';
    include_once '../models/Lieux.php';
    include_once '../middleware/Validator.php';

    $database = new Database();
    $db = $database->getConnexion();

    $lieux = new Lieux($db);

    // Récupère les données envoyées dans le corps de la requête (JSON)
    $donnees = (array) json_decode(file_get_contents("php://input"), true);

    // Règle de validation : l'ID doit être un entier positif
    $rules = [
        'id' => Validator::withMessage(
            Validator::positiveInt(),
            "L'identifiant doit être un entier positif"
        )
    ];

    // Vérifie les données
    $errors = Validator::validate($donnees, $rules);

    if (!empty($errors)) {
        // Si erreur de validation, retourne une réponse d'erreur
        sendValidationErrorResponse("Les données fournies sont invalides.", $errors, 400);
    }

    // Affecte l'ID reçu à l'objet $lieux
    foreach (array_keys($rules) as $champ) {
        $lieux->$champ = $donnees[$champ];
    }

    // Vérifie si le lieu existe
    if (!$lieux->exist()) {
        sendErrorResponse("Ce lieux n'existe pas.", 404);
    }

    // Supprime le lieu
    if ($lieux->delete()) {
        sendDeletedResponse();
    } else {
        sendErrorResponse("La suppression n'a pas été effectuée.", 503);
    }
} else {
    // Méthode non autorisée
    sendErrorResponse("La méthode n'est pas autorisée.", 405);
}
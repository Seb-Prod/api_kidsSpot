<?php

/**
 * @file
 * Endpoint API pour supprimer un lieu spécifique en fonction de son ID.
 */

// Configuration des Headers HTTP
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Inclusion du middleware d'authentification
include_once '../middleware/auth_middleware.php';
include_once '../middleware/UserAutorisation.php';

// Inclusion du middleware des réponses
include_once '../middleware/ResponseHelper.php';

// Vérification de l'authentification
$donnees_utilisateur = verifierAuthentification();
validateUserAutorisation($donnees_utilisateur, 4);

// Vérification de la Méthode HTTP
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // --- Inclusion des Fichiers Nécessaires ---
    include_once '../config/Database.php';
    include_once '../models/Lieux.php';
    include_once '../middleware/Validator.php';

    // Crée une nouvelle instance de la classe Database pour établir une connexion à la base de données.
    $database = new Database();
    $db = $database->getConnexion();

    // Crée une nouvelle instance de la classe Lieux.
    $lieux = new Lieux($db);

    // Les données envoyées au format JSON dans le corps de la requête sont décodées en un objet PHP.
    $donnees = (array) json_decode(file_get_contents("php://input"), true);

    // Régles de validation des données
    $rules = [
        'id' => Validator::positiveInt()
    ];

    // Vérification des données
    $errors = Validator::validate($donnees, $rules);

    // Si des erreurs
    if (!empty($errors)) {
        sendValidationErrorResponse("Les données fournies sont invalides.", $errors, 400);
    }

    // On assigne les valeurs des données reçues aux propriétés correspondantes de l'objet $lieux.
    foreach (array_keys($rules) as $champ) {
        $lieux->$champ = $donnees[$champ];
    }

    // Vérification si le lieux existe
     if (!$lieux->exist()) {
        sendErrorResponse("Ce lieux n'existe pas.", 404);
    }

    // Tentative de suppression du lieux dans la base de données.
    if ($lieux->delete()) {
        sendDeletedResponse();
    } else {
        sendErrorResponse("La suppression n'a pas été effectuée.", 503);
    }
} else {
    sendErrorResponse("La méthode n'est pas autorisée.", 405);
}

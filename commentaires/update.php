<?php

/**
 * @file
 * API Endpoint pour la modifiaction d'un commentaire et d'une note sur un lieu par l'utilisateur.
 */

// Configuration des Headers HTTP
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
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
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // --- Inclusion des Fichiers Nécessaires ---
    include_once '../config/Database.php';
    include_once '../models/Commentaires.php';
    include_once '../middleware/Validator.php';

    // Crée une nouvelle instance de la classe Database pour établir une connexion à la base de données.
    $database = new Database();
    $db = $database->getConnexion();

    // Crée une nouvelle instance de la classe Commentaire.
    $commentaire = new Commentaires($db);

    // Les données envoyées au format JSON dans le corps de la requête sont décodées en un objet PHP
    $donnees = (array) json_decode(file_get_contents("php://input"), true);

    // Régles de validation des données
    $rules = [
        'id' => Validator::positiveInt(),
        'note' => Validator::range(0, 5),
        'commentaire' => Validator::requiredString(),
    ];

    // Vérification des données
    $errors = Validator::validate($donnees, $rules);

    // Si des erreurs
    if (!empty($errors)) {
        sendValidationErrorResponse("Les données fournies sont invalides.", $errors, 400);
    }

    // On assigne les valeurs des données reçues aux propriétés correspondantes de l'objet $commentaite.
    foreach (array_keys($rules) as $champ) {
        $commentaire->$champ = $donnees[$champ];
    }

    // Vérification si un commentaire existe
    if (!$commentaire->exists()) {
        sendErrorResponse("Ce commentaire n'existe pas.", 404);
    }

    // Assignation de l'id de l'user
    $commentaire->id_user = $donnees_utilisateur['id'];

    // Vérifier si l'utilisateur a les droits pour modifier ce commentaire
    if (!$commentaire->peutModifierOuSupprimer($donnees_utilisateur['id'], $donnees_utilisateur['grade'], 'update')) {
        sendErrorResponse("Vous n'avez pas les droits pour effectuer cette action.", 403);
    }

    // Tentative de modification du commentaire dans la base de données.
    if ($commentaire->update()) {
        sendUpdatedResponse();
    } else {
        sendErrorResponse("La modification n'a pas été effectué.", 503);
    }
} else {
    sendErrorResponse("La méthode n'est pas autorisée.", 405);
}

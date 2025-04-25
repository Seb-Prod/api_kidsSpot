<?php

/**
 * @file
 * API Endpoint pour la création d'un nouvel utilisateur.
 *
 * Gère les requêtes HTTP POST. Le corps de la requête doit contenir un JSON avec les informations
 * du nouvel utilisateur à créer. Retourne un statut HTTP et un message JSON indiquant le succès
 * ou l'échec de l'opération, ainsi que les erreurs de validation si nécessaire.
 *
 * @date 2025-04-12
 */
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// --- Configuration des Headers HTTP ---

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Inclusion du middleware des réponses
include_once '../middleware/ResponseHelper.php';

// Vérification de la Méthode HTTP
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once '../config/Database.php';
    include_once '../models/Users.php';
    include_once '../middleware/Validator.php';

    // Crée une nouvelle instance de la classe Database pour établir une connexion à la base de données.
    $database = new Database();
    $db = $database->getConnexion();

    // Crée une nouvelle instance de la classe Users.
    $users = new Users($db);

    // Les données envoyées au format JSON dans le corps de la requête sont décodées en un objet PHP.
    $donnees = (array) json_decode(file_get_contents("php://input"), true);

    // Régles de validation des données
    $rules = [
        'pseudo' => Validator::withMessage(
            Validator::requiredStringMax(50),
            "Le pseudo est obligatoire et ne doit pas dépasser 150 caractères"
        ),
        'mail' => Validator::withMessage(
            Validator::email(),
            "Un email valide est obligatoire"
        ),
        'mot_de_passe' => Validator::withMessage(
            Validator::password(),
            "Le mot de passe doit être de 8 caractères, une majuscule, une minuscile et un chiffre"
        ),
        'telephone' => Validator::withMessage(
            Validator::telephone(),
            "Numero de téléphone doit être valide"
        )
    ];

    // Vérification des données
    $errors = Validator::validate($donnees, $rules);

    // Si des erreurs
    if (!empty($errors)) {
        sendValidationErrorResponse("Les données fournies sont invalides.", $errors, 400);
    }

    // Vérification si le pseudo existe déjà
    if (isset($donnees['pseudo']) && $users->pseudoExists($donnees['pseudo'])) {
        sendErrorResponse("Ce pseudo existe déjà.", 409);
    }

    // Vérification si l'email existe déjà
    if (isset($donnees['mail']) && $users->emailExists($donnees['mail'])) {
        sendErrorResponse("Cet email existe déjà.", 409);
    }

    // On assigne les valeurs des données reçues aux propriétés correspondantes de l'objet $users.
    foreach (array_keys($rules) as $champ) {
        if (isset($donnees[$champ])) {
            $users->$champ = $donnees[$champ];
        }
    }

    // Tentative de création du lieux dans la base de données.
    if ($users->creer()) {
        sendCreatedResponse("L'ajout a été effectué.");
    } else {
        sendErrorResponse("L'ajout n'a pas été effectué.", 503);
    }
} else {

    sendErrorResponse("La méthode n'est pas autorisée.", 405);
}


ob_end_flush();

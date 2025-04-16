<?php

/**
 * @file
 * API Endpoint pour la création d'un nouveau lieu.
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
validateUserAutorisation($donnees_utilisateur, 4);

// Vérification de la Méthode HTTP
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Inclusion des Fichiers Nécessaires
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
        // Champs principaux du lieu
        'nom' => Validator::requiredStringMax(150),
        'description' => Validator::requiredStringMax(1000),
        'horaires' => Validator::requiredStringMax(50),
        'adresse' => Validator::requiredStringMax(100),
        'ville' => Validator::requiredStringMax(50),
        'code_postal' => Validator::codePostal(),
        'latitude' => Validator::latitude(),
        'longitude' => Validator::longitude(),
        'telephone' => Validator::telephone(),
        'site_web' => Validator::url(),
        'id_type' => Validator::positiveInt(),
    ];

    // Règles pour les relations (peuvent être optionnelles)
    $optionalRules = [
        'tranches_age' => Validator::arrayOfUniqueIntsInRange(1, 3),
        'equipements' => Validator::arrayOfUniqueIntsInRange(1, 5),
    ];

    // Vérification des données
    $errors = Validator::validate($donnees, $rules);

    // Si des erreurs
    if (!empty($errors)) {
        sendValidationErrorResponse("Les données fournies sont invalides.", $errors, 400);
    }

    // Préparation des valeurs par défaut pour les relations
    $equipements = isset($donnees['equipements']) ? $donnees['equipements'] : [];
    $tranches_age = isset($donnees['tranches_age']) ? $donnees['tranches_age'] : [];

    // Validation des relations si elles sont présentes
    if (!empty($equipements)) {
        $equipementErrors = Validator::validate(['equipements' => $equipements], ['equipements' => $optionalRules['equipements']]);
        if (!empty($equipementErrors)) {
            sendValidationErrorResponse("Les équipements fournis sont invalides.", $equipementErrors, 400);
        }
    }

    if (!empty($tranches_age)) {
        $ageErrors = Validator::validate(['tranches_age' => $tranches_age], ['tranches_age' => $optionalRules['tranches_age']]);
        if (!empty($ageErrors)) {
            sendValidationErrorResponse("Les tranches d'âge fournies sont invalides.", $ageErrors, 400);
        }
    }

    // On assigne les valeurs des données reçues aux propriétés correspondantes de l'objet $commentaite.
    foreach (array_keys($rules) as $champ) {
        if (isset($donnees[$champ])) {
            $lieux->$champ = $donnees[$champ];
        }
    }

    // Assignation de l'id de l'user
    $lieux->id_user = $donnees_utilisateur['id'];


    // Tentative de création du commentaire dans la base de données.
    if ($lieux->create($equipements, $tranches_age)) {
        sendCreatedResponse("L'ajout a été effectué.");
    } else {
        sendErrorResponse("L'ajout n'a pas été effectué.", 503);
    }
} else {
    sendErrorResponse("La méthode n'est pas autorisée.", 405);
}

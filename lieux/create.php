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
    include_once '../middleware/Helpers.php';

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
        'tranches_age' => Validator::arrayOfUniqueIntsInRange(1, 3),
        'equipements' => Validator::arrayOfUniqueIntsInRange(1, 5),
    ];

    // Règles pour les relations (peuvent être optionnelles)
    $optionalRules = [
        'date_debut' => Validator::date('d/m/Y'),
        'date_fin' => Validator::date('d/m/Y'),
    ];

    // Vérification des données
    $errors = Validator::validate($donnees, $rules);

    // Si des erreurs
    if (!empty($errors)) {
        sendValidationErrorResponse("Les données fournies sont invalides.", $errors, 400);
    }

    // Validation des dates si elles sont présentes
    foreach (['date_debut', 'date_fin'] as $dateKey) {
        if (isset($donnees[$dateKey])) {
            $errors = Validator::validate([$dateKey => $donnees[$dateKey]], [$dateKey => $optionalRules[$dateKey]]);
            if (!empty($errors)) {
                sendValidationErrorResponse("La {$dateKey} fournie est invalide.", $errors, 400);
            }
        }
    }

    // Vérification de cohérence pour les dates
    if ((isset($donnees['date_debut']) && !isset($donnees['date_fin'])) ||
        (!isset($donnees['date_debut']) && isset($donnees['date_fin']))
    ) {
        sendValidationErrorResponse("Si une date est fournie, les dates de début et de fin doivent toutes deux être renseignées.", ['date_debut', 'date_fin'], 400);
    }

    // On assigne les valeurs des données reçues aux propriétés correspondantes de l'objet $lieux.
    foreach (array_keys($rules) as $champ) {
        if (isset($donnees[$champ])) {
            $lieux->$champ = $donnees[$champ];
        }
    }

    // Assignation de l'id de l'user
    $lieux->id_user = $donnees_utilisateur['id'];

    // Assiggnation des équipement et tranche d'age
    $equipements = isset($donnees['equipements']) ? $donnees['equipements'] : [];
    $tranches_age = isset($donnees['tranches_age']) ? $donnees['tranches_age'] : [];

    // Assignation des dates (uniquement si c'est un évenement)
    $date_debut = isset($donnees['date_debut']) ? convertirDateFrancaisVersUs($donnees['date_debut']) : null;
    $date_fin = isset($donnees['date_fin']) ? convertirDateFrancaisVersUs($donnees['date_fin']) : null;

    // Tentative de création du lieux dans la base de données.
    if ($lieux->create($equipements, $tranches_age, $date_debut, $date_fin)) {
        sendCreatedResponse("L'ajout a été effectué.");
    } else {
        sendErrorResponse("L'ajout n'a pas été effectué.", 503);
    }
} else {
    sendErrorResponse("La méthode n'est pas autorisée.", 405);
}

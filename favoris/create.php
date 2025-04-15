<?php

/**
 * @file
 * API Endpoint pour l'ajout d'un lieu en favorris par un utilisateur.
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
    // --- Inclusion des Fichiers Nécessaires ---
    include_once '../config/Database.php';
    include_once '../models/Favoris.php';

    // Crée une nouvelle instance de la classe Database pour établir une connexion à la base de données.
    $database = new Database();
    $db = $database->getConnexion();

    // Crée une nouvelle instance de la classe Favoris.
    $favoris = new Favoris($db);

    // Les données envoyées au format JSON dans le corps de la requête sont décodées en un objet PHP.
    $donnees = json_decode(file_get_contents("php://input"));

    // Validation des Données Reçues
    $validation_regles = [
        'id_lieu' => function ($val) {
            return is_numeric($val) && $val > 0;
        }
    ];

    // Tableau pour stocker les erreurs de validation.
    $erreurs = [];
    // Parcours des règles de validation pour chaque champ.
    foreach ($validation_regles as $champ => $regle) {
        // Vérifie si le champ existe dans les données reçues et si la règle de validation est respectée.
        if (!isset($donnees->$champ) || !$regle($donnees->$champ)) {
            // Si la validation échoue, ajoute le nom du champ au tableau des erreurs.
            $erreurs[] = $champ;
        }
    }

    // Si aucune erreur de validation n'a été détectée.
    if (empty($erreurs)) {
        // On assigne les valeurs des données reçues aux propriétés correspondantes de l'objet $lieux.
        foreach (array_keys($validation_regles) as $champ) {
            $favoris->$champ = $donnees->$champ;
        }

        // Assignation de l'id de l'user
        $favoris->id_user = $donnees_utilisateur['id'];

        // Vérification si le lieu exite
        if (!$favoris->exists()) {
            sendErrorResponse("Ce lieu n'existe pas.", 404);
        }

        // Vérification si l'user à déjà ajouté le lieu en favoris
        if ($favoris->alreadyExists()) {
            sendErrorResponse("Vous avez déjà ajouté ce lieu.", 409);
        }

        // Tentative de création du lieu dans la base de données.
        if ($favoris->create()) {
            sendCreatedResponse("L'ajout a été effectué");
        } else {
            sendErrorResponse("L'ajout n'a pas été effectué.", 503);
        }
    } else {
        sendValidationErrorResponse("Les données fournies sont invalides.", $erreurs, 400);
    }
} else {
    sendErrorResponse("La méthode n'est pas autorisée.", 405);
}

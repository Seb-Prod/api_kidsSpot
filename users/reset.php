<?php

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

    // Les données envoyeées au format JSON dans le corps de la requête sont décodées en un objet PHP.
    $donnees = (array) json_decode(file_get_contents("php://input"), true);

    // Régles de validation des données
    $rules = [
        'mail' => Validator::withMessage(
            Validator::email(),
            "Un email valide est obligatoire"
        ),
        'mot_de_passe' => Validator::withMessage(Validator::password(),"Le mot de passe doit être de 8 caratère, une majuscule, une minuscule et un chiffre"),
        'token_reinitialisation' =>  Validator::withMessage(Validator::requiredString(), "Code de réinitialisation reçus par email"),
    ];

    // Vérification des données
    $errors = Validator::validate($donnees, $rules);

    // Si des erreurs
    if(!empty($errors)){
        sendValidationErrorResponse("Les données fournies sont invalides.", $errors, 400);
    }

    // On assigne les valeurs des données reçues aux propriétés correspondantes de l'objet $users.
    foreach (array_keys($rules) as $champ) {
        if (isset($donnees[$champ])) {
            $users->$champ = $donnees[$champ];
        }
    }

    // Vérification si le pseudo, le token et la date (non périmé) sont ok
    if(!$users->verifyResetToken()){
        sendErrorResponse("Code de réinitialisation invalide ou périmé", 400);
    }

    // Tentative de changement du mot de passe
    if ($users->changePassword()){
        sendCreatedResponse("Le mot de passe a bien été changé");
    } else {
        sendErrorResponse("Le mot de passe n'a pas été changé.", 503);
    }

}else {

    sendErrorResponse("La méthode n'est pas autorisée.", 405);
}

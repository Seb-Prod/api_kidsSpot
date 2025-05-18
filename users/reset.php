<?php
/**
 * API Endpoint pour la réinitialisation du mot de passe via un token (POST).
 *
 * L'utilisateur fournit un email, un nouveau mot de passe, et un token valide reçu par email.
 */

// --- Configuration des Headers HTTP ---
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Inclusion du middleware de gestion des réponses
include_once '../middleware/ResponseHelper.php';

/**
 * Vérifie si la requête est bien de type POST.
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Inclusion des fichiers nécessaires
    include_once '../config/Database.php';
    include_once '../models/Users.php';
    include_once '../middleware/Validator.php';

    // Création de la connexion à la base de données
    $database = new Database();
    $db = $database->getConnexion();

    // Création d'une instance du modèle utilisateur
    $users = new Users($db);

    /**
     * Récupération et décodage des données JSON du corps de la requête.
     */
    $donnees = (array) json_decode(file_get_contents("php://input"), true);

    /**
     * Définition des règles de validation :
     * - mail : email valide requis
     * - mot_de_passe : 8 caractères, 1 majuscule, 1 minuscule, 1 chiffre
     * - token_reinitialisation : chaîne non vide requise
     */
    $rules = [
        'mail' => Validator::withMessage(
            Validator::email(),
            "Un email valide est obligatoire"
        ),
        'mot_de_passe' => Validator::withMessage(
            Validator::password(),
            "Le mot de passe doit comporter au moins 8 caractères, une majuscule, une minuscule et un chiffre"
        ),
        'token_reinitialisation' => Validator::withMessage(
            Validator::requiredString(),
            "Code de réinitialisation reçu par email"
        ),
    ];

    /**
     * Vérifie les données selon les règles définies.
     * Retourne une réponse d'erreur si des données sont invalides.
     */
    $errors = Validator::validate($donnees, $rules);

    if (!empty($errors)) {
        sendValidationErrorResponse("Les données fournies sont invalides.", $errors, 400);
    }

    /**
     * Attribution des données reçues aux propriétés de l'objet $users.
     */
    foreach (array_keys($rules) as $champ) {
        if (isset($donnees[$champ])) {
            $users->$champ = $donnees[$champ];
        }
    }

    /**
     * Vérifie que le token est valide (existe et non expiré).
     */
    if (!$users->verifyResetToken()) {
        sendErrorResponse("Code de réinitialisation invalide ou périmé", 400);
    }

    /**
     * Tente de modifier le mot de passe de l'utilisateur.
     */
    if ($users->changePassword()) {
        sendCreatedResponse("Le mot de passe a bien été changé");
    } else {
        sendErrorResponse("Le mot de passe n'a pas été changé.", 503);
    }

} else {
    /**
     * Si la méthode HTTP utilisée n’est pas POST.
     */
    sendErrorResponse("La méthode n'est pas autorisée.", 405);
}

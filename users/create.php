<?php

/**
 * API Endpoint pour créer un utilisateur (POST).
 */
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * Configuration des Headers HTTP pour autoriser les requêtes CORS et définir le type de contenu.
 */
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

/**
 * Inclusion du fichier contenant les fonctions de gestion des réponses HTTP.
 */
include_once '../middleware/ResponseHelper.php';

/**
 * Vérifie si la méthode de la requête est POST.
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    /**
     * Inclusion des fichiers nécessaires pour la connexion à la base de données,
     * la manipulation des utilisateurs et la validation des données.
     */
    include_once '../config/Database.php';
    include_once '../models/Users.php';
    include_once '../middleware/Validator.php';

    /**
     * Création d'une instance de la base de données et récupération de la connexion.
     */
    $database = new Database();
    $db = $database->getConnexion();

    /**
     * Création d'une instance de la classe Users.
     */
    $users = new Users($db);

    /**
     * Décodage des données JSON reçues dans le corps de la requête.
     */
    $donnees = (array) json_decode(file_get_contents("php://input"), true);

    /**
     * Définition des règles de validation pour les données reçues.
     */
    $rules = [
        'pseudo' => Validator::withMessage(Validator::requiredStringMax(50), "Le pseudo est obligatoire et ne doit pas dépasser 150 caractères"),
        'mail' => Validator::withMessage(Validator::email(), "Un email valide est obligatoire"),
        'mot_de_passe' => Validator::withMessage(Validator::password(), "Le mot de passe doit être de 8 caractères, une majuscule, une minuscile et un chiffre"),
        'telephone' => Validator::withMessage(Validator::telephone(), "Numero de téléphone doit être valide")
    ];

    /**
     * Validation des données selon les règles définies.
     */
    $errors = Validator::validate($donnees, $rules);

    /**
     * Si des erreurs de validation sont présentes, envoie une réponse d'erreur.
     */
    if (!empty($errors)) {
        sendValidationErrorResponse("Les données fournies sont invalides.", $errors, 400);
    }

    /**
     * Vérifie si le pseudo existe déjà dans la base de données.
     */
    if (isset($donnees['pseudo']) && $users->pseudoExists($donnees['pseudo'])) {
        sendErrorResponse("Ce pseudo existe déjà.", 409);
    }

    /**
     * Vérifie si l'email existe déjà dans la base de données.
     */
    if (isset($donnees['mail']) && $users->emailExists($donnees['mail'])) {
        sendErrorResponse("Cet email existe déjà.", 409);
    }

    /**
     * Assignation des valeurs des données reçues aux propriétés de l'objet utilisateur.
     */
    foreach (array_keys($rules) as $champ) {
        if (isset($donnees[$champ])) {
            $users->$champ = $donnees[$champ];
        }
    }

    /**
     * Tentative de création de l'utilisateur dans la base de données.
     */
    if ($users->creer()) {
        sendCreatedResponse("L'ajout a été effectué.");
    } else {
        sendErrorResponse("L'ajout n'a pas été effectué.", 503);
    }
} else {
    /**
     * Si la méthode de la requête n'est pas POST, envoie une réponse d'erreur.
     */
    sendErrorResponse("La méthode n'est pas autorisée.", 405);
}

ob_end_flush();
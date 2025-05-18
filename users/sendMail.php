<?php
/**
 * @file
 * Endpoint pour l'envoi d'emails groupés aux utilisateurs (POST).
 * 
 * Seuls les administrateurs (grade 4) peuvent accéder à cette ressource.
 * Envoie un email personnalisé à tous les utilisateurs ayant activé l'option `opt_in_email`.
 * Les emails incluent une personnalisation simple via la variable {PSEUDO}.
 */

// --- Configuration des Headers HTTP ---
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Inclusion des middlewares nécessaires
include_once '../middleware/auth_middleware.php';
include_once '../middleware/UserAutorisation.php';
include_once '../middleware/ResponseHelper.php';

// Vérification de l'authentification et des autorisations (grade 4 requis)
$donnees_utilisateur = verifierAuthentification();
validateUserAutorisation($donnees_utilisateur, 4);

// Vérifie que la méthode utilisée est POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Inclusion des dépendances
    include_once '../config/Database.php';
    include_once '../models/Users.php';
    include_once '../middleware/Mailer.php';
    include_once '../middleware/Validator.php';

    // Connexion à la base de données
    $database = new Database();
    $db = $database->getConnexion();

    // Instanciation du modèle Users
    $user = new Users($db);

    // Récupération et décodage du corps JSON de la requête
    $donnees = (array) json_decode(file_get_contents("php://input"), true);

    // Définition des règles de validation
    $rules = [
        'sujet' => Validator::withMessage(
            Validator::requiredStringMax(50),
            "Le sujet du mail est obligatoire et ne doit pas dépasser 50 caractères"
        ),
        'contenueHTML' => Validator::withMessage(
            Validator::requiredStringMax(500),
            "Le contenu du mail est obligatoire et ne doit pas dépasser 500 caractères"
        )
    ];

    // Validation des données
    $errors = Validator::validate($donnees, $rules);

    if (!empty($errors)) {
        sendValidationErrorResponse("Les données fournies sont invalides.", $errors, 400);
    }

    // Préparation des variables
    $sujet = $donnees['sujet'];
    $contenuHTML = $donnees['contenueHTML'];

    // Récupération des utilisateurs ayant accepté de recevoir des emails
    $users = $user->getUsersWithEmailOptIn()->fetchAll(PDO::FETCH_ASSOC);

    $countSuccess = 0;
    $countFailed = 0;
    $failedEmails = [];

    // Boucle d'envoi des emails
    foreach ($users as $userData) {
        // Personnalisation du contenu
        $emailContent = str_replace('{PSEUDO}', $userData['pseudo'], $contenuHTML);
        
        if (envoyerEmail($userData['mail'], $sujet, $emailContent)) {
            $countSuccess++;
        } else {
            $countFailed++;
            $failedEmails[] = $userData['mail'];
        }

        // Pause pour ne pas saturer le serveur SMTP
        usleep(200000); // 200 ms
    }

    // Log optionnel (désactivé)
    //$logMessage = date('Y-m-d H:i:s') . " - Envoi groupé: $countSuccess réussis, $countFailed échoués. Sujet: " . substr($sujet, 0, 50);
    //error_log($logMessage, 3, "../logs/email.log");

    // Réponse
    $response = [
        "status" => "success",
        "message" => "Emails envoyés avec succès à $countSuccess utilisateurs. $countFailed échecs.",
        "total" => count($users),
        "success" => $countSuccess,
        "failed" => $countFailed
    ];

    if ($countFailed > 0) {
        $response["failed_emails"] = $failedEmails;
    }

    http_response_code(200);
    echo json_encode($response);

} else {
    sendErrorResponse("La méthode n'est pas autorisée", 405);
}
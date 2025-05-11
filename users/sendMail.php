<?php
/**
 * @file
 * Endpoint pour l'envoi d'emails groupés aux utilisateurs.
 * 
 * Cet endpoint permet d'envoyer un email à tous les utilisateurs
 * ayant activé l'option opt_in_email dans leur profil.
 */

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once '../config/Database.php';
    include_once '../models/Users.php';
    include_once '../middleware/Mailer.php';
    include_once '../middleware/Validator.php';

    // Crée une nouvelle instance de la classe Database pour établir une connexion à la base de données.
    $database = new Database();
    $db = $database->getConnexion();

    // Crée une nouvelle instance de la classe Users.
    $user = new Users($db);

    // Les données envoyées au format JSON dans le corps de la requête sont décodées en un objet PHP.
    $donnees = (array) json_decode(file_get_contents("php://input"), true);

    // Régles de validation des données
    $rules = [
        'sujet' => Validator::withMessage(
            Validator::requiredStringMax(50),
            "Le sujet du mail est obligatoire et ne doit pas dépasser 50 caractères"
        ),
        'contenuHTML' => Validator::withMessage(
            Validator::requiredStringMax(150),
            "Le contenue du mail est obligatoire et ne doit pas dépasser 150 caractères"
        )

    ];
    
    // Vérification des données
    $errors = Validator::validate($donnees, $rules);

    // Si des erreurs
    if (!empty($errors)) {
        sendValidationErrorResponse("Les données fournies sont invalides.", $errors, 400);
    }

    $sujet = $donnees['sujet'];
    $contenuHTML = $donnees['contenuHTML'];

    // Récupération de tous les utilisateurs qui ont opté pour recevoir des emails
    $users = $user->getUsersWithEmailOptIn()->fetchAll(PDO::FETCH_ASSOC);

    // Envoi des emails à chaque utilisateur
    foreach ($users as $userData) {
        // Personnalisation optionnelle du contenu pour chaque utilisateur
        $emailContent = str_replace('{PSEUDO}', $userData['pseudo'], $contenuHTML);
        
        // Envoi de l'email
        if (envoyerEmail($userData['mail'], $sujet, $emailContent, $contenuTexte)) {
            $countSuccess++;
        } else {
            $countFailed++;
            $failedEmails[] = $userData['mail'];
        }
        
        // Pause courte pour éviter de surcharger le serveur SMTP
        usleep(200000); // 200ms
    }

    // Journal des emails envoyés
    $logMessage = date('Y-m-d H:i:s') . " - Envoi groupé: $countSuccess réussis, $countFailed échoués. Sujet: " . substr($sujet, 0, 50);
    error_log($logMessage, 3, "../logs/email.log");

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


?>
<?php
/**
 * @file
 * API Endpoint pour lire les préférences d'un utilisateur connecté.
 */

// --- Configuration des Headers HTTP ---
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// --- Middlewares ---
include_once '../middleware/auth_middleware.php';
include_once '../middleware/UserAutorisation.php';
include_once '../middleware/ResponseHelper.php';

// Vérification de l'authentification
$donnees_utilisateur = verifierAuthentification();
validateUserAutorisation($donnees_utilisateur, 1);

// Vérification de la méthode HTTP
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // --- Dépendances ---
    include_once '../config/Database.php';
    include_once '../models/Profil.php';
    include_once '../middleware/FormatHelper.php';

    // Connexion à la base de données
    $database = new Database();
    $db = $database->getConnexion();

    // Instanciation du modèle Profil
    $profil = new Profil($db);
    $profil->id = $donnees_utilisateur['id'];

    // Lecture des préférences
    $stmt = $profil->read();

    if ($stmt && $stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $userPreferences = FormatHelper::userPreference($row);
        sendSuccessResponse($userPreferences);
    } else {
        sendErrorResponse("Aucune préférence trouvée pour cet utilisateur.", 404);
    }

} else {
    sendErrorResponse("La méthode n'est pas autorisée.", 405);
}

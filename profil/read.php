<?php

/**
 * @file
 * API Endpoint pour lire les préférence d'un user.
 */

// Configuration des Headers HTTP
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
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
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // --- Inclusion des Fichiers Nécessaires ---
    include_once '../config/Database.php';
    include_once '../models/Profil.php';
    include_once '../middleware/FormatHelper.php';

    // Crée une nouvelle instance de la classe Database pour établir une connexion à la base de données.
    $database = new Database();
    $db = $database->getConnexion();

    // Crée une nouvelle instance de la classe Commentaire.
    $profil = new Profil($db);

    // Récupération de l'id de l'user
    $profil->id = $donnees_utilisateur['id'];

    // Appel à la Méthode du Modèle
    $stmt = $profil->read();

    if ($stmt && $stmt->rowCount() > 0) {
        // Récupération des données utilisateur
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Formate les données utilisateur selon le modèle défini
        $userPreferences = FormatHelper::userPreference($row);
        
        // Envoie la réponse
        sendSuccessResponse($userPreferences);
    } else {
        sendErrorResponse("Aucune préférence trouvée pour cet utilisateur.", 404);
    }
} else {
    sendErrorResponse("La méthode n'est pas autorisée.", 405);
}

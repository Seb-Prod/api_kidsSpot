<?php

/**
 * @file
 * Endpoint API pour récupérer une liste de lieux situés autour de coordonnées géographiques spécifiées.
 */

// Configuration des headers HTTP
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Inclusion du middleware pour les réponses
include_once '../middleware/ResponseHelper.php';

// Vérifie que la méthode utilisée est bien GET
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    // Inclusion des fichiers nécessaires
    include_once '../config/Database.php';
    include_once '../models/Lieux.php';
    include_once '../middleware/CoordinatesValidator.php';
    include_once '../middleware/FormatHelper.php';

    // Connexion à la base de données
    $database = new Database();
    $db = $database->getConnexion();

    // Création d’un objet Lieux
    $lieux = new Lieux($db);

    // Récupération et validation des coordonnées passées en paramètres
    $coordinates = validateCoordinates();
    if ($coordinates === false) {
        sendErrorResponse("Coordonnées invalides.", 400);
        exit;
    }

    $latitude = $coordinates['latitude'];
    $longitude = $coordinates['longitude'];

    // Récupération des lieux autour des coordonnées
    $stmt = $lieux->getPlacesAround($latitude, $longitude);

    // Vérifie s'il y a des résultats
    if ($stmt && $stmt->rowCount() > 0) {
        $tableauLieux = [];

        // Boucle sur chaque lieu trouvé
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Formate les données du lieu
            $unLieu = FormatHelper::lieuLight($row);
            // Ajoute le lieu au tableau de résultats
            $tableauLieux[] = $unLieu;
        }

        // Réponse avec les lieux trouvés
        sendSuccessResponse($tableauLieux);
    } else {
        // Aucun lieu trouvé
        sendErrorResponse("Aucun lieu trouvé.", 404);
    }

} else {
    // Méthode HTTP non autorisée
    sendErrorResponse("La méthode n'est pas autorisée.", 405);
}

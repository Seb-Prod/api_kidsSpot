<?php

/**
 * @file
 * Endpoint API pour récupérer une liste de lieux situés autour de coordonnées géographiques spécifiées.
 */

// Configuration des Headers HTTP
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../middleware/ResponseHelper.php';

// Vérification de la Méthode HTTP
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // --- Inclusion des Fichiers Nécessaires ---
    include_once '../config/Database.php';
    include_once '../models/Lieux.php';
    include_once '../middleware/CoordinatesValidator.php';
    include_once '../middleware/FormatHelper.php';

    // Crée une nouvelle instance de la classe Database pour établir une connexion à la base de données.
    $database = new Database();
    $db = $database->getConnexion();

    // Crée une nouvelle instance de la classe Lieux.
    $lieux = new Lieux($db);

    // Récupération et validation des coordonnées
    $coordinates = validateCoordinates();
    if ($coordinates === false) {
        sendErrorResponse("Coordonnées invalides.", 400);
        exit;
    }
    $latitude = $coordinates['latitude'];
    $longitude = $coordinates['longitude'];

    // Appel à la Méthode du Modèle
    $stmt = $lieux->getPlacesAround($latitude, $longitude);

    // Vérifie si l'exécution de la requête a réussi et s'il y a au moins un résultat.
    if ($stmt && $stmt->rowCount() > 0) {
        // Initialisation d'un tableau associatif pour stocker les lieux trouvés.
        $tableauLieux = [];

        // Boucle à travers chaque ligne (chaque lieu) retournée par la requête.
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            // Définit la structure d'un tableau représentant un lieu individuel.
            $unLieu = FormatHelper::lieuLight($row);
            // Ajoute le lieu formaté au tableau principal des lieux.
            $tableauLieux[] = $unLieu;
        }
        // envoie la réponse
        sendSuccessResponse($tableauLieux);
    } else {
        sendErrorResponse("Aucun lieu trouvé.", 404);
    }
} else {
    sendErrorResponse("La méthode n'est pas autorisée.", 405);
}

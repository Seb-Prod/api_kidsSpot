<?php

/**
 * @file
 * Endpoint API pour récupérer les détails d'un lieu spécifique via son ID.
 */

// Headers HTTP
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Inclusion des helpers
include_once '../middleware/ResponseHelper.php';

// Vérifie que la méthode utilisée est GET
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Connexion à la base de données
    include_once '../config/Database.php';
    include_once '../models/Lieux.php';
    include_once '../middleware/FormatHelper.php';

    $database = new Database();
    $db = $database->getConnexion();

    $lieux = new Lieux($db);

    // Vérifie si l'ID est passé dans l'URL
    if (isset($_GET['id'])) {
        // Vérifie que l'ID est un entier positif
        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        if ($id === false || $id <= 0) {
            sendErrorResponse("L'ID fourni n'est pas valide.", 400);
            exit;
        }

        // Récupère le lieu par son ID
        $stmt = $lieux->getPlaceById($id);

        // Si un lieu est trouvé
        if ($stmt && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $tableauLieu = FormatHelper::lieuDetail($row);

            // Renvoie les détails du lieu
            sendSuccessResponse($tableauLieu, 200);
        } else {
            // Aucun lieu trouvé
            sendErrorResponse("Aucun lieu trouvé pour cet ID.", 404);
        }
    } else {
        // ID non fourni
        sendErrorResponse("L'ID du lieu est manquant.", 400);
    }
} else {
    // Méthode non autorisée
    sendErrorResponse("La méthode n'est pas autorisée.", 405);
}
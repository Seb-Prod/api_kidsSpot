<?php

/**
 * @file
 * API Endpoint pour lire un commentaire et sa note par son id.
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
    include_once '../models/Commentaires.php';
    include_once '../middleware/FormatHelper.php';

    // Crée une nouvelle instance de la classe Database pour établir une connexion à la base de données.
    $database = new Database();
    $db = $database->getConnexion();

    // Crée une nouvelle instance de la classe Commentaire.
    $commentaire = new Commentaires($db);

    // Vérifie si le paramètre 'id' est présent dans l'URL de la requête GET.
    if (isset($_GET['id'])) {
        // Filtre et valide la valeur du paramètre 'id' pour s'assurer que c'est un entier.
        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

        // Vérifie si la validation a réussi et si l'ID est un nombre entier positif.
        if ($id !== false && $id > 0) {
            // Récupération du commentaire par ID
            $stmt = $commentaire->read($id);

            // Vérifie si l'exécution de la requête a réussi et s'il y a au moins un résultat.
            if ($stmt && $stmt->rowCount() > 0) {
                // Récupère la première (et unique) ligne de résultat sous forme de tableau associatif.
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                // Initialisation du tableau associatif pour stocker les informations.
                $tableauCommentaire = FormatHelper::commentaire($row);
                // Reponse
                sendSuccessResponse($tableauCommentaire, 200);
            } else {
                sendErrorResponse("Le commentaire n'existe pas.", 404);
            }
        } else {
            sendErrorResponse("L'ID fourni n'est pas valide.", 400);
        }
    } else {
        sendErrorResponse("L'ID du commentaire est manquant dans l'URL.", 400);
    }
} else {
    sendErrorResponse("La méthode n'est pas autorisée.", 405);
}

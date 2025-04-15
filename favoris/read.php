<?php

/**
 * @file
 * API Endpoint pour récupérer la liste de lieux en favoris d'un user.
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
    include_once '../models/Favoris.php';
    include_once '../middleware/CoordinatesValidator.php';
    include_once '../middleware/Helpers.php';

    // Crée une nouvelle instance de la classe Database pour établir une connexion à la base de données.
    $database = new Database();
    $db = $database->getConnexion();

    // Crée une nouvelle instance de la classe Favoris.
    $favoris = new Favoris($db);

    // Récupération de l'id de l'user
    $favoris->id_user = $donnees_utilisateur['id'];

    // Récupération et validation des coordonnées
    $coordinates = validateCoordinates();
    $latitude = $coordinates['latitude'];
    $longitude = $coordinates['longitude'];

    // Appel à la Méthode du Modèle
    $stmt = $favoris->read($latitude, $longitude);

    // Si requêtte OK et contien au moins un éléments.
    if ($stmt && $stmt->rowCount() > 0) {
        // Initialisation d'un tableau associatif pour stocker les lieux trouvés.
        $tableauLieux = [];

        // Boucle à travers chaque ligne (chaque lieu) retournée par la requête.
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Extrait les champs de chaque ligne dans des variables individuelles.
            extract($row);
            // Définit la structure d'un tableau représentant un lieu individuel.
            $unLieux = [
                "id" => (int)$id_lieu,
                "nom" => html_entity_decode($nom_lieu),
                "horaires" => html_entity_decode($horaires),
                "adresse" => [
                    "adresse" => html_entity_decode($adresse),
                    "code_postal" => $code_postal,
                    "ville" => html_entity_decode($ville),
                ],
                "type" => html_entity_decode($type_lieu),
                "est_evenement" => (bool)$est_evenement,
                "position" => [
                    "latitude" => round((float)$row['latitude'], 5),
                    "longitude" => round((float)$row['longitude'], 5),
                    "distance_km" => round((float)$row['distance'], 5)
                ],
                "equipements" => parseCommaSeparated($equipements),
                "ages" => parseCommaSeparated($tranches_age),
                "date_evenement" => [
                        "debut" => $row['date_debut'],
                        "fin" => $row['date_fin']
                    ],
            ];
            // Ajoute le lieu formaté au tableau principal des lieux.
            $tableauLieux[] = $unLieux;
        }
        // envoie la réponse
        sendSuccessResponse($tableauLieux);
    } else {
        sendErrorResponse("Aucun lieu trouvé.", 404);
    }
} else {
    sendErrorResponse("La méthode n'est pas autorisée.", 405);
}

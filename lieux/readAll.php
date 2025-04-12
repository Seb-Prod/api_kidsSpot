<?php
/**
 * @file
 * Endpoint API pour récupérer une liste de lieux situés autour de coordonnées géographiques spécifiées.
 *
 * Ce script PHP répond aux requêtes HTTP de type GET. Il nécessite les paramètres de latitude (`lat`) et de longitude (`lng`) dans l'URL pour effectuer la recherche des lieux à proximité.
 * 
 * Les résultats sont retournés au format JSON.
 */

// --- Configuration des Headers HTTP ---

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// --- Vérification de la Méthode HTTP ---

// On s'assure que la requête HTTP reçue par le serveur est bien de type GET.
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // --- Inclusion des Fichiers Nécessaires ---
    include_once '../config/Database.php';
    include_once '../models/Lieux.php';

    // --- Instanciation des Objets ---

    // Crée une nouvelle instance de la classe Database pour établir une connexion à la base de données.
    $database = new Database();
    $db = $database->getConnexion();

    // Crée une nouvelle instance de la classe Lieux, en passant l'objet de connexion à la base de données comme dépendance.
    $lieux = new Lieux($db);

    // --- Validation des Paramètres Requis ---

    // Vérifie si les paramètres 'lat' (latitude) et 'lng' (longitude) sont présents dans la requête GET.
    if (!isset($_GET['lat']) || !isset($_GET['lng'])) {
        // Si l'un des paramètres est manquant, on envoie un code de réponse HTTP 400 (Bad Request)
        http_response_code(400);
        // Et on retourne un message JSON indiquant que les paramètres sont requis.
        echo json_encode(["message" => "Les paramètres lat et lng sont requis."]);
        // On termine l'exécution du script.
        exit();
    }

    // --- Conversion et Validation des Valeurs des Paramètres ---

    // Filtre et convertit la valeur du paramètre 'lat' en un nombre à virgule flottante.
    $latitude = filter_var($_GET['lat'], FILTER_VALIDATE_FLOAT);
    // Filtre et convertit la valeur du paramètre 'lng' en un nombre à virgule flottante.
    $longitude = filter_var($_GET['lng'], FILTER_VALIDATE_FLOAT);

    // Vérifie si la conversion des paramètres en nombres flottants a réussi.
    if ($latitude === false || $longitude === false) {
        // Si la conversion échoue (les valeurs ne sont pas des nombres valides), on envoie un code de réponse HTTP 400.
        http_response_code(400);
        // Et on retourne un message JSON indiquant que les coordonnées doivent être des nombres.
        echo json_encode(["message" => "Les coordonnées doivent être des nombres"]);
        // On termine l'exécution du script.
        exit();
    }

    // --- Validation des Plages de Coordonnées ---

    // Vérifie si les coordonnées fournies se trouvent dans les plages géographiques valides.
    if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
        // Si les coordonnées sont hors limites, on envoie un code de réponse HTTP 400.
        http_response_code(400);
        // Et on retourne un message JSON expliquant les limites valides pour la latitude et la longitude.
        echo json_encode(["message" => "Coordonnées hors limites. Latitude: -90 à 90, Longitude: -180 à 180."]);
        // On termine l'exécution du script.
        exit;
    }

    // --- Arrondissement des Coordonnées ---

    // Arrondit les valeurs de latitude et de longitude à 6 décimales pour une meilleure précision dans la requête.
    $latitude = round($latitude, 6);
    $longitude = round($longitude, 6);

    // --- Appel à la Méthode du Modèle ---

    // Appelle la méthode 'obtenirLieuxAutour' de l'objet Lieux, en passant la latitude et la longitude validées.
    // Cette méthode devrait retourner un objet PDOStatement contenant les résultats de la requête SQL.
    $stmt = $lieux->obtenirLieuxAutour($latitude, $longitude);

    // --- Traitement des Résultats de la Requête ---

    // Vérifie si l'exécution de la requête a réussi et s'il y a au moins un résultat.
    if ($stmt && $stmt->rowCount() > 0) {
        // Initialisation d'un tableau associatif pour stocker les lieux trouvés.
        $tableauLieux = [];
        $tableauLieux['lieux'] = [];

        // Boucle à travers chaque ligne (chaque lieu) retournée par la requête.
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Extrait les champs de chaque ligne dans des variables individuelles pour une manipulation plus facile.
            extract($row);
            // Définit la structure d'un tableau représentant un lieu individuel.
            $unLieux = [
                "id" => $id_lieu,
                // Décode les chaînes JSON potentiellement encodées pour les noms.
                "nom" => json_decode('"' . $nom_lieu . '"'),
                "adresse" => [
                    // Décode les chaînes JSON potentiellement encodées pour l'adresse.
                    "adresse" => json_decode('"' . $adresse . '"'),
                    // Décode les chaînes JSON potentiellement encodées pour le code postal.
                    "code_postal" => json_decode('"' . $code_postal . '"'),
                    // Décode les chaînes JSON potentiellement encodées pour la ville.
                    "ville" => json_decode('"' . $ville . '"'),
                ],
                // Décode la chaîne JSON potentiellement encodée pour le type de lieu.
                "type" => json_decode('"' . $type_lieu . '"'),
                // Convertit la valeur de 'est_evenement' en un booléen.
                "est_evenement" => (bool)$est_evenement,
                "position" => [
                    // Convertit et arrondit la latitude à 5 décimales.
                    "latitude" => round(floatval($row['latitude']), 5),
                    // Convertit et arrondit la longitude à 5 décimales.
                    "longitude" => round(floatval($row['longitude']), 5),
                    // Convertit et arrondit la distance à 5 décimales.
                    "distance_km" => round(floatval($row['distance']), 5)
                ],
                // Explose la chaîne des équipements en un tableau, en supprimant les espaces blancs autour de chaque équipement.
                "equipements" => array_map('trim', explode(',', $equipements))
            ];
            // Ajoute le lieu formaté au tableau principal des lieux.
            $tableauLieux['lieux'][] = $unLieux;
        }

        // Envoie un code de réponse HTTP 200 (OK) indiquant que la requête a réussi.
        http_response_code(200);
        // Retourne le tableau des lieux au format JSON, en s'assurant que les caractères Unicode sont correctement encodés.
        echo json_encode($tableauLieux, JSON_UNESCAPED_UNICODE);
    } else {
        // Si aucun lieu n'a été trouvé, on envoie un code de réponse HTTP 404 (Not Found).
        http_response_code(404);
        // Et on retourne un message JSON indiquant qu'aucun lieu n'a été trouvé.
        echo json_encode(["message" => "Aucun lieu trouvé."]);
    }
} else {
    // --- Gestion des Méthodes HTTP Non Autorisées ---

    // Si la méthode de la requête HTTP n'est pas GET, on envoie un code de réponse HTTP 405 (Method Not Allowed).
    http_response_code(405);

    // Et on retourne un message JSON indiquant que la méthode n'est pas autorisée pour cet endpoint.
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}

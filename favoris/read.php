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

// Vérification de l'authentification
$donnees_utilisateur = verifierAuthentification();

if (!$donnees_utilisateur) {
    http_response_code(401);
    echo json_encode(["message" => "Accès non autorisé. Veuillez vous connecter."]);
    exit;
}

// Vérification du niveau d'autorisation (grade 1 minimum pour ajouter un commentaire)
if (!verifierAutorisation($donnees_utilisateur, 1)) {
    http_response_code(403);
    echo json_encode(["message" => "Vous n'avez pas les droits suffisants pour effectuer cette action."]);
    exit;
}

// --- Vérification de la Méthode HTTP ---

// On s'assure que la requête HTTP reçue par le serveur est bien de type GET.
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // --- Inclusion des Fichiers Nécessaires ---
    include_once '../config/Database.php';
    include_once '../models/Favoris.php';

    // --- Instanciation des Objets ---

    // Crée une nouvelle instance de la classe Database pour établir une connexion à la base de données.
    $database = new Database();
    $db = $database->getConnexion();

    // Crée une nouvelle instance de la classe Lieux, en passant l'objet de connexion à la base de données comme dépendance.
    $favoris = new Favoris($db);

    $favoris->id_user = $donnees_utilisateur['id'];

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

    
    $stmt = $favoris->read($latitude, $longitude);

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
                "nom" => json_decode('"' . $nom_lieu . '"'),
                "horaires" => json_decode('"' . $horaires . '"'),
                "adresse" => [
                    "adresse" => json_decode('"' . $adresse . '"'),
                    "code_postal" => json_decode('"' . $code_postal . '"'),
                    "ville" => json_decode('"' . $ville . '"'),
                ],
                "type" => json_decode('"' . $type_lieu . '"'),
                "est_evenement" => (bool)$est_evenement,
                "position" => [
                    "latitude" => round(floatval($row['latitude']), 5),
                    "longitude" => round(floatval($row['longitude']), 5),
                    "distance_km" => round(floatval($row['distance']), 5)
                ],
                "equipements" => array_map('trim', explode(',', $equipements)),
                "ages" => array_map('trim', explode(',', $tranches_age))

            ];
            // Ajoute le lieu formaté au tableau principal des lieux.
            $tableauLieux['lieux'][] = $unLieux;
        }

        // Envoie un code de réponse HTTP 200 (OK) indiquant que la requête a réussi.
        http_response_code(200);
        // Retourne le tableau des lieux au format JSON, en s'assurant que les caractères Unicode sont correctement encodés.
        echo json_encode($tableauLieux, JSON_UNESCAPED_UNICODE);
    } else {
        echo $favoris->id_user;
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
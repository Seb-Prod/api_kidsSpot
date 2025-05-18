<?php
/**
 * API Endpoint pour l'authentification des utilisateurs (POST).
 *
 * Retourne un token JWT en cas de connexion réussie.
 */

/**
 * Configuration des Headers HTTP pour autoriser les requêtes CORS
 * et définir le type de contenu.
 */
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

/**
 * Vérifie si la méthode de la requête est POST.
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    /**
     * Inclusion des fichiers nécessaires :
     * - Connexion à la base de données
     * - Modèle utilisateur
     * - Classe JWT pour générer un token
     */
    include_once '../config/Database.php';
    include_once '../models/Users.php';
    include_once '../config/JWT.php';

    /**
     * Création de la connexion à la base de données.
     */
    $database = new Database();
    $db = $database->getConnexion();

    /**
     * Création d'une instance de la classe Users.
     */
    $user = new Users($db);

    /**
     * Décodage des données JSON reçues dans le corps de la requête.
     */
    $donnees = json_decode(file_get_contents("php://input"));

    /**
     * Validation de la présence de l'email et du mot de passe.
     */
    if (!empty($donnees->mail) && !empty($donnees->mot_de_passe)) {

        /**
         * Recherche de l'utilisateur en base via son e-mail.
         */
        $stmt = $user->rechercherParEmail($donnees->mail);

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $id = $row['id'];
            $email = $row['mail'];
            $mot_de_passe_hash = $row['mot_de_passe'];
            $grade = $row['grade'];
            $compte_verrouille = $row['compte_verrouille'];
            $pseudo = $row['pseudo'];

            /**
             * Vérifie si le compte utilisateur est verrouillé.
             */
            if ($compte_verrouille) {
                http_response_code(401);
                echo json_encode([
                    "message" => "Compte verrouillé. Veuillez utiliser la fonction de réinitialisation de mot de passe."
                ]);
                exit;
            }

            /**
             * Vérifie que le mot de passe fourni correspond au hash stocké.
             */
            if (password_verify($donnees->mot_de_passe, $mot_de_passe_hash)) {
                /**
                 * Réinitialise les tentatives de connexion et met à jour la dernière connexion.
                 */
                $user->id = $id;
                $user->tentatives_connexion = 0;
                $user->derniere_connexion = date('Y-m-d H:i:s');
                $user->modifier();

                /**
                 * Génère un token JWT avec les données de l'utilisateur.
                 */
                $config = require '../config/config.php';
                $jwt = new JWT($config);
                $token = $jwt->generer([
                    "id" => $id,
                    "email" => $email,
                    "grade" => $grade
                ]);

                /**
                 * Envoie la réponse avec le token et les informations utilisateur.
                 */
                http_response_code(200);
                echo json_encode([
                    "message" => "Connexion réussie",
                    "token" => $token,
                    "grade" => $grade,
                    "pseudo" => $pseudo,
                    "expiresIn" => 3600
                ]);
            } else {
                /**
                 * Si le mot de passe est incorrect, incrémente les tentatives de connexion.
                 */
                $user->id = $id;
                $user->tentatives_connexion = $row['tentatives_connexion'] + 1;

                /**
                 * Verrouille le compte après 5 tentatives échouées (exemple).
                 */
                if ($user->tentatives_connexion >= 5) {
                    $user->compte_verrouille = true;
                    $user->date_verrouillage = date('Y-m-d H:i:s');
                }

                $user->modifier();

                http_response_code(401);
                echo json_encode(["message" => "Email ou mot de passe incorrect"]);
            }
        } else {
            /**
             * Si aucun utilisateur n'est trouvé avec cet email.
             */
            http_response_code(401);
            echo json_encode(["message" => "Email ou mot de passe incorrect"]);
        }
    } else {
        /**
         * Si des données sont manquantes dans la requête.
         */
        http_response_code(400);
        echo json_encode(["message" => "Données incomplètes"]);
    }
} else {
    /**
     * Si la méthode HTTP utilisée n’est pas POST.
     */
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
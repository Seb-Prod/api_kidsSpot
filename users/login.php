<?php
/**
 * @file
 * API Endpoint pour l'authentification des utilisateurs.
 *
 * Retourne un token JWT en cas de connexion réussie.
 */

// Configuration des Headers HTTP
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Vérification de la Méthode HTTP
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Inclusion des Fichiers Nécessaires
    include_once '../config/Database.php';
    include_once '../models/Users.php';
    include_once '../config/JWT.php';

    // Instanciation des Objets
    $database = new Database();
    $db = $database->getConnexion();
    $user = new Users($db);

    // Récupération des Données Envoyées
    $donnees = json_decode(file_get_contents("php://input"));

    // Validation des Données Reçues
    if (!empty($donnees->mail) && !empty($donnees->mot_de_passe)) {
        // Recherche de l'utilisateur par son email
        $stmt = $user->rechercherParEmail($donnees->mail);
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $id = $row['id'];
            $email = $row['mail'];
            $mot_de_passe_hash = $row['mot_de_passe'];
            $grade = $row['grade'];
            $compte_verrouille = $row['compte_verrouille'];
            
            // Vérifier si le compte est verrouillé
            if ($compte_verrouille) {
                http_response_code(401);
                echo json_encode(["message" => "Compte verrouillé. Veuillez utiliser la fonction de réinitialisation de mot de passe."]);
                exit;
            }
            
            // Vérifier le mot de passe
            if (password_verify($donnees->mot_de_passe, $mot_de_passe_hash)) {
                // Réinitialiser les tentatives de connexion
                $user->id = $id;
                $user->tentatives_connexion = 0;
                $user->derniere_connexion = date('Y-m-d H:i:s');
                $user->modifier();
                
                // Générer le token JWT
                $config = require '../config/config.php';
                $jwt = new JWT($config);
                $token = $jwt->generer([
                    "id" => $id,
                    "email" => $email,
                    "grade" => $grade
                ]);
                
                // Réponse avec le token
                http_response_code(200);
                echo json_encode([
                    "message" => "Connexion réussie",
                    "token" => $token,
                    "expiresIn" => 3600
                ]);
            } else {
                // Incrémenter les tentatives de connexion
                $user->id = $id;
                $user->tentatives_connexion = $row['tentatives_connexion'] + 1;
                
                // Vérifier si on doit verrouiller le compte (par exemple, après 5 tentatives)
                if ($user->tentatives_connexion >= 5) {
                    $user->compte_verrouille = true;
                    $user->date_verrouillage = date('Y-m-d H:i:s');
                }
                
                $user->modifier();
                
                http_response_code(401);
                echo json_encode(["message" => "Email ou mot de passe incorrect"]);
            }
        } else {
            http_response_code(401);
            echo json_encode(["message" => "Email ou mot de passe incorrect"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Données incomplètes"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
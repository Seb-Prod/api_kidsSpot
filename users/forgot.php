<?php
/**
 * @file
 * Endpoint pour la demande de réinitialisation de mot de passe.
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once '../config/Database.php';
    include_once '../models/Users.php';
    include_once '../middleware/Mailer.php'; // contient la fonction envoyerEmail()

    $database = new Database();
    $db = $database->getConnexion();
    $user = new Users($db);

    $donnees = json_decode(file_get_contents("php://input"));
    
    if (!empty($donnees->mail)) {
        $email = $donnees->mail;

        // Vérifie que l'email existe
        if (!$user->emailExists($email)) {
            http_response_code(404);
            echo json_encode(["message" => "Aucun utilisateur trouvé avec cet e-mail."]);
            exit;
        }

        // Récupère l'utilisateur (pour son ID)
        $stmt = $user->rechercherParEmail($email);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $id_user = $row['id'];

        $result = $user->genererTokenReinitialisation($id_user);
        if (!$result) {
            http_response_code(500);
            echo json_encode(["message" => "Impossible de générer le token."]);
            exit;
        }
        
        $token = $result['token'];
        $lien = "https://votre-domaine.com/reset_password.php?token=" . urlencode($token);
        
        $html = "<p>Voici votre code de réinitialisation : <strong>$token</strong></p>
                 <p>Ce code est valable pendant 20 minutes.</p>";
        $texte = "Votre code de réinitialisation est : $token (valide 20 minutes)";

        // Envoi du mail
        if (envoyerEmail($email, "Mot de passe oublié", $html, $texte)) {
            echo json_encode(["message" => "Un e-mail de réinitialisation a été envoyé."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Échec de l'envoi de l'e-mail."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["message" => "L'e-mail est requis."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["message" => "Méthode non autorisée."]);
}
<?php
/**
 * Vérification de l'user et du grade
 *
 * @param array|boolean Donnees_utilisateur
 * @param integer niveau requis
 * @return void
 */
function validateUserAutorisation($donnees_utilisateur, $niveau_requis) {
    if (!$donnees_utilisateur) {
        http_response_code(401);
        echo json_encode(["message" => "Accès non autorisé. Veuillez vous connecter."]);
        exit;
    }

    if (!verifierAutorisation($donnees_utilisateur, $niveau_requis)) {
        http_response_code(403);
        echo json_encode(["message" => "Vous n'avez pas les droits suffisants pour effectuer cette action."]);
        exit;
    }
}
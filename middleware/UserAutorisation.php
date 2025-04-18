<?php

/**
 * Vérifie l'existence et le niveau d'autorisation d'un utilisateur.
 *
 * Cette fonction prend en entrée les informations de l'utilisateur (provenant
 * généralement d'une vérification de token ou de session) et le niveau
 * d'autorisation requis pour accéder à une ressource ou effectuer une action.
 * Si l'utilisateur n'est pas connecté ou ne possède pas le niveau d'autorisation
 * suffisant, la fonction envoie une réponse HTTP appropriée (401 ou 403) au client
 * et termine l'exécution du script.
 *
 * @param array|bool $donnees_utilisateur Un tableau associatif contenant les
 * informations de l'utilisateur, incluant potentiellement son niveau
 * d'autorisation (par exemple, sous la clé 'grade'). Peut également
 * être `false` si l'utilisateur n'est pas authentifié.
 * @param int $niveau_requis Un entier représentant le niveau d'autorisation minimal
 * requis pour accéder à la ressource ou effectuer l'action. La structure
 * et la signification des niveaux d'autorisation sont spécifiques à
 * l'application.
 * @return void La fonction ne retourne rien. Elle termine l'exécution du script
 * et envoie une réponse HTTP en cas d'échec de l'autorisation.
 */
function validateUserAutorisation($donnees_utilisateur, $niveau_requis)
{
    // Vérifier si les données de l'utilisateur sont présentes (l'utilisateur est connecté).
    if (!$donnees_utilisateur) {
        // Si les données de l'utilisateur sont manquantes (false), renvoyer une erreur 401 (Non autorisé).
        http_response_code(401);
        echo json_encode(["message" => "Accès non autorisé. Veuillez vous connecter."]);
        exit; // Terminer l'exécution du script après avoir envoyé la réponse.
    }

    // Vérifier si l'utilisateur possède le niveau d'autorisation requis.
    // La fonction 'verifierAutorisation' (non définie dans ce snippet) est supposée
    // prendre les données de l'utilisateur et le niveau requis en entrée et
    // retourner true si l'utilisateur est autorisé, false sinon.
    if (!verifierAutorisation($donnees_utilisateur, $niveau_requis)) {
        // Si l'utilisateur n'a pas le niveau d'autorisation suffisant, renvoyer une erreur 403 (Interdit).
        http_response_code(403);
        echo json_encode(["message" => "Vous n'avez pas les droits suffisants pour effectuer cette action."]);
        exit; // Terminer l'exécution du script après avoir envoyé la réponse.
    }
}

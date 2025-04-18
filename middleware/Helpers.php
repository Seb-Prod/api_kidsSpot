<?php

/**
 * Parse une chaîne de valeurs séparées par des virgules en un tableau.
 *
 * Cette fonction prend une chaîne de caractères en entrée, la divise en utilisant
 * la virgule comme délimiteur, supprime les espaces blancs au début et à la fin
 * de chaque élément, et filtre les éléments vides pour retourner un tableau
 * contenant les valeurs non vides.
 *
 * @param string $value La chaîne de caractères contenant des valeurs séparées par des virgules.
 * @return array Un tableau de chaînes de caractères, où chaque élément est une des
 * valeurs de la chaîne d'entrée après avoir été nettoyé des espaces et
 * les éléments vides ont été supprimés.
 */
function parseCommaSeparated($value): array {
    return array_filter(array_map('trim', explode(',', $value)));
}

/**
 * Convertit une date du format français (jj/mm/aaaa) vers le format américain (aaaa/mm/jj).
 *
 * Cette fonction prend une chaîne de caractères représentant une date au format
 * français `jj/mm/aaaa` et tente de la convertir au format américain `aaaa/mm/jj`.
 * Elle effectue une vérification du format d'entrée avant de procéder à la
 * conversion. En cas de format incorrect ou d'erreur lors de l'analyse de la date,
 * la fonction retourne `null`.
 *
 * @param string $dateFr La chaîne de caractères représentant la date au format français (jj/mm/aaaa).
 * @return string|null La date convertie au format américain (aaaa/mm/jj) en cas de succès,
 * ou `null` si le format de la date d'entrée est incorrect ou si une
 * erreur de parsing survient.
 */
function convertirDateFrancaisVersUs(string $dateFr): ?string {
    // Vérifie si la date entrée correspond au format attendu jj/mm/aaaa à l'aide d'une expression régulière.
    if (!preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dateFr)) {
        return null; // Retourne null si le format de la date n'est pas celui attendu.
    }

    // Utilise la classe DateTime et sa méthode statique createFromFormat pour analyser la date française
    // en spécifiant le format d'entrée 'd/m/Y' (jour/mois/année).
    $dateObjet = DateTime::createFromFormat('d/m/Y', $dateFr);

    // Vérifie si la création de l'objet DateTime a réussi. Si la chaîne de date ne correspond pas
    // au format spécifié, createFromFormat retourne false.
    if ($dateObjet === false) {
        return null; // Retourne null en cas d'échec de l'analyse de la date.
    }

    // Si l'analyse a réussi, formate la date au format américain aaaa/mm/jj en utilisant la méthode format('Y/m/d').
    return $dateObjet->format('Y/m/d');
}
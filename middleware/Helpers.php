<?php

function parseCommaSeparated($value) {
    return array_filter(array_map('trim', explode(',', $value)));
}

function convertirDateFrancaisVersUs(string $dateFr): ?string {
    // Vérifie si la date entrée est au format jj/mm/aaaa
    if (!preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dateFr)) {
        return null; // Retourne null si le format n'est pas correct
    }

    // Utilise la fonction DateTime::createFromFormat pour analyser la date française
    $dateObjet = DateTime::createFromFormat('d/m/Y', $dateFr);

    // Vérifie si la création de l'objet DateTime a réussi
    if ($dateObjet === false) {
        return null; // Retourne null en cas d'erreur de parsing
    }

    // Formate la date au format américain aaaa-mm-jj
    return $dateObjet->format('Y/m/d');
}
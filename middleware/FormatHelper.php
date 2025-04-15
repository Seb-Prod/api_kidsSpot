<?php
class FormatHelper {

public static function commentaire(array $row): array {
    return [
        "id" => (int)$row['id_commentaire'],
        "commentaire" => html_entity_decode($row['commentaire']),
        "note" => (int)($row['note']),
        "date" => [
            "ajout" => html_entity_decode($row['date_ajout']),
            "modification" => html_entity_decode($row['date_modification']),
        ],
        "user" => [
            "id" => (int)($row['id_user']),
            "pseudo" => html_entity_decode($row['pseudo_user']),
        ],
        "lieu" => [
            "id" => (int)html_entity_decode($row['id_lieu']),
            "nom" => html_entity_decode($row['nom_lieu']),
        ]
    ];
}

}
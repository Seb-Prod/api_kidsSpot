<?php
class FormatHelper
{

    public static function commentaire(array $row): array
    {
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

    public static function lieuLight(array $row): array
    {
        return [
            "id" => (int)$row['id_lieu'],
                "nom" => html_entity_decode($row['nom_lieu']),
                "horaires" => html_entity_decode($row['horaires']),
                "adresse" => [
                    "adresse" => html_entity_decode($row['adresse']),
                    "code_postal" => html_entity_decode($row['code_postal']),
                    "ville" => html_entity_decode($row['ville']),
                ],
                "type" => html_entity_decode($row['type_lieu']),
                "est_evenement" => (bool)$row['est_evenement'],
                "date_evenement" => [
                        "debut" => $row['date_debut'],
                        "fin" => $row['date_fin']
                    ],
                "position" => [
                    "latitude" => round((float)$row['latitude'], 5),
                    "longitude" => round((float)$row['longitude'], 5),
                    "distance_km" => round((float)$row['distance'], 5)
                ],
                "equipements" => parseCommaSeparated($row['equipements']),
                "ages" => parseCommaSeparated($row['tranches_age'])
        ];
    }
}

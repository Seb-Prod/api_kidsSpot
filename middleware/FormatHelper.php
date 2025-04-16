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
            "type" => is_string($row['type_lieu']) && $row['type_lieu'][0] === '{' 
                ? json_decode($row['type_lieu'], true) 
                : ['id' => null, 'nom' => html_entity_decode($row['type_lieu'])],
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
            "equipements" => self::parseCommaSeparated($row['equipements']),
            "ages" => self::parseCommaSeparated($row['tranches_age'])
        ];
    }

    private static function parseCommaSeparated($data) {
        if (empty($data)) {
            return [];
        }
    
        if ($data[0] === '{') {
            $items = explode(',', $data);
            $result = [];
    
            foreach ($items as $item) {
                $jsonObj = json_decode($item, true);
                if ($jsonObj) {
                    $result[] = [
                        'id' => (int)$jsonObj['id'],
                        'nom' => html_entity_decode($jsonObj['nom'])
                    ];
                }
            }
    
            return $result;
        } else {
            return array_map(function($item) {
                return [
                    'id' => null,
                    'nom' => html_entity_decode(trim($item))
                ];
            }, explode(',', $data));
        }
    }
}


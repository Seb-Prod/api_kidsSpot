<?php
class FormatHelper
{
    public static function commentaire(array $row): array
    {
        return [
            "id" => (int)($row['id_commentaire'] ?? 0),
            "commentaire" => self::safeJsonDecode($row['commentaire'] ?? ''),
            "note" => (int)($row['note'] ?? 0),
            "date" => [
                "ajout" => self::safeJsonDecode($row['date_ajout'] ?? ''),
                "modification" => self::safeJsonDecode($row['date_modification'] ?? ''),
            ],
            "user" => [
                "id" => (int)($row['id_user'] ?? 0),
                "pseudo" => self::safeJsonDecode($row['pseudo_user'] ?? ''),
            ],
            "lieu" => [
                "id" => (int)($row['id_lieu'] ?? 0),
                "nom" => self::safeJsonDecode($row['nom_lieu'] ?? ''),
            ]
        ];
    }

    public static function lieuLight(array $row): array
    {
        return [
            "id" => (int)($row['id_lieu'] ?? 0),
            "nom" => self::safeJsonDecode($row['nom_lieu'] ?? ''),
            "horaires" => self::safeJsonDecode($row['horaires'] ?? ''),
            "adresse" => [
                "adresse" => self::safeJsonDecode($row['adresse'] ?? ''),
                "code_postal" => self::safeJsonDecode($row['code_postal'] ?? ''),
                "ville" => self::safeJsonDecode($row['ville'] ?? ''),
            ],
            "type" => is_string($row['type_lieu'] ?? '') && ($row['type_lieu'][0] ?? '') === '{'
                ? json_decode($row['type_lieu'], true)
                : ['id' => null, 'nom' => self::safeJsonDecode($row['type_lieu'] ?? '')],
            "est_evenement" => (bool)($row['est_evenement'] ?? false),
            "date_evenement" => [
                "debut" => $row['date_debut'] ?? null,
                "fin" => $row['date_fin'] ?? null
            ],
            "position" => [
                "latitude" => round((float)($row['latitude'] ?? 0), 5),
                "longitude" => round((float)($row['longitude'] ?? 0), 5),
                "distance_km" => round((float)($row['distance'] ?? 0), 5)
            ],
            "equipements" => self::parseCommaSeparated($row['equipements'] ?? ''),
            "ages" => self::parseCommaSeparated($row['tranches_age'] ?? '')
        ];
    }

    public static function lieuDetail(array $row): array
    {
        return [
            "id" => (int)($row['id_lieu'] ?? 0),
            "nom" => self::safeJsonDecode($row['nom_lieu'] ?? ''),
            "description" => self::safeJsonDecode($row['description'] ?? ''),
            "horaires" => self::safeJsonDecode($row['horaires'] ?? ''),
            "adresse" => [
                "adresse" => self::safeJsonDecode($row['adresse'] ?? ''),
                "ville" => self::safeJsonDecode($row['ville'] ?? ''),
                "code_postal" => self::safeJsonDecode($row['code_postal'] ?? ''),
                "telephone" => self::safeJsonDecode($row['telephone'] ?? ''),
                "site_web" => self::safeJsonDecode($row['site_web'] ?? ''),
            ],
            "type" => is_string($row['type_lieu'] ?? '') && ($row['type_lieu'][0] ?? '') === '{'
                ? json_decode($row['type_lieu'], true)
                : ['id' => null, 'nom' => self::safeJsonDecode($row['type_lieu'] ?? '')],
            "est_evenement" => (bool)($row['est_evenement'] ?? false),
            "date_evenement" => [
                "debut" => $row['date_debut'] ?? null,
                "fin" => $row['date_fin'] ?? null
            ],
            "position" => [
                "latitude" => round((float)($row['latitude'] ?? 0), 5),
                "longitude" => round((float)($row['longitude'] ?? 0), 5)
            ],
            "equipements" => self::decodeJsonArray($row['equipements'] ?? ''),
            "ages" => self::decodeJsonArray($row['tranches_age'] ?? ''),
            "commentaires" => self::decodeJsonArray($row['commentaires'] ?? ''),
            "note_moyenne" => round((float)($row['note_moyenne'] ?? 0), 2),
            "nombre_commentaires" => (int)($row['nombre_commentaires'] ?? 0)
        ];
    }

    private static function parseCommaSeparated($data): array
    {
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
                        'id' => (int)($jsonObj['id'] ?? 0),
                        'nom' => html_entity_decode($jsonObj['nom'] ?? '')
                    ];
                }
            }

            return $result;
        } else {
            return array_map(function ($item) {
                return [
                    'id' => null,
                    'nom' => html_entity_decode(trim($item))
                ];
            }, explode(',', $data));
        }
    }

    private static function decodeJsonArray(string $json): array
    {
        if (empty($json)) {
            return [];
        }

        $decoded = json_decode('[' . $json . ']', true);

        return is_array($decoded) ? $decoded : [];
    }

    private static function safeJsonDecode(string $value)
    {
        $decoded = json_decode('"' . $value . '"', true);
        return $decoded === null ? html_entity_decode($value) : $decoded;
    }
}

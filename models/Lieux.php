<?php
class Lieux
{
    // connexion
    private $connexion;

    // object properties
    public $id;

    public function __construct($db)
    {
        $this->connexion = $db;
    }

    public function obtenirLieu($id)
    {
        $sql = "SELECT
        l.id AS id_lieu,
        l.nom AS nom_lieu,
        l.description,
        l.latitude,
        l.longitude,
        l.adresse,
        l.ville,
        l.code_postal,
        l.telephone,
        site_web,
        t.nom AS type_lieu,
        CASE WHEN e.id IS NOT NULL THEN 1 ELSE 0 END AS est_evenement,
        GROUP_CONCAT(DISTINCT te.nom SEPARATOR ', ') AS equipements,
        e.date_debut,
        e.date_fin
    FROM
        lieux l
    JOIN
        types_lieux t ON l.id_type = t.id
    LEFT JOIN
        lieux_equipement le ON l.id = le.id_lieux
    LEFT JOIN
        types_equipement te ON le.id_equipement = te.id
    LEFT JOIN
        evenements e ON l.id = e.id_lieux
    WHERE
        l.id = :id
    GROUP BY
        l.id, e.id";

        $query = $this->connexion->prepare($sql);
        $query->bindParam(':id', $id);
        $query->execute();
        return $query;
    }

    public function obtenirLieuxAutour($latitude, $longitude)
    {
        $sql = "SELECT 
            l.id AS id_lieu, 
            l.nom AS nom_lieu, 
            l.latitude,
            l.longitude,
            l.adresse,
            l.ville, 
            l.code_postal,
            t.nom AS type_lieu, 
            CASE WHEN e.id IS NOT NULL THEN 1 ELSE 0 END AS est_evenement, 
            GROUP_CONCAT(DISTINCT te.nom SEPARATOR ', ') AS equipements, 
            e.date_debut, 
            e.date_fin,
            (
                6371 * acos(
                    cos(radians(:latitude)) * 
                    cos(radians(l.latitude)) * 
                    cos(radians(l.longitude) - radians(:longitude)) + 
                    sin(radians(:latitude)) * 
                    sin(radians(l.latitude))
                )
            ) AS distance
        FROM 
            lieux l 
        JOIN 
            types_lieux t ON l.id_type = t.id 
        LEFT JOIN 
            lieux_equipement le ON l.id = le.id_lieux 
        LEFT JOIN 
            types_equipement te ON le.id_equipement = te.id 
        LEFT JOIN 
            evenements e ON l.id = e.id_lieux 
        GROUP BY 
            l.id, e.id 
        ORDER BY 
            distance ASC";

        $query = $this->connexion->prepare($sql);
        $query->bindParam(':latitude', $latitude);
        $query->bindParam(':longitude', $longitude);
        $query->execute();
        return $query;
    }
}

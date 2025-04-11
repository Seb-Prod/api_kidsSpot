<?php
class Lieux
{
    // connexion
    private $connexion;

    // object properties
    public $id;
    public $nom;
    public $description;
    public $adresse;
    public $ville;
    public $code_postal;
    public $latitude;
    public $longitude;
    public $telephone;
    public $site_web;
    public $date_creation;
    public $date_modification;
    public $id_type;

    public function __construct($db)
    {
        $this->connexion = $db;
    }

    /**
     * Obtenir un lieu par son identifiant unique
     *
     * @param [type] $id L'idendifiant unique du lieu à récupérer
     * @return PDOStatement|false Un objet PDOStatement si la requête s'exécute correctement, ou `false` en cas d'erreur
     */
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
        
        try {
            $query->execute();
            return $query;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Obtenir une liste des lieux situés autour d'une latitude et d'une longitude données, triés par distance croissante.
     * 
     * @param float $latitude La latitude du point central autour duquel rechercher les lieux.
     * @param float $longitude La longitude du point central autour duquel rechercher les lieux.
     * @return PDOStatement|false Un objet PDOStatement si la requête s'exécute correctement, ou `false` en cas d'erreur
     */
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
        
        try {
            $query->execute();
            return $query;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Créer un nouveau lieu dans la base de données.
     *
     * @return bool Retourne `true` si l'insertion a réussi, `false` en cas d'échec.
     */
    public function creer()
    {
        $sql = "INSERT INTO lieux SET nom=:nom, description=:description, 
            adresse=:adresse, ville=:ville, code_postal=:code_postal, 
            latitude=:latitude, longitude=:longitude, telephone=:telephone, 
            site_web=:site_web, date_creation=:date_creation, 
            date_modification=:date_modification, id_type=:id_type";

        $query = $this->connexion->prepare($sql);

        // Nettoyage et sécurisation des données
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->adresse = htmlspecialchars(strip_tags($this->adresse));
        $this->ville = htmlspecialchars(strip_tags($this->ville));
        $this->code_postal = htmlspecialchars(strip_tags($this->code_postal));
        $this->latitude = htmlspecialchars(strip_tags($this->latitude));
        $this->longitude = htmlspecialchars(strip_tags($this->longitude));
        $this->telephone = htmlspecialchars(strip_tags($this->telephone));
        $this->site_web = htmlspecialchars(strip_tags($this->site_web));
        $this->date_creation = date('Y-m-d');
        $this->date_modification = date('Y-m-d');
        $this->id_type = htmlspecialchars(strip_tags($this->id_type));

        // Liaison des valeurs
        $query->bindParam(":nom", $this->nom);
        $query->bindParam(":description", $this->description);
        $query->bindParam(":adresse", $this->adresse);
        $query->bindParam(":ville", $this->ville);
        $query->bindParam(":code_postal", $this->code_postal);
        $query->bindParam(":latitude", $this->latitude);
        $query->bindParam(":longitude", $this->longitude);
        $query->bindParam(":telephone", $this->telephone);
        $query->bindParam(":site_web", $this->site_web);
        $query->bindParam(":date_creation", $this->date_creation);
        $query->bindParam(":date_modification", $this->date_modification);
        $query->bindParam(":id_type", $this->id_type);

        // Exécution de la requête
        if ($query->execute()) {
            return true;
        }

        return false;
    }
}

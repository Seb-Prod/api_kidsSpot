<?php

class Profil
{
    private $connexion;
    public $id;
    public $ages = [];
    public $equipements = [];

    public function __construct($db)
    {
        $this->connexion = $db;
    }

    public function create()
    {
        try {
            // Démarrer une transaction
            $this->connexion->beginTransaction();

            // 1. Supprimer les préférences d'âge existantes
            $sql_delete_ages = "DELETE FROM user_preference_age WHERE id_user = :id_user";
            $query_delete_ages = $this->connexion->prepare($sql_delete_ages);
            $query_delete_ages->bindParam(":id_user", $this->id);
            $query_delete_ages->execute();

            // 2. Insérer les nouvelles préférences d'âge
            if (!empty($this->ages)) {
                $sql_insert_age = "INSERT INTO user_preference_age (id_user, id_tranche_age) VALUES (:id_user, :id_age)";
                $query_insert_age = $this->connexion->prepare($sql_insert_age);

                foreach ($this->ages as $age_id) {
                    $age_id = htmlspecialchars(strip_tags($age_id));
                    $query_insert_age->bindParam(":id_user", $this->id);
                    $query_insert_age->bindParam(":id_age", $age_id);
                    $query_insert_age->execute();
                }
            }

            // 3. Supprimer les préférences d'équipement existantes
            $sql_delete_equip = "DELETE FROM user_preference_equipement WHERE id_user = :id_user";
            $query_delete_equip = $this->connexion->prepare($sql_delete_equip);
            $query_delete_equip->bindParam(":id_user", $this->id);
            $query_delete_equip->execute();

            // 4. Insérer les nouvelles préférences d'équipement
            if (!empty($this->equipements)) {
                $sql_insert_equip = "INSERT INTO user_preference_equipement (id_user, id_equipement) VALUES (:id_user, :id_equipement)";
                $query_insert_equip = $this->connexion->prepare($sql_insert_equip);

                foreach ($this->equipements as $equipement_id) {
                    $equipement_id = htmlspecialchars(strip_tags($equipement_id));
                    $query_insert_equip->bindParam(":id_user", $this->id);
                    $query_insert_equip->bindParam(":id_equipement", $equipement_id);
                    $query_insert_equip->execute();
                }
            }

            // Valider la transaction
            $this->connexion->commit();
            return true;
        } catch (PDOException $e) {
            // Annuler la transaction en cas d'erreur
            $this->connexion->rollBack();
            return false;
        }
    }

    public function update(){
        try {
            // Démarrer une transaction
            $this->connexion->beginTransaction();
            
            // Vérifier que l'ID utilisateur existe
            $sql_check = "SELECT id FROM users WHERE id = :id";
            $query_check = $this->connexion->prepare($sql_check);
            $query_check->bindParam(":id", $this->id);
            $query_check->execute();
            
            if ($query_check->rowCount() == 0) {
                return false; // L'utilisateur n'existe pas
            }
            
            // 1. Supprimer les préférences d'âge existantes
            $sql_delete_ages = "DELETE FROM user_preference_age WHERE id_user = :id_user";
            $query_delete_ages = $this->connexion->prepare($sql_delete_ages);
            $query_delete_ages->bindParam(":id_user", $this->id);
            $query_delete_ages->execute();
            
            // 2. Insérer les nouvelles préférences d'âge
            if (!empty($this->ages)) {
                $sql_insert_age = "INSERT INTO user_preference_age (id_user, id_tranche_age) VALUES (:id_user, :id_age)";
                $query_insert_age = $this->connexion->prepare($sql_insert_age);
                
                foreach ($this->ages as $age_id) {
                    $age_id = htmlspecialchars(strip_tags($age_id));
                    $query_insert_age->bindParam(":id_user", $this->id);
                    $query_insert_age->bindParam(":id_age", $age_id);
                    $query_insert_age->execute();
                }
            }
            
            // 3. Supprimer les préférences d'équipement existantes
            $sql_delete_equip = "DELETE FROM user_preference_equipement WHERE id_user = :id_user";
            $query_delete_equip = $this->connexion->prepare($sql_delete_equip);
            $query_delete_equip->bindParam(":id_user", $this->id);
            $query_delete_equip->execute();
            
            // 4. Insérer les nouvelles préférences d'équipement
            if (!empty($this->equipements)) {
                $sql_insert_equip = "INSERT INTO user_preference_equipement (id_user, id_equipement) VALUES (:id_user, :id_equipement)";
                $query_insert_equip = $this->connexion->prepare($sql_insert_equip);
                
                foreach ($this->equipements as $equipement_id) {
                    $equipement_id = htmlspecialchars(strip_tags($equipement_id));
                    $query_insert_equip->bindParam(":id_user", $this->id);
                    $query_insert_equip->bindParam(":id_equipement", $equipement_id);
                    $query_insert_equip->execute();
                }
            }
            
            // Valider la transaction
            $this->connexion->commit();
            return true;
            
        } catch (PDOException $e) {
            // Annuler la transaction en cas d'erreur
            $this->connexion->rollBack();
            return false;
        }
    }

    public function read(){
        $sql = "SELECT * FROM vue_user_preference WHERE id_user = :id";

        $query = $this->connexion->prepare($sql);
        $query->bindParam(':id', $this->id);

        try {
            $query->execute();
            return $query;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function save() {
        try {
            // Vérifie s'il existe déjà des préférences âge ou équipement
            $sql = "SELECT COUNT(*) FROM user_preference_age WHERE id_user = :id_user";
            $query = $this->connexion->prepare($sql);
            $query->bindParam(":id_user", $this->id);
            $query->execute();
            $age_count = $query->fetchColumn();
    
            $sql2 = "SELECT COUNT(*) FROM user_preference_equipement WHERE id_user = :id_user";
            $query2 = $this->connexion->prepare($sql2);
            $query2->bindParam(":id_user", $this->id);
            $query2->execute();
            $equip_count = $query2->fetchColumn();
    
            if ($age_count > 0 || $equip_count > 0) {
                return $this->update();  // déjà des préférences → update
            } else {
                return $this->create();  // aucune préférence → create
            }
    
        } catch (PDOException $e) {
            return false;
        }
    }
}

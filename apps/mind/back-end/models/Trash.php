<?php
class Trash extends ActiveRecord {
    public $table_name;
    public $item_id;
    public $user_id;

    public function __construct($args = []) {
        $this->table_name = $args["table_name"] ?? "";
        $this->item_id = $args["item_id"] ?? "";
        $this->user_id = $args["user_id"] ?? "";
    }

    public function moveToTrash(){
        $query = "UPDATE $this->table_name SET row_status = 0 WHERE id = ? AND user_id = ?";
        $params = [$this->item_id, $this->user_id];
        $stmt = self::$db->prepare($query);
        if (!$stmt || !$stmt->execute($params)) { 
            return false; 
        }
        return true;
    }

    public function recoverFromTrash(){
        $query = "UPDATE $this->table_name SET row_status = 1 WHERE id = ? AND user_id = ?";
        $params = [$this->item_id, $this->user_id];
        $stmt = self::$db->prepare($query);
        if (!$stmt || !$stmt->execute($params)) { 
            return false; 
        }
        return true;
    }

    public function getTrash(){
        if ($this->table_name === "appointments") {
            $query = "SELECT a.*, p.patient_name FROM appointments a 
                     JOIN patients p ON a.patient_id = p.id 
                     WHERE a.row_status = 0 AND a.user_id = ? ORDER BY a.appt_date DESC";
        } else {
            $query = "SELECT * FROM $this->table_name WHERE row_status = 0 AND user_id = ?";
        }
        
        $params = [$this->user_id];
        $stmt = self::$db->prepare($query);
        if (!$stmt || !$stmt->execute($params)) { 
            return false; 
        }
        
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getRowsCount(){
        $query = "SELECT COUNT(*) as total_rows FROM $this->table_name WHERE row_status = 0 AND user_id = ?";
        $params = [$this->user_id];
        $stmt = self::$db->prepare($query);
        if (!$stmt || !$stmt->execute($params)) { 
            return false; 
        }
        
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
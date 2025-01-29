<?php
class Patient extends ActiveRecord {
    protected static $table = "patients";
    protected static $columns = [
        "id",
        "user_id",
        "patient_name",
        "patient_birthdate",
        "patient_school",
        "patient_school_grade",
        "patient_contact_phone",
        "patient_contact_email",
        "patient_gender",
        "patient_status",
        "patient_notes",
        "patient_appt_price",
        "row_status"
    ];

    public $id;
    public $user_id;
    public $patient_name;

    public function __construct($args = []) {
        $this->id = $args["id"] ?? NULL;
        $this->user_id = $args["user_id"] ?? "";
        $this->patient_name = $args["patient_name"] ?? NULL;
        $this->patient_birthdate = $args["patient_birthdate"] ?? NULL;
        $this->patient_school = $args["patient_school"] ?? NULL;
        $this->patient_school_grade = $args["patient_school_grade"] ?? NULL;
        $this->patient_contact_phone = $args["patient_contact_phone"] ?? NULL;
        $this->patient_contact_email = $args["patient_contact_email"] ?? NULL;
        $this->patient_gender = $args["patient_gender"] ?? NULL;
        $this->patient_status = $args["patient_status"] ?? 1;
        $this->patient_notes = $args["patient_notes"] ?? NULL;
        $this->patient_appt_price = $args["patient_appt_price"] ?? NULL;
        $this->row_status = $args["row_status"] ?? 1;
    }

    public static function getRowsCount($user_id, $filters){
        $query = "SELECT COUNT(*) as count FROM patients WHERE user_id = ?";
        $params = [$user_id];

        if(!empty($filters["search"])){
            $query .= " AND patient_name LIKE ?";
            $params[] = "%".$filters["search"]."%";
        }
        if(isset($filters["row_status"])){
            $query .= " AND row_status = ?";
            $params[] = $filters["row_status"];
        }else{
            $query .= " AND row_status = 1";
        }

        $stmt = self::$db->prepare($query);
        if (!$stmt || !$stmt->execute($params)) { return false; }
        $result = $stmt->get_result();
        if (!$result) { return false;}
        return $result->fetch_assoc();
    }

    public static function getPatients($data_array) {
        $user_id = $data_array["user_id"];
        $filters = $data_array["filters"];
        $limit = $data_array["limit"];
        $offset = $data_array["offset"];
    
        $query = "SELECT * FROM patients WHERE user_id = ?";
        $params = [$user_id];
    
        if (!empty($filters["search"])) {
            $query .= " AND patient_name LIKE ?";
            $params[] = "%" . $filters["search"] . "%";
        }
    
        if (!empty($filters["status"]) && $filters["status"] !== 'all_status') {
            $query .= " AND patient_status = ?";
            $params[] = $filters["status"];
        }
    
        if (isset($filters["row_status"])) {
            $query .= " AND row_status = ?";
            $params[] = $filters["row_status"];
        } else {
            $query .= " AND row_status = 1";
        }
    
        if (!empty($filters["order"]) && !empty($filters["order_by"])) {
            $allowedColumns = ['patient_name', 'patient_birthdate', 'patient_status'];
            $allowedOrders = ['ASC', 'DESC'];
    
            $orderBy = in_array($filters["order_by"], $allowedColumns) ? $filters["order_by"] : 'patient_name';
            $order = in_array(strtoupper($filters["order"]), $allowedOrders) ? strtoupper($filters["order"]) : 'ASC';
    
            $query .= " ORDER BY " . $orderBy . " " . $order;
        } else {
            $query .= " ORDER BY patient_name ASC";
        }
    
        $query .= " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
    
        $stmt = self::$db->prepare($query);
        if (!$stmt) { return false; }
        if (!$stmt->execute($params)) { return false; }
        $result = $stmt->get_result();
        if (!$result) { return false; }
    
        $patients = $result->fetch_all(MYSQLI_ASSOC);
    
        // Desencriptar los campos encriptados
        foreach ($patients as &$patient) {
            if (!empty($patient['patient_school'])) {
                $patient['patient_school'] = Encrypt::decrypt($patient['patient_school']);
            }
            if (!empty($patient['patient_school_grade'])) {
                $patient['patient_school_grade'] = Encrypt::decrypt($patient['patient_school_grade']);
            }
            if (!empty($patient['patient_contact_phone'])) {
                $patient['patient_contact_phone'] = Encrypt::decrypt($patient['patient_contact_phone']);
            }
            if (!empty($patient['patient_contact_email'])) {
                $patient['patient_contact_email'] = Encrypt::decrypt($patient['patient_contact_email']);
            }
            if (!empty($patient['patient_gender'])) {
                $patient['patient_gender'] = Encrypt::decrypt($patient['patient_gender']);
            }
            if (!empty($patient['patient_notes'])) {
                $patient['patient_notes'] = Encrypt::decrypt($patient['patient_notes']);
            }
        }
    
        return $patients;
    }
    

    public static function getStats($data_array){
        $filters = $data_array["filters"] ?? [];
        $query = "SELECT 
            SUM(CASE WHEN patient_status = '1' THEN 1 ELSE 0 END) as count_active,
            SUM(CASE WHEN patient_status = '2' THEN 1 ELSE 0 END) as count_discharged,
            SUM(CASE WHEN patient_status = '3' THEN 1 ELSE 0 END) as count_inactive
            FROM patients WHERE user_id = ?";
        $params = [$data_array["user_id"]]; 

        if(!empty($filters["search"])){
            $query .= " AND patient_name LIKE ?";
            $params[] = "%".$filters["search"]."%";
        }
        if(isset($filters["row_status"])){
            $query .= " AND row_status = ?";
            $params[] = $filters["row_status"];
        }else{
            $query .= " AND row_status = 1";
        }

        $stmt = self::$db->prepare($query);
        if (!$stmt || !$stmt->execute($params)) { return false; }
        $result = $stmt->get_result();
        if (!$result) { return false;}
        return $result->fetch_assoc();
    }


}
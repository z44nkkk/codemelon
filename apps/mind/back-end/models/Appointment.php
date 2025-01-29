<?php
require_once("../helpers/Encrypt.php");
class Appointment extends ActiveRecord {
    protected static $table = "appointments";
    protected static $columns = [
        "id",
        "user_id",
        "patient_id",
        "appt_date",
        "appt_time",
        "appt_status",
        "appt_cost",
        "appt_concept",
        "appt_price",
        "appt_payment_status",
        "appt_mode",
        "row_status"
    ];

    public $id;
    public $user_id;
    public $patient_id;
    public $patient_name;
    public $appt_date;
    public $appt_time;
    public $appt_status;
    public $appt_cost;
    public $appt_concept;
    public $appt_price;
    public $appt_payment_status;
    public $appt_mode;
    public $row_status;

    public function __construct($args = []) {
        $this->id = $args["id"] ?? NULL;
        $this->user_id = $args["user_id"] ?? "";
        $this->patient_id = $args["patient_id"] ?? "";
        $this->patient_name = $args["patient_name"] ?? "";
        $this->appt_date = $args["appt_date"] ?? "";
        $this->appt_time = $args["appt_time"] ?? "";
        $this->appt_status = $args["appt_status"] ?? "";
        $this->appt_cost = $args["appt_cost"] ?? 0.00;
        $this->appt_concept = $args["appt_concept"] ?? "";
        $this->appt_price = $args["appt_price"] ?? 0;
        $this->appt_payment_status = $args["appt_payment_status"] ?? 1;
        $this->appt_mode = $args["appt_mode"] ?? 1;
        $this->row_status = $args["row_status"] ?? 1;
    }


    public static function getRowsCount($user_id, $filters){
        $query = "SELECT COUNT(*) as count FROM appointments WHERE user_id = ?";
        $params = [$user_id];

        if(!empty($filters["month"])){
            $months = explode(',', $filters["month"]);
            $placeholders = str_repeat('?,', count($months) - 1) . '?';
            $query .= " AND MONTH(appt_date) IN ($placeholders)";
            $params = array_merge($params, $months);
        }

        if(!empty($filters["year"])){
            $years = explode(',', $filters["year"]);
            $placeholders = str_repeat('?,', count($years) - 1) . '?';
            $query .= " AND YEAR(appt_date) IN ($placeholders)";
            $params = array_merge($params, $years);
        }

        if(!empty($filters["status"])){
            $statuses = explode(',', $filters["status"]);
            $placeholders = str_repeat('?,', count($statuses) - 1) . '?';
            $query .= " AND appt_status IN ($placeholders)";
            $params = array_merge($params, $statuses);
        }

        if(!empty($filters["patient"])){
            if($filters["patient"] === "all_patients"){
                $query .= " AND patient_id IS NOT NULL";
            } else {
                $query .= " AND patient_id = ?";
                $params[] = $filters["patient"];
            }
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

    public static function getAppts($data_array){
        $user_id = $data_array["user_id"];
        $filters = $data_array["filters"];
        $limit = $data_array["limit"] ?? 100;
        $offset = $data_array["offset"] ?? 0;

        // $query = "SELECT * FROM appointments WHERE user_id = ?";
        $query = "SELECT a.*, p.patient_name FROM appointments a LEFT JOIN patients p ON a.patient_id = p.id WHERE a.user_id = ?";
        $params = [$user_id];

        if(!empty($filters["month"])){
            $months = explode(',', $filters["month"]);
            $placeholders = str_repeat('?,', count($months) - 1) . '?';
            $query .= " AND MONTH(a.appt_date) IN ($placeholders)";
            $params = array_merge($params, $months);
        }

        if(!empty($filters["year"])){
            $years = explode(',', $filters["year"]);
            $placeholders = str_repeat('?,', count($years) - 1) . '?';
            $query .= " AND YEAR(a.appt_date) IN ($placeholders)";
            $params = array_merge($params, $years);
        }

        if(!empty($filters["status"])){
            $statuses = explode(',', $filters["status"]);
            $placeholders = str_repeat('?,', count($statuses) - 1) . '?';
            $query .= " AND a.appt_status IN ($placeholders)";
            $params = array_merge($params, $statuses);
        }

        if(!empty($filters["patient"])){
            if($filters["patient"] === "all_patients"){
                $query .= " AND a.patient_id IS NOT NULL";
            } else {
                $query .= " AND a.patient_id = ?";
                $params[] = $filters["patient"];
            }
        }

        if(isset($filters["row_status"])){
            $query .= " AND a.row_status = ?";
            $params[] = $filters["row_status"];
        }else{
            $query .= " AND a.row_status = 1";
        }

        $query .= " ORDER BY a.appt_date DESC, a.appt_time DESC";
        // $query .= " ORDER BY CONCAT(appt_date, ' ', appt_time) DESC";
        $query .= " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = self::$db->prepare($query);
        if (!$stmt) { return false; }
        if (!$stmt->execute($params)) { return false; }
        $result = $stmt->get_result();
        if (!$result) { return false; }

        $appointments = $result->fetch_all(MYSQLI_ASSOC);

        foreach ($appointments as &$appt) {  
            if (!empty($appt["appt_concept"])) {
                $appt["appt_concept"] = Encrypt::decrypt($appt["appt_concept"]);
            }
        }

        return $appointments;
        
        // $appointments = [];
        // while($row = $result->fetch_assoc()) {
        //     $appointments[] = new self($row);
        // }
        
        // return $appointments;
    }

    public static function getStats($data_array){
        $filters = $data_array["filters"] ?? [];
        $query = "SELECT 
            SUM(CASE WHEN appt_status != '3' THEN appt_price ELSE 0 END) as total_cost,
            SUM(CASE WHEN appt_status = '1' THEN 1 ELSE 0 END) as count_pending,
            SUM(CASE WHEN appt_status = '2' THEN 1 ELSE 0 END) as count_completed,
            SUM(CASE WHEN appt_status = '3' THEN 1 ELSE 0 END) as count_cancelled
            FROM appointments WHERE user_id = ?";
        $params = [$data_array["user_id"]];

        if(!empty($filters["month"])){
            $months = explode(',', $filters["month"]);
            $placeholders = str_repeat('?,', count($months) - 1) . '?';
            $query .= " AND MONTH(appt_date) IN ($placeholders)";
            $params = array_merge($params, $months);
        }

        if(!empty($filters["year"])){
            $years = explode(',', $filters["year"]);
            $placeholders = str_repeat('?,', count($years) - 1) . '?';
            $query .= " AND YEAR(appt_date) IN ($placeholders)";
            $params = array_merge($params, $years);
        }

        if(!empty($filters["status"])){
            $statuses = explode(',', $filters["status"]);
            $placeholders = str_repeat('?,', count($statuses) - 1) . '?';
            $query .= " AND appt_status IN ($placeholders)";
            $params = array_merge($params, $statuses);
        }

        if(!empty($filters["patient"])){
            if($filters["patient"] === "all_patients"){
                $query .= " AND patient_id IS NOT NULL";
            } else {
                $query .= " AND patient_id = ?";
                $params[] = $filters["patient"];
            }
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

    public static function checkAppointment($data_array){
        $query = "SELECT * FROM appointments WHERE user_id = ? AND appt_date = ? AND appt_time = ?";
        $params = [$data_array["user_id"], $data_array["appt_date"], $data_array["appt_time"]];
        if(isset($data_array["id"])){
            $query .= " AND id != ?";
            $params[] = $data_array["id"];
        }
        $stmt = self::$db->prepare($query);
        if (!$stmt || !$stmt->execute($params)) { return false; }
        $result = $stmt->get_result();
        if (!$result) { return false;}
        return $result->fetch_assoc();
    }
}
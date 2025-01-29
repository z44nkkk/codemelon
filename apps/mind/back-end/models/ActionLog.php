<?php
class ActionLog extends ActiveRecord {
    protected static $table = "action_logs";
    protected static $columns = [
        "id",
        "user_id",
        "owner_id",
        "action_id",
        "action_datetime",
        "target_id",
        "target_type",
        "changes", // this is gonna be a json
    ];

    public $id;
    public $user_id;
    public $owner_id;
    public $action_id;
    public $action_datetime;
    public $target_id;
    public $target_type;
    public $changes;

    public function __construct($args = []) {
        $this->id = $args["id"] ?? NULL;
        $this->user_id = $args["user_id"] ?? "";
        $this->owner_id = $args["owner_id"] ?? "";
        $this->action_id = $args["action_id"] ?? "";
        $this->action_datetime = $args["action_datetime"] ?? "";
        $this->target_id = $args["target_id"] ?? "";
        $this->target_type = $args["target_type"] ?? "";
        $this->changes = $args["changes"] ?? "";
    }


    /**
     * Saves a new action log entry with default values if not provided
     * @param array $log The log data containing user_id and other optional fields
     * @return bool True if save successful, false otherwise
     * @throws InvalidArgumentException If required fields are missing
     */
    public static function saveLog(array $log): bool {
        // Validate required field
        if (!isset($log['user_id']) || empty($log['user_id'])) {
            throw new InvalidArgumentException('user_id is required');
        }

        // Set default values using null coalescing operator
        $log['owner_id'] = $log['owner_id'] ?? $log['user_id'];
        $log['action_datetime'] = $log['action_datetime'] ?? date('Y-m-d H:i:s');
        $log['changes'] = $log['changes'] ?? '{}';
        $log['id'] = null;

        $actionLog = new self($log);
        $result = $actionLog->save();
        return $result["ok"] === true;
    }

    public static function getDifference($old_value, $new_value){
        // Convertir objetos a arrays
        $old_value = is_object($old_value) ? get_object_vars($old_value) : $old_value;
        $old_value = is_string($old_value) ? json_decode($old_value, true) : $old_value;
    
        // Eliminar claves específicas si existen
        if (isset($new_value['owner_id'])) unset($new_value['owner_id']);
        if (isset($new_value['action_id'])) unset($new_value['action_id']);
        
        // Eliminar claves vacías de old_value
        $old_value = array_filter($old_value, function($value) {
            return $value !== '' && $value !== null;
        });
        $new_value = array_filter($new_value, function($value) {
            return $value !== '' && $value !== null;
        });
    
        // Normalizar valores a string
        $normalized_old_value = array_map('strval', $old_value);
        $normalized_new_value = array_map('strval', $new_value);
        
        $difference_old_values = array_diff_assoc($normalized_old_value, $normalized_new_value);
        $difference_new_values = array_diff_assoc($normalized_new_value, $normalized_old_value);

        $response = [
            "old_values" => $difference_old_values,
            "new_values" => $difference_new_values
        ];

        return $response;
    }

    public static function getLog($data_array){
        $target_id = $data_array["id"];
        $user_id = $data_array["user_id"];
        $query = "SELECT * FROM action_logs WHERE target_id = ? AND user_id = ? LIMIT 1000";
        $stmt = self::$db->prepare($query);
        $stmt->bind_param("ii", $target_id, $user_id);
        if (!$stmt || !$stmt->execute()) { return false; }
        $result = $stmt->get_result();
        if (!$result) { return false;}
        $logs = [];
        while ($row = $result->fetch_assoc()) {
            $logs[] = new self($row);
        }
        return $logs;

    }

    
     
}
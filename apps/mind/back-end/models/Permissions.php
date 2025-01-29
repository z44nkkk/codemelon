<?php
class Permissions extends ActiveRecord {
    protected static $table = "permissions";
    protected static $columns = [
        "id",
        "user_id",
        "action_id",
        "owner_id"
    ];

    public $id;
    public $user_id;
    public $action_id;
    public $owner_id;

    public function __construct($args = []) {
        $this->id = $args["id"] ?? NULL;
        $this->user_id = $args["user_id"] ?? "";
        $this->action_id = $args["action_id"] ?? "";
        $this->owner_id = $args["owner_id"] ?? "";
    }


    private static function checkPermissionsTable($user_id, $action_id, $owner_id){
        $query = "SELECT * FROM " . static::$table . " WHERE user_id = ${user_id} AND action_id = ${action_id} AND owner_id = ${owner_id}";
        $result = self::querySQL($query);
        if(empty($result)){
            return false;
        }else{
            return true; 
        }
    }

    public static function validatePermissions(array $data){

        if($data["owner_id"] == null || !isset($data["owner_id"])){
            return true; 
            // If owner_id is null, permission is granted
        }
        // validate required fields
        try {

            if(!isset($data["user_id"]) || !isset($data["action_id"]) || !isset($data["owner_id"])){
                throw new InvalidArgumentException('user_id, action_id and owner_id are required');
            }

            // validate numeric fields
            if(!is_numeric($data["user_id"]) || !is_numeric($data["action_id"]) || !is_numeric($data["owner_id"])){
                throw new InvalidArgumentException('user_id, action_id and owner_id must be numeric');
            }

            // convert to integers
            $user_id = (int) $data["user_id"];
            $action_id = (int) $data["action_id"];
            $owner_id = (int) $data["owner_id"];

            // validate positive values
            if ($user_id <= 0 || $action_id <= 0 || $owner_id <= 0) {
                throw new InvalidArgumentException('IDs must be positive numbers');
            }

            if ($user_id === $owner_id) {
                return true;
            }
            
            return self::checkPermissionsTable($user_id, $action_id, $owner_id);
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    // public static function validatePermissions($user_id, $action_id, $owner_id){
    //     try {
    //         // Validación básica de entrada
    //         if (!is_numeric($user_id) || !is_numeric($action_id) || !is_numeric($owner_id)) {
    //             throw new InvalidArgumentException('Los IDs deben ser numéricos');
    //         }

    //         // Conversión a enteros
    //         $user_id = (int) $user_id;
    //         $action_id = (int) $action_id;
    //         $owner_id = (int) $owner_id;

    //         // Validación de valores positivos
    //         if ($user_id <= 0 || $action_id <= 0 || $owner_id <= 0) {
    //             throw new InvalidArgumentException('Los IDs deben ser positivos');
    //         }

    //         // Propietario accediendo a sus propios recursos
    //         if ($user_id === $owner_id) {
    //             return true;
    //         }

    //         // Verificar permisos para otros usuarios
    //         return self::checkPermissionsTable($user_id, $action_id, $owner_id);
    //     } catch (Exception $e) {
    //         // Log del error
    //         error_log($e->getMessage());
    //         return false;
    //     }
    // }
}
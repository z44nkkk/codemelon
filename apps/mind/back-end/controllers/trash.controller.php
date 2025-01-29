<?php
require_once("../config/connect.php");
require_once("../models/Trash.php");
require_once("../models/Appointment.php");
require_once("../models/Patient.php");
require_once("../../../../back-end/config/session.php");
require_once("../models/Permissions.php");
require_once("../models/ActionLog.php");
require_once("../helpers/Pagination.php");

$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

if(!isset($data["item_type"])){
    $response = [
        "success" => false,
        "message" => "Operation not specified",
    ];
    echo json_encode($response);
    exit();
}

$operation = "";
if($data["item_type"] === "appt"){
    $table_name = "appointments";
    $action_id = 8;
}
if($data["item_type"] === "patient"){
    $table_name = "patients";
    $action_id = 3;
}

switch ($data["op"]){
    case "move_to_trash":
        
        $data_array = [
            "table_name" => $table_name,
            "item_id" => $data["item_id"],
            "user_id" => $userid,
            "owner_id" => $data["owner_id"] ?? null,
            "action_id" => $action_id
        ];

        $log = [
            "user_id" => $userid,
            "action_id" => $data_array["action_id"],
            "target_type" => $table_name,
            "target_id" => $data_array["item_id"]
        ];

        try{
            $db->autocommit(false);

            $permissions = Permissions::validatePermissions($data_array);
            if (!$permissions) { throw new Exception('Insufficient permissions'); }
            if (!is_null($data_array['owner_id'])) { $data_array['user_id'] = $data['owner_id']; }

            $trash = new Trash($data_array);
            $result = $trash->moveToTrash();
            if(!$result){ throw new Exception('Failed to move to trash'); }

            $log["changes"] = json_encode([
                "old_values" => ["row_status" => 1],
                "new_values" => ["row_status" => 0]
            ]);
            $log['owner_id'] = $data_array['owner_id'] ?? $userid;
            $log_result = ActionLog::saveLog($log);
            if(!$log_result){ throw new Exception('Failed to save action log'); }

            $db->commit();
            $response = [
                "success" => true,
                "message" => "Item moved to trash successfully",
            ];

        } catch (Exception $e) {
            $db->rollback();
            $response = [
                "success" => false,
                "message" => $e->getMessage()
            ];
        }

        echo json_encode($response);
        break;
    case "recover_from_trash":

        $data_array = [
            "table_name" => $table_name,
            "item_id" => $data["item_id"],
            "user_id" => $userid,
            "owner_id" => $data["owner_id"] ?? null,
            "action_id" => $action_id
        ];
        
        $log = [
            "user_id" => $userid,
            "action_id" => $data_array["action_id"],
            "target_type" => $table_name,
            "target_id" => $data_array["item_id"]
        ];

        try{
            $db->autocommit(false);

            $permissions = Permissions::validatePermissions($data_array);
            if (!$permissions) { throw new Exception('Insufficient permissions'); }
            if (!is_null($data_array['owner_id'])) { $data_array['user_id'] = $data['owner_id']; }

            $trash = new Trash($data_array);
            $result = $trash->recoverFromTrash();
            if(!$result){ throw new Exception('Failed to recover from trash'); }

            $log["changes"] = json_encode([
                "old_values" => ["row_status" => 0],
                "new_values" => ["row_status" => 1]
            ]);
            $log['owner_id'] = $data_array['owner_id'] ?? $userid;
            $log_result = ActionLog::saveLog($log);
            if(!$log_result){ throw new Exception('Failed to save action log'); }

            $db->commit();
            $response = [
                "success" => true,
                "message" => "Item recovered from trash successfully",
            ];

        } catch (Exception $e) {
            $db->rollback();
            $response = [
                "success" => false,
                "message" => $e->getMessage()
            ];
        }

        echo json_encode($response);
        break;

    case "get_trash":
        $data_array = [
            "table_name" => $table_name,
            "user_id" => $userid,
            "owner_id" => $data["owner_id"] ?? null,
            "page" => $data["page"] ?? 0,
            "limit" => Pagination::getPageLimit($data["limit"] ?? null),
            "filters" => ["row_status" => "0"]
        ];

        try{
            $db->autocommit(false);

            $permissions = Permissions::validatePermissions($data_array);
            if (!$permissions) { throw new Exception('Insufficient permissions'); }

            $pagination_values = Pagination::PaginationValues($data_array["page"], $data_array["limit"]);
            $data_array["limit"] = $pagination_values["limit"];
            $data_array["offset"] = $pagination_values["offset"];


            if($data["item_type"] === "appt"){
                $total_rows = Appointment::getRowsCount($data_array["user_id"], $data_array["filters"]);
                if($total_rows === false){ throw new Exception('Failed to get total rows'); }

                $result = Appointment::getAppts($data_array);
                if($result === false){ throw new Exception('Failed to get data'); }
            }

            if($data["item_type"] === "patient"){
                $total_rows = Patient::getRowsCount($data_array["user_id"], $data_array["filters"]);
                if($total_rows === false){ throw new Exception('Failed to get total rows'); }

                $result = Patient::getPatients($data_array);
                if($result === false){ throw new Exception('Failed to get data'); }
            }

            // $trash = new Trash($data_array);
            // $result = $trash->getTrash();
            // if($result === false){ throw new Exception('Failed getting the trash'); }

            // $total_rows = $trash->getRowsCount($data_array["user_id"]);
            // if($total_rows === false){ throw new Exception('Failed to get total rows'); }
            
            $db->commit();
            $response = [
                "success" => true,
                "data" => $result,
                "pagination" => [
                    "total_rows" => $total_rows["count"],
                    "limit" => $data_array["limit"],
                    "offset" => $data_array["offset"],
                    "page" => $data_array["page"]
                ]
            ];

        } catch (Exception $e) {
            $db->rollback();
            $response = [
                "success" => false,
                "message" => $e->getMessage()
            ];
        }

        echo json_encode($response);
        break;
    default:
        $response = [
            "success" => false,
            "message" => "Invalid Operation",
        ];
        echo json_encode($response);
        break;
}
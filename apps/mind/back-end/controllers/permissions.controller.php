<?php
require_once("../config/connect.php");
require_once("../models/Permissions.php");
require_once("../../../../back-end/config/session.php");

$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

switch ($data["op"]){
    case "permission_create":
        $data_array = [
            "id" => null,
            "user_id" => $userid,
            "action_id" => filter_var($data["action_id"], FILTER_SANITIZE_NUMBER_INT),
            "owner_id" => filter_var($data["owner_id"], FILTER_SANITIZE_NUMBER_INT),
        ];

        $permissions = new Permissions($data_array);
        $result = $permissions->save();
        if(!$result["ok"]){
            echo json_encode([
                "success" => false,
                "message" => "error executing the action"
            ]);
            exit;
        }

        echo json_encode([
            "success" => true,
        ]);
        
        break;
    default:
        $response = [
            "success" => false,
            "message" => "Invalid Operation"
        ];
        echo json_encode($response);
        break;
}

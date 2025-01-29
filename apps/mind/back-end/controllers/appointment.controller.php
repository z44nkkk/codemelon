<?php
require_once("../config/connect.php");
require_once("../models/Appointment.php");
require_once("../../../../back-end/config/session.php");
require_once("../models/Permissions.php");
require_once("../models/ActionLog.php");
require_once("../helpers/Pagination.php");
require_once("../helpers/Encrypt.php");

$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

switch ($data["op"]){
    case "appt_create":
        $data_array = [
            "id" => null,
            "user_id" => $userid,
            "owner_id" => $data["owner_id"] ?? null,
            "action_id" => 6,
            "patient_id" => $data["patient_id"],
            "appt_date" => $data["appt_date"],
            "appt_time" => $data["appt_time"],
            "appt_status" => 1,
            "appt_cost" => $data["appt_cost"] ?? 0.00,
            "appt_concept" => $data["appt_concept"] ?? "",
            "appt_price" => $data["appt_price"] ?? 0,
            "appt_payment_status" => $data["appt_payment_status"] ?? 1,
            "appt_mode" => $data["appt_mode"] ?? 1,
        ];

        // Encrypt appt concept / note
        $data_array["appt_concept"] = Encrypt::encrypt($data_array["appt_concept"]);

        $log = [
            "user_id" => $userid,
            "action_id" => $data_array["action_id"],
            "target_type" => "appointments"
        ];

        try{
            $db->autocommit(false);

            $permissions = Permissions::validatePermissions($data_array);
            if (!$permissions) { throw new Exception('Insufficient permissions'); }
            if (!is_null($data_array['owner_id'])) { $data_array['user_id'] = $data['owner_id']; }

            // Check if there is an appointment at the same time
            if($data["ignore_taken"] == false){
                $check = Appointment::checkAppointment($data_array);
                if($check === false){ throw new Exception('Failed to check appointment'); }
                if($check > 0){ throw new Exception('appt_date_time_taken'); }
            }

            $appointment = new Appointment($data_array);
            $result = $appointment->save();
            if(!$result){ throw new Exception('Failed to save appointment'); }

            $log['target_id'] = $result['id'];
            $log['owner_id'] = $data_array['owner_id'] ?? $userid;
            $log_result = ActionLog::saveLog($log);
            if(!$log_result){ throw new Exception('Failed to save action log'); }

            $db->commit();
            $response = [
                "success" => true,
                "message" => "Appointment created successfully",
                "appt_id" => $result['id'],
                "patient_id" => $data_array['patient_id']
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
    case "appt_edit":
        $data_array = [
            "id" => $data["id"] ?? null,
            "user_id" => $userid,
            "owner_id" => $data["owner_id"] ?? null,
            "action_id" => 8,
            "patient_id" => $data["patient_id"],
            "appt_date" => $data["appt_date"],
            "appt_time" => $data["appt_time"],
            "appt_cost" => number_format((float)$data["appt_cost"], 2, '.', '') ?? 0.00,
            "appt_concept" => $data["appt_concept"],
            "appt_price" => number_format((float)$data["appt_price"], 2, '.', ''),
            "appt_payment_status" => $data["appt_payment_status"],
            "appt_mode" => $data["appt_mode"],
            "appt_status" => $data["appt_status"],
            "row_status" => 1
        ];

        // Encrypt appt concept / note
        $data_array["appt_concept"] = Encrypt::encrypt($data_array["appt_concept"]);

        $log = [
            "user_id" => $userid,
            "action_id" => $data_array["action_id"],
            "target_type" => "appointments",
            "target_id" => $data_array["id"],
        ];

        try {
            $db->autocommit(false);

            $permissions = Permissions::validatePermissions($data_array);
            if (!$permissions) { throw new Exception('Insufficient permissions'); }
            if (!is_null($data_array['owner_id'])) { $data_array['user_id'] = $data['owner_id']; }

            if($data["ignore_taken"] == false){
                $check = Appointment::checkAppointment($data_array);
                if($check === false){ throw new Exception('Failed to check appointment'); }
                if($check > 0){ throw new Exception('appt_date_time_taken'); }
            }
            
            $appointment = new Appointment($data_array);
            $current_value = $appointment->getByIdAndUser($data_array["id"], $userid);
            if($current_value == false) { throw new Exception('Failed to get current value'); }            
            
            $result = $appointment->save();
            if($result == false){ throw new Exception('Failed to save appointment'); }

            $difference = ActionLog::getDifference($current_value, $data_array);
            $log['changes'] = json_encode($difference);
            $log['owner_id'] = $data_array['owner_id'] ?? $userid;
            $log_result = ActionLog::saveLog($log);
            if(!$log_result){ throw new Exception('Failed to save action log'); }
            
            $db->commit();
            $response = [
                "success" => true,
                "message" => "Appointment updated successfully",
                // "depuration" => [
                //     "test" => $difference
                // ]
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

    case "appts_get_list":
        $data_array = [
            "id" => null,
            "user_id" => $userid,
            "owner_id" => $data["owner_id"] ?? null,
            "action_id" => 10,
            "page" => $data["page"] ?? 0,
            "filters" => $data["filters"] ?? [],
            "limit" => Pagination::getPageLimit($data["limit"] ?? null)
        ];

        try {
            $db->autocommit(false);

            $permissions = Permissions::validatePermissions($data_array);
            if (!$permissions) { throw new Exception('Insufficient permissions'); }
            if (!is_null($data_array['owner_id'])) { $data_array['user_id'] = $data['owner_id']; }

            $pagination_values = Pagination::PaginationValues($data_array["page"], $data_array["limit"]);
            $data_array["limit"] = $pagination_values["limit"];
            $data_array["offset"] = $pagination_values["offset"];

            $total_rows = Appointment::getRowsCount($data_array["user_id"], $data_array["filters"]);
            if($total_rows === false){ throw new Exception('Failed to get total rows'); }

            $stats = Appointment::getStats($data_array);
            if($stats === false){ throw new Exception('Failed to get stats'); }

            $result = Appointment::getAppts($data_array);
            if($result === false){ throw new Exception('Failed to get data'); }

            $db->commit();
            $response = [
                "success" => true,
                "data" => $result,
                "stats" => [
                    "total_cost" => $stats["total_cost"],
                    "count_pending" => $stats["count_pending"],
                    "count_completed" => $stats["count_completed"],
                    "count_cancelled" => $stats["count_cancelled"]
                ],
                "pagination" => [
                    "total_rows" => $total_rows["count"],
                    "limit" => $data_array["limit"],
                    "offset" => $data_array["offset"]
                ]
            ];

        } catch (Exception $e) {
            $db->rollback();
            $response = [
                "success" => false,
                "message" => $e->getMessage(),
            ];
        }

        echo json_encode($response);
        break;
    case "appt_delete":
        $data_array = [
            "id" => $data["id"] ?? null,
            "user_id" => $userid,
            "owner_id" => $data["owner_id"] ?? null,
            "action_id" => 7
        ];

        $log = [
            "user_id" => $userid,
            "action_id" => $data_array["action_id"],
            "target_type" => "appointments",
            "target_id" => $data_array["id"]
        ];

        try {
            $db->autocommit(false);

            if(is_null($data_array["id"])) { throw new Exception('Invalid appointment id'); }

            $permissions = Permissions::validatePermissions($data_array);
            if (!$permissions) { throw new Exception('Insufficient permissions'); }
            if (!is_null($data_array['owner_id'])) { $data_array['user_id'] = $data['owner_id']; }

            $appointment = new Appointment($data_array);
            $result = $appointment->deleteByUser();
            if($result == false){ throw new Exception('Failed to delete appointment'); }

            $log['owner_id'] = $data_array['owner_id'] ?? $userid;
            $log_result = ActionLog::saveLog($log);
            if(!$log_result){ throw new Exception('Failed to save action log'); }

            $db->commit();
            $response = [
                "success" => true,
                "message" => "Appointment deleted successfully"
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
    case "appt_get_log":
        $data_array = [
            "id" => $data["id"] ?? null,
            "user_id" => $userid,
            "owner_id" => $data["owner_id"] ?? null,
        ];

        try {
            $db->autocommit(false);

            if(is_null($data_array["id"])) { throw new Exception('Invalid appointment id'); }

            $permissions = Permissions::validatePermissions($data_array);
            if (!$permissions) { throw new Exception('Insufficient permissions'); }
            if (!is_null($data_array['owner_id'])) { $data_array['user_id'] = $data['owner_id']; }

            $result = ActionLog::getLog($data_array);
            if($result === false){ throw new Exception('Failed to get log data'); }

            $db->commit();
            $response = [
                "success" => true,
                "data" => $result
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
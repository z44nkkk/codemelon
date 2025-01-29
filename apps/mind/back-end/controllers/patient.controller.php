<?php
require_once("../config/connect.php");
require_once("../models/Patient.php");
require_once("../../../../back-end/config/session.php");
require_once("../models/Permissions.php");
require_once("../models/ActionLog.php");
require_once("../helpers/Pagination.php");
require_once("../helpers/Encrypt.php");


$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

switch ($data["op"]){

    case "patient_create":

        // 1. Define data array and log array
        $data_array = [
            "id" => null,
            "user_id" => $userid,
            "patient_name" => htmlspecialchars($data["patient_name"], ENT_QUOTES, 'UTF-8'),
            "patient_birthdate" => htmlspecialchars($data["patient_birthdate"], ENT_QUOTES, 'UTF-8'),
            "patient_school" => htmlspecialchars($data["patient_school"], ENT_QUOTES, 'UTF-8'),
            "patient_school_grade" => htmlspecialchars($data["patient_school_grade"], ENT_QUOTES, 'UTF-8'),
            "patient_contact_phone" => htmlspecialchars($data["patient_contact_phone"], ENT_QUOTES, 'UTF-8'),
            "patient_contact_email" => htmlspecialchars($data["patient_contact_email"], ENT_QUOTES, 'UTF-8'),
            "patient_gender" => htmlspecialchars($data["patient_gender"], ENT_QUOTES, 'UTF-8'),
            "patient_appt_price" => htmlspecialchars($data["patient_appt_price"], ENT_QUOTES, 'UTF-8'),
            "row_status" => 1,
            "owner_id" => $data["owner_id"] ?? null,
            "action_id" => 1
        ];

        // Encrypt sensitive data
        // $data_array["patient_contact_phone"] = openssl_encrypt($data_array["patient_contact_phone"], "AES-128-ECB", $encryption_key);
        $data_array["patient_school"] = Encrypt::encrypt($data_array["patient_school"]);
        $data_array["patient_school_grade"] = Encrypt::encrypt($data_array["patient_school_grade"]);
        $data_array["patient_contact_phone"] = Encrypt::encrypt($data_array["patient_contact_phone"]);
        $data_array["patient_contact_email"] = Encrypt::encrypt($data_array["patient_contact_email"]);
        $data_array["patient_gender"] = Encrypt::encrypt($data_array["patient_gender"]);

        $log = [
            "user_id" => $userid,
            "action_id" => $data_array["action_id"],
            "target_type" => "patients"
        ];

        // 2. start process with transaction
        try {
            $db->autocommit(false);

            // 3. Validate permissions
            $permissions = Permissions::validatePermissions($data_array);
            if (!$permissions) { throw new Exception('Insufficient permissions'); }
            if (!is_null($data_array['owner_id'])) { $data_array['user_id'] = $data['owner_id']; }

            // 4. execute the action
            $patient = new Patient($data_array);
            $result = $patient->save();
            if(!$result){ throw new Exception('Failed to save patient'); }

            // 5. save action log
            $log['target_id'] = $result['id'];
            $log['owner_id'] = $data_array['owner_id'] ?? $userid;
            $log_result = ActionLog::saveLog($log);
            if(!$log_result){ throw new Exception('Failed to save action log'); }

            // 6. commit transaction
            $db->commit();
            $response = [
                "success" => true,
                "message" => "Patient created successfully",
                "patient_id" => $result['id']
            ];

        } catch (Exception $e) {
            $db->rollback();
            $response = [
                "success" => false,
                "message" => $e->getMessage(),
                "details" => [
                    "permissions" => $permissions ?? "No permissions",
                    "result" => $result ?? "No result",
                    "log_result" => $log_result ?? "No log"
                ]
            ];
        }

        echo json_encode($response);
        break;
    case "patient_edit":
        $data_array = [
            "id" => $data["patient_id"],
            "user_id" => $userid,
            "owner_id" => $data["owner_id"] ?? null,
            "action_id" => 3,
            "patient_name" => htmlspecialchars(trim($data["patient_name"] ?? null), ENT_QUOTES, 'UTF-8'),
            "patient_birthdate" => htmlspecialchars(trim($data["patient_birthdate"] ?? null), ENT_QUOTES, 'UTF-8'),
            "patient_gender" => htmlspecialchars(trim($data["patient_gender"] ?? null), ENT_QUOTES, 'UTF-8'),
            "patient_status" => htmlspecialchars(trim($data["patient_status"] ?? null), ENT_QUOTES, 'UTF-8'),
            "patient_contact_phone" => htmlspecialchars(trim($data["patient_contact_phone"] ?? null), ENT_QUOTES, 'UTF-8'),
            "patient_contact_email" => htmlspecialchars(trim($data["patient_contact_email"] ?? null), ENT_QUOTES, 'UTF-8'),
            "patient_school" => htmlspecialchars(trim($data["patient_school"] ?? null), ENT_QUOTES, 'UTF-8'),
            "patient_school_grade" => htmlspecialchars(trim($data["patient_school_grade"] ?? null), ENT_QUOTES, 'UTF-8'),
            "patient_notes" => htmlspecialchars(trim($data["patient_notes"] ?? ""), ENT_QUOTES, 'UTF-8'),
            "patient_appt_price" => htmlspecialchars(trim($data["patient_appt_price"] ?? null), ENT_QUOTES, 'UTF-8'),
            "row_status" => 1
        ];

        // Encrypt sensitive data
        $data_array["patient_school"] = Encrypt::encrypt($data_array["patient_school"]);
        $data_array["patient_school_grade"] = Encrypt::encrypt($data_array["patient_school_grade"]);
        $data_array["patient_contact_phone"] = Encrypt::encrypt($data_array["patient_contact_phone"]);
        $data_array["patient_contact_email"] = Encrypt::encrypt($data_array["patient_contact_email"]);
        $data_array["patient_gender"] = Encrypt::encrypt($data_array["patient_gender"]);
        $data_array["patient_notes"] = Encrypt::encrypt($data_array["patient_notes"]);
        
        $log = [
            "user_id" => $userid,
            "action_id" => $data_array["action_id"],
            "target_type" => "patients",
            "target_id" => $data_array["id"]
        ];

        try {
            $db->autocommit(false);

            $permissions = Permissions::validatePermissions($data_array);
            if (!$permissions) { throw new Exception('Insufficient permissions'); }
            if (!is_null($data_array['owner_id'])) { $data_array['user_id'] = $data['owner_id']; }
            
            $patient = new Patient($data_array);
            $current_value = $patient->getByIdAndUser($data_array["id"], $userid);
            if($current_value == false) { throw new Exception('Failed to get current value'); }            
            
            $result = $patient->save();
            if($result == false){ throw new Exception('Failed to save patient'); }

            $difference = ActionLog::getDifference($current_value, $data_array);
            $log['changes'] = json_encode($difference);
            $log['owner_id'] = $data_array['owner_id'] ?? $userid;
            $log_result = ActionLog::saveLog($log);
            if(!$log_result){ throw new Exception('Failed to save action log'); }
            
            $db->commit();
            $response = [
                "success" => true,
                "message" => "patient updated successfully",
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
    case "patients_get_list":
        // 1. Define data array
        $data_array = [
            "id" => null,
            "user_id" => $userid,
            "owner_id" => $data["owner_id"] ?? null,
            "action_id" => 5,
            "page" => $data["page"] ?? 0,
            "filters" => $data["filters"] ?? [],
            "limit" => Pagination::getPageLimit($data["limit"] ?? null) 
        ];

        // 2. start process with transaction
        try {
            $db->autocommit(false);

            // 3. Validate permissions
            $permissions = Permissions::validatePermissions($data_array);
            if (!$permissions) { throw new Exception('Insufficient permissions'); }
            if (!is_null($data_array['owner_id'])) { $data_array['user_id'] = $data['owner_id']; }

            // 4. get pagination offset
            $pagination_values = Pagination::PaginationValues($data_array["page"], $data_array["limit"]);
            $data_array["limit"] = $pagination_values["limit"];
            $data_array["offset"] = $pagination_values["offset"];

            // 4. get table total rows
            $total_rows = Patient::getRowsCount($data_array["user_id"], $data_array["filters"]);
            if($total_rows === false){ throw new Exception('Failed to get total rows'); }

            // 4.5 get stats
            $stats = Patient::getStats($data_array);
            if($stats === false){ throw new Exception('Failed to get stats'); }

            // 6. execute the action
            $result = Patient::getPatients($data_array);
            if($result === false){ throw new Exception('Failed to get patients'); }
            
            // 7. No action log needed for this action

            // 8. commit transaction
            $db->commit();
            $response = [
                "success" => true,
                "data" => $result,
                "pagination" => [
                    "total_rows" => $total_rows["count"],
                    "limit" => $data_array["limit"],
                    "offset" => $data_array["offset"]
                ],
                "stats" => $stats
                
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
    case "patient_delete":
        $data_array = [
            "id" => $data["id"],
            "user_id" => $userid,
            "owner_id" => $data["owner_id"] ?? null,
            "action_id" => 2
        ];

        $log = [
            "user_id" => $userid,
            "action_id" => $data_array["action_id"],
            "target_type" => "patients",
            "target_id" => $data_array["id"]
        ];

        try {
            $db->autocommit(false);

            if(is_null($data_array["id"])) { throw new Exception('Invalid appointment id'); }

            $permissions = Permissions::validatePermissions($data_array);
            if (!$permissions) { throw new Exception('Insufficient permissions'); }
            if (!is_null($data_array['owner_id'])) { $data_array['user_id'] = $data['owner_id']; }

            $patient = new Patient($data_array);
            $result = $patient->deleteByUser();
            if($result == false){ throw new Exception('Failed to delete appointment'); }

            $log['owner_id'] = $data_array['owner_id'] ?? $userid;
            $log_result = ActionLog::saveLog($log);
            if(!$log_result){ throw new Exception('Failed to save action log'); }

            $db->commit();
            $response = [
                "success" => true,
                "message" => "Patient deleted successfully"
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

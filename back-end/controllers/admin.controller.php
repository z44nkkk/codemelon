<?php
require_once("../config/connect.php");
require_once("../models/Admin.php");
require_once("../config/session.php");
$admin = new Admin();

// Data
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

switch ($data["op"]){
    case "get_users":
        $page = filter_var($data["page"], FILTER_SANITIZE_NUMBER_INT);
        $limit = 100;
        $offset = (($page+1) * $limit)-$limit;

        $data_array = [
            "page" => $page,
            "limit" => $limit,
            "offset" => $offset,
            "user_id" => $userid
        ];
        $get_users = $admin->getUsers($data_array);
        if($get_users["success"]){
            $response = [
                "success" => true,
                "data" => $get_users["data"],
                "total_rows" => $admin->getTotalRows("users"),
                "limit" => $data_array["limit"],
                "offset" => $data_array["offset"]
            ];
        }else{
            $response = [
                "success" => false,
                "message" => "Error al obtener los usuarios"
            ];
        }
        echo json_encode($response);
        
        break;
    case "get_page_access":
        $page = filter_var($data["page"], FILTER_SANITIZE_NUMBER_INT);
        $limit = 100;
        $offset = (($page+1) * $limit)-$limit;

        $data_array = [
            "page" => $page,
            "limit" => $limit,
            "offset" => $offset,
            "user_id" => $userid
        ];
        $get_page_access = $admin->getPageAccess($data_array);
        if($get_page_access["success"]){
            $response = [
                "success" => true,
                "data" => $get_page_access["data"],
                "total_rows" => $admin->getTotalRows("user_page_access"),
                "limit" => $data_array["limit"],
                "offset" => $data_array["offset"]
            ];
        }else{
            $response = [
                "success" => false,
                "message" => "Error al obtener los accesos"
            ];
        }
        echo json_encode($response);
        break;

        case "get_usersLike":
    

            $result = $admin->getUsersLike($data["term"]);
            if($result["success"]){
                $response = [
                    "success" => true,
                    "data" => $result["data"]
                ];
            }else{
                $response = [
                    "success" => false,
                    "message" => "Error al obtener los usuarios"
                ];
            }
            echo json_encode($response);
            break;
    case "get_suggestions":
        $page = filter_var($data["page"], FILTER_SANITIZE_NUMBER_INT);
        $limit = 100;
        $offset = (($page+1) * $limit)-$limit;

        $data_array = [
            "page" => $page,
            "limit" => $limit,
            "offset" => $offset,
            "user_id" => $userid
        ];
        $get_suggestions = $admin->getSuggestions($data_array);
        if($get_suggestions["success"]){
            $response = [
                "success" => true,
                "data" => $get_suggestions["data"],
                "total_rows" => $admin->getTotalRows("suggestions"),
                "limit" => $data_array["limit"],
                "offset" => $data_array["offset"]
            ];
        }else{
            $response = [
                "success" => false,
                "message" => "Error al obtener las sugerencias"
            ];
        }
        echo json_encode($response);
        break;
    default:
        $response = [
            "success" => false,
            "message" => "Invalid Operation"
        ];
        echo json_encode($response);
        break;
}
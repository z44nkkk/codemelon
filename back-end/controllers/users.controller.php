<?php
require_once("../config/connect.php");
require_once("../models/Users.php");
require_once("../models/User_page_access.php");
require_once("../config/cookies.php");
require_once("../config/session.php");
$users = new users();

if (isset($_GET["cookies"])) {
    if (isset($_COOKIE[$cookie_uid])) {
        $username = $_COOKIE[$cookie_uid];
        $pwd= $_COOKIE[$cookie_pwd];
        $login = $users->login($username, $pwd, $cookie_uid, $cookie_pwd);
        if($login){header("location: ".$_GET["desired_url"]);}else{
     
            header("location: ../index?error=true");
        }
        exit;
    }
}

// Data
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

switch ($data["op"]) {
    case 'login':
        $username = $data["user"];
        $pwd = $data["pwd"];
        $login = $users->login($username, $pwd, $cookie_uid, $cookie_pwd);

        if($login){echo json_encode("access_accepted");}
        break;
    case 'signup' :
        $email = htmlspecialchars($data["email"]);
        $pwd = $data["pwd"];
        $signup = $users->signup($email, $pwd);
        if($signup){
            $login = $users->login($email, $pwd, $cookie_uid, $cookie_pwd);
            if($login){echo json_encode("access_accepted");}
        }
        break;
    case 'getUserData' :
        include_once("../config/session.php");
        $user_data = $users->getUserData($userid);
        $response = [
            "id" => $user_data["id"],
            "name" => $user_data["name"],
            "email" => $user_data["email"]
        ];
        echo json_encode($response);
        break;
    case 'modifyUserData' :
        include_once("../config/session.php");
        $name = htmlspecialchars($data["name"]);
        $modify = $users->modifyUserData($name, $userid);
        if($modify){echo json_encode(true);}
        break;
    case 'logout' :
        include_once ("../config/cookies.php");
        deleteCookies($cookie_uid, $cookie_pwd);

        include_once ("../config/session.php");
        session_unset();
        session_destroy();
        echo json_encode(true);
        break;
    case 'sendPasswordResetCode' :
        $email = $data["email"];
        $sendPasswordResetCode = $users->sendPasswordResetCode($email);
        if($sendPasswordResetCode){echo json_encode($sendPasswordResetCode);}
        break;
    case 'validatePasswordResetCode' :
        $email = $data["email"];
        $code = $data["code"];
        $validatePasswordResetCode = $users->validatePasswordResetCode($email, $code);
        if($validatePasswordResetCode){echo json_encode($validatePasswordResetCode);}
        break;
    case 'updatePassword' :
        $email = $data["email"];
        $pwd = $data["password"];
        $code = $data["code"];
        $updatePassword = $users->updatePassword($email, $pwd, $code);
        if(!$updatePassword){echo json_encode(false); exit;}
        echo json_encode($updatePassword);
        break;
    case 'registerAccess' : 
        $userRegister = new User_page_access();
        $page = $data["page"];
        $device = $data["device"];
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $result = $userRegister->registerAccess($userid, $page, $device, $ip_address);
        if(!$result){echo json_encode(false); exit;}
        echo json_encode($result);
        break;
    case "google_auth":
        $credential = $data["credential"];
    
        list($header, $payload, $signature) = explode (".", $credential); 
        $responsePayload = json_decode(base64_decode($payload)); 
    
        if(empty($responsePayload)){
            echo json_encode(["status" => 0, "pdata" => "Invalid credential"]);
            exit;
        }
    
        $email = $responsePayload->email;
        $name = $responsePayload->name;
        $google_id = $responsePayload->sub;
        $profile_picture = $responsePayload->picture;
    
        // Aquí se realiza la verificación del token JWT
        require_once '../config/google-api-php-client/vendor/autoload.php';
        $client_id = "819722503345-qies2hv7hjl6ig525dtau8327qccj82a.apps.googleusercontent.com";
        $client_secret = "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";

        $client = new Google\Client();
        $client->setClientId($client_id);
        $client->setClientSecret($client_secret);
        $client->setRedirectUri("http://localhost");
        // Verifica el token
        try {
            $payload = $client->verifyIdToken($credential);
            if ($payload) {
                // 1. Check if user exists
                $check_email_taken = $users->checkEmailTaken($email);
                if ($check_email_taken) {
                    // Si el usuario existe, iniciar sesión
                    $login = $users->login($email, null, $cookie_uid, $cookie_pwd);
                    if ($login) {
                        echo json_encode(["status" => 1, "pdata" => "access_accepted"]);
                    } else {
                        echo json_encode(["status" => 0, "message" => "Login failed"]);
                    }
                    exit;
                }
    
                // 2. Check if username is taken
                $check_name_taken = $users->checkNameTaken($name);
                if ($check_name_taken) {
                    $name = $users->generateUniqueName($name);
                }
    
                // 3. Register user
                $signup = $users->signup($email, null, [ "google_id" => $google_id, "profile_picture" => $profile_picture, "name" => $name] );
                if ($signup) {
                    $login = $users->login($email, null, $cookie_uid, $cookie_pwd);
                    if ($login) {
                        echo json_encode(["status" => 1, "pdata" => "access_accepted"]);
                    } else {
                        echo json_encode(["status" => 0, "message" => "Login failed"]);
                    }
                } else {
                    echo json_encode(["status" => 0, "message" => "Signup failed"]);
                }
            } else {
                echo json_encode(["status" => 0, "pdata" => "Invalid token"]);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => 0, "pdata" => "Token verification failed: " . $e->getMessage()]);
        }
        break;
    
        
    default:
        # code...
        break;
}


 

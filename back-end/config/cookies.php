<?php
require_once 'config.php';
$cookie_uid = $_ENV["SESSION_NAME"]."-uid";
$cookie_pwd = $_ENV["SESSION_NAME"]."-pwd";

function cookiesRedirect($cookie_uid, $current_url) {

  if (isset($_COOKIE[$cookie_uid])) {
    if(!isset($_SESSION["id"])){
      header("location: ".BASE_URL."controllers/users.controller.php?cookies&desired_url=".$current_url."");
      exit();
      // echo "<br>session not started and you are suppose to be redirectec to ".BASE_URL."controllers/users.controller.php?cookies&desired_url=".$current_url."<br>";
    }else{
      // echo "<br>session started<br>";
    }
  }
}

function deleteCookies($cookie_uid, $cookie_pwd) {
  if (isset($_COOKIE[$cookie_uid])) {
    unset($_COOKIE[$cookie_uid]);
    unset($_COOKIE[$cookie_pwd]);
    setcookie($cookie_uid, null, -1, '/');
    setcookie($cookie_pwd, null, -1, '/');
  }
}


?>
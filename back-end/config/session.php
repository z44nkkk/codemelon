<?php
require_once 'config.php';
session_name($_ENV["SESSION_NAME"]);
session_start();
if(isset($_SESSION["id"])){$userid = $_SESSION["id"];}
?>
<?php
require_once __DIR__ . '/../models/ActiveRecord.php';
$db = mysqli_connect('localhost','u600341407_melon_mind','V|aHCOmhn3i','u600341407_melon_mind');
// $db = mysqli_connect('localhost','root','','sb_mind');

if (!$db) {
    echo "Error: No se pudo conectar a MySQL.";
    echo "error de depuración: " . mysqli_connect_error();
    echo "error de depuración: " . mysqli_connect_error();
    exit;
}
ActiveRecord::setDB($db);
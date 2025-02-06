<?php
class Connect {
    protected function Conection(){
        $serverName = "localhost";
        //$dbUsername = "u600341407_codemelon_main";$dbPassword = "4bDn0NE&dJ^I";$bNamed = "u600341407_codemelon_main";
        $dbUsername = "root";$dbPassword = "";$bNamed = "cocounut_sb";
        try {
            $connect = new PDO("mysql:host=$serverName;dbname=$bNamed", $dbUsername, $dbPassword);
            $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $connect; // Agrega esta línea para devolver la conexión
        } catch (Exception $e) {
            print "¡Error BD!: " . $e->getMessage() . "<br/>";
            die();
        }
    }
}

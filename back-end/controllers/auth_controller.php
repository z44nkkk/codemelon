<?php
function checkSession($cookie_uid){
    if (!isset($_SESSION["id"])) {
        $currentPage = basename($_SERVER['PHP_SELF']);
        $allowedPages = ['index.php'];
        if (!in_array($currentPage, $allowedPages)) {
            if(!isset($_COOKIE[$cookie_uid])){
                // echo "Current Page: ".$_SERVER['REQUEST_URI'];
                header ("location: index?redirected");        
                exit();
            }
        }
    }else{
        // $currentPage = basename($_SERVER['PHP_SELF']);
        // $allowedPages = [''];
        // if (in_array($currentPage, $allowedPages)) {
        //     header ("location: home");
        //     exit();
        // }  
    }
}

?>
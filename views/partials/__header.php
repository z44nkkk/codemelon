<?php 
define('BASE_URL', '/');
// include $_SERVER['DOCUMENT_ROOT'] . BASE_URL .'controllers/auth_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . BASE_URL .'back-end/config/config.php';
// include_once $_SERVER['DOCUMENT_ROOT'] . BASE_URL ."config/session.php";
// include_once  $_SERVER['DOCUMENT_ROOT'] . BASE_URL .'config/cookies.php';
// cookiesRedirect($cookie_uid, "$_SERVER[REQUEST_URI]");

// checkSession($cookie_uid);

// if(!isset($_SESSION["id"])){
//     echo "<span style='opacity:0.5;position:absolute;top:0;left:0;z-index:10000;color:white;background:red;'>La sesión No existe </span>";
//   }else{
//     echo "<span style='opacity:0.5;position:absolute;top:0;left:0;z-index:10000;color:white;background:green;'>La sesión Sí existe </span>";
//     echo "<span style='opacity:0.5;position:absolute;top:24px;left:0;z-index:10000;color:white;background:green;'>UserId:".$_SESSION['id']."</span>";
//   }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title><?php echo $_ENV['APP_NAME'] ?></title>
    <script> const BASE_URL = "<?= BASE_URL ?>"</script>
    <script src="https://accounts.google.com/gsi/client" async></script>

    <!-- style and themes -->
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css?v=1.8.2">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/theme/theme.css?v=1.7.0">
    <link id="theme-style" rel="stylesheet" href="<?= BASE_URL ?>css/theme/colors/oled.css">
    <script src="<?= BASE_URL ?>js/theme.js"></script>
    
    <!-- Material Web Components -->
    <script src="<?= BASE_URL?>js/bundle.js"></script>


    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/png" href="<?=BASE_URL?>assets/icon.png">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <!-- <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"> -->

    <!-- Manifest -->
    <?php 
      if (strpos($_SERVER['REQUEST_URI'], '/apps/') !== false) {
        $current_url = explode('/', $_SERVER['REQUEST_URI'])[2];
      } else {
        $current_url = '';
      }
      
      switch ($current_url) {
        case 'notes':
          echo '<link rel="manifest" href="'.BASE_URL.'apps/notes/config/site.webmanifest" >';
          echo '
            <meta property="og:title" content="stepbro Notes" />
            <meta property="og:description" content="Una aplicación de notas completa y multiplataforma: toma notas rápidas, organiza con carpetas, listas de tareas y un diario encriptado." />
            <meta property="og:image" content="https://stepbro.site/assets/icon.png" />
            <meta property="og:url" content="https://notes.stepbro.site" />
            <meta property="og:type" content="website" />

            <script type="application/ld+json">
              {
                "@context": "https://schema.org",
                "@type": "WebApplication",
                "name": "stepbro Notes",
                "description": "Organiza tus tareas y notas con facilidad. Incluye carpetas, to-do list y un diario seguro.",
                "url": "https://notes.stepbro.site",
                "image": "https://stepbro.site/assets/icon.png",
                "applicationCategory": "ProductivityApplication",
                "operatingSystem": "All"
              }
            </script>
          ';
          break;
        case 'mind':
          echo '<link rel="manifest" href="'.BASE_URL.'apps/mind/back-end/config/site.webmanifest" >';
          echo '
            <meta property="og:title" content="stepbro mind" />
          ';
          break;
        
        default:
          # code...
          break;
      }

    ?>

    <meta name="description" content="Empresa de desarrollo de software de verdadera calidad y enfoque en el detalle.">
    <meta name="author" content="Luis David Elizarraraz Mondaca ('Davo')">
    <meta name="keywords" content="codemelon, carded, Stepbro Software, stepbro, Luis David Elizarraraz Mondaca, Davo, desarrollo web, software, aplicaciones">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="Codemelon">
    <meta property="og:url" content="https://codemelon.net">
    <meta property="og:type" content="website">

    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- fonts / icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">  -->
    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
    />
    
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,200..800&family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    
  </head>
<body>
<!-- <transparent>
  <?php
    // if(isset($_SESSION['id'])){
    //   include_once $_SERVER['DOCUMENT_ROOT'] . BASE_URL .'views/windows/window-settings.php';
    //   include_once $_SERVER['DOCUMENT_ROOT'] . BASE_URL .'views/windows/window-send-suggestion.php';

    //   if($_SESSION['additional_data']['permissions'] == 7){
    //     include_once $_SERVER['DOCUMENT_ROOT'] . BASE_URL .'views/windows/window-admin-panel.php';
    //   }
      
    // } else{
    //   include_once $_SERVER['DOCUMENT_ROOT'] . BASE_URL .'views/windows/window-sb-signup.php';
    //   include_once $_SERVER['DOCUMENT_ROOT'] . BASE_URL .'views/windows/window-sb-login.php';
    // }
  ?>
</transparent> -->
<?php 
define('BASE_URL', '/codemelon/');
include $_SERVER['DOCUMENT_ROOT'] . BASE_URL .'back-end/controllers/auth_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . BASE_URL .'back-end/config/config.php';
include_once $_SERVER['DOCUMENT_ROOT'] . BASE_URL ."back-end/config/session.php";
include_once  $_SERVER['DOCUMENT_ROOT'] . BASE_URL .'back-end/config/cookies.php';
cookiesRedirect($cookie_uid, "$_SERVER[REQUEST_URI]");

checkSession($cookie_uid);

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
    
    <script src="<?= BASE_URL ?>js/components/cocounut-chart.js"></script>


    <meta charset="UTF-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <!-- <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"> -->


    <meta name="description" content="Empresa de desarrollo de software de verdadera calidad y enfoque en el detalle.">
    <meta name="author" content="Luis David Elizarraraz Mondaca ('Davo')">
    <meta name="keywords" content="codemelon, carded, Stepbro Software, stepbro, Luis David Elizarraraz Mondaca, Davo, desarrollo web, software, aplicaciones">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="Codemelon">
    <meta property="og:url" content="https://codemelon.net">
    <meta property="og:type" content="website">

    <!-- Manifest -->
    <?php 
      if (strpos($_SERVER['REQUEST_URI'], '/apps/') !== false) {
        $current_url = explode('/', $_SERVER['REQUEST_URI'])[2];
      } else {
        $current_url = '';
      }
      
      switch ($current_url) {
        case 'mind':
          echo "<link rel='shortcut icon' type='image/png' href='https://codemelon.net/apps/mind/assets/favicon.png'>";
          echo '<link rel="manifest" href="'.BASE_URL.'apps/mind/back-end/config/site.webmanifest?v=1" >';
          echo '
            <meta property="og:title" content="Melon Mind" />
            <meta property="og:description" content="Software profesional para psicólogos: Gestiona eficientemente pacientes, citas, expedientes clínicos, facturación y más en una sola plataforma integrada" />
            <meta property="og:image" content="https://codemelon.net/apps/mind/assets/icon.png" />
            <meta property="og:url" content="https://mind.codemelon.net" />
            <meta property="og:type" content="website" />

            <script type="application/ld+json">
              {
                "@context": "https://schema.org",
                "@type": "WebApplication",
                "name": "Melon Mind",
                "description": "Software profesional para psicólogos: Gestiona eficientemente pacientes, citas, expedientes clínicos, facturación y más en una sola plataforma integrada",
                "url": "https://mind.codemelon.net",
                "image": "https://codemelon.net/apps/mind/assets/icon.png",
                "applicationCategory": "ProductivityApplication",
                "operatingSystem": "All"
              }
            </script>
          ';
          break;        
        default:
          echo "<link rel='shortcut icon' type='image/png' href='https://codemelon.net/assets/icon.png'>";
          break;
      }

    ?>
    
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
<transparent>
  <?php
    if(isset($_SESSION['id'])){
      include_once $_SERVER['DOCUMENT_ROOT'] . BASE_URL .'views/windows/window-settings.php';
      include_once $_SERVER['DOCUMENT_ROOT'] . BASE_URL .'views/windows/window-send-suggestion.php';

      if($_SESSION['additional_data']['permissions'] == 7){
        include_once $_SERVER['DOCUMENT_ROOT'] . BASE_URL .'views/windows/window-admin-panel.php';
      }
      
    } else{
      include_once $_SERVER['DOCUMENT_ROOT'] . BASE_URL .'views/windows/window-sb-signup.php';
      include_once $_SERVER['DOCUMENT_ROOT'] . BASE_URL .'views/windows/window-sb-login.php';
    }
  ?>
</transparent>
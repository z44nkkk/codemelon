<?php 
include_once '../../views/partials/__header.php'; 
include_once '../../back-end/config/utilities.php';
?>

<transparent>
  <?php include_once "views/windows.php"; ?>
</transparent>

<main>
  <md-linear-progress indeterminate style="position:absolute; width:100%; z-index:10;" id="main-app-loading-indicator"></md-linear-progress>
  <nav class="nav-style-2" id="nav-parent">
    <?php include_once 'views/partials/__navbar_items.php'; ?>
  </nav>
  <holder>
    <!-- <div class="simple-container" style="position:absolute; bottom:16px; right:16px; z-index:1">
      <md-fab label="Iniciar servicio" variant="primary" onclick="toggleWindow('#window-start-operation', '', 1)" data-flip-id="animate">
        <md-icon slot="icon">content_cut</md-icon>
      </md-fab>
    </div> -->
    <?php 
      include_once 'views/dialogs/dialogs.php'; 
      include_once 'views/sections.php'; 
    ?>  

    <div class="simple-container" style="position:absolute; bottom:16px; right:16px; z-index:1">
      <md-fab class="hover-scale-small" size="small" id="toggler-menu-app-actions" variant="primary" onclick="toggleMenu('menu-app-actions')"  data-flip-id="animate">
        <md-icon slot="icon">more_horiz</md-icon>
      </md-fab>
      <md-menu id="menu-app-actions" class="style-modern" style="min-width:264px;" anchor="toggler-menu-app-actions">
        <md-menu-item onclick="ApptsManager.openCreateApptWindow()" data-flip-id="animate">
          <md-icon slot="start" aria-hidden="true" class="filled">calendar_add_on</md-icon>
          <div slot="headline">Agendar cita</div>
        </md-menu-item>
        <md-menu-item onclick="PatientsManager.openCreatePatientWindow()" data-flip-id="animate">
          <md-icon slot="start" aria-hidden="true" class="filled">person_add</md-icon>
          <div slot="headline">Agregar paciente</div>
        </md-menu-item>
        <md-menu-item data-trash-opener data-flip-id="animate">
          <md-icon slot="start" aria-hidden="true" class="filled">delete</md-icon>
          <div slot="headline">Abrir papelera</div>
        </md-menu-item>
        <md-menu-item onclick="toggleWindow('#window-settings')" class="only-on-mobile" data-flip-id="animate">
          <md-icon slot="start" aria-hidden="true">settings</md-icon>
          <div slot="headline">Configuración</div>
        </md-menu-item>
        <md-menu-item onclick="window.location.href='index'" class="only-on-mobile">
          <md-icon slot="start" aria-hidden="true" class="filled">first_page</md-icon>
          <div slot="headline">Página principal</div>
        </md-menu-item>
      </md-menu>
    </div>
  </holder>
</main>
<script src="js/register-access.js?v=7"></script>

<script>
  document.addEventListener("DOMContentLoaded", function(event) {
    registerAccess();
  });
</script>

<script src="js/main.js?v=3" type="module"></script>


<!-- <script src="js/patients-functions.js?v=1.0.3"></script> -->
<script src="js/permissions-functions.js?v=1.0.0"></script>
<!-- <script src="js/appointments-functions.js?v=1.0.1"></script> -->



<?php 
include_once '../../views/partials/__footer.php'; 
?>
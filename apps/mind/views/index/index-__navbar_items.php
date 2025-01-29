<div class="simple-container grow-1 justify-between-wide-screen h-padding-16 max-width-1200">
  <div class="simple-container grow-0-1">
    <button 
      class="nav-button stepbro" active 
      data-section="section-index" 
      onclick="toggleSection('section-index', '#index-scroll-target')"
      >
      <md-ripple></md-ripple>
      <span class="icon-holder" >
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="16" viewBox="0 0 24 16" fill="none">
          <rect y="0.696289" width="14.6076" height="14.6076" rx="7.30381" fill="var(--md-sys-color-on-background)"/>
          <rect x="16.5786" y="0.696289" width="7.42162" height="14.6076" rx="3.71081" fill="var(--md-sys-color-on-background)"/>
        </svg> 
      </span>
      <span class="simple-container align-center gap-8">
        <span class="only-on-mobile">Inicio</span>
        <span class="hide-on-mobile">codemelon</span>
      </span>
    </button>
  </div>
  <div class="simple-container grow-0-1 gap-8">
    <!-- <button 
      class="nav-button"
      data-section="section-pricing" 
      onclick="toggleSection('section-pricing')"
      >
      <span class="icon-holder only-on-mobile">
        <span class="material-symbols-rounded">payments</span>
      </span>
      <md-ripple></md-ripple>
      Precios
    </button> -->
    

    <?php
      if(isset($_SESSION['id'])){
        echo "
          <button 
            class='nav-button'
            id='direct-action-header-button'
            onclick='window.location=\"home\"'
            >
            <span class='icon-holder only-on-mobile'>
            <span class='material-symbols-rounded'>arrow_circle_right</span>
            </span>
            <md-ripple></md-ripple>
            <span>Ir a app</span>
          </button>
          <button 
            class='nav-button'
            data-flip-id='animate'
            onclick='toggleWindow(\"#window-settings\")'
            >
            <span class='icon-holder'>
            <span class='material-symbols-rounded'>settings</span>
            </span>
            <md-ripple></md-ripple>
            <span class='only-on-mobile'>Configuración</span>
          </button>
        ";
      } else{
        echo "
          <button 
            class='nav-button'
            data-flip-id='animate' 
            onclick='toggleWindow(\"#window-sb-login\",\"\",1)'
            >
            <span class='icon-holder only-on-mobile'>
            <span class='material-symbols-rounded'>login</span>
            </span>
            <md-ripple></md-ripple>
            Iniciar sesión
          </button>
          <button 
            class='nav-button'
            id='direct-action-header-button'
            data-flip-id='animate' 
            onclick='toggleWindow(\"#window-sb-signup\",\"\",1)'
            >
            <span class='icon-holder only-on-mobile'>
            <span class='material-symbols-rounded'>person_add</span>
            </span>
            <md-ripple></md-ripple>
            Crear cuenta
          </button>
        ";
      }
    ?>
    
  </div>

  



  
</div>



<script>
  const directActionButton = document.getElementById('direct-action-header-button');
  document.addEventListener("DOMContentLoaded", function(event) {
    const activeSection = document.querySelector("#section-index");

    // Escucha el evento de scroll
    activeSection.addEventListener('scroll', function() {
      // Obtén el valor de scroll
      const scrollValue = activeSection.scrollTop;

      // if (scrollValue > 0) {
        directActionButton.setAttribute("directActionOn", ""); 
      // } else {
      //   directActionButton.removeAttribute("directActionOn"); 
      // }
    });
   

  });
  
</script>




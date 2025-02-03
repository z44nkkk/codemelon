<section id="section-index" active class="landing-page align-center  on-background-text">


  <!-- <img style="width:200px;" src="https://i.ibb.co/HqyFdS8/Cocounut-Mind-New-Icon-1.png" alt="stepbro mind icon"> -->

  
  <div class="simple-container direction-column width-100 max-width-1600 align-center">
    <div class="simple-container direction-column ">
      <div class="simple-container direction-column align-center gap-24">
        <md-icon 
          class="filled primary-container-text" 
          style="--md-icon-size: 80px;">
          cognition
        </md-icon>
        <h1 class="main-title margin-0 text-center-on-mobile">Melon Mind</h1>
      </div>
    </div>
    <div class="simple-container max-width-600 top-margin-8">
      <span class="title-text outline-text text-center">
        <!-- La herramienta para psicólogos: gestiona pacientes, citas y finanzas, accesible donde y cuando la necesites -->
         La herramienta más poderosa para psicólogos
      </span>
    </div>
    <div class="simple-container top-margin-16 justify-center">
      <?php
        if(isset($_SESSION['id'])){
          echo "
            <button 
              class='style-3 primary-container on-primary-container-text hover-shadow'
              onclick='window.location=\"home\"'
              >
              <md-ripple></md-ripple>
              Comenzar
            </button>
          ";
        } else{
          echo "
              <button 
                class='style-3 primary-container on-primary-container-text hover-shadow'
                data-flip-id='animate'
                onclick='toggleWindow(\"#window-sb-signup\",undefined,1)'
                >
                <md-ripple></md-ripple>
                Comenzar
              </button>
          ";
        }
      ?>
    </div>
  </div>

  <div class="simple-container position-relative top-margin-64 bottom-margin-64">
    <img src="assets/melon-mind-ui-preview.png" alt="Melon Mind Ui Preview" class="width-100 max-width-1600">
    <!-- <img class="main-image" src="https://i.ibb.co/KWnbjsH/146-1x-shots-so-2.png" alt="app preview"> -->
     <!-- <img class="main-image" src="https://i.ibb.co/x1nRVTH/339-1x-shots-so.png" alt="app preview"> -->
  </div>
  
  <div class="content-box direction-row width-100 flex-wrap">
    <div class="simple-container grow-1 basis-normal">
      <img class="width-100 fit-cover" src="https://i.ibb.co/ZV1PRC4/235-1x-shots-so.png" alt="patients preview">
    </div>
    <div class="simple-container grow-1 basis-normal justify-center direction-column gap-8">
      <span class="display-medium dm-sans weight-500 line-height-1">Administra tus pacientes</span>
      <p class="headline-small outline-text">
        Lleva un registro de tus pacientes, sus datos y su historial clínico de forma segura y organizada
      </p>
    </div>
  </div>
  <div class="content-box direction-row width-100 flex-wrap">
    <div class="simple-container grow-1 basis-normal">
      <img class="width-100 fit-cover" src="https://i.ibb.co/R2RFdyp/898-1x-shots-so.png" alt="appointments preview">
    </div>
    <div class="simple-container grow-1 basis-normal justify-center direction-column gap-8">
      <span class="display-medium dm-sans weight-500 line-height-1">Gestiona tus citas</span>
      <p class="headline-small outline-text">
        Organiza tus citas y manten un control total de tu agenda
      </p>
    </div>
  </div>
  <div class="content-box direction-row width-100 flex-wrap">
    <div class="simple-container grow-1 basis-normal">
      <img class="width-100 fit-cover" src="https://i.ibb.co/q7zsnQy/740-1x-shots-so.png" alt="calendar preview">
    </div>
    <div class="simple-container grow-1 basis-normal justify-center direction-column gap-8">
      <span class="display-medium dm-sans weight-500 line-height-1">Visualiza tu agenda</span>
      <p class="headline-small outline-text">
        Visualiza tus citas y eventos en un calendario intuitivo y fácil de usar
      </p>
      <!-- <span class="body-large outline-text">(En desarrollo)</span> -->
    </div>
  </div>
  <div class="content-box direction-row width-100 flex-wrap">
    <div class="simple-container grow-1 basis-normal">
      <img class="width-100 fit-cover" src="https://i.ibb.co/6sMQDhc/788-1x-shots-so.png" alt="calendar preview">
    </div>
    <div class="simple-container grow-1 basis-normal justify-center direction-column gap-8">
      <span class="display-medium dm-sans weight-500 line-height-1">Controla tus finanzas</span>
      <p class="headline-small outline-text">
        Lleva un control de tus ingresos y egresos de forma sencilla y eficiente
      </p>
      <span class="body-large outline-text">(En desarrollo)</span>
    </div>
  </div>
  <div class="content-box direction-row width-100 flex-wrap">
    <div class="simple-container grow-1 basis-normal">
      <img class="width-100 fit-cover" src="https://i.ibb.co/D449qXc/921-1x-shots-so.png" alt="calendar preview">
    </div>
    <div class="simple-container grow-1 basis-normal justify-center direction-column gap-8">
      <span class="display-medium dm-sans weight-500 line-height-1">Personaliza a tu estilo</span>
      <p class="headline-small outline-text">
        Personaliza tu experiencia con temas y colores que se adapten a tu estilo
      </p>
    </div>
  </div>

  <div class="simple-container direction-column width-100 max-width-1200 top-margin-64 bottom-margin-64">
    <span class="display-medium dm-sans weight-500 text-center">
      Otras funcinalidades planeadas
    </span>
    <div class="top-margin-24" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); grid-template-rows: repeat(1, 1fr); gap: 8px;">
      <div class="content-box padding-32 direction-row hover-scale-small light-color">
      <div class="simple-container"><md-icon class="primary-container-text filled pretty small" aria-hidden="true">work</md-icon></div>
      <div class="simple-container direction-column gap-8 grow-1">
          <span class="headline-small  weight-500">Sistema de clínicas</span>
          <p class="body-large text-wrap-pretty">
          Crea y personaliza clínicas de manera profesional. Registra colaboradores como psicólogos, recepcionistas u otros empleados, asignándoles roles específicos dentro del sistema. Centraliza la gestión de pacientes, citas y operaciones en una plataforma diseñada para optimizar el trabajo en equipo y el flujo de la clínica.
          </p>
      </div>
      </div>
      <div class="content-box padding-32 direction-row hover-scale-small light-color">
      <div class="simple-container"><md-icon class="primary-container-text filled pretty small" aria-hidden="true">admin_panel_settings</md-icon></div>
      <div class="simple-container direction-column gap-8 grow-1">
          <span class="headline-small  weight-500">Sistema avanzado de permisos</span>
          <p class="body-large text-wrap-pretty">Otorga control total a los usuarios con un sistema de permisos flexible y potente. Permite asignar roles y autorizar a otros usuarios para realizar acciones como registrar citas, agregar pacientes, editar, o incluso eliminar información. Diseñado para adaptarse a cualquier flujo de trabajo y asegurar una colaboración eficiente y segura.</p>
      </div>
      </div>
      <div class="content-box padding-32 direction-row hover-scale-small light-color">
      <div class="simple-container"><md-icon class="primary-container-text filled pretty small" aria-hidden="true">analytics</md-icon></div>
      <div class="simple-container direction-column gap-8 grow-1">
          <span class="headline-small  weight-500">Estadísticas avanzadas</span>
          <p class="body-large text-wrap-pretty">Accede a reportes detallados y visualizaciones intuitivas sobre pacientes, citas y finanzas. Obtén insights clave para tomar decisiones informadas, desde el rendimiento de tu clínica hasta tendencias en tus ingresos y ocupación. Todo en un solo lugar, diseñado para optimizar tu gestión.</p>
      </div>
      </div>
    </div>
  </div>

  <div class="simple-container direction-column position-relative manual-height-grow user-select-none">
    <img src="https://i.ibb.co/tcqqRp6/82-1x-shots-so.png" alt="full preview" class="width-100 fit-cover">
    <div class="simple-container direction-column position-absolute top-24 other-title-container">
      <span class="display-large dm-sans weight-600 line-height-1 other-title">Comenzar a usar</span>
      <div class="content-box translucid-background max-width-600 hover-scale-small other-container gap-4 bottom-margin-64">
        <p class="headline-small dm-sans line-height-1">
          Regístrate y comienza a usar Melon Mind hoy mismo.
        </p>
        <p class="body-large outline-text">
          Esta es una versión de prueba y algunas funcionalidades pueden no estar disponibles.
        </p>
        <div class="simple-container top-margin-8">
        <?php
          if(isset($_SESSION['id'])){
            echo "
              <button 
                class='style-3 primary-container on-primary-container-text hover-shadow'
                onclick='window.location=\"home\"'
                >
                <md-ripple></md-ripple>
                Comenzar
              </button>
            ";
          } else{
            echo "
                <button 
                  class='style-3 primary-container on-primary-container-text hover-shadow'
                  data-flip-id='animate'
                  onclick='toggleWindow(\"#window-sb-signup\",undefined,1)'
                  >
                  <md-ripple></md-ripple>
                  Comenzar
                </button>
            ";
          }
        ?>
        </div>

      </div>
    </div>
    
  </div>

  <div class="simple-container direction-column top-margin-64 width-100 justify-center align-center on-background-text">
      <div class="simple-container width-100 max-width-1200 flex-wrap gap-16 padding-24 " style="box-sizing:border-box">
        
        <div class="simple-container grow-1 basis-normal direction-column h-padding-16">
          <div class="simple-container align-center gap-8">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="24" viewBox="0 0 24 16" fill="none">
              <rect y="0.696289" width="14.6076" height="14.6076" rx="7.30381" fill="var(--md-sys-color-on-background)"/>
              <rect x="16.5786" y="0.696289" width="7.42162" height="14.6076" rx="3.71081" fill="var(--md-sys-color-on-background)"/>
            </svg>
            <span class="poppins weight-600 headline-medium">codemelon</span>
          </div>
          <p class="outline-text dm-sans">Tu empresa de desarrollo web de confianza.</p>
        </div>


        <div class="simple-container grow-1 basis-normal direction-column">
          <span class="headline-small poppin weight-600 left-margin-16 top-margin-8">Contacto</span>
          <div class="simple-container direction-column">
            <button class="style-4 background on-background-text dm-sans" onclick="window.location.href='mailto:stepbro.corp@gmail.com'; return false;">
              <md-icon class="filled primary-text">mail</md-icon>
              <span id="copy-contact-email-value">stepbro.corp@gmail.com</span>
              <md-ripple></md-ripple>
            </button>
            <button class="style-4 background on-background-text dm-sans" onclick="window.location.href='mailto:luisdavid.gris@gmail.com'; return false;">
              <md-icon class="filled primary-text">mail</md-icon>
              <span id="copy-contact-email-value">luisdavid.gris@gmail.com</span>
              <md-ripple></md-ripple>
            </button>
          </div>
        </div>

        <div class="simple-container grow-1 basis-normal direction-column bottom-margin-48">
          <span class="headline-small poppin weight-600 left-margin-16 top-margin-8">Acciones</span>

          <div class="simple-container">
            <button class="style-4 background on-background-text dm-sans" onclick="window.location.href='mailto:stepbro.corp@gmail.com'; return false;" data-flip-id="animate">
              <md-icon class="filled primary-text">forum</md-icon>
              <span id="copy-contact-email-value">Contáctanos</span>
              <md-ripple></md-ripple>
            </button>
          </div>
          <div class="simple-container">
            <button class="style-4 background on-background-text dm-sans" onclick="toggleWindow('#window-privacy')" data-flip-id="animate">
              <md-icon class="filled primary-text">policy</md-icon>
              <span id="copy-contact-email-value">Política de privacidad</span>
              <md-ripple></md-ripple>
            </button>
          </div>

          <div class="simple-container">
            <button class="style-4 background on-background-text dm-sans" onclick="toggleWindow('#window-credits')" data-flip-id="animate">
              <md-icon class="filled primary-text">info</md-icon>
              <span id="copy-contact-email-value">Ver créditos</span>
              <md-ripple></md-ripple>
            </button>
          </div>
            
        </div>

      </div>
    </div>

    
  

  <style>


    .landing-page .title-text{
      font-size:24px;
      font-family: 'DM Sans', sans-serif;
      user-select: none;
      font-weight:400;
    }

    .landing-page .main-title{
      font-size:64px !important;
      /* font-family: "Bricolage Grotesque", system-ui !important; */
      font-family: 'DM Sans', sans-serif !important;
      color:var(--md-sys-color-on-background);
      user-select: none;
      font-weight:600;
      line-height:0.88;
    } 
    .landing-page .main-image{
      width:100%;
      border-radius:64px;
      background: var(--md-sys-color-surface-container-lowest)
    }
    
    .landing-page .other-title-container{
      left:64px;
      top:64px
      /* left: 50%; */
      /* top: 50%; */
      /* transform: translate(-50%, -50%); */
    }
    .landing-page .other-title{
      font-size:8vw;
      mix-blend-mode: difference;
      color: var(--md-sys-color-background);
    }
    .translucid-background{
      background: rgba(255,255,255,0.8);
      backdrop-filter: blur(16px);
    }


    @media (prefers-color-scheme: dark) {
      .landing-page .other-title{
        color: var(--md-sys-color-on-background) !important;
      }

      .translucid-background{
        background: rgba(0,0,0,0.5);
      }
    }

    

    [only-on-mobile]{display:none;}
    [hide-on-mobile]{display:flex;}
    [hide-on-mobile][dark-mode]{display:none;}
    @media only screen and (max-width: 680px){
      .manual-height-grow{
        min-height:400px;
      }
      .direction-column-on-mobile{
        flex-direction:column;
      }
      .text-center-on-mobile{
        text-align:center;
      }
      .justify-center-on-mobile{
        justify-content:center;
      }
      .landing-page .other-title-container{
        left:0;
        top:0;
      }
      .landing-page .other-title{
        font-size:16vw;
        text-align:center;
      }

      [only-on-mobile]{display:flex;}
      [hide-on-mobile]{display:none;}

      .main-title-parent{
        text-align:center;
        align-items:center;
      }

      .landing-page .main-title{
        font-size:64px !important;
        line-height:1;
      }
    

      .landing-page .main-image{
        border-radius:16px;
      }
    }

    @media only screen and (max-width: 680px) and (prefers-color-scheme: light){
      [only-on-mobile][dark-mode]{display:none ;}
    }
    @media only screen and (min-width: 680px) and (prefers-color-scheme: dark){
      [hide-on-mobile][dark-mode]{display:flex;}
      [hide-on-mobile][light-mode]{display:none;}
    }
    @media only screen and (max-width: 680px) and (prefers-color-scheme: dark){
      [only-on-mobile][light-mode]{display:none;}
    }

  </style>
 
</section>



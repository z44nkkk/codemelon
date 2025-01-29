<window 
    id="window-settings"
    class="increased semi-slim on-background-text"
    data-flip-id="animate"
    >
    <div class="simple-container padding-16">
        <md-icon-button onclick="toggleWindow()"><md-icon>close</md-icon></md-icon-button>
    </div>
    <holder>
        <div class="simple-container gap-16 grow-1 w-section-holder">
            <div class="w-nav simple-container direction-column w-nav-parent" style="z-index:1">
                <!-- <span class="body-large bottom-margin-8 on-surface-variant-text">Configuración</span> -->
                <button 
                    class="w-nav-button"
                    data-w-section="w-section-account"
                    onclick="toggleWSection('w-section-account', this)"
                    active
                    >
                    <md-ripple></md-ripple>
                    <md-icon>account_circle</md-icon>
                    <span>Cuenta</span>
                </button>
                <button 
                    class="w-nav-button"
                    data-w-section="w-section-appearance"
                    onclick="toggleWSection('w-section-appearance', this)"
                    >
                    <md-ripple></md-ripple>
                    <md-icon>palette</md-icon>
                    <span>Apariencia</span>
                </button>
                <button 
                    class="w-nav-button"
                    data-w-section="w-section-information"
                    onclick="toggleWSection('w-section-information', this)"
                    >
                    <md-ripple></md-ripple>
                    <md-icon>info</md-icon>
                    <span>Información</span>
                </button>
                <?php 
                    if(isset($_SESSION["additional_data"])){
                        if($_SESSION['additional_data']['permissions'] == 7){
                            echo "
                                <button 
                                    class='w-nav-button'
                                    onclick='changeWindow(\"#window-admin-panel\"); AdminPanel.syncAdminPanel();'
                                    >
                                    <md-ripple></md-ripple>
                                    <md-icon>admin_panel_settings</md-icon>
                                    <span>Panel de admin</span>
                                </button>
                            ";
                        }
                    }
                ?>
            </div>

            <div class="w-section simple-container direction-column grow-1 gap-8" active id="w-section-account">
                <div class="simple-container direction-row justify-center">
                    <?php 
                        if(isset($_SESSION["additional_data"])){
                            if($_SESSION["additional_data"]["profile_picture"] != ""){
                                $picture = $_SESSION["additional_data"]["profile_picture"];
                                echo "<span class='simple-container overflow-hidden border-radius-64'><img class='width-100' src='$picture'></span>";
                            }else{
                                echo '
                                    <div class="simple-container padding-40 border-radius-64 surface-variant relative user-select-none">
                                        <span id="response-settings-account-username-first-letter" class="display-large absolute-centered bricolage weight-600">...</span>
                                    </div>
                                ';
                            }
                        }
                    ?>
                    
                </div>
                <div class="simple-container justify-center">
                    <span id="response-settings-account-username-title" class="body-large">...</span>
                </div>
                <div class="simple-container direction-column v-margin gap-8">
                    
                    <div class="content-box direction-row light-color padding-24 border-radius-16 justify-between">
                        <div class="simple-container"><span class="label-large">Correo</span></div>
                        <div class="simple-container"><span id="response-settings-account-email" class="body-large">...</span></div>
                    </div>
                    <div class="content-box direction-column light-color padding-24 border-radius-16 justify-between">
                        <div class="simple-container justify-between">
                            <div class="simple-container"><span class="label-large">Nombre de usuario</span></div>
                            <div class="simple-container"><span id="response-settings-account-username" class="body-large">...</span></div>
                        </div>
                        <div class="simple-container justify-right">
                            <span 
                                class="label-small data-line interactive"
                                onclick="toggleDialog('dialog-account')"
                                >
                                <md-ripple aria-hidden="true"></md-ripple>
                                Editar
                            </span>
                        </div>
                    </div>
                    <div 
                        class="content-box direction-row light-color padding-16 border-radius-16 justify-center user-select-none cursor-pointer"
                        onclick="toggleDialog('dialog-logout-confirmation')"
                        >
                        <md-ripple></md-ripple>
                        <span class="body-medium error-text">Cerrar sesión</span>
                    </div>

                </div>
            </div>
            <div class="w-section simple-container direction-column grow-1" id="w-section-appearance">
                <div class="simple-container direction-column grow-1 gap-16">
                    <div class="simple-container gpa-8 direction-column">
                        <span class="headline-medium">Apariencia</span>
                        <span class="body-large outline-text">Modifica la apariencia de la app a un color de tu preferencia</span>
                    </div>
                    <div class="theme-selector-parent v-margin" id="app-theme-selector-parent">
                        <div 
                            class="ball" 
                            data-theme="black"
                            onclick="changeTheme(this)" 
                            >
                            <span style="background:#000000;"></span>
                            <span style="background:#122644;"></span>
                            <span style="background:#b4c7ed;"></span>
                            <md-ripple></md-ripple>
                        </div>
                        <div 
                            class="ball" 
                            data-theme="blue"
                            onclick="changeTheme(this)"
                            >
                            <span style="background:#0045b2;"></span>
                            <span style="background:#0066ff;"></span>
                            <span style="background:#b3c5ff;"></span>
                            <md-ripple></md-ripple>
                        </div>
                        <div 
                            class="ball" 
                            data-theme="green"
                            onclick="changeTheme(this)"
                            >
                            <span style="background:#006d34;"></span>
                            <span style="background:#5de989;"></span>
                            <span style="background:#9fffb3;"></span>
                            <md-ripple></md-ripple>
                        </div>
                        <div 
                            class="ball" 
                            data-theme="brown"
                            onclick="changeTheme(this)"
                            >
                            <span style="background:#944b00;"></span>
                            <span style="background:#ff9947;"></span>
                            <span style="background:#ffbc8d;"></span>
                            <md-ripple></md-ripple>
                        </div>
                        <div 
                            class="ball" 
                            data-theme="cold-blue"
                            onclick="changeTheme(this)"
                            >
                            <span style="background:#006590;"></span>
                            <span style="background:#55c0ff;"></span>
                            <span style="background:#96d3ff;"></span>
                            <md-ripple></md-ripple>
                        </div>
                        <div 
                            class="ball" 
                            data-theme="pink"
                            onclick="changeTheme(this)"
                            >
                            <span style="background:#8900a3;"></span>
                            <span style="background:#c400e8;"></span>
                            <span style="background:#f7acff;"></span>
                            <md-ripple></md-ripple>
                        </div>
                        <div 
                            class="ball" 
                            data-theme="purple"
                            onclick="changeTheme(this)"
                            >
                            <span style="background:#5e00d0;"></span>
                            <span style="background:#853fff;"></span>
                            <span style="background:#d2bcff;"></span>
                            <md-ripple></md-ripple>
                        </div>
                        <div 
                            class="ball" 
                            data-theme="oled"
                            onclick="changeTheme(this)"
                            active
                            >
                            <span style="background:#000000;"></span>
                            <span style="background:#0f0f0f;"></span>
                            <span style="background:#0a0a0a;"></span>
                            <md-ripple></md-ripple>
                        </div>
                        <div 
                            class="ball" 
                            data-theme="super-blue"
                            onclick="changeTheme(this)"
                            >
                            <span style="background:#0028db;"></span>
                            <span style="background:#4058ff;"></span>
                            <span style="background:#afb8ff;"></span>
                            <md-ripple></md-ripple>
                        </div>
                        <div 
                            class="ball" 
                            data-theme="red"
                            onclick="changeTheme(this)"
                            >
                            <span style="background:#a50012;"></span>
                            <span style="background:#db3331;"></span>
                            <span style="background:#ff958b;"></span>
                            <md-ripple></md-ripple>
                        </div>
                        <!-- <div 
                            class="ball" 
                            data-theme="frutiger-aero"
                            onclick="changeTheme(this)"
                            >
                            <span style="background:blue;"></span>
                            <span style="background:cyan;"></span>
                            <span style="background:red;"></span>
                            <md-ripple></md-ripple>
                        </div> -->
                        <div 
                            class="ball" 
                            data-theme="modern-1"
                            onclick="changeTheme(this)"
                            >
                            <span style="background:linear-gradient(45deg   , #E7A4DC 0%, #D56CC5 53.2%, #6042E1 100%)"></span>
                            <span style="background:linear-gradient(180deg, #E7A4DC 0%, #D56CC5 53.2%, #6042E1 100%)"></span>
                            <span style="background:linear-gradient(0deg, #E7A4DC 0%, #D56CC5 10.2%, #6042E1 100%)"></span>
                            <md-ripple></md-ripple>
                        </div>
                    </div>
                    <div class="simple-container">
                        <div 
                            class="content-box direction-row padding-16 border-radius-16 justify-center user-select-none cursor-pointer"
                            onclick="resetTheme()"
                            >
                            <md-ripple></md-ripple>
                            <span class="body-medium">Restablecer tema</span>
                        </div>
                    </div>
                    <div class="simple-container direction-column gap-16 hide-on-mobile">
                        <div class="simple-container gpa-8 direction-column">
                            <span class="headline-small">Navegación</span>
                            <span class="body-large on-surface-variant-text">Elige el estilo de navegación que más te guste </span>
                        </div>
                        <div class="simple-container nav-selector-parent" id="nav-selector-parent">
                            <div 
                                class="nav-option" 
                                data-nav-option="1"
                                onclick="changeNav(this)"
                                >
                                <md-ripple></md-ripple>
                                Clásica
                            </div>
                            <div 
                                class="nav-option"
                                data-nav-option="2"
                                onclick="changeNav(this)" 
                                active
                                >
                                <md-ripple></md-ripple>
                                Moderna
                            </div>
                            <div 
                                class="nav-option"
                                data-nav-option="3"
                                onclick="changeNav(this)"
                                >
                                <md-ripple></md-ripple>
                                Inferior
                            </div>
                            <div 
                                class="nav-option"
                                data-nav-option="2 glass-nav"
                                onclick="changeNav(this)"
                                >
                                <md-ripple></md-ripple>
                                Detallada
                            </div>
                            <div 
                                class="nav-option"
                                data-nav-option="6"
                                onclick="changeNav(this)"
                                >
                                <md-ripple></md-ripple>
                                Interesante
                            </div>
                            <div 
                                class="nav-option"
                                data-nav-option="7"
                                onclick="changeNav(this)"
                                >
                                <md-ripple></md-ripple>
                                Dock
                            </div>
                            <div 
                                class="nav-option"
                                data-nav-option="8"
                                onclick="changeNav(this)"
                                >
                                <md-ripple></md-ripple>
                                Bonita
                            </div>
                        </div>
                        <div class="simple-container">
                            <span class="label-large outline-text">
                                Los cambios solo se verán reflejados dentro de una Stepbro App
                            </span>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="w-section simple-container direction-column gap-16 grow-1" id="w-section-information">
                <div class="simple-container gpa-8 direction-column">
                    <span class="headline-medium" data-shared_element_info_title>Información</span>
                    <span class="body-large outline-text" data-shared_element_stepbro_notes_subtitle>Información sobre Stepbro Apps y Desarrolladores</span>
                </div>

                <div class="simple-container direction-column grow-1 gap-8 on-background-text">
                    <div 
                        class="
                            content-box 
                            align-center 
                            direction-row
                            padding-24 
                            border-radius-16 
                            justify-between 
                            cursor-pointer
                            hover-outline
                        "
                        data-shared_element_stepbro_build_info_container
                        onclick="toggleWSection('#w-sub-section-stepbro_build_info')"
                        >
                        <md-ripple></md-ripple>
                        <div class="label-large">Codemelon Build</div>
                        <span class="label-large simple-container align-center gap-8">
                            Información
                            <md-icon class=" filled">arrow_circle_right</md-icon>
                        </span>
                    </div>

                    <div 
                        class="
                            content-box 
                            align-center 
                            direction-row
                            padding-24 
                            border-radius-16 
                            justify-between 
                            cursor-pointer
                            hover-outline
                        "
                        data-shared_element_stepbro_notes_app_info_container
                        onclick="toggleWSection('#w-sub-section-notes_app_info')"
                        >
                        <md-ripple></md-ripple>
                        <div class="label-large">Melon Mind</div>
                        <span class="label-large simple-container align-center gap-8">
                            Información
                            <md-icon class=" filled">arrow_circle_right</md-icon>
                        </span>
                    </div>
                   
                </div>
                

            </div>

            <div class="w-section simple-container direction-column gap-16  grow-1" id="w-sub-section-notes_app_info">
                
                <div class="simple-container">
                    <md-icon-button onclick="toggleWSection('#w-section-information')"><md-icon>arrow_back</md-icon></md-icon-button>
                </div>
                <div class="simple-container gpa-8 direction-column">
                    <span class="headline-medium fit-content dm sans" data-shared_element_info_title>Melon Mind</span>
                    <span class="body-large outline-text">Información sobre la app y el desarrollador</span>
                </div>
                <div class="simple-container direction-column gap-8" data-shared_element_stepbro_notes_app_info_container>
                    <div class="content-box direction-row padding-24 border-radius-16 justify-between">
                        <div class="simple-container"><span class="label-large">Versión</span></div>
                        <div class="simple-container"><span class="body-large
                        ">Beta 1</span></div>
                    </div>

                    <div class="content-box padding-8 border-radius-16 on-background-tex">
                        <div class="simple-container direction-column gap-8 b-padding-8 padding-16">
                            <span class="label-large">Créditos</span>
                        </div>
                        
                        <div class="content-box light-color padding-24 border-radius-8 justify-between">
                            <div class="simple-container direction-column gap-8">
                                <div class="simple-container direction-column gap-4">
                                    <span class="headline-small bricolage weight-500">Luis David Elizarraraz Mondaca</span>
                                    <span class="body-medium outline-text">Desarrollador Full Stack | Arquitecto de Software | Diseñador de UI/UX | Fundador del proyecto Melon Mind con iniciativa como Stepbro Mind</span>
                                </div>

                                <div class="simple-container top-margin-8 gap-8">

                                    <a href="https://www.youtube.com/@stepbro_davo" target="_blank" class="content-box outline-light-1 width-auto cursor-pointer border-radius-8 padding-16">
                                        <md-ripple></md-ripple>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M23.498 6.18598C23.3624 5.67526 23.095 5.20912 22.7226 4.83425C22.3502 4.45937 21.8858 4.18892 21.376 4.04998C19.505 3.54498 12 3.54498 12 3.54498C12 3.54498 4.495 3.54498 2.623 4.04998C2.11341 4.18917 1.64929 4.45972 1.27708 4.83456C0.904861 5.20941 0.637591 5.67542 0.502 6.18598C0 8.06998 0 12 0 12C0 12 0 15.93 0.502 17.814C0.637586 18.3247 0.904975 18.7908 1.27739 19.1657C1.64981 19.5406 2.11418 19.811 2.624 19.95C4.495 20.455 12 20.455 12 20.455C12 20.455 19.505 20.455 21.377 19.95C21.8869 19.8111 22.3513 19.5407 22.7237 19.1658C23.0961 18.7909 23.3635 18.3248 23.499 17.814C24 15.93 24 12 24 12C24 12 24 8.06998 23.498 6.18598ZM9.545 15.568V8.43198L15.818 12L9.545 15.568Z" fill="var(--md-sys-color-on-background)"/>
                                        </svg>
                                    </a>
                                    <!-- <a href="https://github.com/srdavo" target="_blank" class="content-box outline-light-1 width-auto cursor-pointer border-radius-8 padding-16">
                                        <md-ripple></md-ripple>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <g clip-path="url(#clip0_58_7)">
                                            <path d="M12 0.296997C5.37 0.296997 0 5.67 0 12.297C0 17.6 3.438 22.097 8.205 23.682C8.805 23.795 9.025 23.424 9.025 23.105C9.025 22.82 9.015 22.065 9.01 21.065C5.672 21.789 4.968 19.455 4.968 19.455C4.422 18.07 3.633 17.7 3.633 17.7C2.546 16.956 3.717 16.971 3.717 16.971C4.922 17.055 5.555 18.207 5.555 18.207C6.625 20.042 8.364 19.512 9.05 19.205C9.158 18.429 9.467 17.9 9.81 17.6C7.145 17.3 4.344 16.268 4.344 11.67C4.344 10.36 4.809 9.29 5.579 8.45C5.444 8.147 5.039 6.927 5.684 5.274C5.684 5.274 6.689 4.952 8.984 6.504C9.944 6.237 10.964 6.105 11.984 6.099C13.004 6.105 14.024 6.237 14.984 6.504C17.264 4.952 18.269 5.274 18.269 5.274C18.914 6.927 18.509 8.147 18.389 8.45C19.154 9.29 19.619 10.36 19.619 11.67C19.619 16.28 16.814 17.295 14.144 17.59C14.564 17.95 14.954 18.686 14.954 19.81C14.954 21.416 14.939 22.706 14.939 23.096C14.939 23.411 15.149 23.786 15.764 23.666C20.565 22.092 24 17.592 24 12.297C24 5.67 18.627 0.296997 12 0.296997Z" fill="var(--md-sys-color-on-background)"/>
                                            </g>
                                            <defs>
                                            <clipPath id="clip0_58_7">
                                            <rect width="24" height="24" fill="white"/>
                                            </clipPath>
                                            </defs>
                                        </svg>
                                    </a>   -->
                                    <a href="mailto:luisdavid.gris@gmail.com" target="_blank" class="content-box outline-light-1 width-auto cursor-pointer border-radius-8 padding-16">
                                        <md-ripple></md-ripple>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <g clip-path="url(#clip0_59_10)">
                                            <path d="M24 5.45703V19.366C24 20.27 23.268 21.002 22.364 21.002H18.545V11.73L12 16.64L5.455 11.73V21.003H1.636C1.42107 21.003 1.20825 20.9607 1.0097 20.8784C0.811145 20.7961 0.63075 20.6755 0.47882 20.5235C0.32689 20.3715 0.206404 20.191 0.124246 19.9924C0.0420884 19.7938 -0.000131068 19.581 3.05652e-07 19.366V5.45703C3.05652e-07 3.43403 2.309 2.27903 3.927 3.49303L5.455 4.64003L12 9.54803L18.545 4.63803L20.073 3.49303C21.69 2.28003 24 3.43403 24 5.45703Z" fill="var(--md-sys-color-on-background)"/>
                                            </g>
                                            <defs>
                                            <clipPath id="clip0_59_10">
                                            <rect width="24" height="24" fill="white"/>
                                            </clipPath>
                                            </defs>
                                        </svg>
                                    </a>
                                    
                                </div>

                            </div>    
                        </div>

                        
                    </div>

                </div>
                
            </div>

            <div class="w-section simple-container direction-column gap-16  grow-1" id="w-sub-section-stepbro_build_info">
                <div class="simple-container">
                    <md-icon-button onclick="toggleWSection('#w-section-information')"><md-icon>arrow_back</md-icon></md-icon-button>
                </div>
                <div class="simple-container gpa-8 direction-column">
                    <span class="headline-medium fit-content" data-shared_element_info_title>Codemelon Build</span>
                    <span class="body-large outline-text">Información sobre la app y el desarrollador</span>
                </div>
                <div class="simple-container direction-column gap-8" data-shared_element_stepbro_build_info_container>
                    <div class="content-box direction-row padding-24 border-radius-16 justify-between">
                        <div class="simple-container"><span class="label-large">Versión</span></div>
                        <div class="simple-container"><span class="body-large
                        ">Beta 1</span></div>
                    </div>   
                </div>    
            </div>

        </div>
    
    </holder>

</window>

<md-dialog id="dialog-account" style="min-width: calc(-1600px + 100vw)">
  <div slot="headline">Cuenta</div>
  <form id="form-dialog-account" slot="content" method="dialog">
    <md-list style="border-radius:16px;">
      <md-list-item>
        <md-icon slot="start">tag</md-icon>
        <div slot="headline" id="response-account-id">...</div>
      </md-list-item>
      <md-list-item>
        <md-icon slot="start">mail</md-icon>
        <div slot="headline" id="response-account-email">...</div>
      </md-list-item>
    </md-list>
    <div class="simple-container direction-column gap-8 v-margin">

      <md-outlined-text-field 
        id="modify-account-username"
        label="Nombre de usuario" 
        role="presentation"
        style="margin-top:8px;"
        >
      </md-outlined-text-field>
    </div>
  </form>
  <div slot="actions">
    <md-text-button form="form-dialog-account" value="cancel">Cancelar</md-text-button>
    <md-filled-tonal-button form="form-dialog-account" onclick="modifyUserData()" value="save">Guardar</md-filled-tonal-button>
  </div>
</md-dialog>

<md-dialog id="dialog-logout-confirmation">
  <div slot="headline">Cerrar sesión</div>
  <md-icon slot="icon" aria-hidden="true">logout</md-icon>
  <form id="form-dialog-logout-confirmation" slot="content" method="dialog">
    ¿Estas seguro de que quieres cerrar sesión?
  </form>
  <div slot="actions">
    <md-text-button form="form-dialog-logout-confirmation" value="cancel">Cancelar</md-text-button>
    <md-filled-tonal-button value="save" onclick="logOut()">Cerrar sesión</md-filled-tonal-button>
  </div>
</md-dialog>

<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        // getUserData();
        syncUserData();
    });
</script>
<window 
    id="window-admin-panel"
    class="increased full-size"
    data-flip-id="animate"
    >
    <div class="simple-container padding-16">
        <md-text-button onclick="toggleWindow()"><md-icon slot="icon">exit_to_app</md-icon>Salir del panel</md-text-button>
    </div>
    <holder>
        <div class=" simple-container direction-column grow-1 align-center on-background-text gap-16">
            <div class="w-nav simple-container direction-column width-100 max-width-1200 overflow-hidden gap-16">
                <div class="simple-container gap-8">
                    <button 
                        class="w-nav-button style-2"
                        data-w-section="w-section-admin-panel-users"
                        onclick="toggleWSection('w-section-admin-panel-users', this)"
                        active
                        >
                        <md-ripple></md-ripple>
                        <md-icon>home</md-icon>
                        <span>Inicio</span>
                    </button>
                    <button 
                        class="w-nav-button style-2"
                        data-w-section="w-section-admin-panel-app-access"
                        onclick="toggleWSection('w-section-admin-panel-app-access', this)"
                        >
                        <md-ripple></md-ripple>
                        <md-icon>open_in_phone</md-icon>
                        <span>Accesos</span>
                    </button>
                    <button 
                        class="w-nav-button style-2"
                        data-w-section="w-section-admin-panel-suggestions"
                        onclick="toggleWSection('w-section-admin-panel-suggestions', this)"
                        >
                        <md-ripple></md-ripple>
                        <md-icon>feedback</md-icon>
                        <span>Sugerencias</span>
                    </button>

                    <button 
                        class="w-nav-button style-2"
                        data-w-section="w-section-admin-panel-email"
                        onclick="toggleWSection('w-section-admin-panel-email', this)"
                        >
                        <md-ripple></md-ripple>
                        <md-icon>email</md-icon>
                        <span>Email</span>
                    </button>
                </div>
                <div class="simple-container direction-column v-margin-large">
                    <span class="display-small dm-sans weight-500 on-background-text">Panel de administrador</span>
                    <span class="body-large dm-sans outline-text">
                        Bienvenido al panel de administrador. Aquí podrás gestionar los usuarios y sus datos.
                    </span>
                </div>
            </div>
            <div 
                class="w-section simple-container direction-column width-100 max-width-1200 overflow-hidden gap-16" 
                id="w-section-admin-panel-users"
                active
                >

                <div class="simple-container gap-8 flex-wrap">
                    <div class="content-box basis-normal grow-1 overflow-hidden rounded gap-0 outline-1-light-inset">
                        <span class="body-large">Usuarios totales</span>
                        <span class="display-large weight-bold" id="response-admin-panel-total-users">...</span>
                        <md-icon class="absolute-card" aria-hidden="true">person_add</md-icon>
                    </div>
                </div>
                
                <div class="simple-container direction-column overflow-auto padding-1">
                    <table 
                        class="style-2"
                        id="response-users-table"
                    >
                    </table>
                    <div class="simple-container width-100 container-info-empty-table grow-1"></div>
                    <div class="simple-container" id="pagination-users-table"></div>  
                </div>
                
                    
            </div>
            <div
                class="w-section simple-container direction-column width-100 max-width-1200 overflow-hidden gap-16" 
                id="w-section-admin-panel-app-access"
                >
                <div class="simple-container gap-8 flex-wrap">
                    <div class="content-box basis-normal grow-1 overflow-hidden rounded gap-0 outline-1-light-inset cursor-pointer">
                        <md-ripple></md-ripple>
                        <span class="body-large">Accesos totales</span>
                        <span class="display-large weight-bold" id="response-admin-panel-total-access">...</span>
                        <md-icon class="absolute-card" aria-hidden="true">open_in_phone</md-icon>
                    </div>
                </div>

                <div class="simple-container direction-column overflow-auto padding-1">
                    <table 
                        class="style-2"
                        id="response-admin-panel-access-table"
                    >
                    </table>
                    <div class="simple-container width-100 container-info-empty-table grow-1"></div>
                    <div class="simple-container" id="pagination-admin-panel-access-table"></div>  
                </div>

            </div>
            <div
                class="w-section simple-container direction-column width-100 max-width-1200 overflow-hidden gap-16" 
                id="w-section-admin-panel-suggestions"
                >
                <div class="simple-container gap-8 flex-wrap">
                    <div class="content-box basis-normal grow-1 overflow-hidden rounded gap-0 outline-1-light-inset cursor-pointer">
                        <md-ripple></md-ripple>
                        <span class="body-large">Sugerencias totales</span>
                        <span class="display-large weight-bold" id="response-admin-panel-total-suggestions">...</span>
                        <md-icon class="absolute-card" aria-hidden="true">feedback</md-icon>
                    </div>
                </div>

                <div class="simple-container direction-column overflow-auto padding-1">
                    <table 
                        class="style-2"
                        id="response-admin-panel-suggestions-table"
                    >
                    </table>
                    <div class="simple-container width-100 container-info-empty-table grow-1"></div>
                    <div class="simple-container" id="pagination-admin-panel-suggestions-table"></div>  
                </div>
            </div>

            <div
                class="w-section simple-container direction-column width-100 max-width-1200 overflow-hidden gap-16" 
                id="w-section-admin-panel-email"
                >
                <!-- <div class="simple-container gap-8 flex-wrap">
                    <div class="content-box basis-normal grow-1 overflow-hidden rounded gap-0 outline-1-light-inset cursor-pointer">
                        <md-ripple></md-ripple>
                        <span class="body-large">Opciones de correo</span>
                        <span class="display-large weight-bold" id="response-admin-panel-total-suggestions">4</span>
                        <md-icon class="absolute-card" aria-hidden="true">email</md-icon>
                    </div>
                </div> -->

                <div class="simple-container direction-column gap-8 max-width-800" id="admin-panel-form-send-user-email">
                    
                    <div class="simple-container justify-between gap-8">
                        <span class="headline-medium dm-sans" id='Email-label'>Enviar correo</span>
                        <select name="email-options" id="email-options">
                            <option value="1" selected>Enviar un correo</option>
                        </select>
                    </div>       
                    
                    <md-outlined-text-field label="Asunto" name="subject" id="headerEmail"></md-outlined-text-field>
                    <md-outlined-text-field label="Mensaje" type="textarea" id="contentEmail"></md-outlined-text-field>


                    <!-- <label for="subject">Asunto:</label>
                    <input type="text" name="subject" placeholder="Asunto del correo" id='headerEmail'> -->
                        
                    <!-- <label for="message">Mensaje:</label>
                    <input type="text" id='contentEmail'> -->




                    <div id='emailOption1' class='active simple-container direction-column top-margin-16 gap-16'>
                        <!-- <input type="text" id="buscar" placeholder="Buscar usuarios..." /> -->
                        <md-outlined-text-field id="admin-panel-email-user-search" label="Busca usuarios"></md-outlined-text-field>
                        <div id="admin-panel-email-user-search-result" class="simple-container direction-column gap-4"></div>

                        <div class="content-box light-color padding-16 border-radius-16 user-select-none">
                            <span class="label-normal outline-text">Usuarios seleccionados</span>
                            <div id="admin-panel-email-selected-users" class="simple-container direction-column gap-4"></div>
                        </div>

                        <div class="simple-container justify-right">
                            <md-filled-button id='sendEmail'><md-icon slot="icon">send</md-icon>Enviar correo</md-filled-button>
                        </div>
                        <!-- <input type="button" value='Enviar correo' id='sendEmail'> -->
      
                    </div>

                </div>

                <div id='emailOption2' class='hidden'>
                    <input type="button" value='Enviar correo' id='sendEmailAllUsers'>    
                </div>


            </div>
            
        </div>

    </holder>
</window>
<script src="<?= BASE_URL ?>js/admin-functions.js"></script>
<section id="section-home" active class="direction-row flex-wrap">


    <!-- column 1 -->
    <div class="simple-container grow-1 basis-large direction-column gap-8">
        <div class="simple-container direction-column padding-16">
            <span class="headline-small dm-sans">Hola, Ana Delia</span>
            <span class="body-large outline-text dm-sans">3 de Febrero</span>
        </div>

        <div class="simple-container flex-wrap gap-8">
            <div class="content-box padding-32 primary-container on-primary-container-text gap-0 rounded grow-1 basis-normal">
                <span class="headline-small dm-sans opacity-0-8">Hoy tienes</span>
                <span class="display-medium weight-600 dm-sans line-height-1">5 citas</span>
                <div class="simple-container position-absolute bottom-16 right-16">
                    <button
                        title="Agendar cita este día"
                        onclick="ApptsManager.openCreateApptWindow({openStyle: 'absolute'})"
                        data-flip-id="animate" 
                        class="style-5 on-primary-container primary-container-text hover-scale-small"
                        >
                        <md-ripple></md-ripple>
                        <md-icon>calendar_add_on</md-icon>
                    </button>
                </div>
            </div>

            <div class="content-box padding-32 background outline-light-1 gap-0 overflow-hidden justify-center rounded grow-1 basis-normal">
                <span class="headline-small dm-sans opacity-0-8">Tu próxima cita:</span>
                <div class="simple-container overflow-hidden">
                    <!-- <span class="headline-medium weight-600 dm-sans" style="text-wrap: nowrap;text-overflow: ellipsis;">1:00 PM, Yahel Ramirez Felix</span> -->
                </div>
                <span class="headline-medium weight-600 dm-sans">1:00 PM, Yahel</span>
                
            </div>

        </div>

        
        
        <div class="content-box background outline-light-1 padding-8 grow-1">
            <div class="simple-container direction-column padding-16 b-padding-8">
                <span class="body-large dm-sans">Citas de hoy</span>
                <span class="headline-medium weight-500 dm-sans">3 de Febrero</span>
            </div>
    
            <div class="simple-container left-margin-16 bottom-margin-16 gap-8">
                <button class="style-2" active>Pendientes</button>
                <button class="style-2">Completadas</button>
                <!-- <span class="label-large outline-text">Pendientes</span> -->
            </div>
    
            <div 
                class="
                    content-box
                    border-radius-16
                    padding-16
                    "
                >
                <!-- <span class="label-medium outline-text">Completada</span> -->
                <span class="body-large">1:00 PM, Yahel Ramírez Félix</span>
                <div class="simple-container gap-8">
                    <button class="style-2 "active>
                        <md-ripple></md-ripple>
                        <md-icon class="dynamic">check</md-icon>
                        Marcar completada
                    </button>
    
                    <button class="style-2 primary-container">
                        <md-ripple></md-ripple>
                        <md-icon class="dynamic">edit</md-icon>
                        Editar
                    </button>
    
                </div>
            </div>
            <div 
                class="
                    content-box
                    border-radius-16
                    padding-16
                    "
                >
                <!-- <span class="label-medium outline-text">Pendiente</span> -->
                <span class="body-large">2:00 PM, Victor Mendevil Espinoza</span>
                <div class="simple-container gap-8">
                    <button class="style-2 primary-container" active>
                        <md-ripple></md-ripple>
                        <md-icon class="dynamic">check</md-icon>
                        Marcar completada
                    </button>
    
                    <button class="style-2 primary-container">
                        <md-ripple></md-ripple>
                        <md-icon class="dynamic">edit</md-icon>
                        Editar
                    </button>
    
                </div>
            </div>
            <div 
                class="
                    content-box
                    border-radius-16
                    padding-16
                    "
                >
                <!-- <span class="label-medium outline-text">Pendiente</span> -->
                <span class="body-large">3:00 PM, Sebastían Osuna Anaya</span>
                <div class="simple-container gap-8">
                    <button class="style-2 primary-container" active>
                        <md-ripple></md-ripple>
                        <md-icon class="dynamic">check</md-icon>
                        Marcar completada
                    </button>
    
                    <button class="style-2 primary-container">
                        <md-ripple></md-ripple>
                        <md-icon class="dynamic">edit</md-icon>
                        Editar
                    </button>
    
                </div>
            </div>
            
    
        </div>
    </div>


    <!-- column 2 -->
    <div class="simple-container grow-1 basis-large direction-column ">
        <cocounut-chart data-x-values="[&quot;2025-01-09&quot;,&quot;2025-01-10&quot;,&quot;2025-01-13&quot;,&quot;2025-01-14&quot;,&quot;2025-01-15&quot;,&quot;2025-01-17&quot;,&quot;2025-01-20&quot;,&quot;2025-01-21&quot;,&quot;2025-01-24&quot;,&quot;2025-01-27&quot;,&quot;2025-01-28&quot;,&quot;2025-01-29&quot;]" data-y-values="[1000,1000,500,1000,500,1850,1000,350,2000,1000,1000,3500]" data-chart-title="Ingresos por día" data-x-values-type="date" data-y-values-type="money"></cocounut-chart>
    </div>


    <!-- <div class="simple-container gap-8 flex-wrap">

        <div class="content-box background outline-light-1 gap-0 width-auto grow-1 basis-normal padding-16 border-radius-16">
            <span class="label-large dm-sans outline-text">Hoy tienes</span>
            <span class="headline-small dm-sans">5 citas</span>
        </div>

    </div> -->


    

    

    <!-- <div class="simple-container direction-column grow-1 align-center justify-center outline-text gap-16 user-select-none">
        <md-icon class="filled pretty-minimal">border_clear</md-icon>
        <span class="headline-small dm-sans outline-text width-100 max-width-600 text-center">
            Plataforma en desarrollo <br>
        </span>
        <button 
            class="style-2 primary-container on-primary-container-text hover-scale-small" 
            onclick="toggleWindow('#window-send-suggestion')"
            data-flip-id="animate"
            >
            <md-icon class="dynamic" style="vertical-align:middle;">feedback</md-icon>
            <md-ripple></md-ripple>
            Enviar comentarios
        </button>
    
    </div> -->

    <!-- <div class="simple-container gap-8 flex-wrap">
        <md-outlined-button
            onclick="toggleWindow('#window-test-cfo')"
            data-flip-id="animate"
            >
            <md-icon slot="icon">experiment</md-icon>
            Registrar paciente para otro            
        </md-outlined-button>

        <md-outlined-button
            onclick="toggleWindow('#window-test-create_permission')"
            data-flip-id="animate"
            >
            <md-icon slot="icon">experiment</md-icon>
            Asignar permisos      
        </md-outlined-button>

        <md-outlined-button
            onclick='toggleWindow("#window-appt-data")'
            data-flip-id="animate"
            >
            Información de cita
        </md-outlined-button>
    </div>

    <div class="simple-container">
        <div class="content-box direction-row padding-16 gap-16 cursor-pointer user-select-none on-background-text">
            <md-ripple></md-ripple>
            <div class="simple-container body-large">
                <md-icon class="dynamic">circle</md-icon>
            </div>
            <div class="simple-container direction-column grow-1 gap-4">
                <span class="label-medium">28 Dic, 3:00 PM</span>
                <span class="body-large">Luis David Elizarraraz Mondaca</span>
            </div>
        </div>
    </div> -->
</section>
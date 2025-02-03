<section id="section-patients" class="direction-row overflow-hidden gap-24">
    
    <!-- main section -->
    <div class="simple-container direction-column width-100 gap-8 flex-1 overflow-auto border-radius-16">

        <div class="simple-container justify-between align-center align-center gap-8 flex-wrap">
            <span class="headline-small on-background-text dm-sans">Pacientes</span>
            <div class="simple-container gap-8 mobile-direction-row-reverse">
                <md-filled-tonal-button onclick="PatientsManager.openSearchPatientWindow()" data-flip-id="animate" class="solid"><md-icon slot="icon">search</md-icon>Búscar</md-filled-tonal-button>
                <md-filled-button onclick="PatientsManager.openCreatePatientWindow()" data-flip-id="animate"><md-icon slot="icon">person_add</md-icon>Agregar paciente</md-filled-button>
            </div>
        </div>
        <div class="simple-container gap-8 flex-wrap" id="patients-stats-data-container">
            <div class="content-box gap-0 grow-1 basis-small user-select-none light-color hover-outline overflow-hidden">
                <span class="label-normal outline-text">Totales</span>
                <span class="headline-small dm-sans on-background-text" name="total-patients">...</span>
            </div>
            <div class="content-box gap-0 grow-1 basis-small user-select-none light-color hover-outline overflow-hidden">
                <span class="label-normal outline-text">Activos</span>
                <span class="headline-small dm-sans on-background-text" name="active-patients">...</span>
            </div>
            <div class="content-box gap-0 grow-1 basis-small user-select-none light-color hover-outline overflow-hidden">
                <span class="label-normal outline-text">De alta</span>
                <span class="headline-small dm-sans on-background-text" name="discharged-patients">...</span>
            </div>
            <div class="content-box gap-0 grow-1 basis-small user-select-none light-color hover-outline overflow-hidden">
                <span class="label-normal outline-text">Inactivos</span>
                <span class="headline-small dm-sans on-background-text" name="inactive-patients">...</span>
            </div>
        </div>
        <div class="simple-container">
            <div class="simple-container gap-8 overflow-auto" id="container-patients-table-filters">
                <select name="filter-status" onchange="PatientsManager.applyTableFilters()">
                    <option value="all_status">Estado: Todos</option>
                    <option value="1">Estado: Activo</option>
                    <option value="2">Estado: Dado de alta</option>
                    <option value="3">Estado: Inactivo</option>
                </select>
                <select name="filter-order_by" onchange="PatientsManager.applyTableFilters()">
                    <option value="patient_name">Ordenar por: Nombre</option>
                    <option value="patient_status">Ordenar por: Estado</option>
                    <option value="patient_birthdate">Ordenar por: Edad</option>
                </select>
                <select name="filter-order" onchange="PatientsManager.applyTableFilters()">
                    <option value="ASC">Orden: Ascendente</option>
                    <option value="DESC">Orden: Descendente</option>
                </select>
            </div>
        </div>
        

        <div class="simple-container direction-column gap-8">
            <div id="response-container-patients_table" class="simple-container direction-column gap-4"></div>
            <div class="simple-container justify-center">
                <md-filled-tonal-button id="load-more-patients" disabled="true">Cargar más</md-filled-tonal-button>
            </div>
        </div>
        <!-- 
        <div class="simple-container gap-8">
            <md-filled-button data-flip-id="animate" onclick="toggleSubSection('#sub-section-patient-profile')">Abrir sub section</md-filled-button>
            <md-filled-button data-flip-id="animate" onclick="toggleSubSection('#sub-section-patient-profile-2')">Abrir sub section 2</md-filled-button>
            <md-filled-button data-flip-id="animate" onclick="toggleSubSection('#sub-section-patient-profile-2', {exclusive:true})">Abrir sub section 2 EXCLUSIVE</md-filled-button>
        </div> -->

    </div>


    <!-- sub section 1 -->
    <div 
        data-sub-section 
        data-flip-id="animate"
        id="sub-section-patient-profile"
        class="simple-container flex-1 on-background-text top-padding-safe-area" 
        >
        <div class="simple-container direction-column grow-1 gap-8 overflow-auto border-radius-16">

            <!-- row 1 -->
            <div class="simple-container gap-8 align-center justify-between gap-16">
                <div class="simple-container gap-8 align-center">
                    <md-icon-button onclick="toggleSubSection('#sub-section-patient-profile')"><md-icon>close</md-icon></md-icon-button>
                    <span class="headline-small on-background-text dm-sans">Perfil del paciente</span>
                </div>
                <div class="simple-container gap-8 flex-wrap mobile-direction-row-reverse">
                    <md-filled-tonal-button class="solid" onclick='toggleWindow("#window-patient-data")' data-flip-id="animate"><md-icon slot="icon">edit</md-icon>Editar datos</md-filled-tonal-button>
                    <md-filled-button data-flip-id="animate" name="button-create-appt"><md-icon slot="icon">add</md-icon>Agendar cita</md-filled-button>
                </div>
            </div>

            <!-- row 2 -->
            <div class="content-box light-color direction-row gap-24 flex-wrap justify-center" style="background: var(--md-sys-color-secondary-container););color: var(--md-sys-color-on-secondary-container);">
                <!-- <div class="simple-container"><md-icon class="pretty filled">account_circle</md-icon></div> -->
                <div class="simple-container direction-column justify-center grow-1 gap-8">
                    <span class="display-small dm-sans weight-500 line-height-0-85" name="patient-name"><span class="outline-text"><i>Nombre del paciente</i></span></span>
                    <div class="simple-container direction-column">
                        <span class="body-large" name="patient-gender">...</span>
                        <span class="body-large opacity-0-8" name="patient-age">...</span>
                        <span class="body-large opacity-0-8" name="patient-birthdate">...</span>
                    </div>
                </div>
            </div>

            <div class="simple-container ">
                <div class="simple-container direction-column grow-1 border-radius-24 overflow-hidden">
                    <md-tabs>
                        <md-secondary-tab 
                            id="md-tab-patients-general" 
                            aria-controls="md-panel-patient-general"
                            onclick="toggleMdTab(this)"
                            >
                            <md-icon slot="icon">description</md-icon>
                            Datos
                        </md-secondary-tab>
                        <md-secondary-tab 
                            id="md-tab-patients-appts" 
                            aria-controls="md-panel-patient-appts"
                            onclick="toggleMdTab(this); PatientsManager.loadPatientAppts()"
                            >
                            <md-icon slot="icon">schedule</md-icon>
                            Citas
                        </md-secondary-tab>
                    </md-tabs>
                </div>

            </div>

            <div
                data-md-panel
                active
                class="simple-container direction-column gap-8 grow-1" 
                id="md-panel-patient-general" 
                role="tabpanel" 
                aria-labelledby="md-tab-patients-general"
                >
                   
                <div class="content-box border-radius-16 padding-16 gap-8">
                    <span class="label-normal outline-text user-select-none weight-500">Estado del paciente</span>
                    <span class="body-large" name="patient-status">...</span>
                </div>

                <div class="content-box border-radius-16 padding-16 gap-8">
                    <span class="label-normal outline-text user-select-none weight-500">Contacto</span>
                    <span class="body-large" name="patient-contact_phone">...</span>
                    <span class="body-large" name="patient-contact_email">...</span>
                </div>

                <div class="simple-container">
                    <div class="content-box border-radius-16 padding-16 gap-4 grow-1">
                        <span class="label-normal outline-text user-select-none">Precio por cita</span>
                        <span class="body-large" name="patient-appt_price">...</span>
                    </div>
                </div>

                <div class="simple-container gap-8 flex-wrap">
                    <div class="content-box border-radius-16 padding-16 gap-4 grow-1 basis-normal">
                        <span class="label-normal outline-text user-select-none">Escuela</span>
                        <span class="body-large" name="patient-school">...</span>
                    </div>
                    <div class="content-box border-radius-16 padding-16 gap-4 grow-1 basis-normal">
                        <span class="label-normal outline-text user-select-none">Grado escolar</span>
                        <span class="body-large" name="patient-school_grade">...</span>
                    </div>
                </div>

                <div class="simple-container direction-column position-relative">
                    <md-filled-text-field
                        label="Notas del paciente" 
                        placeholder="Escribe aquí las notas del paciente"
                        type="textarea"
                        style="min-height: 232px;"
                        name="patient-notes"
                    ></md-filled-text-field>
                    <md-icon 
                        class="pretty small ui-confirm-note-changes position-absolute bottom-8 right-8" 
                        title="Indicador de auto guardado"
                        name='ui-indicator-note-updated'
                        >
                        cloud_done
                    </md-icon>
                </div>
                <div class="simple-container bottom-margin-24 justify-between">
                    <md-text-button onclick="TrashManager.openConfirmationDialog('patient')"><md-icon slot="icon">delete</md-icon>Eliminar paciente</md-text-button>
                    <md-text-button
                        onclick="ApptsManager.openApptLog()"
                        disabled="true"
                        title="En desarrollo..."
                        >
                        <md-icon slot="icon">history</md-icon>
                        Historial de cambios
                    </md-text-button>
                </div>
            </div>
            <div 
                data-md-panel
                class="simple-container direction-column gap-8 grow-1" 
                id="md-panel-patient-appts" 
                role="tabpanel" 
                aria-labelledby="md-tab-patient-appts"
                >
                
                <div class="simple-container gap-8 overflow-hidden">
                    <div class="simple-container gap-8 overflow-auto" id="container-patient-appts-table-filters">
                        <select 
                            name="filter-month" 
                            onchange="PatientsManager.applyTableFiltersAppts()"
                            >
                            <?php displayMonths(); ?>
                            <option value="1,2,3,4,5,6,7,8,9,10,11,12">Mes: Todos</option>
                        </select>
                        <select 
                            name="filter-year" 
                            onchange="PatientsManager.applyTableFiltersAppts()"
                            >
                            <?php displayYears(); ?>
                            <option value="2023,2024,2025,2026">Años todos</option>
                        </select>
                        <select 
                            name="filter-status"
                            onchange="PatientsManager.applyTableFiltersAppts()"
                            >
                            <option value="1,2,3">Todas las citas</option>
                            <option value="2">Citas completadas</option>
                            <option value="1">Citas pendientes</option>
                            <option value="3">Citas canceladas</option>
                        </select>
                    </div>
                </div>

                <div class="simple-container direction-column gap-8">
                    <div class="simple-container direction-column gap-4 align-center" id="response-container-patient-appts_table"></div>
                    <div class="simple-container justify-center">
                        <md-filled-tonal-button id="load-more-patient-appts" disabled="true">Cargar más</md-filled-tonal-button>
                    </div>
                </div>
            </div>
            

            

        </div>
    </div>

    <!-- sub section 2 -->
    <div 
        data-sub-section
        data-flip-id="animate"
        id="sub-section-patient-profile-2"
        class="simple-container flex-1" 
        >
        <div class="simple-container direction-column grow-1 gap-16">

            <md-filled-tonal-icon-button onclick="toggleSubSection('#sub-section-patient-profile-2')"><md-icon>close</md-icon></md-filled-tonal-icon-button>
            <span class="display-medium dm-sans">Sub section 2</span>

            <!-- name column -->
            <div class="content-box light-color border-radius-16 padding-16 gap-8">
                <span class="label-normal outline-text">Paciente</span>
                <div class="simple-container gap-8">
                    <span class="body-large data-line hover-outline cursor-pointer" name="patient-name"><span class="outline-text"><i>Nombre del paciente</i></span></span>
                    <span class="data-line body-large simple-container align-center cursor-pointer light-color"><md-ripple></md-ripple><md-icon class="dynamic outline-text">arrow_outward</md-icon></span>
                </div>
            </div>

            <div class="simple-container gap-8 flex-wrap">

                <!-- second row column 1 -->
                <div class="simple-container direction-column gap-8 grow-1 basis-normal">

                    <span class="label-normal outline-text left-margin-8">Datos generales</span>
                    <div class="content-box border-radius-16 padding-16 gap-4">
                        <span class="label-normal outline-text user-select-none">Cobro por cita</span>
                        <span class="body-large" name="appt-cost">$0.00</span>
                    </div>
                    <div class="content-box border-radius-16 padding-16 gap-4">
                        <span class="label-normal outline-text user-select-none">Concepto de la cita</span>
                        <span class="body-large" name="appt-concept">...</span>
                    </div>
                    <div class="content-box border-radius-16 padding-16 gap-4">
                        <span class="label-normal outline-text user-select-none">Estado de la cita</span>
                        <span class="body-large" name="appt-status">...</span>
                    </div>

                </div>
                <!-- second row column 2 -->
                <div class="simple-container direction-column gap-8 grow-1 basis-normal">

                    <span class="label-normal outline-text left-margin-8">Fecha y hora</span>
                    <div class="content-box border-radius-16 padding-16 gap-4">
                        <span class="label-normal outline-text user-select-none">Fecha de la cita</span>
                        <span class="body-large" name="appt-date">...</span>
                    </div>
                    <div class="content-box border-radius-16 padding-16 gap-4">
                        <span class="label-normal outline-text user-select-none">Hora de la cita</span>
                        <span class="body-large" name="appt-time">...</span>
                    </div>

                </div>
            </div>


        </div>
    </div>

</section>
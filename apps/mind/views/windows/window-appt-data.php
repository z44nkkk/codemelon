<window
    id="window-appt-data"
    data-flip-id="animate"
    class="increased semi-slim h-auto"
    >
    <div class="simple-container padding-16 justify-between flex-wrap align-center gap-8">
        <div class="simple-container gap-8 align-center">
            <md-icon-button onclick="toggleWindow()"><md-icon>close</md-icon></md-icon-button>
            <span class="headline-small on-background-text">Cita</span>
        </div>
        <div class="simple-container gap-8">
            <md-text-button
                onclick="ApptsManager.openApptLog()"
                >
                <md-icon slot="icon">history</md-icon>
                Historial de cambios
            </md-text-button>
        </div>
    </div>
    <holder class="on-background-text gap-16">
        <div class="content-box light-color border-radius-16 padding-16 gap-8">
            <span class="label-normal outline-text">Paciente</span>
            <div class="simple-container gap-8  flex-wrap">
                <span class="body-large data-line hover-outline cursor-pointer" name="patient-name"><span class="outline-text"><i>Nombre del paciente</i></span></span>
                <span class="data-line body-large simple-container align-center cursor-pointer light-color" name="patient-direct-access"><md-ripple></md-ripple><md-icon class="dynamic outline-text">arrow_outward</md-icon></span>
            </div>
        </div>

        <form 
            id="form-edit-appt"
            class="simple-container direction-column gap-16"
            >

            <div class="simple-container gap-16 flex-wrap">  

                <div class="simple-container direction-column gap-8 grow-1 basis-normal">
                    <span class="label-normal outline-text left-margin-8">Fecha y hora</span>
                    <input type="date" name="appt-date" onclick="this.showPicker()">
                    <input type="time" name="appt-time" onclick="this.showPicker()">
                </div>

                <div class="simple-container direction-column gap-8 grow-1 basis-normal">
                    <span class="label-normal outline-text left-margin-8">Datos generales</span>
                    <md-filled-select
                        name="appt-status" 
                        label="Estado de la cita"
                        >
                        <md-select-option value="1">
                            <div slot="headline">Cita pendiente</div>
                        </md-select-option>
                        <md-select-option value="2">
                            <div slot="headline">Cita completada</div>
                        </md-select-option>
                        <md-select-option value="3">
                            <div slot="headline">Cita cancelada</div>
                        </md-select-option>
                    </md-filled-select>
                    <md-filled-text-field
                        name="appt-concept" 
                        label="Nota de la cita"
                        >
                    </md-filled-text-field>
                </div>
            </div>

            <div class="simple-container gap-16 flex-wrap">
                <div class="simple-container direction-column gap-8 grow-1 basis-normal">
                    <span class="label-normal outline-text left-margin-8">Finanzas</span>
                    <md-filled-text-field 
                        label="Precio de cita"
                        type="number"
                        name="appt-price"
                        prefix-text="<?php currencySymbol() ?>"
                        class=""
                        >
                    </md-filled-text-field>
                    <md-filled-select
                        label="Estado del pago"
                        id=""
                        name="appt-payment-status"
                        value="1"
                        >
                        <md-select-option value="1">
                            <md-icon slot="start" class="filled error-text">money_off</md-icon>
                            <div slot="headline">Pago pendiente</div>
                        </md-select-option>
                        <md-select-option value="2">
                            <md-icon slot="start" class="filled primary-text">check_circle</md-icon>
                            <div slot="headline">Pago completado</div>
                        </md-select-option>
                    </md-filled-select>
                </div>
                <div class="simple-container direction-column gap-8 grow-1 basis-normal">
                    <span class="label-normal outline-text left-margin-8">Modalidad</span>
                    <md-filled-select
                        label="Modalidad"
                        name="appt-mode"
                        >
                        <md-select-option value="1">
                            <md-icon slot="start" class="filled">pin_drop</md-icon>
                            <div slot="headline">Presencial</div>
                        </md-select-option>
                        <md-select-option value="2">
                            <md-icon slot="start" class="filled">video_call</md-icon>
                            <div slot="headline">En línea</div>
                        </md-select-option>
                    </md-filled-select>
                </div>
            </div>

        
            <!-- <div class="simple-container gap-8 direction-column">
                <span class="label-normal outline-text left-margin-8">Precio</span>
                <div class="simple-container directiongap-16">
                    <md-filled-text-field 
                        label="Precio de cita"
                        type="number"
                        name="appt-price"
                        prefix-text="$"
                        class="grow-1 basis-normal"
                        >
                    </md-filled-text-field>
                    <md-filled-select
                        label="Estado del pago"
                        id=""
                        name="appt-payment-status"
                        value="1"
                        class="no-reset grow-1 basis-normal"
                        >
                        <md-select-option value="1">
                            <md-icon slot="start" class="filled error-text">money_off</md-icon>
                            <div slot="headline">Pago pendiente</div>
                        </md-select-option>
                        <md-select-option value="2">
                            <md-icon slot="start" class="filled primary-text">check_circle</md-icon>
                            <div slot="headline">Pago completado</div>
                        </md-select-option>
                    </md-filled-select>
                </div>
                
            </div> -->

            <div class="simple-container justify-between align-center">
                <div class="simple-container gap-8">
                    
                    <!-- <md-icon-button 
                        type="button"
                        onclick="TrashManager.openConfirmationDialog('appt')"
                        >
                        <md-icon>delete</md-icon>
                    </md-icon-button> -->
                    <md-text-button
                        type="button"
                        onclick="TrashManager.openConfirmationDialog('appt')"
                        >
                        <md-icon slot="icon">delete</md-icon>
                        Eliminar cita
                    </md-text-button>

                    <!-- <md-icon-button type="button"><md-icon>history</md-icon></md-icon-button> -->
                </div>
                <div class="simple-container">
                    <md-filled-button>Guardar cambios</md-filled-button>
                </div>
            </div>
        </form>
    </holder>

    <div class="overmessage" id="overmessage-appt-data-action-logs">
        

        <div class="simple-container width-100 justify-center">
            <div class="simple-container direction-column width-100 max-width-800 gap-24">
                <div class="simple-container align-center gap-16">
                    <md-filled-tonal-icon-button onclick="toggleOvermessage();">
                        <md-icon>arrow_back</md-icon>
                    </md-filled-tonal-icon-button>
                    <span class="headline-small on-background-text">Historial de cambios</span>
                    <!-- <md-text-button onclick="toggleOvermessage(); "><md-icon slot="icon">arrow_back</md-icon>Volver</md-text-button> -->
                </div>
                <!-- <div class="simple-container direction-column gap-8">
                    <span class="display-small line-height-0-85">Historial de cambios</span>
                    <span class="body-normal outline-text">
                        Aquí puedes ver los cambios que se han hecho a esta cita.
                    </span>
                </div> -->
                <div class="simple-container direction-column gap-8 bottom-margin-24" name="log-container"></div>
            </div>
        </div>

        
    </div>
</window>
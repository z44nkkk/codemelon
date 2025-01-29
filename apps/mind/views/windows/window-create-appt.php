<window 
    id="window-create-appt"
    data-flip-id="animate"
    class="increased slim h-auto"
    >
    <div class="simple-container padding-16 align-center gap-8">
        <md-icon-button onclick="toggleWindow()"><md-icon>close</md-icon></md-icon-button>
        <!-- <span class="headline-small on-background-text">Agendar cita</span> -->
    </div>
    <holder class="on-background-text">
        <form 
            onsubmit="ApptsManager.createAppt(event)" 
            id="form-create-appt"
            class="simple-container direction-column gap-16"
            >

            <div class="simple-container gap-8 align-center bottom-margin-8">
                <md-icon class="pretty small filled">calendar_add_on</md-icon>
                <headline class="headline-medium">Agendar cita</headline>
            </div>

            <div class="simple-container direction-column gap-16 flex-wrap">
                <div class="simple-container direction-column gap-16 grow-1 basis-normal">
                    <div class="simple-container direction-column gap-8">
                        <span class="label-normal outline-text left-margin-8">Paciente</span>
                        <md-filled-select
                            label="Selecciona un paciente"
                            id="create-appt-patients_option_list"
                            name="appt-patient"
                            shrink='true'
                            >
                            <md-select-option value="0">
                                <div slot="headline">No hay pacientes</div>
                            </md-select-option>
                        </md-filled-select>
                    </div>
        
                    <div class="simple-container direction-column gap-8">
                        <span class="label-normal outline-text left-margin-8">Fecha y hora</span>
                        <input type="date" name="appt-date" onclick="this.showPicker()">
                        <input type="time" name="appt-time" onclick="this.showPicker()">
                    </div>
                </div>
                <div class="simple-container direction-column gap-16 grow-1 basis-normal hidden" name="additional-data">
                    <div class="simple-container direction-column gap-8">
                        <span class="label-normal outline-text left-margin-8">Datos adicionales</span>
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
                        <!-- <input type="date" name="appt-date"> -->
                        <!-- <input type="time" name="appt-time"> -->

                        <md-filled-text-field
                            label="Precio de cita"
                            type="number"
                            prefix-text="<?php currencySymbol() ?>"
                            name="appt-price"
                        ></md-filled-text-field>
                        <md-filled-select
                            label="Estado del pago"
                            id=""
                            name="appt-payment-status"
                            value="1"
                            class="no-reset"
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
                        <!-- <input type="date" name="appt-date"> -->
                        <!-- <input type="time" name="appt-time"> -->
                    </div>
                </div>
            </div>



            <div class="simple-container justify-between align-center gap-16 top-margin-8 bottom-margin-16" name="buttons-area">
                <label class="simple-container gap-8 left-margin-8 outline-text">
                    <md-checkbox aria-label="Definir datos adicionales" name="additional-data-checkbox"></md-checkbox>
                    Definir datos adicionales
                </label>
                <md-filled-button style="margin-left:auto;"><md-icon slot="icon" class="filled">calendar_add_on</md-icon>Agendar</md-filled-button>
            </div>

            

            <!-- <md-filled-text-field
                id="datepicker-test"
                labe="Fecha"
                type="date"
            ></md-filled-text-field> -->

        </form>
    </holder>
</window>
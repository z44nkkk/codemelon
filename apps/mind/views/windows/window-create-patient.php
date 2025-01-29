<window
    id="window-create-patient"
    data-flip-id="animate"
    class="increased on-background-text"
    >
    <!-- <form onsubmit="" id="form-create-patient" class="simple-container direction-column grow-1"> -->
    <div class="simple-container padding-16 z-index-1">
        <md-icon-button type="button" onclick="toggleWindow();"><md-icon>close</md-icon></md-icon-button>
    </div>
    
    <holder class="align-center">
        <form id="form-create-patient" onsubmit="PatientsManager.createPatient(event)" class="simple-container direction-column width-100 max-width-600 grow-1 gap-24 justify-center">

            <div class="simple-container direction-column width-100 user-select-none gap-8">
                <span class="display-small dm-sans weight-500 line-height-0-95">Agregar nuevo paciente</span>
                <span class="headline-small outline-text bottom-margin-8">Información básica del paciente</span>
                <!-- <span class="body-large outline-text dm-sans">Completa los campos a continuación para agregar un nuevo paciente</span> -->
            </div>

            <div class="simple-container direction-column gap-8">

                <div class="simple-container direction-column gap-8" name="minimum-data" id="create-patient-form-minimum-data">
                    <md-filled-text-field 
                        label="Nombre completo"
                        name="patient-name"
                    ></md-filled-text-field>
                    <md-filled-select
                        label="Genero"
                        name="patient-gender"
                        >
                        <md-select-option value="Masculino">
                            <div slot="headline">Masculino</div>
                        </md-select-option>
                        <md-select-option value="Femenino">
                            <div slot="headline">Femenino</div>
                        </md-select-option>
                        <md-select-option value="No binario">
                            <div slot="headline">No binario</div>
                        </md-select-option>
                        <md-select-option value="Otro">
                            <div slot="headline">Otro</div>
                        </md-select-option>

                    </md-filled-select>
                    <md-filled-text-field 
                        label="Fecha de nacimiento"
                        type="date"
                        name="patient-birthdate"
                    ></md-filled-text-field>
                </div>

                <div class="simple-container direction-column gap-8 hidden top-margin-8" name="additional-data">
                    <span class="label-normal outline-text left-margin-8">Datos adicionales</span>
                    <md-filled-text-field 
                        label="Precio por cita"
                        type="number"
                        prefix-text="<?php currencySymbol() ?>"
                        name="patient-appt_price"
                    ></md-filled-text-field>
                    <md-filled-text-field 
                        label="Número de contacto"
                        type="tel"
                        name="patient-contact_phone"
                    ></md-filled-text-field>
                    <md-filled-text-field 
                        label="Correo de contacto"
                        type="email"
                        name="patient-contact_email"
                    ></md-filled-text-field>
                    <md-filled-text-field 
                        label="Escuela"
                        name="patient-school"
                    ></md-filled-text-field>
                    <md-filled-text-field 
                        label="Grado escolar"
                        name="patient-school_grade"
                    ></md-filled-text-field>
                </div>

                <div class="simple-container justify-between align-center gap-16 top-margin-16">
                    <label class="simple-container gap-8 left-margin-8">
                        <md-checkbox aria-label="Definir datos adicionales" name="additional-data-checkbox"></md-checkbox>
                        Definir datos adicionales
                    </label>

                    <md-filled-button>
                        <md-icon slot="icon">person_add</md-icon>
                        <span data-text-content>Agregar paciente</span>
                    </md-filled-button>
                </div>

            </div>

        </form>
        <div class="content-box" style="visibility:hidden;"></div>

    </holder>

    <div class="overmessage padding-0" id="overmessage-create-patient-step-2" name="step-2">
        <div class="simple-container padding-16 position-absolute ">
            <md-filled-tonal-button type="button" onclick="toggleOvermessage('#overmessage-create-patient-step-2')"><md-icon slot="icon">arrow_back</md-icon>Ir atras</md-filled-tonal-button>
        </div>

        <div class="simple-container grow-1 align-center justify-center padding-24">
            <div class="simple-container direction-column width-100 max-width-600 gap-24">

                <div class="simple-container direction-column user-select-none gap-8">
                    <span class="display-small dm-sans weight-500">Agregar nuevo paciente</span>
                    <span class="headline-small outline-text bottom-margin-8">Información de contacto</span>
                </div>

                <div class="simple-container direction-column gap-8">
                    <md-filled-text-field 
                        label="Número de contacto"
                        type="tel"
                        name="patient-contact_phone"
                        class="no-reset"
                    ></md-filled-text-field>
                    <md-filled-text-field 
                        label="Correo de contacto"
                        type="email"
                        name="patient-contact_email"
                        class="no-reset"
                    ></md-filled-text-field>
                </div>

                <div class="simple-container justify-right">
                    <md-filled-button 
                        type="button" 
                        trailing-icon
                        name="go-step-3"
                        >
                        <md-icon slot="icon">arrow_forward</md-icon>
                        Siguiente
                    </md-filled-button>
                </div>

                <!-- <span class="body-large outline-text dm-sans">Completa los campos a continuación para agregar un nuevo paciente</span> -->
            </div>

        </div>

    </div>

    <!-- </form> -->
</window>
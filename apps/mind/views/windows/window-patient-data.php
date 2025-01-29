<window
    id="window-patient-data"
    data-flip-id="animate"
    class="increased on-background-text full-size"
    >
    
    <div class="simple-container padding-16 justify-between">
        <md-icon-button type="button" onclick="toggleWindow()"><md-icon>close</md-icon></md-icon-button>
        <md-filled-button type="submit" form="form-edit-patient">Guardar</md-filled-button>
    </div>
    <holder class="align-center gap-24">
        <form onsubmit="PatientsManager.editPatient(event)" id="form-edit-patient" class="simple-container width-100 align-center direction-column grow-1">
        <div class="simple-container direction-column width-100 max-width-800 bottom-margin-24">
            <span class="display-small dm-sans weight-500">Editar datos</span>
            <span class="body-large outline-text dm-sans">Aquí puedes editar los datos del paciente</span>
        </div>
        <div class="simple-container direction-column width-100 max-width-800 gap-8 grow-1">
            <div class="simple-container flex-wrap gap-8">
                <div class="simple-container grow-1 basis-normal direction-column gap-8">
                    <md-filled-text-field 
                        label="Nombre completo"
                        name="patient-name"
                        class="no-reset"
                    ></md-filled-text-field>
                    <md-filled-text-field 
                        label="Genero"
                        name="patient-gender"
                        class="no-reset"
                    ></md-filled-text-field>
                    <md-filled-text-field 
                        label="Fecha de nacimiento"
                        type="date"
                        name="patient-birthdate"
                        class="no-reset"
                    ></md-filled-text-field>
                    <md-filled-select 
                        label="Estado del paciente"
                        name="patient-status"
                        class="no-reset"
                        >
                        <md-select-option value="1">
                            <div slot="headline">Paciente activo</div>
                        </md-select-option>
                        <md-select-option value="2">
                            <div slot="headline">Paciente dado de alta</div>
                        </md-select-option>
                        <md-select-option value="3">
                            <div slot="headline">Paciente inactivo</div>
                        </md-select-option>
                    </md-filled-select>
                </div>
                <div class="simple-container grow-1 basis-normal direction-column gap-8">
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
                    <md-filled-text-field 
                        label="Escuela"
                        name="patient-school"
                        class="no-reset"
                    ></md-filled-text-field>
                    <md-filled-text-field 
                        label="Grado escolar"
                        name="patient-school_grade"
                        class="no-reset"
                    ></md-filled-text-field>
                </div>
            </div>
            <div class="simple-container direction-column">
                <md-filled-text-field
                    label="Precio por cita"
                    type="number"
                    name="patient-appt_price"
                    prefix-text="<?php currencySymbol() ?>"
                ></md-filled-text-field>
            </div>
            
            <div class="simple-container grow-1 direction-column">
                <md-filled-text-field
                    style="min-height: 300px;"
                    label="Notas"
                    type="textarea"
                    name="patient-notes"          
                ></md-filled-text-field>
            </div>

            
            <div class="content-box" style="visibility:hidden;"></div>
        </div>
        </form>
    </holder>
    
</window>
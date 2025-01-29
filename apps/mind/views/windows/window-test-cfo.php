<window
    id="window-test-cfo"
    data-flip-id="animate"
    >
    <div class="simple-container padding-16 align-center gap-8">
        <md-icon-button onclick="toggleWindow()"><md-icon>close</md-icon></md-icon-button>
        <span class="body-large outline-text">Registrar paciente para otros </span>
    </div>
    <holder class="on-background-text">

        <form 
            class="simple-container direction-column gap-16" 
            id="form-cfo_test"
            onsubmit="PatientsManager.createPatientForOther(event)"
            >

            <div class="simple-container gap-8 flex-wrap align-center">
                <md-outlined-text-field
                    class="no-reset grow-1"
                    label="Id de origen" 
                    disabled="true"
                    value="<?= $_SESSION['id']?>"
                ></md-outlined-text-field>
                <md-icon>arrow_forward</md-icon>
                <md-outlined-text-field
                    class="grow-1"
                    label="Id objetivo"
                    type="number"
                    id="test-create-patient_owner_id"
                ></md-outlined-text-field>
            </div>
            
            
            <md-outlined-text-field
                class="no-reset"
                label="Nombre del paciente"
                value="Nombre de prueba"
                id="test-create-patient_name"
            ></md-outlined-text-field>

            <div class="simple-container justify-right">
                <md-filled-button type="submit">
                    Registrar
                </md-filled-button>
            </div>

        </form>


    </holder>

</window>
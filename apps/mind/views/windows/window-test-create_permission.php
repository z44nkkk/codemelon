<window
    id="window-test-create_permission"
    data-flip-id="animate"
    >
    <div class="simple-container padding-16 align-center gap-8">
        <md-icon-button onclick="toggleWindow()"><md-icon>close</md-icon></md-icon-button>
        <span class="body-large outline-text">Asignarción de permisos</span>
    </div>
    <holder>
        <form 
            class="simple-container direction-column gap-16" 
            id="form-test-create_permissions"
            onsubmit="PermissionsManager.createPermission(event)"
            >

            <md-outlined-text-field
                class="no-reset"
                label="Id de la acción"
                type="number"
                value="1"
                id="test-create-permission_action_id"
            ></md-outlined-text-field>

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
                    id="test-create-permission_owner_id"
                ></md-outlined-text-field>
            </div>

            <div class="simple-container justify-right">
                <md-filled-button type="submit">
                    Asignar permisos
                </md-filled-button>
            </div>

        </form>
    </holder>

</window>
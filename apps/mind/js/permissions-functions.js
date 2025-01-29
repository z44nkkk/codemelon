const PermissionsManager = (function() {

    async function createPermission(event = false){
       
        if(event) event.preventDefault();
        if(!checkEmpty('#form-test-create_permissions', 'input')){return;}
        const data = {
            action_id: document.getElementById("test-create-permission_action_id").value,
            owner_id: document.getElementById("test-create-permission_owner_id").value
        }
        const result = await insertPermission(data);
        if(!result) return false;
        message("Permiso asignado correctamente", "success")
    }

    async function insertPermission(data){
        data.op = "permission_create"
        const url = `back-end/controllers/permissions.controller.php`;
        try {
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(data),
                headers: { 'Content-Type': 'application/json' }
            });
            if (!response.ok) {
                message("Hubo un error en la solicitud", "error");
                return false;
            }
            const result = await response.json();
            if (result.success) {
                return true;
            } else {
                message(`Hubo un error: ${result.message}`, "error");
            }
        } catch (error) {
            message(`Hubo un error: ${error}`, "error");
            return false;
        }
    }

    return {
        createPermission
    }

})();
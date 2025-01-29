const apptService = (() =>{
    const API_URL = 'back-end/controllers/appointment.controller.php';
    

    async function getAppts(data = {}){
        data.op = "appts_get_list";
        try {
            const response = await fetch(API_URL, {
                method: 'POST',
                body: JSON.stringify(data),
                headers: { 'Content-Type': 'application/json' },
            });
            if (!response.ok) { throw new Error(`Error en la solicitud: ${response.statusText}`);}
            const result = await response.json();
            if (!result.success) { throw new Error(result.message || "Error desconocido en la respuesta");}
        
            return result;
        } catch (error) {
            message(`Hubo un error: ${error.message}`, "error");
            return null;
        }
    }

    async function insertAppt(data = {}, ignoreTaken = false){
        data.op = "appt_create";
        data.ignore_taken = ignoreTaken;
        try {
            const response = await fetch(API_URL, {
                method: 'POST',
                body: JSON.stringify(data),
                headers: { 'Content-Type': 'application/json' },
            });
            if (!response.ok) { throw new Error(`Error en la solicitud: ${response.statusText}`);}
            const result = await response.json();
            if (!result.success) { throw new Error(result.message || "Error desconocido en la respuesta");}
        
            return result;
        } catch (error) {
            if(error.message === "appt_date_time_taken" && !ignoreTaken){
                toggleDialog("dialog-appt-taken");
                document.getElementById("dialog-appt-taken").querySelector("[name='button-confirm-create-appt']").onclick = () =>{
                    ApptsManager.createAppt(undefined, true);
                }

                return false;
            }
            message(`Hubo un error: ${error.message}`, "error");
            return false;
        }
    }

    async function updateAppt(data = {}, ignoreTaken = false){
        data.op = "appt_edit";
        data.ignore_taken = ignoreTaken
        try {
            const response = await fetch(API_URL, {
                method: 'POST',
                body: JSON.stringify(data),
                headers: { 'Content-Type': 'application/json' },
            });
            if (!response.ok) { throw new Error(`Error en la solicitud: ${response.statusText}`);}
            const result = await response.json();
            if (!result.success) { throw new Error(result.message || "Error desconocido en la respuesta");}
        
            return result;
        } catch (error) {
            if(error.message === "appt_date_time_taken" && !ignoreTaken){
                toggleDialog("dialog-appt-taken");
                document.getElementById("dialog-appt-taken").querySelector("[name='button-confirm-create-appt']").onclick = () =>{
                    ApptsManager.editApptIgnoreTaken();
                }
                return false;
            }
            message(`Hubo un error: ${error.message}`, "error");
            return false;
        }
    }

    async function deleteAppt(data = {}){
        data.op = "appt_delete";
        try {
            const response = await fetch(API_URL, {
                method: 'POST',
                body: JSON.stringify(data),
                headers: { 'Content-Type': 'application/json' },
            });
            if (!response.ok) { throw new Error(`Error en la solicitud: ${response.statusText}`);}
            const result = await response.json();
            if (!result.success) { throw new Error(result.message || "Error desconocido en la respuesta");}
        
            return result;
        } catch (error) {
            message(`Hubo un error: ${error.message}`, "error");
            return false;
        }
    }

    async function getApptLog(data = {}){
        data.op = "appt_get_log";
        try {
            const response = await fetch(API_URL, {
                method: 'POST',
                body: JSON.stringify(data),
                headers: { 'Content-Type': 'application/json' },
            });
            if (!response.ok) { throw new Error(`Error en la solicitud: ${response.statusText}`);}
            const result = await response.json();
            if (!result.success) { throw new Error(result.message || "Error desconocido en la respuesta");}
        
            return result;
        } catch (error) {
            message(`Hubo un error: ${error.message}`, "error");
            return null;
        }
    }

    return {
        insertAppt,
        updateAppt,
        deleteAppt,
        getApptLog,
        getAppts
    }

})();

export default apptService;
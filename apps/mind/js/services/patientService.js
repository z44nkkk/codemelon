const patientService = (() => {
    const API_URL = 'back-end/controllers/patient.controller.php';

    async function getPatients(data = {}, owner_id = false) {
        data.op = "patients_get_list";
        if (owner_id !== false) data.owner_id = owner_id;
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

    async function insertPatient(data, owner_id = false) {
        data.op = "patient_create";
        if (owner_id !== false) data.owner_id = owner_id;
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

    async function updatePatient( data = {}){
        data.op = "patient_edit";
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

    async function deletePatient(data = {}){
        data.op = "patient_delete";
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

    async function updateNote(data = {}){
        data.op = "patient_note_edit";
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

    // window.insertPatient = insertPatient

    return { 
        getPatients, 
        insertPatient,
        updatePatient,
        deletePatient,
        updateNote
    };
})();

export default patientService;
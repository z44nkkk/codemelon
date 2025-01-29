const trashService = (() => {
    const API_URL = 'back-end/controllers/trash.controller.php';

    async function moveToTrash(data = {}){
        data.op = "move_to_trash";
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

    async function recoverFromTrash(data = {}){
        data.op = "recover_from_trash";
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

    async function getTrash(data = {}){
        data.op = "get_trash";
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
        moveToTrash,
        recoverFromTrash,
        getTrash,
    }

})();

export default trashService;
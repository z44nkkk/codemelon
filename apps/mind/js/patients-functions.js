const PatientsManager =  (function() {

    const createPatientForm = document.getElementById('form-create_patient');
    const createPatientName = createPatientForm.querySelector('#create-patient_name');

    const patientsTableContainer = document.getElementById("response-container-patients_table");

    let patients = []; // Variable interna para almacenar los pacientes

    // Inicialización del módulo
    async function initialize() {
        patients = await getPatients(); // Cargar los pacientes al iniciar
        return true;
    }

    // let patients = getPatients();

    async function createPatient(event){
        event.preventDefault();
        if(!checkEmpty('#form-create_patient', 'input')){return;}
        const data = {
            patient_name: createPatientName.value.trim()
        }
        const result = await insertPatient(data);
        if(!result){return;}
        toggleWindow();
        message("Paciente creado correctamente", "success");
    }

    async function createPatientForOther(event = false){
        if (event) event.preventDefault();
        if(!checkEmpty('#form-cfo_test', 'input')){return;}
        var testOwnerValue = document.getElementById("test-create-patient_owner_id").value;
        var testPatientName = document.getElementById("test-create-patient_name").value;
        const data = {
            patient_name: testPatientName
        }
        const result = await insertPatient(data, testOwnerValue);
        if(!result){return;}
        // toggleWindow();
        message("Paciente creado correctamente", "success");
    }

    async function displayPatientsTable(page = 0, owner_id = false){
        const patients = await getPatients(page, owner_id);
        if(!patients) return false;
        patientsTableContainer.innerHTML = `
            <tr>
                <td>id</td>            
                <td>user_id</td>            
                <td>patient_name</td>            
            </tr>
            ${patients.map(patient => {
                return `
                    <tr>
                        <td>${patient.id}</td>
                        <td>${patient.user_id}</td>
                        <td>${patient.patient_name}</td>
                    </tr>
                `;
            }).join("")}
        `;
    }



    async function insertPatient(data, owner_id = false){
        data.op = "patient_create";
        if(owner_id != false){data.owner_id = owner_id}
        const url = `back-end/controllers/patient.controller.php`;
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
                console.log(patients)
                // PatientsManager.patients.push({
                //     id: result.patient_id,
                //     patient_name: data.patient_name
                // });
                return true;
            } else {
                message(`Hubo un error: ${result.message}`, "error");
            }
        } catch (error) {
            message(`Hubo un error: ${error}`, "error");
            return false;
        }
    }

    async function getPatients(page = 0, owner_id = false){
        data = { 
            op:"patients_get_list",
            page: page
        }
        if(owner_id != false){data.owner_id = owner_id}
        const url = `back-end/controllers/patient.controller.php`;
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
                return result.data;
            } else {
                message(`Hubo un error: ${result.message}`, "error");
            }
        } catch (error) {
            message(`Hubo un error: ${error}`, "error");
            return false;
        }
    }

    return {
        initialize, // Método para inicializar el módulo
        createPatient,
        createPatientForOther,
        displayPatientsTable,
        getPatients,
        getPatientsCached: () => patients // Método para acceder a los pacientes cargados
    };

})();

// Uso del módulo
(async function () {
    await PatientsManager.initialize(); // Inicializar el módulo
    // console.log("Pacientes cargados:", PatientsManager.getPatientsCached());
})();
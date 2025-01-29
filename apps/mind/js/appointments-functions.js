const ApptManager = (function() {

    

    async function openCreateApptWindow(){
        const activeWindow = document.getElementById("window-create-appt");
        toggleWindow(`#${activeWindow.id}`);
        
        const patientSelector = activeWindow.querySelector("#create-appt-patients_option_list");
        patientSelector.innerHTML = buildPatientsOptionList( await PatientsManager.patients);

    }

    function buildPatientsOptionList(patients = false){
        if(!patients) return false;


        const list = patients.map(patient =>{
            return `
                <md-select-option
                    value=${patient.id}
                    >
                    <div slot="headline">${patient.patient_name}</div>
                </md-select-option>
            `;
        }).join("")

        return list;
    }

    async function createAppt(event = false, owner_id = false){
        if(event) event.preventDefault();
        // if(!checkEmpty('#form-create_appointment', 'input')){return;}
        const data = {
            patient_id: 1,
            appt_date: "2021-12-27",
            appt_time: "10:00:00",
            appt_cost: 500.00,
            appt_note: "cita de prueba"
        }
        const result = await insertAppointment(data, owner_id);
        if(!result){return false;}
        message("Cita creada correctamente", "success");
    }

    async function insertAppointment(data, owner_id = false){
        data.op = "appt_create";
        if(owner_id != false){data.owner_id = owner_id}
        const url = `back-end/controllers/appointment.controller.php`;
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
        createAppt,
        openCreateApptWindow
    }

})();
import apptService from '../services/apptService.js';
import patientService from '../services/patientService.js';
import PatientsManager from './patientsManager.js';
import TrashManager from './trashManager.js';
import CalendarManager from './calendarManager.js';

const ApptsManager = (() => {

    let appts = [];
    let apptsForTable = [];
    let apptsForCalendar = []
    
    async function loadAppts() {
        // appts = await apptService.getAppts({limit: "no_limit"});
        apptsForTable = await apptService.getAppts({filters: getFiltersValue()});
        apptsForCalendar = await apptService.getAppts({filters: CalendarManager.getCalendarRange(), limit: "no_limit"});
        patientSelector.innerHTML = buildPatientsOptionList(PatientsManager.patientsForOptions().data);
    }

    async function updateApptsForCalendar(){
        apptsForCalendar = await apptService.getAppts({filters: CalendarManager.getCalendarRange(), limit: "no_limit"});
    }

    let currentOpenApptId = "";

    // window create
    const createApptWindow = document.getElementById("window-create-appt");
    const createApptForm = document.getElementById("form-create-appt");
    const patientSelector = createApptForm.querySelector("[name='appt-patient']");

    // Window data
    const editApptWindow = document.getElementById("window-appt-data");
    const editApptForm = document.getElementById("form-edit-appt");

    // Dialog data
    const apptDataDialog = document.getElementById("dialog-appt-data");
    const apptDataDialogForm = apptDataDialog.querySelector("#form-dialog-appt-data");

    // table
    const apptsTableContainer = document.getElementById("response-container-appts-table");
    const apptsTableFiltersForm = document.getElementById("form-appts-table-filters");

    const apptsLogContainer = editApptWindow.querySelector("[name='log-container']");

    function populateAppointmentPatientFilter() {
        const patientSelector = apptsTableFiltersForm.querySelector("[name='filter-patients']");
        patientSelector.innerHTML = `
            <option value="all_patients">Todos los pacientes</option>
            ${buildPatientsVanillaOptionList(PatientsManager.patientsForOptions().data)}
        `;
        const allPatientsOption = document.createElement("option")
        
    }

    function openCreateApptWindow(options = {}) {
        if(options.openStyle === "absolute"){
            toggleWindow(`#${createApptWindow.id}`, "absolute", 1, true);
        }else{
            toggleWindow(`#${createApptWindow.id}`);
        }
        
        const date = getDate();
        const time = getTime();

        
        // const patientSelector = createApptForm.querySelector("[name='appt-patient']");
        const dateSelector = createApptForm.querySelector("[name='appt-date']");
        const timeSelector = createApptForm.querySelector("[name='appt-time']");
        const apptPrice = createApptForm.querySelector("[name='appt-price']");
        const apptMode = createApptForm.querySelector("[name='appt-mode']");
        const apptPaymentStatus = createApptForm.querySelector("[name='appt-payment-status']");
        const additionalDataButton = createApptForm.querySelector("[name='additional-data-checkbox']");

        apptPrice.value = 0.00;

        patientSelector.addEventListener("change", () => { updateFormApptPrice(patientSelector) });

        apptMode.selectedIndex = 0;
        apptPaymentStatus.selectedIndex = 0;
        additionalDataButton.addEventListener("change", () => { additionalDataButton.checked ? showAdditionalData() : hideAdditionalData() });
        
        dateSelector.value = `${date.year}-${date.month}-${date.day}`;
        timeSelector.value = `${time.hours}:00`;

        if(options.specificDate){
            dateSelector.value = options.specificDate;
        }

        if(options.specificPatient){
            setTimeout(() => {
                patientSelector.value = options.specificPatient;
                updateFormApptPrice(patientSelector);
            }, 1);
        }

    }

    function showAdditionalData(){
        const buttonsArea = createApptForm.querySelector("[name='buttons-area']");
        const additionalData = createApptForm.querySelector("[name='additional-data']");
        
        const buttonsAreaState = Flip.getState(buttonsArea);
        const windowState = Flip.getState(createApptWindow);
        
        additionalData.classList.remove("hidden");
        flowChilds(additionalData.firstElementChild);

        applyAnimation(windowState, createApptWindow, false, false, true);
        applyAnimation(buttonsAreaState, buttonsArea, false, false, true);
    }
    function hideAdditionalData(){
        const buttonsArea = createApptForm.querySelector("[name='buttons-area']");
        const additionalData = createApptForm.querySelector("[name='additional-data']");

        const buttonsAreaState = Flip.getState(buttonsArea);
        const windowState = Flip.getState(createApptWindow);

        additionalData.classList.add("hidden");

        applyAnimation(windowState, createApptWindow, false, false, true);
        applyAnimation(buttonsAreaState, buttonsArea, false, false, true);
    }

    function updateFormApptPrice(patientSelector){
        const apptPrice = patientSelector.options[patientSelector.selectedIndex].getAttribute('data-appt-price');
        createApptForm.querySelector("[name='appt-price']").value = apptPrice;
    }


    function buildPatientsVanillaOptionList(patients = false){
        if(!patients) return false;
        const list = patients.map(patient =>{
            return `
                <option
                    value=${patient.id}
                    >
                    ${patient.patient_name}
                </option>
            `;
        }).join("")

        return list;
    }

    function buildPatientsOptionList(patients = false){
        if(!patients) return false;
        if(patients.length <= 0){
            return `<md-select-option selected value="0">No hay pacientes</md-select-option>`
        };
        const list = patients.map(patient =>{
            return `
                <md-select-option
                    value=${patient.id}
                    data-patient-name='${patient.patient_name}'
                    data-appt-price='${patient.patient_appt_price}'
                    >
                    <div slot="headline">${patient.patient_name}</div>
                </md-select-option>
            `;
        }).join("")

        return list;
    }
    function setApptDataset(data, element) {
        if (!element) return false;
    
        for (const [key, value] of Object.entries(data)) {
            element.dataset[key] = value; // Asigna cada propiedad como atributo `data-*`
        }
        element.dataset.item_type = "appt";
    }
    function getDataset(element) {
        if (!element) return false;
    
        const dataset = {};
        for (const key in element.dataset) {
            dataset[key] = element.dataset[key];
        }
    
        return dataset;
    }
    async function createAppt(event = false, ignoreTaken = false){
        if(event !== false) event.preventDefault();
        if(!checkEmpty(`#${createApptForm.id}`, 'input, select')){return;}
        toggleButton(`#${createApptForm.id}`, true);
        const patientField = createApptForm.querySelector("[name='appt-patient']");
        const data = {
            patient_id: patientField.value.trim(),
            appt_date: createApptForm.querySelector("[name='appt-date']").value.trim(),
            appt_time: createApptForm.querySelector("[name='appt-time']").value.trim(),
            appt_mode: createApptForm.querySelector("[name='appt-mode']").value.trim(),
            appt_price: createApptForm.querySelector("[name='appt-price']").value.trim(),
            appt_payment_status: createApptForm.querySelector("[name='appt-payment-status']").value.trim(),
        }
        const result = await apptService.insertAppt(data, ignoreTaken);
        toggleButton(`#${createApptForm.id}`, false);
        if(!result) return false;

        const newData = {
            id: result.appt_id,
            patient_id: data.patient_id,
            patient_name: patientField.options[patientField.selectedIndex].getAttribute('data-patient-name'),
            appt_concept: "",
            row_status: 1,
            appt_status: 1,
            appt_mode: data.appt_mode,
            appt_price: Number(data.appt_price),
            appt_payment_status: data.appt_payment_status,
            appt_date: data.appt_date,
            appt_time: data.appt_time,
        }

        // console.log(`Range check: ${checkRangeChanges(newData, newData)}`)
        // if(!checkRangeChanges(newData, newData)){    
        //     // apptsForTable.data.push(newData);
        //     apptsForTable.pagination.total_rows = Number(apptsForTable.pagination.total_rows) + 1;
        //     apptsForTable.stats.count_pending = Number(apptsForTable.stats.count_pending) + 1;
        // }
        syncApptData("create", undefined, newData);
        // always update the calendar data
        apptsForCalendar.data.push(newData);
        apptsForCalendar.pagination.total_rows = Number(apptsForCalendar.pagination.total_rows) + 1;
        apptsForCalendar.stats.count_pending = Number(apptsForCalendar.stats.count_pending) + 1;
        // Aquí fatla sumar el costo de la cita

        displayApptsTable(undefined, true);
        CalendarManager.refreshCalendar(new Date());
        toggleWindow();
        toggleDialog("dialog-appt-created-message")
        // message("Cita creada correctamente");
    }
    async function editAppt(event = false, apptId, patientId, ignoreTaken = false){
        if(event !== false) event.preventDefault();
        toggleButton(`#${editApptForm.id}`, true);
        const data = {
            id: apptId,
            patient_id: patientId,
            appt_date: editApptForm.querySelector("[name='appt-date']").value.trim(),
            appt_time: editApptForm.querySelector("[name='appt-time']").value.trim(),
            appt_cost: 0.00,
            appt_price: Number(editApptForm.querySelector("[name='appt-price']").value.trim()) ?? 0.00,
            appt_payment_status: Number(editApptForm.querySelector("[name='appt-payment-status']").value.trim()),
            appt_mode: editApptForm.querySelector("[name='appt-mode']").value.trim(),
            appt_concept: editApptForm.querySelector("[name='appt-concept']").value.trim(),
            appt_status: Number(editApptForm.querySelector("[name='appt-status']").value.trim()),
        };
        const result = await apptService.updateAppt(data, ignoreTaken);
        toggleButton(`#${editApptForm.id}`, false);
        if(!result) return false;

        let apptIndex = apptsForTable.data.findIndex(appt => appt.id == apptId);
        let oldData = apptsForTable.data[apptIndex] ?? null;
        if(oldData === null){
            apptIndex = apptsForCalendar.data.findIndex(appt => appt.id == apptId);
            oldData = apptsForCalendar.data[apptIndex] ?? null;
        }

        // console.log(oldData);
        await syncApptData("edit", oldData, data);

        const apptIndexForCalendar = apptsForCalendar.data.findIndex(appt => appt.id == apptId);
        const oldDataForCalendar = apptsForCalendar.data[apptIndexForCalendar] ?? null;
        if(oldDataForCalendar === null){
            apptsForCalendar.data.push({ ...oldData, ...data }); 
        };
        syncApptData("edit", oldDataForCalendar, data, apptsForCalendar, "calendar");

        displayApptsTable(undefined, true);
        CalendarManager.refreshCalendar(new Date());
        updateAllApptElements(data);

        toggleWindow();
        message("Cita actualizada correctamente");
    }

    function editApptIgnoreTaken(){
        const apptDataset = getDataset(document.querySelector(`[data-item_type='appt'][data-id='${currentOpenApptId}']`));
        editAppt(undefined, currentOpenApptId, apptDataset.patient_id, true)
    }

    function openApptDataWindow(origin){
        toggleWindow(`#${editApptWindow.id}`, "absolute", 1, true, true);
        const apptDataset = getDataset(origin);
        
        currentOpenApptId = apptDataset.id;
        editApptWindow.querySelector("[name='patient-direct-access']").onclick = () => {
            const patientItem = document.querySelector(`[data-item_type='patient'][data-id='${apptDataset.patient_id}']`);
            if(patientItem) { 
                toggleSection("section-patients");
                PatientsManager.openPatientProfile(document.querySelector(`[data-item_type='patient'][data-id='${apptDataset.patient_id}']`));
            }else{       
                message("Paciente aún no cargado", "error");
            }
        }
        editApptWindow.querySelector("[name='patient-name']").innerHTML = apptDataset.patient_name;
        editApptForm.querySelector("[name='appt-date']").value = apptDataset.appt_date;
        editApptForm.querySelector("[name='appt-time']").value = apptDataset.appt_time;
        // editApptForm.querySelector("[name='appt-cost']").value = parseFloat(apptDataset.appt_cost) || 0;
        editApptForm.querySelector("[name='appt-price']").value = parseFloat(apptDataset.appt_price) || 0;
        editApptForm.querySelector("[name='appt-concept']").value = apptDataset.appt_concept;
        editApptForm.querySelector("[name='appt-status']").value = apptDataset.appt_status;

        editApptForm.querySelector("[name='appt-mode']").value = apptDataset.appt_mode;
        editApptForm.querySelector("[name='appt-payment-status']").value = apptDataset.appt_payment_status;

        editApptForm.onsubmit = function(event){ editAppt(event, apptDataset.id, apptDataset.patient_id); }
        
    }
    function openApptDataDialog(origin){
        toggleDialog("dialog-appt-data");
        const apptDataset = getDataset(origin);

        currentOpenApptId = apptDataset.id;
        apptDataDialogForm.querySelector("[name='patient-name']").textContent = apptDataset.patient_name;
        apptDataDialogForm.querySelector("[name='appt-date']").textContent = dateToPrettyDate(apptDataset.appt_date, true);
        apptDataDialogForm.querySelector("[name='appt-time']").textContent = timeToAmPm(apptDataset.appt_time);
        // apptDataDialogForm.querySelector("[name='appt-cost']").textContent = formatMoney(apptDataset.appt_cost);
        apptDataDialogForm.querySelector("[name='appt-concept']").textContent = (apptDataset.appt_concept == "") ? "-" : apptDataset.appt_concept;
        apptDataDialogForm.querySelector("[name='appt-status']").textContent = (apptDataset.appt_status == "1") ? "Pendiente" : (apptDataset.appt_status == "2") ? "Completada" : "Cancelada";
        apptDataDialogForm.querySelector("[name='appt-price']").textContent = formatMoney(apptDataset.appt_price);
        apptDataDialogForm.querySelector("[name='appt-mode']").textContent = (apptDataset.appt_mode == "1") ? "Presencial" : "Virtual";
        apptDataDialogForm.querySelector("[name='appt-payment-status']").textContent = (apptDataset.appt_payment_status == "1") ? "Pagada" : "Pendiente";

        apptDataDialog.querySelector("[name='button-delete-forever']").onclick = function() { TrashManager.openDeleteForeverDialog("appt"); }
        apptDataDialog.querySelector("[name='button-recover']").onclick = function() { TrashManager.openRecoverDialog("appt"); }
    }

    function displayApptsTable(data = apptsForTable.data, replace = false) {
        if (replace) apptsTableContainer.innerHTML = "";
    
        const fragment = document.createDocumentFragment();
        const elements = buildApptsTable(data);
    
        elements.forEach(element => {
            fragment.appendChild(element);
        });
    
        apptsTableContainer.appendChild(fragment);
        
        handleLoadMoreButton();
        setApptsStatsData();
    }
    function buildApptsTable(appts, displayArea = "table") {
        if(appts.length <= 0){
            const item = document.createElement("div");
            item.className = "content-box align-center justify-center user-select-none";
            item.innerHTML = `<span class="body-large outline-text">No hay citas</span>`;
            return [item];
        }
        return appts.map(appt => {
            const item = document.createElement("div");
            item.setAttribute("data-flip-id", "animate");
            if(displayArea === "table"){ item.onclick = () => openApptDataWindow(item);}
            if(displayArea === "trash"){ item.onclick = () => openApptDataDialog(item);}

            const icon = (appt.appt_status == "1") ? "circle" : (appt.appt_status == "2") ? "check_circle" : "cancel";
            const iconClass = (appt.appt_status == "1") ? "on-background-text" : (appt.appt_status == "2") ? "primary-text filled" : "error-text";

            item.className = "content-box direction-row padding-16 border-radius-16 cursor-pointer user-select-none on-background-text hover-outline";

            item.innerHTML = `
                <md-ripple></md-ripple>
                <div class="simple-container body-large">
                    <md-icon class="dynamic ${iconClass}" name="appt-icon">${icon}</md-icon>
                </div>
                <div class="simple-container direction-column grow-1 gap-4">
                    <span class="label-medium"><span name="appt-date">${dateToShort(appt.appt_date)}</span>, <span name="appt-time">${timeToAmPm(appt.appt_time)}</span>, #${appt.id}</span>
                    <span class="body-large" name="patient-name">${appt.patient_name}</span>
                </div>
            `;

            setApptDataset(appt, item);
            return item;
        });
    }
    async function displayNextPage(){
        const currentPage = Math.ceil(apptsForTable.pagination.offset / apptsForTable.pagination.limit);
        const nextPage = currentPage + 1;
        const nextPageResponse = await apptService.getAppts({page: nextPage, filters: getFiltersValue()})
        apptsForTable.data = [...apptsForTable.data, ...nextPageResponse.data];
        apptsForTable.pagination = nextPageResponse.pagination;
        displayApptsTable(nextPageResponse.data);
    }


    function handleLoadMoreButton(){
        const loadMoreButton = document.getElementById("load-more-appts");
        if(!loadMoreButton) return false;

        // Reset button state first
        loadMoreButton.disabled = false;

        // If total rows is less than or equal to limit, no need for load more
        if(apptsForTable.pagination.total_rows <= apptsForTable.pagination.limit) {
            loadMoreButton.disabled = true;
            return;
        }

        // If we've reached or exceeded the total rows, disable the button
        if(apptsForTable.pagination.offset + apptsForTable.pagination.limit >= apptsForTable.pagination.total_rows) {
            loadMoreButton.disabled = true;
            return;
        }

        // Set up click handler for the load more button
        loadMoreButton.onclick = () => displayNextPage();
    }

 
    async function refreshTable(origin){
        apptsTableContainer.innerHTML = "<md-linear-progress indeterminate></md-linear-progress>";
        apptsForTable = await apptService.getAppts({filters: getFiltersValue()});
        apptsTableContainer.innerHTML = "";
        displayApptsTable(apptsForTable.data, true);

        if (origin) {
            origin.disabled = true;
            setTimeout(() => {
                origin.disabled = false;
            }, 5000);
        }
    }
    

    async function applyTableFilters(){
        apptsTableContainer.style.opacity = 0.5;
        apptsTableContainer.style.pointerEvents = "none";
        apptsForTable = await apptService.getAppts({filters:getFiltersValue()});
        apptsTableContainer.style.opacity = "initial";
        apptsTableContainer.style.pointerEvents = "initial";
        displayApptsTable(undefined, true);
    }

    function getFiltersValue(){
        
        const filters = {
            month: apptsTableFiltersForm.querySelector("[name='filter-month']").value,
            year: apptsTableFiltersForm.querySelector("[name='filter-year']").value,
            status: apptsTableFiltersForm.querySelector("[name='filter-status']").value,
            patient: apptsTableFiltersForm.querySelector("[name='filter-patients']").value,
        };
        return filters;
    }



    function setApptsStatsData(){
        const statsContainer = document.getElementById("appts-stats-data-container");
        statsContainer.querySelector("[name='total-appts']").textContent = `${apptsForTable.pagination.total_rows ?? 0}`;        
        statsContainer.querySelector("[name='total-pending-appts']").textContent = `${apptsForTable.stats.count_pending ?? 0}` ;
        statsContainer.querySelector("[name='total-completed-appts']").textContent = `${apptsForTable.stats.count_completed ?? 0}` ;
        statsContainer.querySelector("[name='total-cancelled-appts']").textContent = `${apptsForTable.stats.count_cancelled ?? 0}` ;
        statsContainer.querySelector("[name='total-income']").textContent = formatMoney(apptsForTable.stats.total_cost)               
        
    }

    async function syncApptData(action, oldData = null, newData = null, apptList = apptsForTable, syncData = "table") {
        // console.log(`----${syncData}----`);
        const stats = apptList.stats;
        const validStatuses = [1, 2, 3];

        // console.log("sincronizando datos de citas");

        if(syncData === "table"){ refreshPatientApptsTable(); }
    
        // Validar acción
        const actions = ["create", "edit", "move_to_trash", "recover"];
        if (!actions.includes(action)) {
            throw new Error("Inalid action in SyncApptData");
        }
    
        // Validar estados si aplica
        if (oldData && !validStatuses.includes(Number(oldData.appt_status))) {
            throw new Error("Invalid status in oldData.appt_status");
        }
        if (newData && !validStatuses.includes(Number(newData.appt_status))) {
            throw new Error("Invalid status in newData.appt_status");
        }
    
        // Actualizar estadísticas según acción
        if (action === "edit" && oldData && newData) {
            if (oldData.appt_status !== newData.appt_status) {
                updateStats(-1, oldData, stats);    
                updateStats(1, newData, stats);
            }
    
            // Siempre actualizar total_cost si cambia
            if(Number(newData.appt_status == 3) && Number(oldData.appt_status) != 3){
                // Si la cita se cancela y antes no estaba canelada, restar su valor del precio
                stats.total_cost = Number(stats.total_cost) - Number(oldData.appt_price);
            }
            if(Number(newData.appt_status) != 3){
                // Si la cita ya no está cancelada, sumar su valor al precio
                stats.total_cost = Number(stats.total_cost) - Number(oldData.appt_price);
                stats.total_cost = Number(stats.total_cost) + Number(newData.appt_price);
            }

            // if(Number(oldData.appt_price) == Number(newData.appt_price)){
            //     stats.total_cost = Number(stats.total_cost) - Number(oldData.appt_price);
            //     stats.total_cost = Number(stats.total_cost) + Number(newData.appt_price);
            // }
            // console.log(`Antiguo status: ${oldData.appt_status} - Nuevo status: ${newData.appt_status}`)
            
        } else if (action === "move_to_trash" && oldData) {
            updateStats(-1, oldData, stats);
            if(Number(oldData.appt_status) != 3){
                stats.total_cost -= Number(oldData.appt_price);
            }
        } else if (action === "recover" && newData) {
            updateStats(1, newData, stats);
            if(Number(newData.appt_status) != 3){
                stats.total_cost += Number(newData.appt_price);
            }
        } else if (action === "create" && newData) {
            if(checkSelectedRange(newData)){
                updateStats(1, newData, stats);
                stats.total_cost = Number(stats.total_cost) + Number(newData.appt_price);
            }
            handleLoadMoreButton();
        }
    
        // Actualizar la lista de datos si es necesario
        if (action === "edit" && oldData && newData) {
            // check if the appt got in or out of the visible dates range
            const rangeChange = checkRangeChanges(oldData, newData);
            // console.log(rangeChange);

            // still do changes for the calendar sync
            const index = apptList.data.findIndex(appt => appt.id === oldData.id);
            // console.log(`Index encontrado para editar: ${index}`)
            
            if(index >= 0){
                // console.log("Se encontró la cita a actualizar en la lista")
                apptList.data[index] = { ...apptList.data[index], ...newData };
            }

            // if appt got in or out of the visible date range, update the table
            if(rangeChange && syncData === "table"){
                apptsForTable = await apptService.getAppts({filters: getFiltersValue()});
                displayApptsTable(undefined, true);
                // console.log("Actualizando tabla desde bd")
                return;
            }
        } else if (action === "move_to_trash" && oldData) {
            var index = apptList.data.findIndex(appt => appt.id === oldData.id);
            if(index >= 0){
                apptList.data.splice(index, 1);
                apptList.pagination.total_rows -= 1;
            }   
        } else if (action === "recover" && newData) {
            // check if the appt got in or out of the visible dates range
            const rangeChange = checkRangeChanges(oldData, newData);
            // console.log(rangeChange);
            if(rangeChange && syncData === "table"){
                apptsForTable = await apptService.getAppts({filters: getFiltersValue()});
                displayApptsTable(undefined, true);
                // console.log("Actualizando tabla desde bd")
                return;
            }else if(!rangeChange && syncData === "table"){
                apptList.data.push(newData);
                apptList.pagination.total_rows += 1;
            }

            if(syncData === "calendar"){
                apptList.data.push(newData);
                apptList.pagination.total_rows += 1;
            }
            // check range things here are needed too
        } else if (action === "create" && newData) {

            const rangeCheck = checkRange(newData);
            if(rangeCheck){
                apptList.data.push(newData);
            }
            const selectedRange = checkSelectedRange(newData);
            if(selectedRange){
                apptList.pagination.total_rows = Number(apptsForTable.pagination.total_rows) + 1;
            }


        }        

        apptList.data.sort((a, b) => {
            // Compare dates first to avoid unnecessary time comparisons
            if (a.appt_date > b.appt_date) return -1;
            if (a.appt_date < b.appt_date) return 1;
            // Only compare times if dates are equal
            return a.appt_time > b.appt_time ? -1 : a.appt_time < b.appt_time ? 1 : 0;
        });
    }
    
    function updateStats(delta, data, stats) {
        if (Number(data.appt_status) === 1) stats.count_pending = Number(stats.count_pending) + delta;
        if (Number(data.appt_status) === 2) stats.count_completed = Number(stats.count_completed) + delta;
        if (Number(data.appt_status) === 3) stats.count_cancelled = Number(stats.count_cancelled) + delta;
    }

    function checkRangeChanges(oldData, newData) {
        if (!oldData || !newData) return false;
    
        const oldDate = new Date(oldData.appt_date);
        const newDate = new Date(newData.appt_date);
        newDate.setUTCHours(0, 0, 0, 0);
    
        // #Visible Range: is the range of dates that are currently being displayed in the table
        const visibleRange = getVisibleTableRange(oldData);
        if (visibleRange.start === null || visibleRange.end === null) return true; // true means the appt is out of range because range does not even exist
        let visibleRangeStart = new Date(visibleRange.start);
        let visibleRangeEnd = new Date(visibleRange.end);
        visibleRangeStart.setUTCHours(0, 0, 0, 0); 
        visibleRangeEnd.setUTCHours(0, 0, 0, 0);   
        // Ordenar los rangos siendo el primero el menor
        if (visibleRangeStart > visibleRangeEnd) {[visibleRangeStart, visibleRangeEnd] = [visibleRangeEnd, visibleRangeStart];}

        // #Selected Range: is the range of dates that are currently being filtered by the user
        let selectedRangeStart, selectedRangeEnd, isInSelectedRange = false;
        if (getFiltersValue().month == "1,2,3,4,5,6,7,8,9,10,11,12") {
            selectedRangeStart = new Date(`${getFiltersValue().year}-01-01`);
            selectedRangeEnd = new Date(`${getFiltersValue().year}-12-31`);
        } else {
            selectedRangeStart = new Date(`${getFiltersValue().year}-${getFiltersValue().month}-01`);
            selectedRangeEnd = new Date(selectedRangeStart.getFullYear(), selectedRangeStart.getMonth() + 1, 0); // Last day of month
        }
        selectedRangeStart.setUTCHours(0, 0, 0, 0);
        selectedRangeEnd.setUTCHours(0, 0, 0, 0); 
    
        // Validate if the new date is in the selected range
        if (newDate >= selectedRangeStart && newDate <= selectedRangeEnd) {
            isInSelectedRange = true;
        } else {
            isInSelectedRange = false;
        }
    
        // Validate if the old date was in the visible range
        if (oldDate.getTime() >= visibleRangeStart.getTime() && oldDate.getTime() <= visibleRangeEnd.getTime()) {
            if (newDate.getTime() >= visibleRangeStart.getTime() && newDate.getTime() <= visibleRangeEnd.getTime()) {
                return false;
            } else {
                return true;
            }
        }
    
        // Validate if the old date was out of the visible range
        if (oldDate.getTime() < visibleRangeStart.getTime() || oldDate.getTime() > visibleRangeEnd.getTime()) {
            if (newDate.getTime() >= visibleRangeStart.getTime() && newDate.getTime() <= visibleRangeEnd.getTime()) {
                return true;
            } else {
                if (isInSelectedRange) { return true; }
                return false;
            }
        }
    }
    function checkRange(data){
        if(!data) return false;

        // True: Means the appointment IS in Range.
        // False: Means the appointment IS NOT in Range.

        const dataDate = new Date(`${data.appt_date}T${data.appt_time}`);
        // dataDate.setUTCHours(0, 0, 0, 0);

        // Selected Range
        let selectedRangeStart, selectedRangeEnd, isInSelectedRange = false;
        if (getFiltersValue().month == "1,2,3,4,5,6,7,8,9,10,11,12") {
            const year = parseInt(getFiltersValue().year);
            selectedRangeStart = new Date(year, 0, 1, 0, 0, 0);
            selectedRangeEnd = new Date(year, 11, 31, 23, 59, 59);
        } else {
            const year = parseInt(getFiltersValue().year);
            const month = parseInt(getFiltersValue().month) - 1; // Months are 0-based in JavaScript
            selectedRangeStart = new Date(year, month, 1, 0, 0, 0);
            selectedRangeEnd = new Date(year, month + 1, 0, 23, 59, 59); // Last day of month
        }

        // Visible Range
        const visibleRange = getVisibleTableRange();
        if(visibleRange.start == visibleRange.end){
            if(selectedRangeStart <= dataDate && selectedRangeEnd >= dataDate){return true;}
        }
        if(visibleRange.start === null || visibleRange.end === null){
            if(selectedRangeStart <= dataDate && selectedRangeEnd >= dataDate){return true;}
            return false;
        };

        // if (visibleRange.start === null || visibleRange.end === null) return false; 
        let visibleRangeStart = new Date(`${visibleRange.start}T${visibleRange.startTime}`);
        let visibleRangeEnd = new Date(`${visibleRange.end}T${visibleRange.endTime}`);
        // visibleRangeEnd.setDate(visibleRangeEnd.getDate() + 1);
        // visibleRangeStart.setUTCHours(0, 0, 0, 0); 
        // visibleRangeEnd.setUTCHours(0, 0, 0, 0);

        // Ordenar los rangos siendo el primero el menor
        if (visibleRangeStart > visibleRangeEnd) {[visibleRangeStart, visibleRangeEnd] = [visibleRangeEnd, visibleRangeStart];}

        
        // selectedRangeStart.setUTCHours(0, 0, 0, 0);
        // selectedRangeEnd.setUTCHours(0, 0, 0, 0); 

        // Here looks like we are returning the opposite, but actually we are returning if we should reload the table or not
        // console.log(`Visible Range start: ${visibleRangeStart.toLocaleString()} || Visible Range end: ${visibleRangeEnd.toLocaleString()}`);
        // console.log(`Selected Range start: ${selectedRangeStart.toLocaleString()} || Selected Range end: ${selectedRangeEnd.toLocaleString()}`);
        // console.log(`Appointment Date: ${dataDate.toLocaleString()}`);
        
        // if(visibleRangeEnd <= dataDate && selectedRangeEnd <= dataDate){return false;}
        // if(visibleRangeStart >= dataDate){return false;}
        // if(visibleRangeEnd >= dataDate){return true;}
        // if(visibleRangeEnd <= dataDate && selectedRangeEnd >= dataDate){return true;}
        // if(visibleRangeStart <= dataDate){return true;}

        if(selectedRangeStart.getTime() <= dataDate.getTime() && selectedRangeEnd.getTime() >= dataDate.getTime()){
            if(apptsForTable.data.length < apptsForTable.pagination.limit){
                // console.log("caso del limite activado");
                return true;
            }

        }

        if (visibleRangeEnd.getTime() <= dataDate.getTime() && selectedRangeEnd.getTime() <= dataDate.getTime()) {
            // console.log(`${visibleRangeEnd.toLocaleString()} <= ${dataDate.toLocaleString()} && ${selectedRangeEnd.toLocaleString()} <= ${dataDate.toLocaleString()}`);
            return false;
        }
        if (visibleRangeStart.getTime() > dataDate.getTime()) {
            // console.log(`${visibleRangeStart.toLocaleString()} >= ${dataDate.toLocaleString()}`);
            return false; 
        }
        // console.log(`Date ${dataDate.toLocaleString()} is in range`);
        return true;


        // Si la fecha del rango final es mayor a la fecha de la cita, la cita está en el rango
        // Si la fecha del rango final es menor a la fecha de la cita, pero la fecha del rango seleccinoado final es mayor, entonces está en el rango
        // Si la fecha del rango final es menor a la fecha de la cita, y la fecha del rango seleccionado final es menor, entonces no está en el rango
        // Si la fecha del rango inicial es menor a la fecha de la cita, la cita está en el rango
        // Si la fecha del rango inicial es mayor a la fecha de la cita, la cita no está en el rango. Cargará más tarde con la paginación.
    }

    function checkSelectedRange(data){
        if(!data) return false;
    
        // Convert the appointment date and time to a Date object
        const dataDate = new Date(`${data.appt_date}T${data.appt_time}`);
    
        // Selected Range with time
        let selectedRangeStart, selectedRangeEnd;
        if (getFiltersValue().month == "1,2,3,4,5,6,7,8,9,10,11,12") {
            // Full year range
            selectedRangeStart = new Date(`${getFiltersValue().year}-01-01T00:00:00`);
            selectedRangeEnd = new Date(`${getFiltersValue().year}-12-31T23:59:59`);
        } else {
            // Specific month range
            const year = parseInt(getFiltersValue().year);
            const month = parseInt(getFiltersValue().month) - 1; // Months are 0-based in JavaScript
            selectedRangeStart = new Date(year, month, 1, 0, 0, 0);
            selectedRangeEnd = new Date(year, month + 1, 0, 23, 59, 59); // Last day of month, end of day
        }
    
        // Check if the appointment date is within the selected range
        if (selectedRangeEnd.getTime() >= dataDate.getTime()) {
            return true;
        }
        if (selectedRangeEnd.getTime() < dataDate.getTime()) {
            return false;
        }
    
        // This line should not be reached if logic is correct, but included for completeness
        return false;
    }
    


    function getVisibleTableRange(){
        const dataObject = apptsForTable.data;
        if(dataObject.length <= 0) return {start: null, end: null};
        return {
            start: dataObject[0].appt_date ?? null,
            startTime: dataObject[0].appt_time ?? null,
            end: dataObject[dataObject.length - 1].appt_date ?? null,
            endTime: dataObject[dataObject.length - 1].appt_time ?? null,
        }
    }

    
    function deleteForever(data={}){
        if(!data.id) return false;
        const result = apptService.deleteAppt(data);
        if(!result) return false;
        message("Cita eliminada permanentemente");
        toggleDialog();
        toggleDialog();
        
        const trashAppts = TrashManager.trashItems().appts;
        const itemIndex = trashAppts.data.findIndex(appt => appt.id == data.id);
        if (itemIndex !== -1) {trashAppts.data.splice(itemIndex, 1);}
        trashAppts.pagination.total_rows -= 1;
        TrashManager.displayTrashTable("appt");
    }

    function openApptLog(){
        toggleOvermessage('#overmessage-appt-data-action-logs');
        displayApptLogs();
        // toggleWindowFullSize()
        // setTimeout(() => toggleWindowFullSize(), 100);
    }

    async function displayApptLogs(){
        const apptLog =  await apptService.getApptLog({id: currentOpenApptId});

        const fragment = document.createDocumentFragment();
        const rows = buildApptsLogTable(apptLog.data);
        rows.forEach(row => { fragment.appendChild(row); });
        apptsLogContainer.innerHTML = "";
        apptsLogContainer.appendChild(fragment);
    }
    function buildApptsLogTable(data){
        return data.map(row => {
            const element = document.createElement("div");
            element.className="content-box padding-8 light-color gap-4 justify-between flex-wrap hover-outline ";

            const actionDatetime = row.action_datetime;
            const [actionDate, actionTime] = row.action_datetime.split(' ');
            
            
            var actionName = "";
            switch (row.action_id) {
                case 6: actionName = "<md-icon class='filled dynamic pretty padding-8'>add_circle</md-icon>Cita creada"; break;
                case 7: actionName = "<md-icon class='filled dynamic pretty padding-8'>edit_square</md-icon>Cita eliminada"; break;
                case 8: actionName = "<md-icon class='filled dynamic pretty padding-8'>edit_square</md-icon>Cita editada"; break;
                default: actionName = row.action_id
                    break;
            }
            
            let changesHTML = '';
            const changes = JSON.parse(row.changes);
            const keys = Object.keys({ ...changes.old_values, ...changes.new_values });

            var tableVisibility = ""
            if(Object.keys(changes).length <= 0){tableVisibility = "hidden"}

            keys.forEach(key => {
                let columnName = "";
                let formatedOldValue = "";
                let formatedNewValue = "";
            
                // Determinar el nombre de la columna basado en la clave
                switch (key) {
                    case "appt_date":
                        columnName = "Fecha"; 
                        formatedOldValue = dateToPrettyDate(changes.old_values[key], true);
                        formatedNewValue = dateToPrettyDate(changes.new_values[key], true);
                        break;
                    case "appt_time":
                        columnName = "Hora";
                        formatedOldValue = timeToAmPm(changes.old_values[key]);
                        formatedNewValue = timeToAmPm(changes.new_values[key]); 
                        break;
                    case "appt_cost":
                        columnName = "Costo"; 
                        formatedOldValue = changes.old_values[key];
                        formatedNewValue = changes.new_values[key];
                        break;
                    case "appt_price":
                        columnName = "Precio";
                        formatedOldValue = formatMoney(changes.old_values[key]);
                        formatedNewValue = formatMoney(changes.new_values[key]); 
                        break;
                    case "appt_concept":
                        columnName = "Concepto"; 
                        formatedOldValue = changes.old_values[key] || "-";
                        formatedNewValue = changes.new_values[key];
                        break;
                    case "appt_status":
                        columnName = "Estado"; 
                        formatedOldValue = (changes.old_values[key] == 1) ? "Pendiente" : (changes.old_values[key] == 2) ? "Completada" : "Cancelada";
                        formatedNewValue = (changes.new_values[key] == 1) ? "Pendiente" : (changes.new_values[key] == 2) ? "Completada" : "Cancelada";
                        break;
                    case "appt_payment_status":
                        columnName = "Estado de pago";
                        formatedOldValue = (changes.old_values[key] == 1) ? "Pagada" : "Pendiente";
                        formatedNewValue = (changes.new_values[key] == 1) ? "Pagada" : "Pendiente";
                        break;
                    case "appt_mode":
                        columnName = "Modalidad";
                        formatedOldValue = (changes.old_values[key] == 1) ? "Presencial" : "En línea";
                        formatedNewValue = (changes.new_values[key] == 1) ? "Presencial" : "En línea";
                        break;
                    default: columnName = key; break;
                }
                
                if(key === "row_status"){
                    tableVisibility = "hidden";
                    
                    if(changes.new_values[key] == 0){
                        actionName = "<md-icon class='filled dynamic error-text'>delete</md-icon>Cita eliminada";
                    }
                    if(changes.new_values[key] == 1){
                        actionName = "<md-icon class='filled dynamic primary-text'>restore</md-icon>Cita recuperada";
                    }   
                    

                    return;
                }

              
                // Generar el HTML dinámico
                // const oldValue = changes.old_values[key] || "-"; // Si no existe, mostrar "N/A"
                // const newValue = changes.new_values[key] || "-"; // Si no existe, mostrar "N/A"
            
                changesHTML += `
                    <tr>
                        <td class="error-text">
                            ${columnName}: ${formatedOldValue}
                        </td>
                        <td>
                            <md-icon class="dynamic">arrow_forward</md-icon>
                        </td>
                        <td class="primary-text">
                            ${columnName}: ${formatedNewValue}
                        </td>
                    </tr>
                `;
            });

            
            // console.log(tableVisibility)

            element.innerHTML = `
                <div class="simple-container justify-between gap-8 flex-wrap">
                    <div class="simple-container padding-8 align-center gap-8 border-radius-16 font-family-inter">
                        ${actionName} el ${dateToPrettyDate(actionDate, true)} a las ${timeToAmPm(actionTime)}
                    </div>
                </div>
                <div class="simple-container ${tableVisibility}">
                    <table class="style-3 variant-3 font-family-inter">
                        <tr>
                            <td>Valor anterior:</td>
                            <td></td>
                            <td>Valor nuevo:</td>
                        </tr>
                        ${changesHTML}
                    </table>
                </div>
            `;

            return element;
        })
    }


    function updateAllApptElements(newData) {
        const apptElements = document.querySelectorAll(`[data-item_type="appt"][data-id="${newData.id}"]`);
        apptElements.forEach(element => {

            // if(element.getAttribute("data-item_type") === "appt"){
            //     element.classList.add("updated-item");
            //     element.addEventListener("animationend", () => element.classList.remove("updated-item"), { once: true });
            // }
            setApptDataset(newData, element);
            
            // Update visible content
            // const patientName = element.querySelector('[name="patient-name"]');
            const apptDate = element.querySelector('[name="appt-date"]');
            const apptTime = element.querySelector('[name="appt-time"]');
            const statusIcon = element.querySelector('[name="appt-icon"]');
            
            // if (patientName) patientName.textContent = newData.patient_name;
            if (apptDate) apptDate.textContent = dateToShort(newData.appt_date);
            if (apptTime) apptTime.textContent = timeToAmPm(newData.appt_time);
            if (statusIcon) {
                const icon = (newData.appt_status == "1") ? "circle" : (newData.appt_status == "2") ? "check_circle" : "cancel";
                const iconClass = (newData.appt_status == "1") ? "on-background-text" : (newData.appt_status == "2") ? "primary-text filled" : "error-text";
                
                statusIcon.textContent = icon;
                statusIcon.className = `dynamic ${iconClass}`;
            }
        });
    }


    async function refreshPatientApptsTable(){
        const patientProfile = document.getElementById("sub-section-patient-profile");
        const patientProfileAppts = patientProfile.querySelector("#md-panel-patient-appts");
        if(patientProfile.hasAttribute("active") && patientProfileAppts.hasAttribute("active")){
            PatientsManager.loadPatientAppts();
        }
    }

   
    return {
        openCreateApptWindow,
        createAppt,
        loadAppts,
        openApptDataWindow,
        applyTableFilters,
        displayApptsTable,
        displayNextPage,
        populateAppointmentPatientFilter,
        buildApptsTable,
        syncApptData,
        deleteForever,
        openApptLog,
        setApptDataset,
        getVisibleTableRange,
        updateApptsForCalendar,
        refreshTable,
        patientSelector,
        buildPatientsOptionList,
        editApptIgnoreTaken,
        currentOpenApptId: () => currentOpenApptId,
        appts: () => appts,
        apptsForTable: () => apptsForTable,
        apptsForCalendar: () => apptsForCalendar,
    }

})();

export default ApptsManager;
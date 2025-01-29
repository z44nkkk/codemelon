import patientService from '../services/patientService.js';
import apptService from '../services/apptService.js';
import ApptsManager from './apptsManager.js';
import TrashManager from './trashManager.js';

const PatientsManager = (() => {
    let patients = [];
    let patientsForOptions = [];
    let currentOpenPatientId = false;
    let currentOpenPatientDialogId = false;

    let apptsForPatient = [];

    // const createPatientWindow = document.getElementById("window-create-patient");
    const createPatientForm = document.getElementById("form-create-patient");

    // patient data
    const patientProfile = document.getElementById("sub-section-patient-profile");
    const editPatientForm = document.getElementById("form-edit-patient");

    // patient profile
    const patientApptsTableFiltersContainer = document.getElementById("container-patient-appts-table-filters");
    
    // dialog profile
    const patientProfileDialog = document.getElementById("dialog-patient-profile");
    const patientProfileDialogForm = document.getElementById("form-dialog-patient-profile");

    // table 
    const patientsTableContainer = document.getElementById("response-container-patients_table");
    const patientsTableFiltersParent = document.getElementById("container-patients-table-filters");
    const searchPatientField = document.getElementById("search-patient-field");

    const searchPatientTableContainer = document.getElementById("response-search-patient-results");

    async function loadPatients() {
        patientsTableContainer.innerHTML = "<md-linear-progress indeterminate></md-linear-progress>";
        patients = await patientService.getPatients({filters: getFiltersValue()});
        patientsForOptions = await patientService.getPatients({limit: "no_limit"});
        patientsTableContainer.innerHTML = "";
    }
    
    function displayPatientsTable(data = patients.data, replace = false){
        if(replace) patientsTableContainer.innerHTML = '';
        const fragment = document.createDocumentFragment();
        const rows = buildPatientsTable(data);
        
        rows.forEach(row => fragment.appendChild(row));
        // patientsTableContainer.innerHTML = '';
        patientsTableContainer.appendChild(fragment);

        setPatientsStatsData();
        handleLoadMoreButton();
    }

    function buildPatientsTable(patients, displayArea ="table"){
        const rows = [];
        if(patients.length <= 0){
            const row = document.createElement("div");
            row.className = "content-box align-center justify-center user-select-none";
            row.innerHTML = `<span class="body-large outline-text">No hay pacientes</span>`;
            rows.push(row);
            return rows;
        }

        patients.forEach(patient => {
            const row = document.createElement("div");
            row.className = "content-box light-color padding-16 overflow-hidden border-radius-16 cursor-pointer user-select-none on-background-text hover-outline";
            row.setAttribute("data-item_type", "patient");
            setPatientDataset(patient, row);
            
            if(displayArea === "table"){ row.onclick = () => openPatientProfile(row); }
            if(displayArea === "trash"){ row.onclick = () => openPatientDialog(row); }

            
            
            var patientStatus = patient.patient_status == 1 ? 'Activo' : patient.patient_status == 2 ? 'Dado de alta' : 'Inactivo';
            var patientStatusClass = patient.patient_status == 1 ? 'primary-text' : patient.patient_status == 2 ? 'on-background-text' : 'error-text';

            row.innerHTML = `
                <div class="simple-container direction-column grow-1 gap-4">
                <span class="body-large simple-container align-center gap-4 font-family-inter"><md-icon class="outline-text dynamic filled">person</md-icon> ${patient.patient_name}</span>
                    <span class="label-medium data-line square font-family-inter  ${patientStatusClass}">${patientStatus}</span>
                </div>
                <md-ripple></md-ripple>
            `;
            rows.push(row);
        });
        return rows
    }

    async function displayNextPage(){
        const currentPage = Math.ceil(patients.pagination.offset / patients.pagination.limit);
        const nextPage = currentPage + 1;
        const nextPageResponse = await patientService.getPatients({page: nextPage});
        patients.data = [...patients.data, ...nextPageResponse.data];
        patients.pagination = nextPageResponse.pagination;
        displayPatientsTable(patients.data, true);
    }

    function handleLoadMoreButton(){
        const loadMoreButton = document.getElementById("load-more-patients");
        if(!loadMoreButton) return false;

        loadMoreButton.disabled = false;
        if(patients.pagination.total_rows <= patients.pagination.limit){
            loadMoreButton.disabled = true;
            return false;
        }
        if(patients.pagination.offset + patients.pagination.limit >= patients.pagination.total_rows){
            loadMoreButton.disabled = true;
            return false;
        }

        loadMoreButton.onclick = () => {displayNextPage()};
    }

    async function applyTableFilters(){
        patientsTableContainer.style.opacity = 0.5;
        patientsTableContainer.style.pointerEvents = "none";
        patients = await patientService.getPatients({filters:getFiltersValue()});
        patientsTableContainer.style.opacity = "initial";
        patientsTableContainer.style.pointerEvents = "initial";
        displayPatientsTable(undefined, true);
    }
    
    function getFiltersValue(){
        const filters = {
            status: patientsTableFiltersParent.querySelector("[name='filter-status']").value,
            order: patientsTableFiltersParent.querySelector("[name='filter-order']").value,
            order_by: patientsTableFiltersParent.querySelector("[name='filter-order_by']").value,
        };
        return filters;
    }

    function openPatientProfile(origin){
        
        currentOpenPatientId = origin.dataset.id;
        origin.classList.add("primary-container");

        const createApptButton = patientProfile.querySelector("[name='button-create-appt']");
        createApptButton.onclick = () => { ApptsManager.openCreateApptWindow({specificPatient: currentOpenPatientId, openStyle: "absolute"}) }

        toggleSubSection('#sub-section-patient-profile', {exclusive: true, action: 'open'});
        animateChilds(document.getElementById("md-panel-patient-general"));
        
        setProfilePatientData(origin);
        setFormPatientData(origin);
        toggleWindow();

        const defaultTab = document.getElementById("md-tab-patients-general");
        if(defaultTab && defaultTab.hasAttribute('active')) return;
        document.getElementById("md-tab-patients-general").click();
    }

    async function loadPatientAppts(){
        const tableContainer = document.getElementById("response-container-patient-appts_table")
        tableContainer.innerHTML = `
            <md-circular-progress indeterminate></md-circular-progress>
        `;
        var filters = getApptsFilterValue();
        filters.patient = currentOpenPatientId;
        const result = await apptService.getAppts({filters: filters});
        if(!result) return false;
        apptsForPatient = result;
        tableContainer.innerHTML = ``;
        displayPatientApptsTable(result.data);
    }

    function displayPatientApptsTable(data, repalce = false){
        if(repalce) document.getElementById("response-container-patient-appts_table").innerHTML = '';

        const fragment = document.createDocumentFragment();
        const rows = ApptsManager.buildApptsTable(data);

        rows.forEach(row => fragment.appendChild(row));
        document.getElementById("response-container-patient-appts_table").appendChild(fragment);

        handleLoadMoreButtonAppts();
    }

    function handleLoadMoreButtonAppts(){
        // this function is for the patient profile appointments table
        const loadMoreButton = document.getElementById("load-more-patient-appts");
        if(!loadMoreButton) return false;

        loadMoreButton.disabled = false;
        if(apptsForPatient.pagination.total_rows <= apptsForPatient.pagination.limit){
            loadMoreButton.disabled = true;
            return false;
        }
        if(apptsForPatient.pagination.offset + apptsForPatient.pagination.limit >= apptsForPatient.pagination.total_rows){
            loadMoreButton.disabled = true;
            return false;
        }

        loadMoreButton.onclick = () => {displayNextPageAppts()};
    }

    async function displayNextPageAppts(){
        const currentPage = Math.ceil(apptsForPatient.pagination.offset / apptsForPatient.pagination.limit);
        const nextPage = currentPage + 1;
        const nextPageResponse = await apptService.getAppts({filters: {patient: currentOpenPatientId}, page: nextPage});
        apptsForPatient.data = [...apptsForPatient.data, ...nextPageResponse.data];
        apptsForPatient.pagination = nextPageResponse.pagination;
        displayPatientApptsTable(apptsForPatient.data, true);
    }

    function animateChilds(parent, options = {startOpacity: 0, animationVariant: ""}){
        Array.from(parent.children).forEach((el, index) => {
            el.style.opacity = options.startOpacity;
            el.style.animationDelay = `${index * 0.05}s`; // Retraso de 0.2s por elemento
            el.classList.add(`search-result-item-in${options.animationVariant}`); // Agrega la clase que activa la animación
            el.addEventListener("animationend", () => {
                el.classList.remove(`search-result-item-in${options.animationVariant}`)
                el.style.opacity = "initial";
            }, {once: true})
        });
    }

    
    function setFormPatientData(origin){
        if(!origin) return false;
        
        editPatientForm.querySelector("[name='patient-name']").value = origin.dataset.patient_name;
        editPatientForm.querySelector("[name='patient-birthdate']").value = (origin.dataset.patient_birthdate == "") ? "" : origin.dataset.patient_birthdate;
        // parent.querySelector("[name='patient-age']").value = (origin.dataset.patient_birthdate == "") ? "<i class='opacity-0-5'>Edad: - </i>" : calculateAge(new Date(origin.dataset.patient_birthdate)) + " años"
        
        editPatientForm.querySelector("[name='patient-gender']").value = (origin.dataset.patient_gender == "") ? "" : origin.dataset.patient_gender
        editPatientForm.querySelector("[name='patient-status']").value = origin.dataset.patient_status;
        editPatientForm.querySelector("[name='patient-contact_phone']").value = (origin.dataset.patient_contact_phone == "") ? "" : origin.dataset.patient_contact_phone
        editPatientForm.querySelector("[name='patient-contact_email']").value = (origin.dataset.patient_contact_email == "") ? "" : origin.dataset.patient_contact_email
        editPatientForm.querySelector("[name='patient-school']").value = (origin.dataset.patient_school == "") ? "" : origin.dataset.patient_school
        editPatientForm.querySelector("[name='patient-school_grade']").value = (origin.dataset.patient_school_grade == "") ? "" : origin.dataset.patient_school_grade
        editPatientForm.querySelector("[name='patient-notes']").value = (origin.dataset.patient_notes == "") ? "" : origin.dataset.patient_notes
        editPatientForm.querySelector("[name='patient-appt_price']").value = (origin.dataset.patient_appt_price == "") ? "" : origin.dataset.patient_appt_price
    }

    function setProfilePatientData(origin){
        if(!origin) return false;

        patientProfile.querySelector("[name='patient-name']").textContent = origin.dataset.patient_name;
        patientProfile.querySelector("[name='patient-birthdate']").innerHTML = (origin.dataset.patient_birthdate == "" || origin.dataset.patient_birthdate == "0000-00-00") ? "<i class='opacity-0-5'>Fecha de nacimiento: - </i>" : dateToShort(origin.dataset.patient_birthdate, true);
        patientProfile.querySelector("[name='patient-age']").innerHTML = (origin.dataset.patient_birthdate == "" || origin.dataset.patient_birthdate == "0000-00-00") ? "<i class='opacity-0-5'>Edad: - </i>" : calculateAge(new Date(origin.dataset.patient_birthdate)) + " años"
        
        patientProfile.querySelector("[name='patient-gender']").innerHTML = (origin.dataset.patient_gender == "") ? "<i class='opacity-0-5'>Género: - </i>" : origin.dataset.patient_gender
        patientProfile.querySelector("[name='patient-status']").innerHTML = origin.dataset.patient_status == 1 ? "<span class='primary-text data-line'>Activo</span>" : origin.dataset.patient_status == 2 ? "Dado de alta" : "Inactivo";
        patientProfile.querySelector("[name='patient-contact_phone']").innerHTML = (origin.dataset.patient_contact_phone == "") ? "<i class='opacity-0-5'>No especificado</i>" : origin.dataset.patient_contact_phone
        patientProfile.querySelector("[name='patient-contact_email']").innerHTML = (origin.dataset.patient_contact_email == "") ? "<i class='opacity-0-5'>No especificado</i>" : origin.dataset.patient_contact_email
        patientProfile.querySelector("[name='patient-school']").innerHTML = (origin.dataset.patient_school == "") ? "<i class='outline-variant-text'>No especificado</i>" : origin.dataset.patient_school
        patientProfile.querySelector("[name='patient-school_grade']").innerHTML = (origin.dataset.patient_school_grade == "") ? "<i class='opacity-0-5'>No especificado</i>" : origin.dataset.patient_school_grade
        patientProfile.querySelector("[name='patient-notes']").value = (origin.dataset.patient_notes == "") ? "Sin notas" : origin.dataset.patient_notes;
        patientProfile.querySelector("[name='patient-appt_price']").innerHTML = (origin.dataset.patient_appt_price == "") ? "<i class='opacity-0-5'>No especificado</i>" : formatMoney(origin.dataset.patient_appt_price);
    }


    function calculateAge(birthday) { 
        const ageDifMs = Date.now() - birthday.getTime();
        const ageDate = new Date(ageDifMs); 
        return Math.abs(ageDate.getUTCFullYear() - 1970);
    }

    function setPatientDataset(data, element) {
        if (!element) return false;
    
        for (const [key, value] of Object.entries(data)) {
            element.dataset[key] = value ?? ""; // Asigna cada propiedad como atributo `data-*`
        }
    }


    // async function createPatient(data) {
    //     const patientId = await patientService.insertPatient(data);
    //     if (patientId) {
    //         patients.push({ id: patientId, ...data });
    //     }
    // }

    async function editPatient(event, data={}){
        if(!event) return false;
        event.preventDefault();

        data.patient_id = currentOpenPatientId;
        data.patient_name = editPatientForm.querySelector("[name='patient-name']").value;
        data.patient_birthdate = editPatientForm.querySelector("[name='patient-birthdate']").value;
        data.patient_gender = editPatientForm.querySelector("[name='patient-gender']").value;
        data.patient_status = editPatientForm.querySelector("[name='patient-status']").value ?? 1;
        data.patient_contact_phone = editPatientForm.querySelector("[name='patient-contact_phone']").value;
        data.patient_contact_email = editPatientForm.querySelector("[name='patient-contact_email']").value;
        data.patient_school = editPatientForm.querySelector("[name='patient-school']").value;
        data.patient_school_grade = editPatientForm.querySelector("[name='patient-school_grade']").value;
        data.patient_notes = editPatientForm.querySelector("[name='patient-notes']").value;
        data.patient_appt_price = editPatientForm.querySelector("[name='patient-appt_price']").value ?? 0.00;

        const result = await patientService.updatePatient(data);
        if(!result) return false;

        toggleWindow();
        message("Paciente actualizado correctamente");

        const patientIndex = patients.data.findIndex(patient => patient.id == currentOpenPatientId);
        syncPatientData("edit", patients.data[patientIndex], data);
        syncPatientData("edit", patients.data[patientIndex], data, patientsForOptions);
        // console.log(patients.data[patientIndex]);

        // patients.data[patientIndex] = {...patients.data[patientIndex], ...data};

        displayPatientsTable(undefined, true);
        openPatientProfile(patientsTableContainer.querySelector(`[data-id='${currentOpenPatientId}']`));
    }

    // search
    function openSearchPatientWindow(isMobile = false){
        searchPatientField.value = "";
        if(isMobile){
            toggleWindow('#window-search-patient', 0, undefined, true)
        }else{
            toggleWindow('#window-search-patient', 'absolute', 2, true)
        }
        searchPatientField.focus();
    }

    async function search(event, page = 0){
        if(!event) return false;
        event.preventDefault();
        const parentId = "#window-search-patient";
        if(!checkEmpty(parentId, "input")){return;}
        // if(searchPatientField.value == currentSearch){return false}
        toggleButton(parentId, true);

        const searchValue = searchPatientField.value.trim();
        const result = await patientService.getPatients({filters: {search: searchValue}}); 
        toggleButton(parentId, false);
        displayResults(result.data);
    }

    function displayResults(data){
        if(!data) return false;
        const fragment = document.createDocumentFragment();
        const rows = buildSearchResultTable(data);

        rows.forEach(row => fragment.appendChild(row));
        const state = Flip.getState("#window-search-patient");
        searchPatientTableContainer.innerHTML = "";
        searchPatientTableContainer.appendChild(fragment);
        applyAnimation(state, `#window-search-patient`, false, false, true);

        searchPatientTableContainer.querySelectorAll(".search-result-item").forEach((el, index) => {
            el.style.animationDelay = `${index * 0.05}s`; // Retraso de 0.2s por elemento
            el.classList.add('search-result-item-in'); // Agrega la clase que activa la animación
            el.addEventListener("animationend", () => {
                el.classList.remove("search-result-item-in")
                el.classList.add("search-result-item-opacity-100");
            }, {once: true})
        });

    }

    function buildSearchResultTable(patients){
        const rows = [];
        if(patients.length <= 0){
            const row = document.createElement("div");
            row.className = "search-result-item simple-container gap-8 padding-16 align-center user-select-none"
            row.innerHTML = `
                <md-icon>search_off</md-icon>
                <p>No se encontraron resultados</p>
            `;
            rows.push(row);
            return rows;
        }

        patients.forEach(patient => {
            const row = document.createElement("div");
            row.className = "content-box search-result-item light-color padding-16 overflow-hidden border-radius-16 cursor-pointer user-select-none on-background-text";
            row.onclick = () => openPatientProfile(row);
            setPatientDataset(patient, row);

            var patientStatus = patient.patient_status == 1 ? 'Activo' : 'Inactivo';

            row.innerHTML = `
                <md-ripple></md-ripple>

                <div class="simple-container direction-column grow-1 gap-4">
                    <span class="body-large font-family-inter">${patient.patient_name}</span>
                </div>
            `;
            rows.push(row);
        });
        return rows
    }




    // Create patient form process
    async function createPatient(event){
        if(!event) return false;
        event.preventDefault();
        const mimimumDataParentId = createPatientForm.querySelector("[name='minimum-data']").id;
        if(!checkEmpty(`#${mimimumDataParentId}`, 'input, select')){return;}
        const data = {
            patient_name: createPatientForm.querySelector("[name='patient-name']").value.trim(),
            patient_gender: createPatientForm.querySelector("[name='patient-gender']").value,
            patient_birthdate: createPatientForm.querySelector("[name='patient-birthdate']").value,
            patient_contact_phone: createPatientForm.querySelector("[name='patient-contact_phone']").value.trim(),
            patient_contact_email: createPatientForm.querySelector("[name='patient-contact_email']").value.trim(),
            patient_school: createPatientForm.querySelector("[name='patient-school']").value.trim(),
            patient_school_grade: createPatientForm.querySelector("[name='patient-school_grade']").value.trim(),
            patient_appt_price: createPatientForm.querySelector("[name='patient-appt_price']").value.trim() ?? 0.00,
        }
        const result = await patientService.insertPatient(data);
        if(!result) return false;

        
        patients.data.push({
            id: result.patient_id,
            patient_name: data.patient_name,
            patient_gender: data.patient_gender,
            patient_birthdate: data.patient_birthdate,
            patient_status: 1,
            patient_contact_phone: data.patient_contact_phone,
            patient_contact_email: data.patient_contact_email,
            patient_school: data.patient_school,
            patient_school_grade: data.patient_school_grade,
            patient_appt_price: data.patient_appt_price,
            patient_notes: ""
        });
        patientsForOptions.data.push({
            id: result.patient_id,
            patient_name: data.patient_name,
            patient_gender: data.patient_gender,
            patient_birthdate: data.patient_birthdate,
            patient_status: 1,
            patient_contact_phone: data.patient_contact_phone,
            patient_contact_email: data.patient_contact_email,
            patient_school: data.patient_school,
            patient_school_grade: data.patient_school_grade,
            patient_appt_price: data.patient_appt_price,
            patient_notes: ""
        });
        patientsForOptions.data = patientsForOptions.data.sort((a, b) => a.patient_name.localeCompare(b.patient_name));

        displayPatientsTable(undefined, true);
        toggleSection("section-patients");
        document.querySelector("#section-patients > *").scrollTo({
            top: patientsTableContainer.scrollHeight,
            behavior: "smooth"
        });
        toggleWindow();
        openPatientProfile(patientsTableContainer.querySelector(`[data-id='${result.patient_id}']`));
        message("Paciente creado correctamente");
        ApptsManager.patientSelector.innerHTML = ApptsManager.buildPatientsOptionList(patientsForOptions.data);
    }

    // async function createPatient(event = false, owner_id = false){
    //     if(event !== false) event.preventDefault();
    //     if(!checkEmpty(`#${createPatientForm.id}`, 'input')){return;}
    //     const data = {
    //         patient_name: createPatientForm.querySelector("[name='patient-name']").value.trim()
    //     }
    //     const result = await patientService.insertPatient(data, owner_id)
    //     if(!result) return false;

    //     patients.push({
    //         id: result.patient_id,
    //         patient_name: data.patient_name
    //     })
    //     displayPatientsTable();
    //     message("Paciente creado correctamente");
    // }

    function openCreatePatientWindow(){
        toggleWindow("#window-create-patient");
        const additionalDataButton = createPatientForm.querySelector("[name='additional-data-checkbox']");
        additionalDataButton.addEventListener("change", () => { additionalDataButton.checked ? showAdditionalData() : hideAdditionalData() });
    }
    function showAdditionalData(){
        const additionalData = createPatientForm.querySelector("[name='additional-data']");
        additionalData.classList.remove("hidden");
    }
    function hideAdditionalData(){
        const additionalData = createPatientForm.querySelector("[name='additional-data']");
        additionalData.classList.add("hidden");
    }

    // stats data
    function setPatientsStatsData(){
        const statsContainer = document.getElementById("patients-stats-data-container");
        statsContainer.querySelector("[name='total-patients']").textContent = patients.pagination.total_rows ?? 0;
        statsContainer.querySelector("[name='active-patients']").textContent = patients.stats.count_active ?? 0;
        statsContainer.querySelector("[name='discharged-patients']").textContent = patients.stats.count_discharged ?? 0;
        statsContainer.querySelector("[name='inactive-patients']").textContent = patients.stats.count_inactive ?? 0;
    }

    // Sync data
    function syncPatientData(action, oldData = null, newData = null, list = patients){
        const stats = list.stats;
        const validStatuses = [1, 2, 3];

        const actions = ["edit", "move_to_trash", "recover"];
        if(!actions.includes(action)) throw new Error("Acción inválida en syncApptData");

        // console.log(oldData);
        
        if(oldData && !validStatuses.includes(Number(oldData.patient_status))) {
            throw new Error("Invalid status in oldData.patient_status");
        }
        if(newData && !validStatuses.includes(Number(newData.patient_status))) {
            throw new Error("Invalid status in newData.patient_status");
        }

        // if(oldData && !validStatuses.includes(Number(oldData.patient_status))) throw new Error("Estado de paciente inválido");
        // if(newData && !validStatuses.includes(Number(newData.patient_status))) throw new Error("Estado de paciente inválido");


        if(action === "edit" && oldData && newData){

            if(oldData.patient_status !== newData.patient_status){
                updateStats(-1, oldData, stats); // restamos al estado anterior
                updateStats(1, newData, stats); // sumamos al estado nuevo
            }

        } else if (action === "move_to_trash" && oldData) {
            updateStats(-1, oldData, stats);
        } else if(action === "recover" && newData){
            updateStats(1, newData, stats);
        }

        if(action === "edit" && oldData && newData){
            const patientIndex = list.data.findIndex(patient => patient.id == oldData.id);
            list.data[patientIndex] = {...list.data[patientIndex], ...newData};
        } else if(action === "move_to_trash" && oldData){
            const patientIndex = list.data.findIndex(patient => patient.id == oldData.id);
            list.data.splice(patientIndex, 1);
            list.pagination.total_rows -= 1;
        } else if(action === "recover" && newData){
            list.data.push(newData);
            list.pagination.total_rows += 1;
        }

        // this may be too inefficient
        setTimeout(() => {
            ApptsManager.patientSelector.innerHTML = ApptsManager.buildPatientsOptionList(patientsForOptions.data);
        }, 600);
    }

    function updateStats(delta, data, stats) {
        if (Number(data.patient_status) === 1) stats.count_active = Number(stats.count_active) + delta;
        if (Number(data.patient_status) === 2) stats.count_discharged = Number(stats.count_discharged) + delta;
        if (Number(data.patient_status) === 3) stats.count_inactive = Number(stats.count_inactive) + delta;
    }

    async function applyTableFiltersAppts(){
        const filters = getApptsFilterValue();
        filters.patient = currentOpenPatientId;
        apptsForPatient = await apptService.getAppts({filters: filters});
        displayPatientApptsTable(apptsForPatient.data, true);
    }

    function getApptsFilterValue(){
        const filters = {
           month: patientApptsTableFiltersContainer.querySelector("[name='filter-month']").value,
            year: patientApptsTableFiltersContainer.querySelector("[name='filter-year']").value,
            status: patientApptsTableFiltersContainer.querySelector("[name='filter-status']").value,
        };
        return filters;
    }

    function openPatientDialog(origin){
        toggleDialog("dialog-patient-profile");
        const patientDataset = origin.dataset;
        
        currentOpenPatientDialogId = patientDataset.id;
        
        patientProfileDialogForm.querySelector("[name='patient-name']").textContent = origin.dataset.patient_name;
        patientProfileDialogForm.querySelector("[name='patient-gender']").innerHTML = (patientDataset.patient_gender == "") ? "<i class='opacity-0-5'>Género: - </i>" : patientDataset.patient_gender;
        patientProfileDialogForm.querySelector("[name='patient-age']").innerHTML = (patientDataset.patient_gender == "" || patientDataset.patient_gender == "0000-00-00") ? "<i class='opacity-0-5'>Edad: - </i>" : calculateAge(new Date(origin.dataset.patient_birthdate)) + " años"
        patientProfileDialogForm.querySelector("[name='patient-birthdate']").innerHTML = (patientDataset.patient_gender == "" || patientDataset.patient_gender == "0000-00-00") ? "<i class='opacity-0-5'>Fecha de nacimiento: - </i>" : dateToShort(origin.dataset.patient_birthdate, true);
        
        patientProfileDialogForm.querySelector("[name='patient-status']").innerHTML = patientDataset.patient_status == 1 ? "<span class='primary-text data-line'>Activo</span>" : patientDataset.patient_status == 2 ? "Dado de alta" : "Inactivo";
        patientProfileDialogForm.querySelector("[name='patient-contact_phone']").innerHTML = (patientDataset.patient_contact_phone == "") ? "<i class='opacity-0-5'>No especificado</i>" : patientDataset.patient_contact_phone
        patientProfileDialogForm.querySelector("[name='patient-contact_email']").innerHTML = (patientDataset.patient_contact_email == "") ? "<i class='opacity-0-5'>No especificado</i>" : patientDataset.patient_contact_email
        patientProfileDialogForm.querySelector("[name='patient-school']").innerHTML = (patientDataset.patient_school == "") ? "<i class='outline-variant-text'>No especificado</i>" : patientDataset.patient_school
        patientProfileDialogForm.querySelector("[name='patient-school_grade']").innerHTML = (patientDataset.patient_school_grade == "") ? "<i class='opacity-0-5'>No especificado</i>" : patientDataset.patient_school_grade
        patientProfileDialogForm.querySelector("[name='patient-notes']").value = (patientDataset.patient_notes == "") ? "Sin notas" : patientDataset.patient_notes;
        patientProfileDialogForm.querySelector("[name='patient-appt_price']").innerHTML = (patientDataset.patient_appt_price == "") ? "<i class='opacity-0-5'>No especificado</i>" : formatMoney(patientDataset.patient_appt_price);
        patientProfileDialog.querySelector("[name='button-delete-forever']").onclick = () => { TrashManager.openDeleteForeverDialog("patient") };
        patientProfileDialog.querySelector("[name='button-recover']").onclick = () => { TrashManager.openRecoverDialog("patient") };
    }

    function deleteForever(data){
        if(!data.id) return false;
        const result = patientService.deletePatient(data);
        if(!result) return false;
        message("Paciente eliminado correctamente");
        toggleDialog();
        toggleDialog();

        const trashPatients = TrashManager.trashItems().patients;
        const itemIndex = trashPatients.data.findIndex(patient => patient.id == data.id);
        if(itemIndex !== -1) trashPatients.splice(itemIndex, 1);
        trashPatients.pagination.total_rows -= 1;
        TrashManager.displayTrashTable("patients");
    }


    return {
        loadPatients,
        createPatient,
        editPatient,
        displayPatientsTable,
        openPatientProfile,
        openCreatePatientWindow,
        openSearchPatientWindow,
        applyTableFilters,
        loadPatientAppts,
        buildPatientsTable,
        search,
        applyTableFiltersAppts,
        deleteForever,
        syncPatientData,
        animateChilds,
        loadPatientAppts,
        patients: () => patients,
        patientsForOptions: () => patientsForOptions,
        apptsForPatient: () => apptsForPatient,
        currentOpenPatientId: () => currentOpenPatientId,
        currentOpenPatientDialogId: () => currentOpenPatientDialogId,
    };
})();

export default PatientsManager;
import trashService from "../services/trashService.js";
import ApptsManager from "./apptsManager.js";
import calendarManager from "./calendarManager.js";

const TrashManager = (() => {

    let trashItems = [];
    async function loadTrashItems(){
        trashItems.appts = await trashService.getTrash({item_type: "appt"});
        trashItems.patients = await trashService.getTrash({item_type: "patient"});
        // trashItems.patients = await trashService.getTrash({item_type: "patient"});
    }

    const moveToTrashDialog = document.getElementById("dialog-move-to-trash-confirmation");
    const moveToTrashDialogForm = moveToTrashDialog.querySelector("#form-dialog-move-to-trash-confirmation");
    const recoverFromTrashDialog = document.getElementById("dialog-recover-from-trash-confirmation");
    const recoverFromTrashDialogForm = recoverFromTrashDialog.querySelector("#form-dialog-recover-from-trash-confirmation");
    const deleteForeverDialog = document.getElementById("dialog-delete-forever-confirmation");
    const deleteForeverDialogForm = deleteForeverDialog.querySelector("#form-dialog-delete-forever-confirmation");

    // tables
    // appts
    const deletedApptsTable = document.getElementById("trash-table-deleted-appts");
    const loadMoreApptsButton = document.getElementById("trash-load-more-appts");
    
    // patients
    const loadMorePatientsButton = document.getElementById("trash-load-more-patients");
    const deletedPatientsTable = document.getElementById("trash-table-deleted-patients");


    function openConfirmationDialog(itemType = null){
        if(!itemType) { return false; }
        var itemName;
        var data = {};

        if(itemType === "appt"){
            itemName = "esta cita";
            data.item_type = "appt";
            data.item_id = ApptsManager.currentOpenApptId();
        }
        if(itemType === "patient"){
            itemName = "este paciente";
            data.item_type = "patient";
            data.item_id = PatientsManager.currentOpenPatientId();
        }

        const itemNameContainer = moveToTrashDialogForm.querySelector("[name='item-name']");
        itemNameContainer.textContent = itemName;

        toggleDialog("dialog-move-to-trash-confirmation");
        moveToTrashDialog.querySelector("[name='button-confirm-move-to-trash']").onclick = function() { moveToTrash(data, itemType); }
    }

    function openRecoverDialog(itemType = null){
        if(!itemType) { return false; }
        var itemName;
        var data = {};

        if(itemType === "appt"){
            itemName = "esta cita";
            data.item_type = "appt";
            data.item_id = ApptsManager.currentOpenApptId();
        }
        if(itemType === "patient"){
            itemName = "este paciente";
            data.item_type = "patient";
            data.item_id = PatientsManager.currentOpenPatientDialogId();
        }

        const itemNameContainer = recoverFromTrashDialogForm.querySelector("[name='item-name']");
        itemNameContainer.textContent = itemName;

        toggleDialog("dialog-recover-from-trash-confirmation");
        recoverFromTrashDialog.querySelector("[name='button-confirm-recover-from-trash']").onclick = async () => await recoverFromTrash(data, itemType); 
    }

    function openDeleteForeverDialog(itemType = null){
        if(!itemType) return false;
        var itemName;
        var data = {};

        if(itemType === "appt"){
            itemName = "esta cita";
            data.item_type = "appt";
            data.id = ApptsManager.currentOpenApptId();
            deleteForeverDialog.querySelector("[name='button-confirm-delete-forever']").onclick = function() { 
                ApptsManager.deleteForever(data); 
            }

        }
        if(itemType === "patient"){
            itemName = "este paciente";
            data.item_type = "patient";
            data.item_id = PatientsManager.currentOpenPatientId();
            data.id = PatientsManager.currentOpenPatientDialogId();
            deleteForeverDialog.querySelector("[name='button-confirm-delete-forever']").onclick = function() { 
                PatientsManager.deleteForever(data);
            }
        }

        const itemNameContainer = deleteForeverDialogForm.querySelector("[name='item-name']");
        itemNameContainer.textContent = itemName;

        toggleDialog("dialog-delete-forever-confirmation");
    }

    async function moveToTrash(data = {}, itemType = null){
        const origin = event.target;
        origin.disabled = true;
        const result = await trashService.moveToTrash(data);
        origin.disabled = false;
        if(!result) return false;
        message("Se movió a la papelera con éxito");
        toggleDialog();
        toggleWindow();

        if(itemType === "appt"){
            var appts = ApptsManager.apptsForTable();
            var itemIndex = appts.data.findIndex(appt => appt.id == data.item_id);     
            var oldData = appts.data[itemIndex] ?? null;

            if(oldData == null){
                appts = ApptsManager.apptsForCalendar();
                itemIndex = appts.data.findIndex(appt => appt.id == data.item_id);
                oldData = appts.data[itemIndex];
            }

            // Sync Trash object
            TrashManager.trashItems().appts.data.push(oldData);
            trashItems.appts.pagination.total_rows += 1;

            // Sync Appointments object
            ApptsManager.syncApptData("move_to_trash", oldData);
            ApptsManager.syncApptData("move_to_trash", oldData, null, ApptsManager.apptsForCalendar(), "calendar");
            ApptsManager.displayApptsTable(undefined, true);
            calendarManager.refreshCalendar();

        }
        if(itemType === "patient"){
            const patients = PatientsManager.patients();
            const itemIndex = patients.data.findIndex(patient => patient.id == data.item_id);
            const oldData = patients.data[itemIndex];

            // Sync Trash object
            TrashManager.trashItems().patients.data.push(oldData);
            trashItems.patients.pagination.total_rows += 1;

            // Sync Patients object
            PatientsManager.syncPatientData("move_to_trash", oldData);
            PatientsManager.syncPatientData("move_to_trash", oldData, undefined, PatientsManager.patientsForOptions());
            PatientsManager.displayPatientsTable(undefined, true);
            toggleSubSection('#sub-section-patient-profile');
        }

    }
    async function recoverFromTrash(data={}, itemType = null){
        if(!data.item_id || !itemType) return false;

        const result = await trashService.recoverFromTrash(data);
        if(!result) return false;
        message("Se recuperó con éxito");
        toggleDialog();
        toggleDialog();

        if(itemType === "appt"){
            var deletedAppts = TrashManager.trashItems().appts;
            var itemIndex = deletedAppts.data.findIndex(appt => appt.id == data.item_id);
            var newData = deletedAppts.data[itemIndex];
            
            // Sync Appointments object
            ApptsManager.syncApptData("recover", newData, newData);
            ApptsManager.syncApptData("recover", null, newData, ApptsManager.apptsForCalendar(), "calendar");
            ApptsManager.displayApptsTable(undefined, true);
            calendarManager.refreshCalendar();
            
            // ApptsManager.apptsForTable().data.push(newData);

            deletedAppts.data.splice(itemIndex, 1);
            deletedAppts.pagination.total_rows -= 1;

            // ApptsManager.apptsForTable().pagination.total_rows += 1;
            // ApptsManager.displayApptsTable(undefined, true);
            displayTrashTable(itemType);
        }
        if(itemType === "patient"){
            const deletedPatients = TrashManager.trashItems().patients;
            const itemIndex = deletedPatients.data.findIndex(patient => patient.id == data.item_id);
            const newData = deletedPatients.data[itemIndex];

            deletedPatients.data.splice(itemIndex, 1);
            deletedPatients.pagination.total_rows -= 1;
            displayTrashTable(itemType);

            // Sync Patients object
            await PatientsManager.loadPatients();
            console.log(PatientsManager.patients() )
            
            PatientsManager.displayPatientsTable(PatientsManager.patients().data, true);
            ApptsManager.patientSelector.innerHTML = ApptsManager.buildPatientsOptionList(PatientsManager.patientsForOptions().data);            
        }
    }

    function displayTrashTable(itemType = null, replace = false){
        if(replace) deletedApptsTable.innerHTML = "";
        if(!itemType || itemType == null) { return false; }

        if(itemType === "appt"){
            deletedApptsTable.innerHTML = "";
            var table = ApptsManager.buildApptsTable(trashItems.appts.data, "trash");
        }
        if(itemType === "patient"){
            deletedPatientsTable.innerHTML = "";
            var table = PatientsManager.buildPatientsTable(trashItems.patients.data, "trash");
        }

        const fragment = document.createDocumentFragment();
        if(table) table.forEach(row => {fragment.appendChild(row);});

        if(itemType === "appt"){
            handleLoadMoreButton(itemType);
            deletedApptsTable.appendChild(fragment);
        }
        if(itemType === "patient"){
            handleLoadMoreButton(itemType);
            deletedPatientsTable.appendChild(fragment);
        }
    }

    function handleLoadMoreButton(itemType = null){
        if(!itemType) return false;

        if(itemType === "appt"){
            var loadMoreButton = loadMoreApptsButton;
            var paginationData = trashItems.appts.pagination;
        }
        if(itemType === "patient"){
            var loadMoreButton = loadMorePatientsButton;
            var paginationData = trashItems.patients.pagination
        }

        // Reset button state first
        loadMoreButton.disabled = false;

        // If total rows is less than or equal to limit, no need for load more
        if(paginationData.total_rows <= paginationData.limit) {
            loadMoreButton.disabled = true;
            // console.log("total rows less than or equal to limit");
            return;
        }

        // If we've reached or exceeded the total rows, disable the button
        if(paginationData.offset + paginationData.limit >= paginationData.total_rows) {
            loadMoreButton.disabled = true;
            // console.log("reached or exceeded total rows");
            return;
        }

        // Set up click handler for the load more button
        loadMoreButton.onclick = () => displayNextPage(itemType);
    }

    async function displayNextPage(itemType = null){
        if(!itemType) return false;

        if(itemType === "appt"){
            var currentPage = Math.ceil(trashItems.appts.pagination.offset / trashItems.appts.pagination.limit);
            var nextPage = currentPage + 1;
            const nextPageResponse = await trashService.getTrash({page: nextPage, item_type: itemType});
            trashItems.appts.data = [...trashItems.appts.data, ...nextPageResponse.data];
            trashItems.appts.pagination = nextPageResponse.pagination;
            displayTrashTable(itemType);
        }

        if(itemType === "patient"){
            var currentPage = Math.ceil(trashItems.patients.pagination.offset / trashItems.patients.pagination.limit);
            var nextPage = currentPage + 1;
            const nextPageResponse = await trashService.getTrash({page: nextPage, item_type: itemType});
            trashItems.patients.data = [...trashItems.patients.data, ...nextPageResponse.data];
            trashItems.patients.pagination = nextPageResponse.pagination;
            displayTrashTable(itemType);
        }
        
        
    }

    return {
        openConfirmationDialog,
        openRecoverDialog,
        openDeleteForeverDialog,
        loadTrashItems,
        displayTrashTable,
        trashItems: () => trashItems,
    }

})();
export default TrashManager;
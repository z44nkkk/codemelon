import PatientsManager from './managers/patientsManager.js';
import ApptsManager from './managers/apptsManager.js';
import TrashManager from './managers/trashManager.js';
import CalendarManager from './managers/calendarManager.js';


(async function main() {
    try {
        // 1. Configuración inicial
        const appLoadingIndicator = document.getElementById("main-app-loading-indicator");
        
        // await ApptsManager.loadTodayAppts();
        
        // 2. Cargar datos iniciales
        await PatientsManager.loadPatients();
        PatientsManager.displayPatientsTable();

        // ApptsManager.loadingAnimation(true, "table");
        await ApptsManager.loadAppts();
        ApptsManager.displayApptsTable(undefined, true);
        ApptsManager.populateAppointmentPatientFilter();
        ApptsManager.displayTodayAppts();

        await TrashManager.loadTrashItems();


        window.PatientsManager = PatientsManager;
        window.ApptsManager = ApptsManager;
        window.TrashManager = TrashManager;
        window.CalendarManager = CalendarManager;

        CalendarManager.renderCalendar(CalendarManager.convertToLocalTimezone(new Date()));
        CalendarManager.loadViewStyle();
        
        // window.App = {
        //     PatientsManager,
        // }


        // Configurar eventos globales
        setupGlobalEvents();
      
        appLoadingIndicator.style.display = "none";
        // console.log('Aplicación lista.');
    } catch (error) {
        console.error('Error al iniciar la aplicación:', error);
    }
})();

function setupGlobalEvents(){

    document.querySelectorAll("[data-trash-opener]").forEach((button) => {
        button.addEventListener("click", function(){

            // turn on this to enable custom page to open
            // let type = this.dataset.trashOpener;
            // TrashManager.displayTrashTable(type);
            TrashManager.displayTrashTable("appt");
            TrashManager.displayTrashTable("patient");
            toggleWindow("#window-trash");
        });
    });

    // document.getElementById("button-open-trash").addEventListener("click", () =>{
    //     toggleWindow("#window-trash");
    //     TrashManager.displayTrashTable("appt");
    //     TrashManager.displayTrashTable("patient");
    // })

}
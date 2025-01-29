import ApptsManager from "./apptsManager.js";
import apptService from "../services/apptService.js";

const calendarManager = (() => {

    // containers
    const calendarContainer = document.getElementById("calendar-container");
    const filtersContainer = document.getElementById("container-calendar-filters");
    const calendarHeaderDesktop = document.getElementById("calendar-header-desktop");
    const calendarHeaderMobile = document.getElementById("calendar-header-mobile");
    const calendarExpandedDay = document.getElementById("sub-section-calendar-day");

    // date data
    const currentDate = new Date();
    
    function getDaysInMonth(month, year){ return new Date(year, month + 1, 0).getDate();}
    function getFirstDayOfMonth(month, year){ return new Date(year, month, 1).getDay();}

    function createCalendarStructure(year, month) {
        const days = getDaysInMonth(month, year);
        const firstDay = getFirstDayOfMonth(month, year);
        
        calendarContainer.innerHTML = "";

        // Add empty cells for the first days
        for(var i = 0; i < firstDay; i++){
            var emptyCell = document.createElement("div");
            emptyCell.classList.add("calendar-day-cell");
            emptyCell.classList.add("calendar-empty-cell")
            calendarContainer.appendChild(emptyCell);
        }

        // Create calendar days
        for(var day = 1; day <= days; day++){
            const cellDate = new Date(year, month, day).toISOString().split('T')[0];
            var dayCell = document.createElement("div");
            dayCell.setAttribute("data-flip-id", "animate");
            dayCell.setAttribute("data-day", day);
            dayCell.setAttribute("data-cell-date", cellDate);
            dayCell.classList.add("calendar-day-cell");

            var firstRow = document.createElement("div");
            firstRow.className = "calendar-day-header";

            var dayNumber = document.createElement("span"); 
            dayNumber.classList.add("calendar-day-number");
            dayNumber.textContent = day;
            firstRow.appendChild(dayNumber);

            var expandButton = document.createElement("button");
            expandButton.classList.add("calendar-expand-button");
            expandButton.innerHTML = `<md-ripple></md-ripple><md-icon>expand_content</md-icon>`;
            expandButton.setAttribute("data-flip-id", "animate");
            expandButton.onclick = () => expandCalendarDay(cellDate);
            firstRow.appendChild(expandButton);

            var eventsContainer = document.createElement("div");
            eventsContainer.className = "calendar-events-container";
            

            dayCell.appendChild(firstRow);
            dayCell.appendChild(eventsContainer);
            calendarContainer.appendChild(dayCell);
        }

        const dateToday = new Date().toISOString().split('T')[0];
        const todayCell = document.querySelector(`.calendar-day-cell[data-cell-date="${dateToday}"]`);
        if(todayCell) todayCell.classList.add("calendar-day-cell-today");

        flowChilds(document.getElementById("calendar-container"), {betweenDelay: 0.01})
    }

    function assignEventsToCalendar(appointments = ApptsManager.apptsForCalendar().data) {
        
        const dayCells = calendarContainer.querySelectorAll('.calendar-day-cell[data-cell-date]');
        dayCells.forEach(dayCell => {
            const eventsContainer = dayCell.querySelector('.calendar-events-container') 
            eventsContainer.innerHTML = "";
            
            const cellDate = dayCell.getAttribute('data-cell-date');
            const eventsToday = appointments.filter(appt => appt.appt_date === cellDate)
                .sort((a, b) => a.appt_time.localeCompare(b.appt_time));


            dayCell.querySelectorAll(".calendar-event-indicator").forEach(indicator => indicator.remove());

            eventsToday.forEach(appt => {
                const eventIndicator = document.createElement("div");
                eventIndicator.className = "calendar-event-indicator";
                dayCell.appendChild(eventIndicator);

                const event = document.createElement("div");
                event.setAttribute("title", appt.patient_name);
                event.setAttribute("data-flip-id", "animate");
                event.setAttribute("data-item_type", "appt");
                const eventColorClass = appt.appt_status == "1" ? "primary-container on-primary-container-text" : 
                                        appt.appt_status == "3" ? "error-container on-error-container-text" : 
                                        "outline-with-shadow";

                event.className = `calendar-event user-select-none cursor-pointer ${eventColorClass}`;
                
                const icon = appt.appt_status == "1" ? "circle" : 
                            appt.appt_status == "3" ? "cancel" : 
                            "check_circle";
                const iconClass = appt.appt_status == "1" ? "" : 
                                appt.appt_status == "3" ? "close" : 
                                "filled";

                event.innerHTML = `
                    <md-ripple></md-ripple>
                    <div class="calendar-event-icon"><md-icon class="dynamic ${iconClass}">${icon}</md-icon></div>
                    <div class="calendar-event-name grow-1">${appt.patient_name}</div>
                    <div class="calendar-event-time ">${timeToAmPm(appt.appt_time)}</div>
                `;

                ApptsManager.setApptDataset(appt, event);
                event.onclick = () => ApptsManager.openApptDataWindow(event);
                eventsContainer.appendChild(event);
            });
        });
    }

    function renderCalendar(date) {
        const year = date.getFullYear();
        const month = date.getMonth();
        
        createCalendarStructure(year, month);
        assignEventsToCalendar();
    }

    function refreshCalendar(){
        const date = new Date();

        const year = date.getFullYear();
        const month = date.getMonth();
        assignEventsToCalendar();
        refreshExpandedCalendarDate();
    }

    function refreshExpandedCalendarDate(){
        if(document.getElementById("sub-section-calendar-day").hasAttribute("active")){
            expandCalendarDay(currentExpandedDate);
        }
    }

    const calendarDaySubSection = document.getElementById("sub-section-calendar-day");
    const dayContainer = calendarDaySubSection.querySelector("[name='day-container']");
    const apptsContainer = calendarDaySubSection.querySelector("[name='appts-container']");
    let currentExpandedDate = "";

    function expandCalendarDay(dateCell){
        currentExpandedDate = dateCell;
        toggleSubSection('#sub-section-calendar-day', {animationType: 'from-origin', action: "open", customOrigin: document.querySelector(`.calendar-day-cell[data-cell-date="${dateCell}"]`)});

        const dateCellContainer = document.querySelector(`.calendar-day-cell[data-cell-date="${dateCell}"]`);

        dayContainer.innerHTML = dateToShort(dateCell)
        apptsContainer.innerHTML = "";

        const appts = dateCellContainer.querySelectorAll(".calendar-event");
        appts.forEach(appt => {

            const item = appt.cloneNode(true);
            item.onclick = () => ApptsManager.openApptDataWindow(item);

            apptsContainer.appendChild(item);

        });

        const createApptButton = calendarExpandedDay.querySelector("[name='button-create-appt']");
        // console.log(dateCell)
        createApptButton.onclick = () => ApptsManager.openCreateApptWindow({specificDate: dateCell, openStyle: "absolute"});
        // console.log(createApptButton)
    
        flowChilds(apptsContainer)
    }



    function getCalendarRange(){
        const filters = {
            month: filtersContainer.querySelector("[name='filter-month']").value,
            year: filtersContainer.querySelector("[name='filter-year']").value,
        }
        return filters;
    }

    function changeViewStyle(origin){
        if(!origin) return false;
        const viewSelectorParent = origin.parentElement;
        if(!viewSelectorParent) return false;
        const activeSelector = viewSelectorParent.querySelector("[active]");
        activeSelector.removeAttribute("active");
        origin.setAttribute("active", "");

        const viewStyle = origin.getAttribute("data-view-style");
        calendarContainer.classList.remove(activeSelector.getAttribute("data-view-style"));
        calendarContainer.classList.add(viewStyle);

        calendarHeaderDesktop.classList.remove(activeSelector.getAttribute("data-view-style"));
        calendarHeaderDesktop.classList.add(viewStyle);

        calendarHeaderMobile.classList.remove(activeSelector.getAttribute("data-view-style"));
        calendarHeaderMobile.classList.add(viewStyle);

        
        localStorage.setItem("codemelon-melon-minde-calendar-view-style", viewStyle);
    }
    function loadViewStyle(){
        const viewStyle = localStorage.getItem("codemelon-melon-minde-calendar-view-style");
        if(viewStyle){
            const viewSelector = filtersContainer.querySelector(`[data-view-style="${viewStyle}"]`);
            if(viewSelector) changeViewStyle(viewSelector);
        }
    }

    async function changeDateFilter(){
        const filters = getCalendarRange();
        const dateString = `${filters.year}-${filters.month}-01`;
        const date = new Date(dateString);

        await ApptsManager.updateApptsForCalendar();

        renderCalendar(date);
        assignEventsToCalendar();
    }






    return {
        renderCalendar,
        refreshCalendar,
        getCalendarRange,
        changeViewStyle,
        loadViewStyle,
        changeDateFilter
    }

})();

export default calendarManager;
<section id="section-calendar" class="gap-4">

    <div class="simple-container justify-between align-center align-center gap-8 flex-wrap bottom-margin-4">
        <span class="headline-small on-background-text dm-sans">Calendario</span>
        <div class="simple-container gap-8" id="container-calendar-filters">
            <div class="simple-container hide-on-mobile" id="folders-view-selector-parent">
                <span class="view-selector" data-view-style="normal" onclick="CalendarManager.changeViewStyle(this)" data-tooltip="Vista normal" title="Vista normal" active>
                    <md-ripple></md-ripple>
                    <md-icon>calendar_view_month</md-icon>
                </span>
                <span class="view-selector" data-view-style="view-style-list" onclick="CalendarManager.changeViewStyle(this)" data-tooltip="Vista de lista" title="Vista de lista">
                    <md-ripple></md-ripple>
                    <md-icon>table_rows</md-icon>
                </span>
            </div>
            <select 
                name="filter-month" 
                onchange="CalendarManager.changeDateFilter(this)"
                >
                <?php displayMonths(); ?>
            </select>
            <select 
                name="filter-year" 
                onchange="CalendarManager.changeDateFilter(this)"
                >
                <?php displayYears(); ?>
            </select>
        </div>
    </div>

    <div class="calendar-header hide-on-mobile" id="calendar-header-desktop">
        <div>Domingo</div>
        <div>Lunes</div>
        <div>Martes</div>
        <div>Miercoles</div>
        <div>Jueves</div>
        <div>Viernes</div>
        <div>Sabado</div>    
    </div>
    <div class="calendar-header only-on-mobile" id="calendar-header-mobile">
        <div>D</div>
        <div>L</div>
        <div>M</div>
        <div>M</div>
        <div>J</div>
        <div>V</div>
        <div>S</div>    
    </div>
    <div class="calendar-container" id="calendar-container">
        
    </div>

    <div
        data-sub-section
        data-flip-id="animate"
        id="sub-section-calendar-day"
        class="simple-container absolute-screen top-padding-safe-area on-background-text"
        >
        <div class="simple-container direction-column grow-1 align-center">

            <div class="simple-container direction-column gap-8 width-100 max-width-800">
                <div class="simple-container justify-between">
                    <md-icon-button onclick="toggleSubSection('#sub-section-calendar-day')"><md-icon>close</md-icon></md-icon-button>
                    <md-filled-tonal-button class="solid" name="button-create-appt" data-flip-id="animate"><md-icon slot="icon">calendar_add_on</md-icon>Agendar cita este día</md-filled-tonal-button>
                </div>
                <!-- <div class="content-box padding-16 light-color">
                    <span class="headline-small weight-500 dm-sans" name="day-container">Martes 21 de enero</span>
                </div> -->
                <span class="display-small weight-500 dm-sans" name="day-container">Martes 21 de enero</span>
                <div class="simple-container direction-column gap-4 calendar-expanded-event-container" name="appts-container"></div>
            </div>

        </div>
    </div>



    <!-- <div class="simple-container grow-1 align-center justify-center">
        <span class="headline-small weight-600 outline-text">En construcción</span>
    </div> -->
</section>
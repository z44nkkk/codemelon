<section id="section-appointments" class="align-center">
    <div class="simple-container direction-column width-100 gap-8 grow-1 ">
    
        <div class="simple-container justify-between align-center align-center gap-8 flex-wrap">
            <span class="headline-small on-background-text dm-sans">Citas</span>
            <div class="simple-container gap-8">
                <!-- <md-filled-tonal-button class="solid"><md-icon slot="icon">search</md-icon>Búscar</md-filled-tonal-button> -->
                <md-filled-button onclick="ApptsManager.openCreateApptWindow()" data-flip-id="animate"><md-icon slot="icon">calendar_add_on</md-icon>Agendar cita</md-filled-button>
            </div>
        </div>
        <div class="simple-container flex-wrap gap-8" id="appts-stats-data-container">
            <div class="content-box gap-0 grow-1 basis-small user-select-none light-color hover-outline overflow-hidden">
                <span class="label-normal outline-text">Totales</span>
                <span class="headline-small dm-sans on-background-text" name="total-appts">...</span>
            </div>
            <div class="content-box gap-0 grow-1 basis-small user-select-none light-color hover-outline overflow-hidden">
                <span class="label-normal outline-text">Pendientes</span>
                <span class="headline-small dm-sans on-background-text" name="total-pending-appts">...</span>
            </div>
            <div class="content-box gap-0 grow-1 basis-small user-select-none light-color hover-outline overflow-hidden">
                <span class="label-normal outline-text">Completadas</span>
                <span class="headline-small dm-sans on-background-text" name="total-completed-appts">...</span>
                <!-- <md-icon class="absolute-card medium">event_available</md-icon> -->
            </div>
            <div class="content-box gap-0 grow-1 basis-small user-select-none light-color hover-outline overflow-hidden">
                <span class="label-normal outline-text">Canceladas</span>
                <span class="headline-small dm-sans on-background-text" name="total-cancelled-appts">...</span>
                <!-- <md-icon class="absolute-card medium">event_busy</md-icon> -->
            </div>
            <div class="content-box gap-0 grow-1 basis-small user-select-none secondary-container on-secondary-containert-text hover-outline">
                <span class="label-normal" data-label>Ingresos estimados</span>
                <span class="headline-small dm-sans primary-text" name="total-income" data-value>...</span>
            </div>
        </div>

        <div class="simple-container direction-column gap-4">
            <span class="label-normal outline-text left-margin-8">Filtros</span>
            <form
                id="form-appts-table-filters" 
                onsubmit="event.preventDefault()" 
                class="simple-container gap-8 overflow-auto"
                >
                <select 
                    name="filter-month" 
                    onchange="ApptsManager.applyTableFilters()"
                    >
                    <?php displayMonths(); ?>
                    <option value="1,2,3,4,5,6,7,8,9,10,11,12">Mes: Todos</option>
                </select>
                <select 
                    name="filter-year" 
                    onchange="ApptsManager.applyTableFilters()"
                    >
                    <?php displayYears(); ?>
                    <option value="2023,2024,2025,2026">Años todos</option>
                </select>
                <select 
                    name="filter-status"
                    onchange="ApptsManager.applyTableFilters()"
                    >
                    <option value="1,2,3">Todas las citas</option>
                    <option value="2">Citas completadas</option>
                    <option value="1">Citas pendientes</option>
                    <option value="3">Citas canceladas</option>
                </select>
                <select 
                    name="filter-patients"
                    onchange="ApptsManager.applyTableFilters()"
                    >
                </select>
                
                <md-icon-button title="Refrescar tabla" style="margin-left:auto;" onclick="ApptsManager.refreshTable(this)"><md-icon>refresh</md-icon></md-icon-button>
            </form>
        </div>

        <div class="simple-container direction-column grow-1 gap-8 basis-large border-radius-16 max-height-100">

            <div class="simple-container direction-column gap-8">
                <div id="response-container-appts-table" class="simple-container direction-column gap-4">
                    <md-linear-progress indeterminate></md-linear-progress>
                </div>
                <div class="simple-container justify-center">
                    <md-filled-tonal-button id="load-more-appts" disabled="true">Cargar más</md-filled-tonal-button>
                </div>
            </div>
        </div>


        <!-- <div class="simple-container justify-between align-center align-center gap-8 flex-wrap">
            <span class="headline-small on-background-text dm-sans">Citas</span>
            <div class="simple-container gap-8">
                <md-filled-tonal-button class="solid"><md-icon slot="icon">search</md-icon>Búscar</md-filled-tonal-button>
                <md-filled-button onclick="ApptsManager.openCreateApptWindow()" data-flip-id="animate"><md-icon slot="icon">add</md-icon>Agendar cita</md-filled-button>
            </div>
        </div>

        <div class="simple-container grow-1 gap-8 flex-wrap overflow-hidden">
            <div class="simple-container direction-column grow-1 basis-small max-height-100 gap-8 overflow-hidden">

                <div 
                    class="
                        content-box 
                        secondary-container 
                        on-secondary-container-text 
                        gap-0
                        padding-32
                        
                    "
                    >
                    <span class="body-large">Ingresos estimados</span>
                    <span class="display-small dm-sans weight-600">$3,478.00</span>
                </div>
                <div class="simple-container gap-8 flex-wrap">
                    <div class="content-box gap-0 grow-1 basis-normal light-color hover-outline">
                        <span class="label-normal outline-text">Citas agendadas</span>
                        <span class="headline-small dm-sans">24 citas</span>
                    </div>
                    <div class="content-box gap-0 grow-1 basis-normal light-color hover-outline">
                        <span class="label-normal outline-text">Citas canceladas</span>
                        <span class="headline-small dm-sans error-text">6 citas</span>
                    </div>
                </div>
                <div class="simple-container direction-column grow-1 overflow-hidden">
                    <cocounut-chart data-x-values="[&quot;2024-12-02&quot;,&quot;2024-12-04&quot;,&quot;2024-12-05&quot;,&quot;2024-12-06&quot;,&quot;2024-12-07&quot;,&quot;2024-12-09&quot;,&quot;2024-12-11&quot;,&quot;2024-12-12&quot;,&quot;2024-12-13&quot;,&quot;2024-12-16&quot;,&quot;2024-12-17&quot;,&quot;2024-12-18&quot;,&quot;2024-12-19&quot;]" data-y-values="[2000,1500,1000,1500,500,500,500,1000,1000,3400,500,1800,1300]" data-chart-title="Citas diarias" data-x-values-type="date" data-y-values-type="money"></cocounut-chart>
                </div>

                <div class="simple-container">
                    <div class="content-box light-color overflow-hidden gap-0 outline-1-light-inset">
                        <span class="body-large">Citas agendadas:</span>
                        <span class="display-large weight-bold dm-sans">24 citas</span>
                    </div>
                </div>

            </div>
            <div class="simple-container direction-column grow-1 basis-large border-radius-16 max-height-100 overflow-auto">
                <div class="simple-container direction-column gap-8">
                    <div id="response-container-appts-table" class="simple-container direction-column gap-4"></div>
                </div>
            </div>
        </div> -->

        


    </div>
</section>
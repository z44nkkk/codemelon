<window
    id="window-search-patient"
    class="increased slim h-auto"
    data-flip-id="animate"
    >
    <div class="simple-container padding-16 gap-16">
        <md-icon-button onclick="toggleWindow()"><md-icon>close</md-icon></md-icon-button>
        <span class="headline-medium dm-sans user-select-none">Buscador</span>
    </div>
    <holder class="on-background-text gap-0">
        <form onsubmit="PatientsManager.search(event)" class="simple-container gap-8 flex-wrap justify-right">
            <input id="search-patient-field" class="grow-1 rounded" type="search" autocomplete="off" placeholder="Buscar un paciente">
            <md-filled-button role="presentation" value="" has-icon=""><md-icon slot="icon" aria-hidden="true">search</md-icon>Buscar</md-filled-button>
        </form>
        <div id="response-search-patient-results" class="top-margin-8 simple-container direction-column gap-8"></div>
        <div class="simple-container width-100 container-info-empty-table grow-1"></div>
        <div class="simple-container" id="pagination-search"></div>
    </holder>
</window>
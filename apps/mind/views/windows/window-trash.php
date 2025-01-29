<window
    id="window-trash"
    class="increased on-background-text"
    data-flip-id="animate"
    >
    <div class="simple-container padding-16">
        <md-icon-button onclick="toggleWindow()"><md-icon>close</md-icon></md-icon-button>
    </div>
    <holder class="align-center">
        <div class="simple-container direction-column width-100 max-width-800 gap-24 ">
            <div class="simple-container direction-column">
                <span class="display-large dm-sans">Papelera</span>
                <span class="body-normal outline-text">
                    Aquí puedes ver y restaurar todo lo que has eliminado.
                </span>
            </div>

            <div class="simple-container gap-8">
                <button
                    active
                    data-w-section="w-section-trash-deleted-appts"
                    onclick="toggleWSection('w-section-trash-deleted-appts')"
                    class="style-2"
                    >
                    <md-ripple></md-ripple>
                    Citas eliminadas
                </button>
                <button
                    data-w-section="w-section-trash-deleted-patients"
                    class="style-2"
                    onclick="toggleWSection('w-section-trash-deleted-patients')"
                    >
                    <md-ripple></md-ripple>
                    Pacientes eliminados
                </button>
            </div>

            <!-- w section appointments -->
            <div active class="w-section simple-container direction-column grow-1" id="w-section-trash-deleted-appts">
                <div class="simple-container direction-column gap-4" id="trash-table-deleted-appts"></div>
                <div class="simple-container justify-center top-margin-16">
                    <md-filled-tonal-button id="trash-load-more-appts" disabled="true">Cargar más</md-filled-tonal-button>
                </div>
            </div>

            <!-- w section patients -->
            <div class="w-section simple-container direction-column grow-1" id="w-section-trash-deleted-patients">
                <div class="simple-container direction-column gap-4" id="trash-table-deleted-patients"></div>
                <div class="simple-container justify-center top-margin-16">
                    <md-filled-tonal-button id="trash-load-more-patients" disabled="true">Cargar más</md-filled-tonal-button>
                </div>
            </div>

        </div>
    </holder>
</window>


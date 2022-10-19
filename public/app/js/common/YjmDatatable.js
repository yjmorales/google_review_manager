'use strict';

/**
 * Manages the datatable lists.
 */
function YjmDatatable() {

    let state = {
        table: null,
    };

    // UI Elements.
    const ui = {
        $table: '.table[data-is-datatable="true"]',
    };

    /**
     * Front controller of the class.
     */
    function init() {
        $(document).ready(initClientDataTable);
    }

    /**
     * Once the page is loaded sets the client list data table
     */
    function initClientDataTable() {
        const config = {
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            'pageLength': 10,
            "compact": true,
            "emptyTable": 'No business available',
            'oLanguage': {
                'sSearch': 'Search',
                "sLengthMenu": "Show _MENU_",
            },
            "aria": {
                "sortAscending": ": activate to sort column ascending",
                "sortDescending": ": activate to sort column descending"
            },
        };

        state.table = $(ui.$table).DataTable(config);
    }

    init();
}
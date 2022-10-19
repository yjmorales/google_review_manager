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
        $(`${ui.$table} tbody`).on('click', 'td.dt-control', expandRow);
    }

    /**
     * Once the page is loaded sets the client list data table
     */
    function initClientDataTable() {
        const config = {
            paging: true,
            lengthChange: true,
            searching: true,
            info: true,
            autoWidth: false,
            responsive: true,
            pageLength: 10,
            compact: true,
            emptyTable: 'No business available',
            oLanguage: {
                'sSearch': 'Search',
                "sLengthMenu": "Show _MENU_",
            },
            aria: {
                "sortAscending": ": activate to sort column ascending",
                "sortDescending": ": activate to sort column descending"
            },
            rowReorder: true,
            columnDefs: [
                {orderable: true, className: 'reorder', targets: [0, 1]},
                {orderable: false, targets: '_all'}
            ]
        };

        state.table = $(ui.$table).DataTable(config);
    }

    /**
     * Function to expand a row
     */
    function expandRow() {
        const $td = $(this);
        const id = $td.data('id');
        const $tr = $td.closest('tr');
        const row = state.table.row($tr);
        const $open = $td.find('i[data-open="true"]');
        const $close = $td.find('i[data-open="false"]');
        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            $tr.removeClass('shown');
            $open.removeClass('d-none');
            $close.addClass('d-none');
        } else {
            // Open this row
            row.child(buildSubRow(id, row.data())).show();
            $tr.addClass('shown');
            $open.addClass('d-none');
            $close.removeClass('d-none');
        }
    }

    /**
     * Builds the inner html to render inside the table row. It's built based on data.
     */
    function buildSubRow(rowId) {
        const $rowDetails = $(`div[data-child-list-row-details="true"][data-id=${rowId}]`);
        if (!$rowDetails.length) {
            return '';
        }
        const $newRowDetails = $rowDetails.clone();
        $newRowDetails.removeClass('d-none');
        return ($newRowDetails.html());
    }

    init();
}
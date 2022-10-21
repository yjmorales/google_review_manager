'use strict';

/**
 * Manages the datatable lists.
 */
function YjmDatatable() {

    /**
     * Holds the table state.
     * @type {Object}
     */
    let state = {
        table: null,
        tableConfig: {
            paging: true,
            lengthChange: true,
            lengthMenu: [[7, 14, 21, -1], [7, 14, 21, "All"]],
            searching: true,
            info: true,
            autoWidth: false,
            responsive: true,
            pageLength: 7,
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
            ],
            initComplete: onDatatableIsLoaded
        }
    };

    // UI Elements.
    const ui = {
        $table: null,
    };

    /**
     * Subscribes the UI events.
     */
    function listenUIEvents() {
        $(`${ui.$table} tbody`).on('click', 'td.dt-control', expandRow);
    }

    /**
     * Function used to displays the table container after the datatable is rendered.
     */
    function onDatatableIsLoaded() {
        const tableContainer = $(ui.$table).data('container');
        if (tableContainer) {
            $(tableContainer).removeClass('d-none');
        }
    }

    /**
     * Once the page is loaded sets the client list data table
     *
     * @param {string} tableSelector
     */
    function initDataTable(tableSelector) {
        if (!tableSelector) {
            throw 'Table selector is required';
        }

        ui.$table = tableSelector;
        if (!$.fn.DataTable.isDataTable(ui.$table)) {
            state.table = $(ui.$table).DataTable(state.tableConfig);
        }

        listenUIEvents();
    }

    /**
     * Function to expand a row to see its child subrow.
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
            row.child(buildDetailsSubRow(id, row.data())).show();
            $tr.addClass('shown');
            $open.addClass('d-none');
            $close.removeClass('d-none');
        }
    }

    /**
     * Builds the inner html to render inside the table row. It's built based on data.
     */
    function buildDetailsSubRow(rowId) {
        const $rowDetails = $(`div[data-child-list-row-details="true"][data-id=${rowId}]`);
        if (!$rowDetails.length) {
            return '';
        }
        const $newRowDetails = $rowDetails.clone();
        $newRowDetails.removeClass('d-none');
        return ($newRowDetails.html());
    }

    /**
     * Adds the new row into the datatable.
     * @param {array} rowData Holds the data to build the row.
     */
    function addRow(rowData) {
        sanitize();
        state.table.row.add(rowData).draw().show().draw(false);
    }

    /**
     * Function used to verify that the module can be used because all its dependencies are correct.
     *
     * @return {boolean}
     */
    function sanitize() {
        if (!state.table) {
            throw 'The datatable has not been initialized. call initDataTable function first.';
        }
    }

    /**
     * Removes a row identified by rowId
     * @param {string} rowSelector Row id to be removed.
     */
    function removeRow(rowSelector) {
        sanitize();
        const $row = $(rowSelector);
        if (!$row.length) {
            throw `No row identified by ${rowSelector}`;
        }
        state.table.row($row).remove().draw();
    }

    /**
     * Gets the data respective to the row identified by the given selector.
     *
     * @param {string} rowSelector
     *
     * @return {Object}
     */
    function getRowData(rowSelector) {
        sanitize();
        if (!rowSelector) {
            throw `No row identifier`;
        }
        const $row = $(rowSelector);
        if (!$row.length) {
            throw `No row identified by ${rowSelector}`;
        }
        const tableRow = state.table.row(rowSelector);
        return tableRow.data();
    }

    /**
     * Updates a row identified by the given selector.
     *
     * @param {string} rowSelector
     * @param {Object} newData
     */
    function updateRow(rowSelector, newData) {
        sanitize();
        if (!rowSelector) {
            throw `No row identifier`;
        }
        const $row = $(rowSelector);
        if (!$row.length) {
            throw `No row identified by ${rowSelector}`;
        }
        const tableRow = state.table.row(rowSelector);
        tableRow.data(newData);
    }

    /**
     * Exporting functions through properties.
     */
    this.initDataTable = initDataTable;
    this.addRow = addRow;
    this.updateRow = updateRow;
    this.removeRow = removeRow;
    this.getRowData = getRowData;
}
'use strict';

/**
 * Manages businesses list.
 */
function BusinessList(YJMDatatable) {

    if (!YJMDatatable) {
        throw 'YJMDatatable is not passed as argument';
    }

    /**
     * @type {Object} Holds the module state.
     */
    const state = {
        modules: {
            YJMDatatable: YJMDatatable, Notification: new Notification(),
            ModalManager: new ModalManagement(),
            OverlayLoaderManager: new OverlayLoaderManager(),
        }, clicked: {
            rowToRemoveSelector: null
        }
    }
    /**
     * UI Elements.
     */
    const ui = {
        // Table selector
        $table: '.table[data-is-datatable="true"]',
        $tableRow: '.business-list-item',
        // Buttons
        $btnGenerateReviewOnListPage: '.btnGenerateNewReviewFromBusinessList',
        $btnRemoveBusinessSelectorListItem: '[data-id="btn-remove-business"]',
        $btnOpenQrDetailsDialog: 'div[data-id="review-details"]',
        // Form containers and dialogs.
        $modalRemoveBusinessConfirmation: '#modalDeleteListItem',
        $modalShowQrCode: '#modalShowQrCode',
        // Filter
        $filterActive: $('select[name="businessIndustrySector"]'),
        $filterIndustrySector: $('select[name="businessStatus"]'),
        $data: $('#data'),
    };

    /**
     * Subscribes UI Events.
     */
    function listenUIEvents() {
        $(ui.$btnRemoveBusinessSelectorListItem).on('click', setBusinessIdToRemove);
        $(document).on('click', ui.$btnOpenQrDetailsDialog, openQrDetailsDialog);
    }

    /**
     * Once the user clicks on remove button, this function saves the clicked button id
     * on the state and opens the confirmation modal.
     */
    function setBusinessIdToRemove() {
        state.clicked.rowToRemoveSelector = `#${$(this).closest('tr').attr('id')}`;
        initModalRemove();
        $(ui.$modalRemoveBusinessConfirmation).modal('show');
    }

    /**
     * Initializes the remove conformation modal.
     */
    function initModalRemove() {
        const $row = $(state.clicked.rowToRemoveSelector),
            businessName = $row.data('name'),
            body = `<p>You selected to remove the business <strong>${businessName}</strong></p>
                       <p>If you remove the business the operation cannot undo. All Google Review Links assosiated with 
                       the business will be removed and cannoy be recovered.</p> 
                       <p>Do you want to proceed?</p>`,
            settings = {
                id: ui.$modalRemoveBusinessConfirmation,
                title: 'Remove business',
                body: body,
                successCallback: removeBusiness,
            };
        state.modules.ModalManager.init(settings);
    }

    /**
     * Removes a business row from datatable.
     */
    function removeBusiness() {
        const $btn = $(this);
        $btn.prop('disabled', true);
        state.modules.OverlayLoaderManager.start();
        try {
            const url = $(state.clicked.rowToRemoveSelector).data('remove-url');
            fetch(url, {method: 'POST'})
                .then((response) => response.json())
                .then((data) => {
                    updateUI(data.business.name);
                });
        }
        catch (e) {
            state.modules.OverlayLoaderManager.stop();
            state.modules.Notification.error('Unable to remove the business.');
            $btn.prop('disabled', false);
        }
        const updateUI = function (businessName) {
            state.modules.YJMDatatable.removeRow(state.clicked.rowToRemoveSelector);
            state.modules.Notification.success(`The business <strong>${businessName}</strong> has been successfully removed.`, 'Removed Business');
            $(ui.$modalRemoveBusinessConfirmation).modal('hide');
            state.modules.OverlayLoaderManager.stop();
            $btn.prop('disabled', false);
        }
    }

    /**
     * This clears the criteria selection on select2 components if any criteria is being selected once the page is loaded.
     */
    function initSelect2() {
        const criteriaIndustrySector = ui.$data.data('criteria-business-industry-sector');
        const criteriaStatus = ui.$data.data('criteria-business-status');
        if (!criteriaIndustrySector) {
            ui.$filterIndustrySector.val(null).trigger('change');
        }
        if (!criteriaStatus) {
            ui.$filterActive.val(null).trigger('change');
        }
    }

    /**
     * Shows the QR Details via a modal.
     */
    function openQrDetailsDialog() {
        const $this = $(this);
        const imgBase64 = $this.data('base64');
        const link = $this.data('link');
        const businessName = $this.data('business-name');
        const $modal = $(ui.$modalShowQrCode);
        $modal.find('.qr-img').attr('src', imgBase64);
        $modal.find('.qr-link').text(link);
        $modal.find('.qr-business-name').text(businessName);
        $modal.modal('show');
    }

    /**
     * Function used to initialize the module.
     */
    function init() {
        listenUIEvents();
        state.modules.YJMDatatable.initDataTable(ui.$table);
        initSelect2();
    }

    this.init = init;
}
'use strict';

/**
 * Manages business
 */
function BusinessManager(YJMDatatable) {

    if (!YJMDatatable) {
        throw 'YJMDatatable is not passed as argument';
    }

    /**
     * @type {Object} Holds the module state.
     */
    const state = {
        modules: {
            YJMDatatable: YJMDatatable,
            Notification: new Notification(),
            ModalManager: new ModalManagement(),
        }, clicked: {
            btnRemoveBusiness: null,
            btnEditBusiness: null,
        }
    }
    /**
     * UI Elements.
     */
    const ui = {
        // Table selector
        $table: '.table[data-is-datatable="true"]',
        $tableRow: 'tr', // !Table selector

        // Buttons
        $btnOpenNewBusinessForm: $('#btnOpenNewBusinessForm'),
        $btnSubmitBusinessForm: $('#btnSubmitBusinessForm'),
        $btnRemoveBusinessListItem: '[data-id="btn-remove-business"]', // ! Buttons
        $btnEditBusinessListItem: '[data-id="btn-edit-business"]', // ! Buttons

        // Form containers and dialogs.
        $formNewBusiness: $('form[data-id="formBusiness"]'),
        $modalBusinessForm: '#modalBusinessForm',
        $modalRemoveBusinessConfirmation: '#modalDeleteListItem', // ! Form containers

        // Form Fields
        $fieldNewBusinessName: $('#fieldNewBusinessName'),
        $fieldNewBusinessCategory: $('#fieldNewBusinessCategory'),
        $fieldNewBusinessAddress: $('#fieldNewBusinessAddress'),
        $fieldNewBusinessCity: $('#fieldNewBusinessCity'),
        $fieldNewBusinessState: $('#fieldNewBusinessState'),
        $fieldNewBusinessZipCode: $('#fieldNewBusinessZipCode'),
        $fieldNewBusinessStatus: $('#fieldNewBusinessStatus'), // ! Form Fields

    };

    /**
     * Subscribes UI Events.
     */
    function listenUIEvents() {
        ui.$btnOpenNewBusinessForm.on('click', clearForm);
        ui.$btnOpenNewBusinessForm.on('click', initModalNewBusiness);
        $(ui.$modalBusinessForm).on('hidden.bs.modal', clearForm);
        $(ui.$btnRemoveBusinessListItem).on('click', setBusinessIdToRemove);
        $(ui.$btnEditBusinessListItem).on('click', openEditBusinessFormModal);
    }

    /**
     * Clear the form fields.
     */
    function clearForm() {
        if (!ui.$formNewBusiness.length) {
            return;
        }
        ui.$formNewBusiness[0].reset();
    }

    /**
     * Initializes the remove conformation modal.
     */
    function initModalRemove() {
        const removeBusinessSettings = {
            id: ui.$modalRemoveBusinessConfirmation,
            triggerSelector: ui.$btnRemoveBusinessListItem,
            title: 'Remove business',
            successCallback: removeBusiness,
        };
        state.modules.ModalManager.init(removeBusinessSettings);
    }

    /**
     * Initializes the new business modal.
     */
    function initModalNewBusiness() {
        const settings = {
            id: ui.$modalBusinessForm,
            title: 'New business',
            successCallback: handleSubmit,
        };
        state.modules.ModalManager.init(settings);
    }

    /**
     * Handle the form submission. Saves/Updates a business.
     */
    function handleSubmit() {
        const business = {
            name: ui.$fieldNewBusinessName.val(),
            category: ui.$fieldNewBusinessCategory.val(),
            address: ui.$fieldNewBusinessAddress.val(),
            city: ui.$fieldNewBusinessCity.val(),
            state: ui.$fieldNewBusinessState.val(),
            zipCode: ui.$fieldNewBusinessZipCode.val(),
            status: ui.$fieldNewBusinessStatus.val(),
        }

        // todo: validate and show errors is any

        // todo: submit the form to backend

        const location = [business.address, business.city, business.state, business.zipCode];
        try {
            state.modules.YJMDatatable.addRow([null, business.name, location.join(', '), business.status, null, null]);
            state.modules.Notification.success(`Business "${business.name}" successfully added .`);
            $(ui.$modalBusinessForm).modal('hide');
        }
        catch (e) {
            state.modules.Notification.error('Unable to add the business');
        }
    }

    /**
     * Once the user clicks on remove button respective to a business, this function saves the clicked button id
     * on the state.
     */
    function setBusinessIdToRemove() {
        state.clicked.btnRemoveBusiness = `[data-id="${$(this).closest('tr').data('id')}"]`;
        initModalRemove();
        $(ui.$modalRemoveBusinessConfirmation).modal('show');
    }

    /**
     * Removes a business row.
     */
    function removeBusiness() {
        try {
            // todo: call to the endpoint to remove the business
            state.modules.YJMDatatable.removeRow(state.clicked.btnRemoveBusiness);
            const message = `Business successfully removed`;
            state.modules.Notification.success(message);
            $(ui.$modalRemoveBusinessConfirmation).modal('hide');
        }
        catch (e) {
            console.debug(e)
            state.modules.Notification.error('Unable to remove the business.');
        }
    }

    /**
     * Open the business form details.
     */
    function openEditBusinessFormModal() {
        try {
            // todo: call to the endpoint to open the business details

            ui.$fieldNewBusinessName.val('name');
            ui.$fieldNewBusinessCategory.val('category');
            ui.$fieldNewBusinessAddress.val('address');
            ui.$fieldNewBusinessCity.val('city');
            ui.$fieldNewBusinessState.val('state');
            ui.$fieldNewBusinessZipCode.val('zip code');
            ui.$fieldNewBusinessStatus.val(true);
            state.clicked.btnEditBusiness = `[data-id="${$(this).closest('tr').data('id')}"]`;
            const settings = {
                id: ui.$modalBusinessForm,
                title: 'Edit business "Name"',
                successCallback: updateBusiness,
            };
            state.modules.ModalManager.init(settings);
            $(ui.$modalBusinessForm).modal('show');
        }
        catch (e) {
            state.modules.Notification.error('Unable to open the business details.');
        }
    }

    /**
     * Updates the business via endpoin call and updates the respective datatable row.
     */
    function updateBusiness() {
        const business = {
            name: ui.$fieldNewBusinessName.val(),
            category: ui.$fieldNewBusinessCategory.val(),
            address: ui.$fieldNewBusinessAddress.val(),
            city: ui.$fieldNewBusinessCity.val(),
            state: ui.$fieldNewBusinessState.val(),
            zipCode: ui.$fieldNewBusinessZipCode.val(),
            status: ui.$fieldNewBusinessStatus.val(),
        }

        // todo: validate and show errors is any

        // todo: submit the form to backend

        const location = [business.address, business.city, business.state, business.zipCode];

        try {
            const dataTableRowData = state.modules.YJMDatatable.getRowData(state.clicked.btnEditBusiness);
            dataTableRowData[1] = business.name;
            state.modules.YJMDatatable.updateRow(state.clicked.btnEditBusiness, dataTableRowData);
            state.modules.Notification.success(`Business "${business.name}" successfully updated.`);
            $(ui.$modalBusinessForm).modal('hide');
        }
        catch (e) {
            console.debug(e)
            state.modules.Notification.error('Unable to add the business');
        }
    }


    /**
     * Function used to initialize the module.
     */
    function init() {
        listenUIEvents();
        state.modules.YJMDatatable.initDataTable(ui.$table);
    }

    this.init = init;
}
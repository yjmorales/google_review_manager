'use strict';

/**
 * Manages the bootstrap components.
 */
function BsComponents() {

    // UI Elements.
    const ui = {
        $tooltip: '[data-toggle="tooltip"]',
        $switch: 'input[data-switch="true"]',
    };

    /**
     * Front controller of the class.
     */
    function init() {
        $(document).ready(initTooltip);
        $(document).ready(initSwitch);
    }

    /**
     * Once the page is loaded it initializes tooltip
     */
    function initTooltip() {
        $(ui.$tooltip).tooltip();
    }

    /**
     * Once the page is loaded it initializes tooltip
     */
    function initSwitch() {
        $(ui.$switch).bootstrapSwitch()
    }

    init();
}
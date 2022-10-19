'use strict';

/**
 * Manages the bootstrap components.
 */
function BsComponents() {

    // UI Elements.
    const ui = {
        $tooltip: '[data-toggle="tooltip"]',
    };

    /**
     * Front controller of the class.
     */
    function init() {
        $(document).ready(initTooltip);
    }

    /**
     * Once the page is loaded it initializes tooltip
     */
    function initTooltip() {
        $(ui.$tooltip).tooltip();
    }

    init();
}
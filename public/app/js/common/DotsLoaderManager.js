'use strict';

/**
 * Manages the dots loaders.
 */
function DotsLoaderManager() {
    /**
     * GUI References.
     * @type {Object}
     */
    const ui = {
        $loaderSm: '[data-id="loading-dot"]',
    };

    /**
     * Starts the dot loader.
     * @return {void}
     */
    function start() {
        $(ui.$loaderSm).removeClass('d-none');
    }

    /**
     * Stops the dot loader.
     * @return {void}
     */
    function stop() {
        $(ui.$loaderSm).addClass('d-none');
    }

    this.start = start;
    this.stop = stop;
}
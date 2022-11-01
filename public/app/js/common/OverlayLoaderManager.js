'use strict';

/**
 * Manages the dots loaders.
 */
function OverlayLoaderManager() {

    const ui = {
        $loaderOverlay: '.overlay',
    };

    /**
     * Starts the dot loader.
     */
    function start() {
        $(ui.$loaderOverlay).removeClass('d-none');
    }

    /**
     * Stops the dot loader.
     */
    function stop() {
        $(ui.$loaderOverlay).addClass('d-none');
    }

    this.start = start;
    this.stop = stop;
}
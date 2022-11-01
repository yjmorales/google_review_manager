'use strict';

/**
 * Manages the loaders.
 */
function LoaderManager() {

    const ui = {
        $loaderSm: '[data-id="loading-dot"]',
        $loaderOverlay: '.overlay',
    };

    /**
     * Starts the dot loader.
     */
    function startDotLoader() {
        $(ui.$loaderSm).removeClass('d-none');
    }

    /**
     * Stops the dot loader.
     */
    function stopDotLoader() {
        $(ui.$loaderSm).addClass('d-none');
    }

    /**
     * Starts the dot loader.
     */
    function startOverlay() {
        $(ui.$loaderOverlay).removeClass('d-none');
    }

    /**
     * Stops the dot loader.
     */
    function stopOverlay() {
        $(ui.$loaderOverlay).addClass('d-none');
    }

    this.startDotLoader = startDotLoader;
    this.stopDotLoader = stopDotLoader;
    this.startOverlay = startOverlay;
    this.stopOverlay = stopOverlay;
}
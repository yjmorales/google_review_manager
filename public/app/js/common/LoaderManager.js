'use strict';

/**
 * Manages the loaders.
 */
function LoaderManager() {
    /**
     * Holds the loaders modules
     * @type {Object}
     */
    const modules = {
        DotsLoaderManager: new DotsLoaderManager(),
        OverlayLoaderManager: new OverlayLoaderManager(),
    }

    /**
     * Starts the dot loader.
     * @return {void}
     */
    function startDotLoader() {
        modules.DotsLoaderManager.start();
    }

    /**
     * Stops the dot loader.
     * @return {void}
     */
    function stopDotLoader() {
        modules.DotsLoaderManager.stop();
    }

    /**
     * Starts the dot loader.
     * @return {void}
     */
    function startOverlay() {
        modules.OverlayLoaderManager.start();
    }

    /**
     * Stops the dot loader.
     * @return {void}
     */
    function stopOverlay() {
        modules.OverlayLoaderManager.stop();
    }

    this.startDotLoader = startDotLoader;
    this.stopDotLoader = stopDotLoader;
    this.startOverlay = startOverlay;
    this.stopOverlay = stopOverlay;
}
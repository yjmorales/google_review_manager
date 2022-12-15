'use strict';

/**
 * Responsible to manage the spinner loading within a input field.
 */
function InputSpinner() {
    /**
     * GUI references.
     * @type {{$inputContainer: string, $iconContainer: string}}
     */
    const ui = {
        $inputContainer: '.inputcontainer',
        $iconContainer: '.icon-container',
    };

    /**
     * Displays the spinner.
     *
     * @param {jquery} $input Field element.
     */
    function start($input) {
        $input.prop('readonly', true);
        $input.closest(ui.$inputContainer).find(ui.$iconContainer).removeClass('d-none');
    }

    /**
     * Hides the spinner.
     *
     * @param {jquery} $input Field element.
     */
    function stop($input) {
        $input.prop('readonly', false);
        $input.closest(ui.$inputContainer).find(ui.$iconContainer).addClass('d-none');
    }

    this.start = start;
    this.stop = stop;
}
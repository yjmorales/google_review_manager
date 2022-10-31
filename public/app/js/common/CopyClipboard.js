'use strict';

/**
 * Manages the clipboard
 */
function CopyClipboard() {

    // UI Elements.
    const ui = {
        $btnCopyToClipBoard: '.btnCopyToClipBoard',
        $text: '.text-copy-clipboard',
    };

    /**
     * Front controller of the class.
     */
    function init() {
        $(document).on('click', ui.$btnCopyToClipBoard, copyToClipBoard);
    }

    /**
     * Copies the review link to the clipboard.
     */
    function copyToClipBoard() {
        const $btn = $(this),
            reviewLink = $btn.data('review-link');

        $btn.find(ui.$text).text('Copied!');
        navigator.clipboard.writeText(reviewLink);
    }

    this.init = init;
}
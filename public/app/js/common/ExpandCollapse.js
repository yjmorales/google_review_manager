'use strict';

/**
 * Manages the bootstrap components.
 */
function ExpandCollapse() {

    // UI Elements.
    const ui = {
        $trigger: '.collapse-link',
    };

    /**
     * Front controller of the class.
     */
    function listenUIEvents() {
        $(document).on('click', ui.$trigger, toggleCollapseIcon);
    }

    /**
     * Once the use collapse a component this change the icon from expandable to collapse and vice versa.
     */
    function toggleCollapseIcon() {
        const $this = $(this);
        const $icon = $this.find('i');
        if (!$icon) {
            return;
        }
        if ($icon.hasClass('fa-angle-down')) {
            $icon.removeClass('fa-angle-down').addClass('fa-angle-right');
        } else if ($icon.hasClass('fa-angle-right')) {
            $icon.removeClass('fa-angle-right').addClass('fa-angle-down');
        }
    }

    /**
     * Initializes the module.
     */
    function init() {
        listenUIEvents();
    }

    this.init = init;
}
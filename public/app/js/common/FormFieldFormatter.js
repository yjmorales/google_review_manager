'use strict';

/**
 * This component represents a formatter for form fields.
 * The format is built using the Cleave.js - 3rd part library.
 * How to use this class:
 * 1) This file should be added within the DOM
 * 2) Then instantiate the class and call the respective method: (new FormFieldFormatter()).function_X();
 *
 * @link: https://github.com/nosir/cleave.js/blob/master/doc/options.md *
 *
 * @constructor
 */
function FormFieldFormatter() {

    /**
     * Holds the used GUI selectors.
     * @type {Object}
     */
    const ui = {
        $cardNumber: '.billing-cc',
        $expDate: '.billing-cc-exp',
        $securityCode: '.billing-cc-security-code',
        $zipCode: '.zip-code'
    };

    /**
     * This function formats the credit card fields.
     */
    function formatCreditCardFields() {
        // Formatting credit card number fields.
        $(ui.$cardNumber).toArray().forEach(function (field) {
            new Cleave(field, {
                creditCard: true,
            });
        });
        // Formatting expiration date fields.
        $(ui.$expDate).toArray().forEach(function (field) {
            new Cleave(field, {
                date: true,
                datePattern: ['m', 'y']
            });
        });
        // Formatting security code fields.
        $(ui.$securityCode).toArray().forEach(function (field) {
            new Cleave(field, {
                numeral: true,
                numeralThousandsGroupStyle: 'wan',
            });
        });
    }

    /**
     * This function formats the zipcode fields.
     */
    function formatZipCodeFields() {
        $(ui.$zipCode).toArray().forEach(function (field) {
            new Cleave(field, {
                numeral: true,
                numeralThousandsGroupStyle: "none",
            });
        });
    }

    this.formatCreditCardFields = formatCreditCardFields;
    this.formatZipCodeFields = formatZipCodeFields;
}
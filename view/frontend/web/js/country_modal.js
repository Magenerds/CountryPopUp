/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

/**
 * @category   Magenerds
 * @package    Magenerds_CountryPopUp
 * @subpackage view
 * @copyright  Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Philipp Steinkopff <p.steinkopff@techdivision.com>
 */

define([
    'jquery',
    'Magento_Ui/js/modal/modal'
], function ($, modal) {
    'use strict';
    $.widget('countryModal.js', {
        _create: function() {
            this.options.user_countrys = this.prepareParam(this.options.user_countrys);
            this.options.hinted_countrys = this.prepareParam(this.options.hinted_countrys);
            this.options.show_modal = $.cookieStorage.get('country_popup_shown');
            this.initModal();
        },

        /**
         * clear param from unnecessary comas and whitespaces and converted to array
         *
         * @param param string
         * @return formatted array
         */
        prepareParam: function (param) {
            param = param.replace(/\s/g,'').split(',');
            return param.filter(function(el){
                return el !== "";
            });
        },

        /**
         * check if the configured country and language combination is inside of the array
         *
         * @returns {boolean}
         */
        countryCheck: function () {
            var matchedCountry = false,
                that = this;
            this.options.user_countrys.forEach(function (elem) {
                if ($.inArray(elem, that.options.hinted_countrys)) {
                    matchedCountry = true;
                }
            });

            return matchedCountry;
        },

        /**
         * init modal if modal was opened successfully
         */
        initModal: function () {
            var options = {
                    type: 'popup',
                    responsive: true,
                    innerScroll: true,
                    wrapperClass: 'hint-country-modal'
                },
                popup = modal(options, this.element);

            if (this.options.show_modal !== true && this.countryCheck()) {
                this.element.modal('openModal', true);
                $.cookieStorage.set('country_popup_shown', true);
            }
        },

    });
    return $.countryModal.js;
});
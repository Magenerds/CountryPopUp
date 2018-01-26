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
            this.options.show_modal = $.cookieStorage.get('country_popup_shown');
            this.initModal();
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

            if (this.options.show_modal !== true && this.options.hinted_country) {
                this.element.modal('openModal', true);
                $.cookieStorage.setConf({
                    path: '/',
                    expires: parseInt(this.options.cookie_lifetime)
                });
                $.cookieStorage.set('country_popup_shown', true);
            }
        },

    });
    return $.countryModal.js;
});
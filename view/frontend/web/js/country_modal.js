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

        /**
         * init module
         * @private
         */
        _create: function() {
            this.options.show_modal = $.cookieStorage.get('country_popup_shown');
            this.getModalData();
        },

        /**
         * load modal data continue only in case of success
         */
        getModalData: function () {
            if (!this.options.show_modal) {
                var that = this;
                $.ajax({
                    url: this.options.contentPath,
                    type: 'get',
                    async: true,
                    success: function(response) {
                        that.options.default_store = (response.defaultStore) ? response.defaultStore : false;
                        that.prepareModal(response);
                    }
                });
            }
        },

        /**
         * fill loaded data into modal
         *
         * @param values object
         */
        prepareModal: function (values) {
            var content = $(values.modalContent);
            this.element.find('img').attr('src', values.modalImage);
            this.element.find('.content').append(content);
            this.initModal();
        },

        /**
         * init modal
         */
        initModal: function () {
            var options = {
                    type: 'popup',
                    responsive: true,
                    innerScroll: true,
                    wrapperClass: 'hint-country-modal'
                },
                popup = modal(options, this.element);
            console.log(this.options.default_store);
            if (!this.options.show_modal && !this.options.default_store) {
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
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
 * @copyright  Copyright (c) 2019 TechDivision GmbH (https://www.techdivision.com)
 * @link       https://www.techdivision.com/
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
                        that.options.delay = (response.useDelay) ? response.delayDuration : 0;
                        that.options.cookie_lifetime = response.cookieLifetime;
                        that.options.show_modal_overlay = response.showModalOverlay;
                        that.options.modal_responsive = response.responsiveModal;
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
            if (values.modalImage) {
                this.element.find('img.modal-image').attr('src', values.modalImage);
            } else {
                this.element.find('.img-wrapper').remove();
            }
            this.element.find('.content').append(values.modalContent);
            this.initModal();
        },

        /**
         * init modal
         */
        initModal: function () {
            var that = this,
                options = {
                    type: 'popup',
                    responsive: this.options.modal_responsive,
                    innerScroll: true,
                    wrapperClass: 'hint-country-modal'
                };

            if (!this.options.show_modal && !this.options.default_store) {
                // only init modal dialog if necessary
                modal(options, this.element);
                this.element.removeClass('hide-country-popup');
                setTimeout(function () {
                    that.element.modal('openModal', true);
                    if (!that.options.show_modal_overlay) {
                        $('.hint-country-modal').addClass('no-overlay');
                    }
                    $.cookieStorage.setConf({
                        path: '/',
                        expires: parseInt(that.options.cookie_lifetime)
                    });
                    $.cookieStorage.set('country_popup_shown', true);

                }, this.options.delay);
            }
        }
    });
    return $.countryModal.js;
});

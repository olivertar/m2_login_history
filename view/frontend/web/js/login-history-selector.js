define([
    'jquery',
    'Magento_Ui/js/modal/confirm',
    'Magento_Ui/js/modal/alert',
    'jquery-ui-modules/widget',
    'mage/translate'
], function ($, confirmation, alert) {
    'use strict';

    $.widget('mage.loginHistorySelector', {
        options: {
            loginCheckbox: '.login-checkbox', // Class name for a item's input checkbox.
            loginProductsCheckFlag: false, // Item checkboxes are initially unchecked.
            loginProductsField: '#login-history-field', // Hidden input field that stores items.
            selectAllLoginMessage: $.mage.__('select all'),
            unselectAllLoginMessage: $.mage.__('unselect all'),
            deleteAllLoginLink: '[data-role="login-delete-all"]',
            selectAllLoginLink: '[data-role="login-select-all"]',
            deleteSelected: '[data-role="login-delete-selected"]',
            animationLayer: '.wrapper-animation',
            loginHistoryForm: '#loginhistory_form'

        },

        _create: function () {
            $(this.options.selectAllLoginLink).on('click', $.proxy(this._selectAllLogin, this));
            $(this.options.loginCheckbox).on('click', $.proxy(this._addLoginToRemoveList, this));
            $(this.options.deleteAllLoginLink).on('click', $.proxy(this._deleteAllLogin, this));
            $(this.options.deleteSelected).on('click', $.proxy(this._submitSelection, this));
        },

        _selectAllLogin: function (e) {
            var innerHTML = this.options.loginProductsCheckFlag ?
                this.options.selectAllLoginMessage : this.options.unselectAllLoginMessage;

            $(e.target).html(innerHTML);
            $(this.options.loginCheckbox).attr(
                'checked',
                this.options.loginProductsCheckFlag = !this.options.loginProductsCheckFlag
            );
            this._addLoginToRemoveList();

            return false;
        },

        _addLoginToRemoveList: function () {
            $(this.options.loginProductsField).val(
                $(this.options.loginCheckbox + ':checked').map(function () {
                    return this.value;
                }).get().join(',')
            );
        },

        _submitSelection: function () {
            if($(this.options.loginProductsField).val() != ''){
                $(this.options.animationLayer).css("display", "flex");
                $(this.options.loginHistoryForm).submit();
            }else{
                alert({
                    title: $.mage.__('History delete'),
                    content: $.mage.__('You have not selected any records. Use the checkbox'),
                    actions: {
                        always: function(){}
                    }
                });
            }
        },

        _deleteAllLogin: function () {
            confirmation({
                title: 'Delete login history',
                content: 'Do you want to delete all records?',
                actions: {

                    confirm: function (event) {
                        $('.wrapper-animation').css("display", "flex");
                        $('#login-history-field').val('all');
                        $('#loginhistory_form').submit();
                    },

                    cancel: function () {
                        return false;
                    }
                }
            });
        }

    });

    return $.mage.loginHistorySelector;
});

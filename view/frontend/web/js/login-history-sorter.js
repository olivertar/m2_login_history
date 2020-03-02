define([
    "jquery",
    'jquery-ui-modules/widget'
], function ($) {
    'use strict';
    $.widget('mage.loginHistorySorter', {
        options: {
            directionControl: '[data-role="logins-direction-switcher"]',
            orderControl: '[data-role="logins-sorter"]',
            direction: 'direct',
            order: 'sortby',
            directionDefault: 'desc',
            orderDefault: 'login_time',
            url: ''
        },

        _create: function () {
            this._bind($(this.options.directionControl), this.options.direction, this.options.directionDefault);
            this._bind($(this.options.orderControl), this.options.order, this.options.orderDefault);
        },

        _bind: function (element, paramName, defaultValue) {
            if (element.is('select')) {
                element.on('change', {
                    paramName: paramName,
                    'default': defaultValue
                }, $.proxy(this._processSelect, this));
            } else {
                element.on('click', {
                    paramName: paramName,
                    'default': defaultValue
                }, $.proxy(this._processLink, this));
            }
        },

        _processLink: function (event) {
            event.preventDefault();
            this.changeUrl(
                event.data.paramName,
                $(event.currentTarget).data('value'),
                event.data.default
            );
        },

        _processSelect: function (event) {
            this.changeUrl(
                event.data.paramName,
                event.currentTarget.options[event.currentTarget.selectedIndex].value,
                event.data.default
            );
        },

        changeUrl: function (paramName, paramValue, defaultValue) {
            var decode = window.decodeURIComponent,
                urlPaths = this.options.url.split('?'),
                baseUrl = urlPaths[0],
                urlParams = urlPaths[1] ? urlPaths[1].split('&') : [],
                paramData = {},
                parameters, i, form, params, key, input, formKey;

            for (i = 0; i < urlParams.length; i++) {
                parameters = urlParams[i].split('=');
                paramData[decode(parameters[0])] = parameters[1] !== undefined ?
                    decode(parameters[1].replace(/\+/g, '%20')) :
                    '';
            }
            paramData[paramName] = paramValue;

            if (paramValue == defaultValue) { //eslint-disable-line eqeqeq
                delete paramData[paramName];
            }
            paramData = $.param(paramData);
            location.href = baseUrl + (paramData.length ? '?' + paramData : '');
        }
    });
    return $.mage.loginHistorySorter;
});

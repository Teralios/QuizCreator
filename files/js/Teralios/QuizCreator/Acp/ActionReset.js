define(['Ajax', 'Ui/Confirmation', 'Language'], function(Ajax, UiConfirmation, Language) {
    "use strict";

    if (!COMPILER_TARGET_DEFAULT) {
        return {
            init: function (dboActionClass, selector, isButton) { },
            confirm: function () { },
            click: function (event) { },
            addEvent: function (element) { },
            _buildClick: function () { },
            _ajaxSetup: function () { },
        };
    }

    var CLICK_EVENT = window.WCF_CLICK_EVENT;

    return {
        /**
         * Init buttons for delete all matches of a quiz.
         * @param {string} dboActionClass
         * @param {string} selector
         * @param {boolean} isButton
         */
        init: function (dboActionClass, selector, isButton) {
            this._dboActionClass = dboActionClass;
            this._selector = selector;
            this._buttonSelector = '.jsResetMatchesButton';
            this._isButton = (isButton !== undefined)

            this._buildClick();
        },

        /**
         * Callback for Ui/Confirmation
         */
        confirm: function () {
            Ajax.api(this, {objectIDs: [this._targetID]});
        },

        /**
         * Callback click
         * @param {Event} event
         */
        click: function (event) {
            var element = event.currentTarget;
            this._targetID = elData(element, 'object-id');
            this._confirmMessage = elData(element, 'confirm-message-html');
            if (this._confirmMessage === undefined || this._confirmMessage === '') {
                this._confirmMessage = Language.get('wcf.acp.quizCreator.matches.reset.confirm')
            }

            UiConfirmation.show({
                confirm: this.confirm.bind(this),
                message: this._confirmMessage,
                messageIsHtml: true,
            })
        },

        /**
         * Add click action to buttons.
         * @param {Element} element
         */
        addEvent: function (element) {
            if (this._isButton) {
                element.addEventListener(CLICK_EVENT, this.click.bind(this))
            } else {
                var button = elBySel(this._buttonSelector, element);
                if (button !== null) {
                    button.addEventListener(CLICK_EVENT, this.click.bind(this));
                }
            }
        },

        /**
         * Build click events.
         * @private
         */
        _buildClick: function () {
            var elements = elBySelAll(this._selector);
            forEach(elements, this.addEvent.bind(this));
        },

        /**
         * Basic javascript
         * @returns {{data: {className: string, actionName: string}}}
         * @private
         */
        _ajaxSetup: function () {
            return {
                data: {
                    actionName: "resetMatches",
                    className: this._dboActionClass,
                }
            }
        }
    }
});
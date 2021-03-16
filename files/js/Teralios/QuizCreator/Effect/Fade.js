define([], function () {
    "use strict";

    return {
        /**
         * Fades a html element in.
         * @param {Element} element DOM element
         * @param {number} time In microseconds.
         * @param {function|null} callBack Callback when effect is finished.
         */
        fadeIn: function (element, time, callBack) {
            if (element instanceof Element) {
                // ticks
                let tick = time / 1000;
                tick = Math.round(tick);

                // set opacity to 0.00 and show element.
                element.style.opacity = 0.00;
                if (elIsHidden(element)) {
                    elShow(element)
                }

                // callback
                this._callBack = callBack;
                this._element = element;
                this._effect = 'in';
                this._opacity = 0.00;

                this._intervalID = setInterval(this._ticker.bind(this), tick);
           }
        },

        /**
         * Fades a html element out.
         * @param {Element} element DOM element
         * @param {number} time In microseconds.
         * @param {function|null} callBack Callback when effect is finished.
         */
        fadeOut: function (element, time, callBack) {
            // ticks
            let tick = time / 1000;
            tick = Math.round(tick);

            // set opacity to 1.00 and show element.
            element.style.opacity = 1.00;

            // callback
            this._callBack = callBack;
            this._element = element;
            this._effect = 'out';
            this._opacity = 1.00;

            this._intervalID = setInterval(this._ticker.bind(this), tick);
        },

        /**
         * Represents a tick.
         * @private
         */
        _ticker: function () {
            if (this._effect === 'in') {
                if (this._opacity >= 1) {
                    this._stop();
                } else {
                    this._opacity = this._opacity + 0.01;
                    this._element.style.opacity = this._opacity;
                }
            } else {
                if (this._opacity <= 0) {
                    this._stop();
                } else {
                    this._opacity = this._opacity - 0.01;
                    this._element.style.opacity = this._opacity;
                }
            }
        },

        /**
         * Stop functions.
         * @private
         */
        _stop: function () {
            clearInterval(this._intervalID);

            // variables
            let element = this._element, callBack = this._callBack, effect = this._effect;

            // reset variables
            this._element = this._callBack = this._intervalID = this._effect = null;

            if (effect === 'in') {
                element.style.visibility = 'hidden';
            }

            if (callBack && typeof callBack === 'function') {
                return callBack(element);
            }
        }
    }
})

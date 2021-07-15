/**
 * Provides helper functions to work with DOM nodes.
 *
 * @author      Karsten (Teralios) Achterrath
 * @copyright   2021 teralios.de
 * @license     GNU General Public License <https://opensource.org/licenses/GPL-3.0>
 * @module      Teralios/QuizCreator/Game/Container/Main
 */
define(["require", "exports", "WoltLabSuite/Core/Dom/Util"], function (require, exports, Util_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    exports.Main = void 0;
    class Main {
        constructor(container = null) {
            this.currentView = '';
            this.container = container !== null && container !== void 0 ? container : this.initContainer();
        }
        initContainer() {
            const container = document.createElement('div');
            container.classList.add('main');
            return container;
        }
        addView(name, view) {
            Util_1.hide(view);
            this.views[name] = view;
        }
        showView(name) {
            if (this.views[name] !== undefined) {
                if (this.currentView !== '') {
                    Util_1.hide(this.views[this.currentView]);
                }
                Util_1.show(this.views[name]);
                this.currentView = name;
            }
        }
        show() {
            this.container.classList.add('show');
        }
        hide() {
            this.container.classList.remove('show');
        }
    }
    exports.Main = Main;
});

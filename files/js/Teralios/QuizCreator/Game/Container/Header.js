define(["require", "exports"], function (require, exports) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    exports.Header = void 0;
    /**
     * Provides helper functions to work with DOM nodes.
     *
     * @author      Karsten (Teralios) Achterrath
     * @copyright   2021 teralios.de
     * @license     GNU General Public License <https://opensource.org/licenses/GPL-3.0>
     * @module      Teralios/QuizCreator/Game/Container/Header
     */
    class Header {
        constructor(container, ...views) {
            this.container = container !== null && container !== void 0 ? container : this.initContainer();
            this.container.append(...views);
        }
        getContainer() {
            return this.container;
        }
        show() {
            this.container.classList.add('show');
        }
        hide() {
            this.container.classList.remove('show');
        }
        initContainer() {
            const container = document.createElement('div');
            container.classList.add('header');
            return container;
        }
    }
    exports.Header = Header;
});

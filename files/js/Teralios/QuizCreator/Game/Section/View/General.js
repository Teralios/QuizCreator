define(["require", "exports", "WoltLabSuite/Core/Language"], function (require, exports, Language_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    exports.StartView = void 0;
    // start view
    const startContainer = document.createElement('div');
    const startButton = document.createElement('button');
    function initStartView() {
        startButton.textContent = Language_1.get('wcf.quizCreator.game.button.start');
        startButton.disabled = true;
        startContainer.appendChild(startButton);
    }
    exports.StartView = {
        getName() {
            return 'start';
        },
        getView() {
            initStartView();
            return startContainer;
        },
        needCallbacks() {
            return ['startCallback'];
        },
        registerCallback(key, callback) {
            if (key == 'startCallback') {
                startButton.addEventListener('click', callback);
            }
        },
        callAfterEffect() {
            return () => {
                startButton.disabled = false;
            };
        }
    };
});

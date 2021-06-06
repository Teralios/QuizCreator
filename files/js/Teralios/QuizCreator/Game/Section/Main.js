define(["require", "exports"], function (require, exports) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    exports.setEffectBasics = void 0;
    let effectDuration;
    let effectClassIn;
    let effectClassOut;
    function setEffectBasics(duration, inClass, outClass) {
        effectDuration = duration * 1000;
        effectClassIn = inClass;
        effectClassOut = outClass;
    }
    exports.setEffectBasics = setEffectBasics;
});

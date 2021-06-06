let effectDuration: number;
let effectClassIn: string;
let effectClassOut: string;

export function setEffectBasics(duration: number, inClass: string, outClass: string) {
    effectDuration = duration * 1000;
    effectClassIn = inClass;
    effectClassOut = outClass;
}


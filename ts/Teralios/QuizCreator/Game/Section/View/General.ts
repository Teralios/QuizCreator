import {get} from 'WoltLabSuite/Core/Language';

export type ViewCallback = () => void;

// interfaces
export interface View {
    getName(): string;
    getView(): HTMLElement;
    callAfterEffect(): ViewCallback | null;
}

export interface InteractiveView extends View {
    needCallbacks(): string[];
    registerCallback(key: string, callback: ViewCallback)
}


// start view
const startContainer = document.createElement('div');
const startButton = document.createElement('button');

function initStartView(): void
{
    startButton.textContent = get('wcf.quizCreator.game.button.start');
    startButton.disabled = true;
    startContainer.appendChild(startButton);
}

export const StartView: InteractiveView = {
    getName(): string
    {
        return 'start';
    },

    getView(): HTMLElement {
        initStartView();
        return startContainer;
    },

    needCallbacks(): string[]
    {
        return ['startCallback'];
    },

    registerCallback(key: string, callback: ViewCallback)
    {
        if (key == 'startCallback') {
            startButton.addEventListener('click', callback);
        }
    },

    callAfterEffect(): ViewCallback | null
    {
        return () => {
            startButton.disabled = false;
        }
    }
}
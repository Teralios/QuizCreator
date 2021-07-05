import DomUtil from 'WoltLabSuite/Core/Dom/Util';
import {get as phrase} from 'WoltLabSuite/Core/Language';

const questionNo = document.createElement('span');
const startButton = document.createElement('button');

function buildIntermissionView(): HTMLElement
{
    const container = document.createElement('div');
    container.classList.add('intermissionView');

    const p = document.createElement('p');
    p.append(phrase('wcf.quizCreator.game.header.question.prefix'), questionNo);

    container.appendChild(p);
    DomUtil.hide(container);

    return container;
}

function buildStartView(): HTMLElement
{
    const container = document.createElement('div');
    container.classList.add('startView');
    container.appendChild(startButton);
    startButton.textContent = phrase('wcf.quizCreator.game.button.start');
    DomUtil.hide(container);

    return container;
}

export type StartCallback = () => void;

export class General{
    protected startView: HTMLElement;
    protected intermissionView: HTMLElement;

    public constructor()
    {
        this.startView = buildStartView();
        this.intermissionView = buildIntermissionView();
    }

    public updateQuestionNo(no: number): void
    {
        questionNo.textContent = String(no);
    }

    public getStartView(): HTMLElement
    {
        return this.startView;
    }

    public getIntermissionView(): HTMLElement
    {
        return this.intermissionView;
    }

    public registerStartCallback(startGame: StartCallback): void
    {
        startButton.addEventListener('click', startGame);
    }
}
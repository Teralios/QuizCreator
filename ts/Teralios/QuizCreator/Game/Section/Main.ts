import {Question} from '../../Data/Data';
import {StartCallback, General} from './View/General';
import {ShowQuestionCallback, NextQuestionCallback, CheckAnswerCallback, QuestionView} from './View/Question';
import DomUtil from "WoltLabSuite/Core/Dom/Util";
import {Header} from "./Header";

let effectDuration = 1000;

export function setEffectBasics(duration: number):void
{
    effectDuration = duration * 1000;
}

export class Main
{
    container: HTMLElement;
    showQuestionCallback: ShowQuestionCallback;
    nextQuestionCallback: NextQuestionCallback;
    checkAnswerCallback: CheckAnswerCallback;
    finishGameCallback: NextQuestionCallback;
    startGameCallback: StartCallback;
    resetClock: () => void;
    questionView: QuestionView;
    generalView: General;
    currentView: HTMLElement;
    header: Header;

    public constructor(showQuestionCallback: ShowQuestionCallback, checkAnswerCallback: CheckAnswerCallback, nextQuestionCallback: NextQuestionCallback, finishGameCallback: NextQuestionCallback, startCallback: StartCallback, header: Header, resetClock: () => void)
    {
        this.showQuestionCallback = showQuestionCallback;
        this.checkAnswerCallback = checkAnswerCallback;
        this.nextQuestionCallback = nextQuestionCallback;
        this.finishGameCallback = finishGameCallback;
        this.startGameCallback = startCallback;
        this.resetClock = resetClock;
        this.header = header;

        this._initGeneral();
    }

    public nextQuestion(question: Question, notLast?: boolean): void
    {
        // remove show / fadeout effect
        if (this.container.classList.contains('show')) {
            this.container.classList.remove('show');
        }

        // remove show from header
        this.header.getView().classList.remove('show');
        setTimeout(
            () => {
                this.intermission();
                if (!notLast) {
                    this.questionView.prepareFor(question, this.finishGameCallback);
                } else {
                    this.questionView.prepareFor(question);
                }
            },
            effectDuration
        );
    }

    public intermission(): void
    {
        DomUtil.hide(this.currentView);
        this.currentView = this.generalView.getIntermissionView();
        DomUtil.show(this.currentView);
        this.container.classList.add('show');
        this.resetClock();

        setTimeout(() => {
            this.showNext();
        }, 2000 + effectDuration);
    }

    public showNext(): void
    {
        this.container.classList.remove('show');
        setTimeout( () => {
            DomUtil.hide(this.currentView);
            this.currentView = this.questionView.getView();
            DomUtil.show(this.currentView);
            this.header.getView().classList.add('show');
            this.container.classList.add('show');
            this.finalizeNext();
        }, effectDuration)
    }

    public finalizeNext(): void
    {
        setTimeout(() => {
            this.container.classList.add('show');
            this.showQuestionCallback();
        }, effectDuration);
    }

    public showStartView(): void
    {
        this.currentView = this.generalView.getStartView();
        DomUtil.show(this.currentView);
        this.container.classList.add('show');
    }

    public getView(): HTMLElement
    {
        return this.container;
    }

    protected _initGeneral(): void
    {
        this.questionView = new QuestionView(this.checkAnswerCallback, this.nextQuestionCallback);
        this.generalView = new General();
        this.generalView.registerStartCallback(this.startGameCallback);

        // build game field.
        this.container = document.createElement('div');
        this.container.classList.add('main');
        this.container.append(
            this.generalView.getStartView(),
            this.generalView.getIntermissionView(),
            this.questionView.getView()
        );
    }
}
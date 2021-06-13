import {Question} from '../../Data/Data';
import {StartCallback, General} from './View/General';
import {ShowQuestionCallback, NextQuestionCallback, CheckAnswerCallback, QuestionView} from './View/Question';
import DomUtil from "WoltLabSuite/Core/Dom/Util";

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
    questionView: QuestionView;
    generalView: General;
    currentView: HTMLElement;

    public constructor(showQuestionCallback: ShowQuestionCallback, checkAnswerCallback: CheckAnswerCallback, nextQuestionCallback: NextQuestionCallback, finishGameCallback: NextQuestionCallback, startCallback: StartCallback)
    {
        this.showQuestionCallback = showQuestionCallback;
        this.checkAnswerCallback = checkAnswerCallback;
        this.nextQuestionCallback = nextQuestionCallback;
        this.finishGameCallback = finishGameCallback;
        this.startGameCallback = startCallback;

        this._initGeneral();
    }

    public nextQuestion(question: Question, isLast?: boolean): void
    {
        // remove show / fadeout effect
        if (this.container.classList.contains('show')) {
            this.container.classList.remove('show');
        }

        if (isLast) {
            this.questionView.prepareFor(question, this.finishGameCallback);
        } else {
            this.questionView.prepareFor(question);
        }

        setTimeout(() => { this.intermission() }, effectDuration);
    }

    public intermission(): void
    {
        DomUtil.hide(this.currentView);
        this.currentView = this.generalView.getIntermissionView();
        DomUtil.show(this.currentView);
        this.container.classList.add('show');

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
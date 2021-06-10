import {Question} from '../../Data/Data';
import {} from './View/General';
import {ShowQuestionCallback, NextQuestionCallback, CheckAnswerCallback, QuestionView} from './View/Question';
import DomUtil from "WoltLabSuite/Core/Dom/Util";

let effectDuration: number;
let effectClassIn: string;
let effectClassOut: string;

export function setEffectBasics(duration: number, inClass: string, outClass: string):void
{
    effectDuration = duration * 1000;
    effectClassIn = inClass;
    effectClassOut = outClass;
}

export class Main
{
    container: HTMLElement;
    showQuestionCallback: ShowQuestionCallback;
    nextQuestionCallback: NextQuestionCallback;
    checkAnswerCallback: CheckAnswerCallback;
    finishGameCallback: NextQuestionCallback;
    questionView: QuestionView;

    public constructor(showQuestionCallback: ShowQuestionCallback, checkAnswerCallback: CheckAnswerCallback, nextQuestionCallback: NextQuestionCallback, finishGameCallback: NextQuestionCallback)
    {
        this.showQuestionCallback = showQuestionCallback;
        this.checkAnswerCallback = checkAnswerCallback;
        this.nextQuestionCallback = nextQuestionCallback;
        this.finishGameCallback = finishGameCallback;
        this.questionView = new QuestionView(this.checkAnswerCallback, this.nextQuestionCallback);
        DomUtil.hide(this.questionView.getView());

        this._initGeneral();
    }

    public nextQuestion(question: Question, questionNumber: number, isLast?: boolean)
    {
        if (isLast) {
            this.questionView.prepareFor(question, this.finishGameCallback);
        } else {
            this.questionView.prepareFor(question)
        }
    }

    public getView(): HTMLElement
    {
        return this.container;
    }

    protected _initGeneral(): void
    {}
}
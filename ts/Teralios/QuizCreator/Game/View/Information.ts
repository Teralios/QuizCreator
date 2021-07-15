/**
 * Provides helper functions to work with DOM nodes.
 *
 * @author      Karsten (Teralios) Achterrath
 * @copyright   2021 teralios.de
 * @license     GNU General Public License <https://opensource.org/licenses/GPL-3.0>
 * @module      Teralios/QuizCreator/Game/Container/Header
 */

// imports
import {get as phrase} from 'WoltLabSuite/Core/Language';

// information to question
const currentQuestionNo = document.createElement('b');
const questionCount = document.createElement('b');
const questionIndicators: HTMLElement[] = [];
const value = document.createElement('span');
const time = document.createElement('p');
const score = document.createElement('span');
const spinner = document.createElement('span');

// templates
function buildQuestionPart(questions: number): HTMLElement {
    // update question count
    questionCount.textContent = String(questions);

    // question (Question 1 from 10)
    const firstParagraph = document.createElement('p');
    firstParagraph.append(
        phrase('wcf.quizCreator.game.header.question.prefix'),
        currentQuestionNo,
        phrase('wcf.quizCreator.game.header.question.suffix'),
        questionCount
    );

    // Question signs (??????????)
    const secondParagraph = document.createElement('p');
    for (let i = 0; i < questions; i++) {
        const questionSign = document.createElement('span');
        questionSign.classList.add('question', 'fa', 'icon16', 'fa-question-circle');
        questionIndicators.push(questionSign);
        secondParagraph.appendChild(questionSign);
    }

    const container = document.createElement('div');
    container.classList.add('questionInfo');
    container.append(firstParagraph, secondParagraph);

    return container;
}

function buildClockPart(): HTMLElement {
    const firstParagraph = document.createElement('p');
    spinner.classList.add('fa fa-icon16 fa-circle');
    spinner.classList.add('paused');
    firstParagraph.append(spinner, ' +', value);

    const container = document.createElement('div');
    container.classList.add('clock');
    container.append(firstParagraph, time);

    return container;
}

function buildScorePart(): HTMLElement {
    const languagePart = document.createElement('b');
    languagePart.textContent = phrase('wcf.quizCreator.game.header.score');

    const container = document.createElement('div');
    container.classList.add('score');
    container.append(score, ' ', languagePart);

    return container;
}

export class Information {
    protected questionPart: HTMLElement;
    protected clockPart: HTMLElement;
    protected scorePart: HTMLElement;

    public constructor(questionCount: number) {
        this.questionPart = buildQuestionPart(questionCount);
        this.clockPart = buildClockPart();
        this.scorePart = buildScorePart();
    }

    public getQuestionPart(): HTMLElement {
        return this.questionPart;
    }

    public getClockPart(): HTMLElement {
        return this.clockPart;
    }

    public getScorePart(): HTMLElement {
        return this.scorePart;
    }

    public switchAnimation(): void
    {
        if (spinner.classList.contains('paused')) {
            spinner.classList.remove('paused');
        } else {
            spinner.classList.add('paused');
        }
    }

    public updateTime(timestamp: number): void {
        const minutes = timestamp / 60;
        const seconds = timestamp % 60;

        time.textContent = ((minutes < 10) ? '0' : '') + String(minutes)
            + ((seconds % 2 == 0) ? ':' : ' ')
            + ((seconds < 10) ? '0' : '') + String(seconds);
    }

    public updateClockStatus(newClass: string): void {
        this.clockPart.classList.remove('s1', 's2', 's3');
        this.clockPart.classList.add(newClass);
    }

    public updateValue(currentValue: number): void {
        value.textContent = String(currentValue);
    }

    public updateScore(currentScore: number): void {
        score.textContent = String(currentScore);
    }

    public currentQuestion(questionNo: number): void {
        currentQuestionNo.textContent = String(questionNo);
    }

    public updateQuestionIndicator(questionNo: number, isCorrect: boolean): void {
        if (questionIndicators[questionNo - 1] !== undefined) {
            questionIndicators[questionNo - 1].classList.add(isCorrect ? 'correct' : 'incorrect');
        }
    }
}

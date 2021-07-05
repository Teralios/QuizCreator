import {Quiz, Question, Goal} from "../Data/Data";
import {Header} from './Section/Header';
import {Main} from "./Section/Main";
import DomUtil from "WoltLabSuite/Core/Dom/Util";

const status1Seconds = 5;
const status2Seconds = 15;
const status1Value = 10;
const status2Value = 5;
const status3Value = 1;

export class Game {
    protected quiz: Quiz;
    protected container: HTMLElement;
    protected header: Header;
    protected main: Main;
    protected watchID: number;
    protected currentQuestion: Question;
    protected seconds = 0;
    protected quizSeconds = 0;
    protected score = 0;
    protected value = 0;
    protected result: Map<string, number|string>[];
    protected currentQuestionIndex: number;

    public constructor(quiz: Quiz, container: HTMLElement)
    {
        this.quiz = quiz;
        this.container = container;
        this.result = [];

        this.init();
    }

    protected init(): void
    {
        // sections
        this.header = new Header(this.quiz.questionsCount);
        this.main = new Main(
            () => { this.startClock() },
            (option: string) => this.registerAnswer(option),
            () => { this.setNextQuestion() },
            () => { this.finishGame() },
            () => { this.setNextQuestion() },
            this.header,
            () => { this.resetClock() }
        );

        // create game field.
        const field = document.createElement('div');
        field.classList.add('gameField');
        this.container.appendChild(field);
        this.container = field;

        // add sections
        this.container.append(this.header.getView());
        this.container.append(this.main.getView());
        setTimeout(
            () => {
                this.main.showStartView();
                },
            1000
        ); // css needs some time to render.
    }

    public registerAnswer(option: string): boolean
    {
        // check answer - may b
        const returnValue = this.currentQuestion.checkAnswer(option)
        if (returnValue) {
            this.score += this.value;
        }
        this.quizSeconds += this.seconds;

        // stop watch and
        this.header.stopAnimation();
        if (this.watchID) {
            clearInterval(this.watchID);
        }

        // update header
        this.header.updateScore(this.score);
        this.header.updateQuestionIndicator(this.currentQuestion.no, returnValue);

        // get current data
        const result = new Map();
        result['time'] = this.seconds;
        result['option'] = option;
        this.result[this.currentQuestionIndex] = result;

        // reset counters
        this.seconds = 0;
        this.value = status1Value;

        return returnValue;
    }

    public resetClock(): void
    {
        this.value = status1Value; // i know i know.
        this.header.updateValue(this.value);
        this.header.updateTime(this.seconds);
        this.header.updateStatus('s1');
    }

    public startClock(): void
    {
        this.resetClock();
        this.header.startAnimation();
        this.watchID = setInterval(() => { this.clockTick() }, 1000);
    }

    public setNextQuestion(): void
    {
        const question = this.quiz.getQuestion();

        if (question != null) {
            this.main.nextQuestion(question, this.quiz.nextQuestion());
            this.currentQuestion = question;
        }
    }

    public finishGame(): void {
        return;
    }

    public clockTick(): void
    {
        this.seconds++;
        this.header.updateTime(this.seconds);

        if (this.seconds > status1Seconds) {
            this.value = status2Value;
            this.header.updateStatus('s2');
            this.header.updateValue(this.value);
        }
        if (this.seconds > status2Seconds) {
            this.value = status3Value;
            this.header.updateStatus('s3');
            this.header.updateValue(this.value);
        }
    }
}

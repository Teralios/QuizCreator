import {Header} from './Section/Header';
import {Quiz, Question, Goal} from "../Data/Data";

const status1Seconds = 5;
const status2Seconds = 15;
const status1Value = 10;
const status2Value = 5;
const status3Value = 1;

export class Game {
    protected quiz: Quiz;
    protected container: HTMLElement;
    protected header: Header;
    protected watchID: number;
    protected currentQuestion: Question;
    protected seconds = 0;
    protected score = 0;
    protected value = 0;

    public constructor(quiz: Quiz, container: HTMLElement)
    {
        this.quiz = quiz;
        this.container = container;

        this.init();
    }

    protected init(): void
    {
        // sections
        this.header = new Header(this.quiz.questionsCount);

        // create game field.
        const field = document.createElement('div');
        field.classList.add('gameField');
        this.container.appendChild(field);

        // add main section

        // show startView
    }

    public registerAnswer(option: string): void
    {

    }
}

import {Quiz} from './Data';
import {QuizLoader, LanguageLoader} from './Loader';
import {get as getLang} from 'WoltLabSuite/Core/Language';

class Game
{
    protected quiz: Quiz;
    protected gameField: HTMLElement;
    protected selector: string;

    public constructor(selector: string) {
        if (selector.startsWith('#')) {
            this.selector = selector;
            this.findField();
        } else {
            console.error('Selector for quiz game must be an html id selector.');
        }
    }

    public startGame(quiz: Quiz): void
    {
        this.quiz = quiz;

        // @todo start working on game
    }

    public showError(): void
    {
        const icon = this.gameField.getElementsByClassName('icon')[0] ?? null;
        const information = this.gameField.getElementsByTagName('p')[0] ?? null;

        if (icon !== null) {
            this.gameField.removeChild(icon);
        }

        if (information !== null) {
            information.textContent = getLang('wcf.quizCreator.game.status.error');
            information.classList.add('error');
        }
    }

    protected findField(): void
    {
       const gameField = document.querySelector(this.selector + ' .game');
        if (gameField instanceof HTMLElement) {
            this.gameField = gameField;
        } else {
            console.error('Can not found game field element.');
            return;
        }

        // add loading information to game field.
        const spinner: HTMLElement = document.createElement('span');
        const information: HTMLElement = document.createElement('p');
        spinner.classList.add('icon', 'icon128', 'fa-spinner');
        information.innerText = getLang('wcf.quizCreator.game.status.loading');
        this.gameField.classList.add('statusLoading');
        this.gameField.appendChild(spinner);
        this.gameField.appendChild(information);

        // load data
        this.loadData();
    }

    protected loadData(): void
    {
        new LanguageLoader('#js-QuizCreator-Language');
        new QuizLoader(this.selector, (quiz) => { this.startGame(quiz) }, () => { this.showError() });
    }

    protected prepareField(): void
    {
    }
}

export = Game;
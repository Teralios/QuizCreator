import {Quiz} from './Data/Data';
import {QuizLoader, LanguageLoader} from './Data/Loader';
import {get as phrase} from 'WoltLabSuite/Core/Language';
import {Game} from './Game/Game';

class QuizGame
{
    protected quiz: Quiz;
    protected gameContainer: HTMLElement;
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

        // its not nice, other do not work well so replace
        const oldContainer = this.gameContainer;
        const newContainer = document.createElement('div');
        newContainer.classList.add('game');
        this.gameContainer = newContainer;
        oldContainer.replaceWith(newContainer);

        new Game(this.quiz, this.gameContainer);
    }

    public showError(): void
    {
        const icon = this.gameContainer.getElementsByClassName('icon')[0] ?? null;
        const information = this.gameContainer.getElementsByTagName('p')[0] ?? null;

        if (icon !== null) {
            this.gameContainer.removeChild(icon);
        }

        if (information !== null) {
            information.textContent = phrase('wcf.quizCreator.game.status.error');
            information.classList.add('error');
        }
    }

    protected findField(): void
    {
       const gameField = document.querySelector(this.selector + ' .game');
        if (gameField instanceof HTMLElement) {
            this.gameContainer = gameField;
        } else {
            console.error('Can not found game field element.');
            return;
        }

        // add loading information to game field.
        const loading = document.createElement('div');
        const spinner = document.createElement('span');
        const information = document.createElement('p');
        spinner.classList.add('icon', 'icon128', 'fa-spinner');
        information.innerText = phrase('wcf.quizCreator.game.status.loading');
        loading.classList.add('statusLoading');
        loading.append(spinner, information);
        this.gameContainer.appendChild(loading);

        // load data
        this.loadData();
    }

    protected loadData(): void
    {
        new LanguageLoader('#js-QuizCreator-Language');
        new QuizLoader(this.selector, (quiz) => { this.startGame(quiz) }, () => { this.showError() });
    }
}

export = QuizGame;
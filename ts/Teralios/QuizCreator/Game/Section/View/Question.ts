// imports
import {Question} from "../../../Data/Data";
import DomUtil from "WoltLabSuite/Core/Dom/Util";
import {get as phrase} from "WoltLabSuite/Core/Language";

export type CheckAnswerCallback = (option: string) => boolean;
export type NextQuestionCallback = () => void;
export type ShowQuestionCallback = () => void;

// buttons
const button1 = document.createElement('button');
const button2 = document.createElement('button');
const button3 = document.createElement('button');
const button4 = document.createElement('button');
const buttons = [button1, button2, button3, button4];

// question
const question = document.createElement('p');
const explanation = document.createElement('p');

// next button
const nextButton = document.createElement('button');

// options
const options = ['A', 'B', 'C', 'D'];

// internal templates
function buildButtonField(): HTMLElement
{
    const buttonList = document.createElement('ul');
    buttonList.classList.add('optionButtons');

    buttons.forEach((button) => {
        const li = document.createElement('li');
        li.appendChild(button);
        buttonList.appendChild(li);
    })

    return buttonList;
}

function buildQuestionField(): HTMLElement
{
    const questionContainer = document.createElement('div');
    questionContainer.classList.add('question');
    questionContainer.appendChild(question);

    return questionContainer;
}

function buildExplanationField(): HTMLElement
{
    explanation.classList.add('explanation');
    const explanationContainer = document.createElement('div');
    explanationContainer.appendChild(explanation);

    return explanationContainer;
}

function buildNextField(): HTMLElement
{
    nextButton.textContent = phrase('wcf.quizCreator.game.button.next');
    nextButton.classList.add('next');
    const nextContainer = document.createElement('div');
    nextContainer.appendChild(nextButton);

    return nextContainer;
}

export class QuestionView {
    public viewContainer: HTMLElement;
    protected registerAnswer: CheckAnswerCallback;
    protected goToNextQuestion: NextQuestionCallback;
    protected question: Question;
    protected selectedOption: string;

    public constructor(registerAnswer: CheckAnswerCallback, nextCallback: NextQuestionCallback)
    {
        this.registerAnswer = registerAnswer;
        this.goToNextQuestion = nextCallback;

        this.viewContainer = document.createElement('div');
        this.viewContainer.classList.add('questionView');
        DomUtil.hide(this.viewContainer);

        this.viewContainer.append(buildQuestionField(), buildButtonField(), buildExplanationField(), buildNextField());
        this.prepareButtons();
    }

    public getView(): HTMLElement
    {
        return this.viewContainer;
    }

    public prepareFor(newQuestion: Question, callback?: NextQuestionCallback): void
    {
        this.question = newQuestion;
        question.textContent = this.question.question;

        // update buttons
        buttons.sort(() => 0.5 - Math.random());
        options.forEach((option, index) => {
            buttons[index].setAttribute('data-option', option.toLowerCase());
            buttons[index].textContent = this.question.options[option];
            buttons[index].classList.remove('correct', 'incorrect');
            buttons[index].disabled = false;
        });

        if (callback) {
            nextButton.textContent = phrase('wcf.quizCreator.game.button.last');
            this.goToNextQuestion = callback;
        }
    }

    public checkAnswer(clickedButton: MouseEvent): void
    {
        const target = clickedButton.target;

        if (target !== null && target instanceof HTMLElement) {
            this.selectedOption = target.getAttribute('data-option') ?? '';
            this.selectedOption = this.selectedOption.toLowerCase();
            this.updateField(this.registerAnswer(this.selectedOption));
        }
    }

    public nextQuestion(): void
    {
        // next button
        nextButton.disabled = true;
        nextButton.classList.remove('show');

        // explanation
        explanation.classList.remove('show')

        // execute callback for next question
        this.goToNextQuestion();
    }

    public updateField(isCorrect: boolean): void
    {
        // update and disable buttons
        buttons.forEach((button) => {
            let option = button.getAttribute('data-option') ?? '';
            option = option.toLowerCase();

            if (isCorrect && option == this.selectedOption) {
                button.classList.add('correct');
            } else {
                if (option == this.selectedOption) {
                    button.classList.add('incorrect');
                }
            }

            button.disabled = true;
        });

        // explanation
        explanation.textContent = this.question.explanation;
        explanation.classList.add('show');

        // next buttons
        nextButton.disabled = false;
        nextButton.classList.add('show');
    }

    protected prepareButtons(): void
    {
        buttons.forEach((button) => {
            button.addEventListener('click', (ev) => {
                this.checkAnswer(ev);
            })
        });

        nextButton.addEventListener('click', () => {
            this.nextQuestion();
        })
    }
}

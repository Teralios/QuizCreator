// imports
import {Question} from "../../../Data/Data";
import {get as phrase} from "WoltLabSuite/Core/Language";

export type CheckCallback = (option: string, callback: () => void) => void;
export type NextCallback = () => void;

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
    const explanationContainer = document.createElement('div');
    explanationContainer.classList.add('explanation', 'invisible');
    explanationContainer.appendChild(explanation);

    return explanationContainer;
}

function buildNextField(): HTMLElement
{
    nextButton.textContent = phrase('wcf.quizCreator.game.button.next');
    const nextContainer = document.createElement('div');
    nextContainer.appendChild(nextButton);

    return nextContainer;
}

export class QuestionView {
    public viewContainer: HTMLElement;
    protected checkCallback: CheckCallback;
    protected nextCallback: NextCallback;
    protected question: Question;
    protected selectedOption: string;

    public constructor(checkCallback: CheckCallback, nextCallback: NextCallback)
    {
        this.checkCallback = checkCallback;
        this.nextCallback = nextCallback;

        this.viewContainer = document.createElement('div');
        this.viewContainer.append(buildQuestionField(), buildButtonField(), buildExplanationField(), buildNextField());
        this.prepareButtons();
    }

    public getView(): HTMLElement
    {
        return this.viewContainer;
    }

    public prepareFor(question: Question, lastQuestion: boolean, callback: NextCallback): void
    {
        this.question = question;

        // update buttons
        buttons.sort(() => 0.5 - Math.random());
        options.forEach((option, index) => {
            buttons[index].setAttribute('data-option', option.toLowerCase());
            buttons[index].textContent = this.question.options[option];
        });

        if (lastQuestion) {
            nextButton.textContent = phrase('wcf.quizCreator.game.button.last');
            this.nextCallback = callback;
        }
    }

    public checkAnswer(clickedButton: MouseEvent): void
    {
        const target = clickedButton.target;

        if (target !== null && target instanceof HTMLElement) {
            this.selectedOption = target.getAttribute('data-option') ?? '';
            this.selectedOption = this.selectedOption.toLowerCase();
            this.checkCallback(this.selectedOption, () => this.updateAfterCheck())
        }
    }

    public nextQuestion(): void
    {
        // next button
        nextButton.disabled = true;
        nextButton.classList.add('invisible');

        // explanation
        explanation.classList.add('invisible')

        // execute callback for next question
        this.nextCallback();
    }

    public updateAfterCheck(): void
    {
        // update and disable buttons
        buttons.forEach((button) => {
            let option = button.getAttribute('data-option') ?? '';
            option = option.toLowerCase();

            if (this.question.checkAnswer(option)) {
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
        explanation.classList.remove('invisible');

        // next buttons
        nextButton.disabled = false;
        nextButton.classList.remove('invisible');
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

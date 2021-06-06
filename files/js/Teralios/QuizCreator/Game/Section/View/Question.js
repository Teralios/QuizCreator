define(["require", "exports", "WoltLabSuite/Core/Language"], function (require, exports, Language_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    exports.QuestionView = void 0;
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
    function buildButtonField() {
        const buttonList = document.createElement('ul');
        buttonList.classList.add('optionButtons');
        buttons.forEach((button) => {
            const li = document.createElement('li');
            li.appendChild(button);
            buttonList.appendChild(li);
        });
        return buttonList;
    }
    function buildQuestionField() {
        const questionContainer = document.createElement('div');
        questionContainer.classList.add('question');
        questionContainer.appendChild(question);
        return questionContainer;
    }
    function buildExplanationField() {
        const explanationContainer = document.createElement('div');
        explanationContainer.classList.add('explanation', 'invisible');
        explanationContainer.appendChild(explanation);
        return explanationContainer;
    }
    function buildNextField() {
        nextButton.textContent = Language_1.get('wcf.quizCreator.game.button.next');
        const nextContainer = document.createElement('div');
        nextContainer.appendChild(nextButton);
        return nextContainer;
    }
    class QuestionView {
        constructor(checkCallback, nextCallback) {
            this.checkCallback = checkCallback;
            this.nextCallback = nextCallback;
            this.viewContainer = document.createElement('div');
            this.viewContainer.append(buildQuestionField(), buildButtonField(), buildExplanationField(), buildNextField());
            this.prepareButtons();
        }
        getView() {
            return this.viewContainer;
        }
        prepareFor(question, lastQuestion, callback) {
            this.question = question;
            // update buttons
            buttons.sort(() => 0.5 - Math.random());
            options.forEach((option, index) => {
                buttons[index].setAttribute('data-option', option.toLowerCase());
                buttons[index].textContent = this.question.options[option];
            });
            if (lastQuestion) {
                nextButton.textContent = Language_1.get('wcf.quizCreator.game.button.last');
                this.nextCallback = callback;
            }
        }
        checkAnswer(clickedButton) {
            var _a;
            const target = clickedButton.target;
            if (target !== null && target instanceof HTMLElement) {
                this.selectedOption = (_a = target.getAttribute('data-option')) !== null && _a !== void 0 ? _a : '';
                this.selectedOption = this.selectedOption.toLowerCase();
                this.checkCallback(this.selectedOption, () => this.updateAfterCheck());
            }
        }
        nextQuestion() {
            // next button
            nextButton.disabled = true;
            nextButton.classList.add('invisible');
            // explanation
            explanation.classList.add('invisible');
            // execute callback for next question
            this.nextCallback();
        }
        updateAfterCheck() {
            // update and disable buttons
            buttons.forEach((button) => {
                var _a;
                let option = (_a = button.getAttribute('data-option')) !== null && _a !== void 0 ? _a : '';
                option = option.toLowerCase();
                if (this.question.checkAnswer(option)) {
                    button.classList.add('correct');
                }
                else {
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
        prepareButtons() {
            buttons.forEach((button) => {
                button.addEventListener('click', (ev) => {
                    this.checkAnswer(ev);
                });
            });
            nextButton.addEventListener('click', () => {
                this.nextQuestion();
            });
        }
    }
    exports.QuestionView = QuestionView;
});

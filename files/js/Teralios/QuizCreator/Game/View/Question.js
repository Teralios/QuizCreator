define(["require", "exports", "WoltLabSuite/Core/Language"], function (require, exports, Language_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    exports.QuestionView = void 0;
    const answerButtons = [];
    const questionText = document.createElement('p');
    const explanation = document.createElement('p');
    const nextButton = document.createElement('button');
    const options = ['A', 'B', 'C', 'D'];
    function buildQuestionBlock(callback) {
        const optionCount = options.length;
        const ulElement = document.createElement('ul');
        ulElement.classList.add('questionList');
        for (let i = 0; i < optionCount; i++) {
            const button = document.createElement('button');
            button.addEventListener('click', callback);
            answerButtons.push(button);
            const li = document.createElement('li');
            li.appendChild(button);
            ulElement.appendChild(li);
        }
        return ulElement;
    }
    function buildNextBlock(callback) {
        nextButton.textContent = Language_1.get('wcf.quizCreator.game.button.next');
        nextButton.addEventListener('click', callback);
        const nextContainer = document.createElement('div');
        nextContainer.classList.add('next');
        nextContainer.append(explanation, nextButton);
        return nextContainer;
    }
    class QuestionView {
        constructor(answer, next, finish) {
            // view template
            this.container = document.createElement('div');
            this.container.classList.add('questionView');
            this.nextBlock = buildNextBlock(next);
            this.container.append(questionText, buildQuestionBlock(answer), this.nextBlock);
            // question class
            questionText.classList.add('question');
            // callback
            this.finish = finish;
            this.next = next;
        }
        getView() {
            return this.container;
        }
        prepareFor(question) {
            questionText.textContent = question.question;
            explanation.textContent = question.explanation;
            let i = 0;
            options.sort(() => 0.5 - Math.random());
            options.forEach((option) => {
                answerButtons[i].textContent = question.options[option];
                answerButtons[i].dataset.option = option;
                answerButtons[i].classList.remove('correct', 'incorrect');
                answerButtons[i].disabled = false;
                i++;
            });
            nextButton.disabled = true;
            this.nextBlock.classList.remove('show');
        }
        updateView(userOption, correctOption, isLast = false) {
            answerButtons.forEach((button) => {
                if (button.dataset.option == correctOption) {
                    button.classList.add('correct');
                }
                else if (button.dataset.option == userOption) {
                    button.classList.add('incorrect');
                }
                button.disabled = true;
            });
            if (isLast) {
                nextButton.textContent = Language_1.get('wcf.quizCreator.game.button.finish');
                nextButton.removeEventListener('click', this.next);
                nextButton.addEventListener('click', this.finish);
            }
            nextButton.disabled = false;
            this.nextBlock.classList.add('show');
        }
    }
    exports.QuestionView = QuestionView;
});

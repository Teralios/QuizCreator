define(['StringUtil', 'Language', 'Teralios/QuizCreator/Result'], function (StringUtil, Language, Result) {
    "use strict";

    return class Quiz {
        title = '';
        image = '';
        description = '';
        _questions = new Map();
        _goals = [];
        _questionMaxKey = 1;
        _questionKey = 1;
        _goalMaxKey = 1;
        _goalKey = 1;

        /**
         * @param {string} title
         * @param {string} description
         * @param {string} image
         */
        constructor(title, description = '', image = '')
        {
            this.title = title;
            this.description = description;
            this.image = image;
        }

        setQuestion(question)
        {
            this._questions.set(this._questionMaxKey, question);
            this._questionMaxKey++;
        }

        getNextQuestion()
        {
            if (this._questions.has(this._questionKey)) {
                let question = this._questions.get(this._questionKey);
                this._questionKey++;

                return question;
            }

            return false;
        }
    }
});
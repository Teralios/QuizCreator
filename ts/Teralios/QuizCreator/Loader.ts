import {AjaxCallbackObject, RequestOptions, RequestData, ResponseData, DatabaseObjectActionResponse} from 'WoltLabSuite/Core/Ajax/Data';
import {api} from 'WoltLabSuite/Core/Ajax';
import {Question, Goal, Quiz} from './Data';

class Loader implements AjaxCallbackObject
{
    protected quizID: number | null;
    protected quiz: Quiz;
    protected quizSelector: string;
    protected jsonData: JSON;

    public constructor(htmlSelector: string)
    {
        this.quizSelector = htmlSelector;
        this.quizID = null;

        this.findQuizID();
    }

    protected findQuizID(): void
    {
        let quizElement: HTMLElement | null;
        let quizID: string | null

        if (!this.quizSelector.startsWith('#')) {
            console.error('Teralios/QuizCreator/Loader aspects id selector for loading quiz.')
        } else {
            quizElement = document.getElementById(this.quizSelector);

            if (quizElement != null) {
                quizID = quizElement.getAttribute('data-quiz-id');

                if (quizID != null) {
                    this.quizID = parseInt(quizID);
                }
            }
        }

        if (this.quizID != null) {
            api(this);
        } else {
            console.error('No quiz id found.');
        }
    }

    protected buildQuiz()
    {

    }

    _ajaxSetup(): RequestOptions {
        return {
            data: {
                actionName: "loadQuiz",
                className: "wcf\\data\\quiz\\QuizAction",
                objectIDs: [this.quizID],
                parameters: {
                    value: 1
                }
            }
        }
    }

    _ajaxSuccess(data: ResponseData | DatabaseObjectActionResponse, responseText: string, xhr: XMLHttpRequest, requestData: RequestData): void {
        this.jsonData = JSON.parse(data.returnValues);
        this.buildQuiz();
    }
}
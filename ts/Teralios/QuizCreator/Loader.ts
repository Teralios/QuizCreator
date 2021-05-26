import {api} from 'WoltLabSuite/Core/Ajax';
import {AjaxCallbackObject, RequestOptions, RequestData, ResponseData, DatabaseObjectActionResponse} from 'WoltLabSuite/Core/Ajax/Data';
import Quiz from './Data/Quiz';
import Question from './Data/Question';

class Loader implements AjaxCallbackObject
{
    protected quizID: number | null;
    protected quiz: Quiz;
    protected quizSelector: string;

    public constructor(htmlSelector: string)
    {
        this.quizSelector = htmlSelector;
        this.quizID = null;
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

        } else {
            console.error('No quiz id found.');
        }
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
    }
}
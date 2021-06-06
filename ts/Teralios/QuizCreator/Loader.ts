import {AjaxCallbackObject, RequestOptions, ResponseData, DatabaseObjectActionResponse} from 'WoltLabSuite/Core/Ajax/Data';
import {add as addLanguageItem} from 'WoltLabSuite/Core/Language';
import {api} from 'WoltLabSuite/Core/Ajax';
import {Question, Goal, Quiz} from './Data';

/**
 * Loads a quiz via ajax from server and parse question and goals.
 */
class QuizLoader implements AjaxCallbackObject
{
    protected quizID: number | null;
    protected quizSelector: string;
    protected jsonData: JSON;
    protected callbackSuccess: (quiz) => void;
    protected callbackFailure: () => void;

    /**
     * Default constructor
     * @param idSelector    selector contains id where quiz id is found.
     * @param callbackSuccess      Callback function after quiz is loaded
     */
    public constructor(idSelector: string, callbackSuccess: (quiz) => void, callbackFailure: () => void)
    {
        this.quizSelector = (idSelector.startsWith('#')) ? idSelector.substr(1) : idSelector;
        this.callbackSuccess = callbackSuccess;
        this.callbackFailure = callbackFailure;
        this.quizID = null;

        this.findQuizID();
    }

    /**
     * search quiz id.
     * @protected
     */
    protected findQuizID(): void
    {
        let quizID: string | null
        const quizElement = document.getElementById(this.quizSelector);

        if (quizElement == null) {
            console.error('Quiz with id ' + this.quizSelector + ' not found.');
            return;
        } else {
            quizID = quizElement.getAttribute('data-quiz-id');

            if (quizID != null) {
                this.quizID = parseInt(quizID);
            }
        }

        if (this.quizID != null) {
            api(this);
        } else {
            console.error('No quiz id found.');
        }
    }

    /**
     * build quiz from given json ajax data.
     * @protected
     */
    protected buildQuiz(): void
    {
        const quiz: Quiz = new Quiz();

        if ('questionList' in this.jsonData && Array.isArray(this.jsonData['questionList'])) {

            // add questions
            const questionList: Array<any> = this.jsonData['questionList'];
            if (questionList.length > 0) {
                questionList.forEach((data) => {
                    const question = String(data.question) ?? '';
                    const optionA = String(data.optionA) ?? '';
                    const optionB = String(data.optionB) ?? '';
                    const optionC = String(data.optionC) ?? '';
                    const optionD = String(data.optionD) ?? '';
                    const correctOption = String(data.answer) ?? '';
                    const explanation = String(data.explanation) ?? '';

                    if (
                        question != ''
                        && optionA != ''
                        && optionB != ''
                        && optionC != ''
                        && optionD != ''
                        && correctOption != ''
                    ) {
                        quiz.addQuestion(new Question(
                            question,
                            optionA,
                            optionB,
                            optionC,
                            optionD,
                            explanation,
                            correctOption
                        ));
                    }
                })
            }

            // add goals
            if ('goalList' in this.jsonData && Array.isArray(this.jsonData['goalList'])) {
                const goalList: Array<any> = this.jsonData['goalList'];

                if (goalList.length > 0) {
                    goalList.forEach((data) => {
                        const points: number = Number(data.points) ?? 0;
                        const title: string = String(data.title) ?? '';
                        const icon: string = String(data.icon) ?? '';
                        const description: string = String(data.description) ?? '';

                        if (title != '') {
                            quiz.addGoal(new Goal(
                                title,
                                icon,
                                description,
                                points,
                            ));
                        }
                    })
                }
            }
        }

        this.callbackSuccess(quiz);
    }

    /**
     * ajax setup data.
     */
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

    /**
     * Ajax success data.
     * @param data
     */
    _ajaxSuccess(data: ResponseData | DatabaseObjectActionResponse): void
    {
        this.jsonData = data.returnValues;
        this.buildQuiz();
    }

    _ajaxFailure(): boolean
    {
        this.callbackFailure();
        return true;
    }
}

/**
 * Load language items from a json script tag.
 */
class LanguageLoader
{
    protected contentID: string;

    public constructor(contentID: string)
    {
        this.contentID = contentID;

        this.loadLanguageItems();
    }

    protected loadLanguageItems(): void
    {
        const jsonField: HTMLElement | null = document.getElementById(this.contentID);
        const languageData: Map<string, string> = new Map();

        if (jsonField === null) {
            console.log('Can not find language items.');
            return;
        }

        JSON.parse(jsonField.innerHTML, (key: any, value: any) => {
            if (typeof(key) == 'string' && typeof(value) == 'string') {
                languageData.set(key, value);
            }
        });

        if (languageData.size > 0) {
            languageData.forEach((key: string, value: string) => {
                addLanguageItem(key, value)
            });
        }
    }
}

export {QuizLoader, LanguageLoader};
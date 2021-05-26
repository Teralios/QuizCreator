import Question from './Question';

class Quiz
{
    protected questions: Question[];
    protected questionsCount: number;
    protected questionIndex: number;

    public constructor()
    {
        this.questionsCount = 0;
        this.questionIndex = 0;
    }

    public addQuestion(question: Question)
    {
        this.questions[this.questionsCount] = question;
        ++this.questionsCount
    }

    public getQuestion(): Question | null
    {
        if (this.questionsCount < this.questionIndex) {
            return null;
        }

        return this.questions[this.questionIndex];
    }

    public nextQuestion(): boolean
    {
        ++this.questionIndex;

        return this.questionIndex <= this.questionsCount;
    }
}

export = Quiz;

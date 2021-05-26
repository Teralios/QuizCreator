export class Question {
    public question: string;
    public options: object;
    public explanation: string;
    public correct: string;

    public constructor (question: string, optionA: string, optionB: string, optionC: string, optionD: string, explanation: string, correctOption: string)
    {
        this.question = question;
        this.options['A'] = optionA;
        this.options['B'] = optionB;
        this.options['C'] = optionC;
        this.options['D'] = optionD;
        this.explanation = explanation;
        this.correct = correctOption.toLowerCase();
    }

    public checkAnswer(givenAnswer: string): boolean
    {
        givenAnswer = givenAnswer.toLowerCase();

        return givenAnswer == this.correct;
    }
}

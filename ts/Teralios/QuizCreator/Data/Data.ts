/**
 * Default question class.
 */
class Question
{
    public question: string;
    public options: Map<string, string>;
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

/**
 * Default goal class.
 */
class Goal
{
    public title: string;
    public description: string;
    public icon: string;
    public minScore: number;

    public constructor (title: string, description: string, icon: string, minScore: number)
    {
        this.title = title;
        this.description = description;
        this.icon = icon;
        this.minScore = minScore;
    }

    public reached(score: number): boolean
    {
        return (score >= this.minScore);
    }
}

/**
 * Default quiz class.
 */
class Quiz
{
    protected questions: Question[];
    protected questionsCount: number;
    protected questionIndex: number;
    protected goals: Goal[];

    public constructor()
    {
        this.questionsCount = 0;
        this.questionIndex = 0;
    }

    public addQuestion(question: Question): void
    {
        this.questions.push(question);
        ++this.questionsCount
    }

    public addGoal(goal: Goal): void
    {
        this.goals.push(goal);
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

    public getGoal(score: number): Goal | null
    {
        let reachedGoal: Goal | null;
        reachedGoal = null;

        if (this.goals.length > 0) {
            this.goals.sort((a:Goal, b:Goal) => {
                return (a >= b) ? 1 : -1;
            })

            this.goals.forEach((goal: Goal) => {
                if (goal.reached(score)) {
                    reachedGoal = goal;
                }
            });
        }

        return reachedGoal;
    }
}

export {Question, Goal, Quiz}
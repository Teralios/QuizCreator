import {get as phrase} from 'WoltLabSuite/Core/Language';

// general information blogs
const question = document.createElement('b');
const questionCounter = document.createElement('b');
const questionMakers: HTMLElement[] = [];
const value = document.createElement('span');
const score = document.createElement('span');
const clock = document.createElement('p');

function buildHeaderView(questions: number): HTMLElement
{
    const container = document.createElement('div');
    container.classList.add('header');
    container.append(
        buildQuestionInfo(questions),
        buildStopwatch(),
        buildScore(),
    );

    return container;
}

function buildQuestionInfo(questions: number): HTMLElement
{
    // main container
    const container = document.createElement('div');
    container.classList.add('questionInfo');

    // text information
    const textInformation = document.createElement('p');
    textInformation.append(
        phrase('wcf.quizCreator.game.header.question.prefix'),
        question,
        phrase('wcf.quizCreator.game.header.question.suffix'),
        questionCounter
    );
    questionCounter.textContent = String(questions);

    // markers
    const makerContainer = document.createElement('p');
    for (let i = 0; i < questions; i++) {
        const marker = document.createElement('span');
        marker.classList.add('fa', 'icon16', 'fa-question-circle');
        makerContainer.append(marker);
        questionMakers.push(marker);
    }

    // finalize container
    container.append(textInformation, makerContainer);

    return container;
}

function buildStopwatch(): HTMLElement
{
    const container = document.createElement('div');
    container.classList.add('stopwatch');

    // top line
    const top = document.createElement('p');
    top.classList.add('top', 'paused');
    const dot = document.createElement('span');
    dot.classList.add('fa', 'icon16', 'fa-circle');
    top.append(dot, ' +', value);

    container.append(top, clock);

    return container;
}

function buildScore(): HTMLElement
{
    const container = document.createElement('div');
    container.classList.add('score');
    container.append(score, document.createElement('b'));

    if (container.lastChild != null) {
        container.lastChild.textContent = phrase('wcf.quizCreator.game.header.score');
    }

    return container;
}

export class Header {
    protected container: HTMLElement;

    public constructor(questions: number)
    {
        this.container = buildHeaderView(questions);
    }

    public getView(): HTMLElement
    {
        return this.container;
    }

    public updateQuestion(questionIndex: number): void
    {
        question.textContent = String(questionIndex);
    }

    public updateScore(newScore: number): void
    {
        score.textContent = String(newScore);
    }

    public updateStatus(newClass: string): void
    {
        const stopWatch = this.container.getElementsByClassName('stopwatch')[0] ?? null;
        stopWatch.classList.remove('s1', 's2', 's3');
        stopWatch.classList.add('s1');
    }

    public updateValue(newValue: number): void
    {
        value.textContent = String(newValue);
    }

    public updateTime(seconds: number): void
    {
        const minute = Math.floor(seconds / 60);
        const second = seconds % 60;
        const dot = ((seconds % 2) == 0) ? ':' : ' ';

        clock.textContent = String(minute) + dot + String(second);
    }

    public stopAnimation(): void
    {
        const top = this.getStopwatchTop();
        if (top != null) {
            top.classList.add('paused')
        }
    }

    public startAnimation(): void
    {
        const top = this.getStopwatchTop();
        if (top != null) {
            top.classList.remove('paused');
        }
    }

    protected getStopwatchTop(): Element
    {
        return this.container.getElementsByClassName('top')[0] ?? null;
    }
}

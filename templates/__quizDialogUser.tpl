{assign var="wrongCount" value=0}
{assign var="correctCount" value=0}

<div class="questionList">
    <div class="questionListTitle">
        {lang}wcf.quizCreator.quiz.question{/lang}
    </div>
    {foreach from=$questions item=question}
        {assign var="answer" value=$game->getQuestion($question->position)}
        {assign var="userChoice" value=$answer['answer']}
        {assign var="userTime" value=$answer['time']}
        {if $userChoice == $question->answer}{assign var="correctCount" value=$correctCount+1}{else}{assign var="wrongCount" value=$wrongCount+1}{/if}

        <div class="question {if $userChoice == $question->answer}correct{else}wrong{/if}">
            <div class="questionIndicator">{#$question->position}</div>
            <div class="questionIcon"><span class="icon icon32 {if $userChoice == $question->answer}fa-thumbs-up{else}fa-thumbs-down{/if}"></span></div>
            <div class="questionText">
                <p>{$question->question}</p>
                <ul class="inlineList dotSeparated small answerList">
                    {foreach from=$question->getPossibleOptions() item=option}
                        {capture assign="answerStartLi"}
                            <li {if $userChoice == $option} class="userAnswer"{/if}>
                            <span class="icon icon16 {if $userChoice == $option}fa-pencil-square{elseif $option == $question->answer}fa-check-square{else}fa-square-o{/if}"></span>
                        {/capture}
                    {@$answerStartLi}{$question->getOption($option)}</li>
                    {/foreach}
                    <li><span class="icon icon16 fa-clock-o"></span> {#$userTime} {lang}wcf.quizCreator.stats.time.seconds{/lang}</li>
                </ul>
            </div>
        </div>
    {/foreach}
</div>
<div class="playerStatistic">
    <div><b>{lang}wcf.quizCreator.stats.correct{/lang}</b>: {#$correctCount}</div>
    <div>{$game->getPlayTime()} {lang}wcf.quizCreator.stats.time.minutes{/lang}</div>
    <div><b>{lang}wcf.quizCreator.stats.incorrect{/lang}</b>: {#$wrongCount}</div>
</div>
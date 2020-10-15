<ol class="tabularList questionList">
    <li class="tabularListRowHead">
        <ol class="tabularListColumns">
            <li class="columnText">{lang}wcf.quizCreator.quiz.question{/lang}</li>
        </ol>
    </li>
    {assign var="wrongCount" value=0}
    {assign var="correctCount" value=0}

    {foreach from=$questions item=question}
        {assign var="answer" value=$game->getQuestion($question->position)}
        {assign var="userChoice" value=$answer['answer']}
        {assign var="userTime" value=$answer['time']}
        {if $userChoice == $question->answer}{assign var="correctCount" value=$correctCount+1}{else}{assign var="wrongCount" value=$wrongCount+1}{/if}

        <li class="tabularListRow question {if $userChoice == $question->answer}correct{else}wrong{/if}">
            <ol class="tabularListColumns">
                <li class="columnID">{#$question->position}</li>
                <li class="columnIcon"><span class="icon icon32 {if $userChoice == $question->answer}fa-thumbs-up{else}fa-thumbs-down{/if}"></span></li>
                <li class="columnTitle">
                    <h3>{$question->question} <span class="small"><span class="icon icon16 fa-clock-o"></span> {#$userTime} {lang}wcf.quizCreator.stats.time.seconds{/lang}</span></h3>
                    <ul class="inlineList dotSeparated small answerList">
                        {foreach from=$question->getPossibleOptions() item=option}
                            {capture assign="answerStartLi"}
                                <li {if $userChoice == $option} class="userAnswer"{/if}>
                                <span class="icon icon16 {if $userChoice == $option}fa-pencil-square{elseif $option == $question->answer}fa-check-square{else}fa-square-o{/if}">
                                </span>
                            {/capture}
                        {@$answerStartLi}{$question->getOption($option)}</li>
                        {/foreach}
                    </ul>
                </li>
            </ol>
        </li>
    {/foreach}
    <li class="tabularListRow userStats">
        <ol class="tabularListColumns">
            <li class="columnStats"><b>{lang}wcf.quizCreator.stats.correct{/lang}</b>: {#$correctCount}</li>
            <li class="columnStats">{$game->getPlayTime()} {lang}wcf.quizCreator.stats.time.minutes{/lang}</li>
            <li class="columnStats"><b>{lang}wcf.quizCreator.stats.incorrect{/lang}</b>: {#$wrongCount}</li>
        </ol>
    </li>
</ol>
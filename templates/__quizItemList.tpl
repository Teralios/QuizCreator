{foreach from=$objects item="quiz"}
    {assign var="media" value=$quiz->getMedia()}
    <div class="quiz{if $quiz->isActive} isActive{else} notActive{/if}" data-object-id="{#$quiz->quizID}">
        <a href="{$quiz->getLink()}">
            <div class="quizInner{if !$quiz->isActive} quizNotActive{/if}">
                <div class="quizBase">
                    <div class="quizBaseInner">
                        <div class="quizBaseIcon">
                        </div>
                    </div>
                </div>
                {if $media}
                    <div class="quizImage">
                        {@$media} {* here normal image tag is better for display adjustments *}
                    </div>
                {/if}
                <div class="quizTitle">
                    <h3>{$quiz->getTitle()}</h3>
                </div>
                <div class="quizInfo">
                    <div>
                        {if $quiz->hasPlayed()}
                            <span class="icon icon16 fa-check jsTooltip" title="{lang}wcf.quizCreator.user.played{/lang}"></span>
                        {else}
                            <span class="icon icon16 fa-circle-o jsTooltip" title="{lang}wcf.quizCreator.user.noPlayed{/lang}"></span>
                        {/if}
                        <span class="small">{@$quiz->creationDate|time}</span>
                    </div>
                    <div>
                        <span class="jsTooltip" title="{lang}wcf.quizCreator.stats.questions{/lang}"><span class="icon icon16 fa-question-circle-o"></span> {#$quiz->questions}</span>
                        <span class="jsTooltip separatorLeft" title="{lang}wcf.quizCreator.stats.players{/lang}"><span class="icon icon16 fa-users"></span> {if $quiz->playery > 0}{$quiz->players}{else}0{/if}</span>
                        {if !$quiz->languageID|empty}
                            <span class="separatorLeft"><img class="iconFlag jsTooltip" title="{lang}wcf.quizCreator.quiz.language{/lang}" src="{$quiz->getLanguageIcon()}"></span>
                        {/if}
                    </div>
                </div>
            </div>
        </a>
    </div>
{/foreach}
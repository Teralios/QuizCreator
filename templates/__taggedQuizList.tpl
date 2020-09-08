<div class="section tabularBox messageGroupList">
        <ol class="tabularList">
                <li class="tabularListRow tabularListRowHead">
                        <ol class="tabularListColumns">
                                <li class="columnSort">{lang}wcf.global.title{/lang}</li>
                        </ol>
                </li>

                {foreach from=$objects item=quiz}
                        <li class="tabularListRow">
                                <ol class="tabularListColumns">
                                        <li class="columnIcon">
                                            <span
                                                class="icon icon32 {if $quiz->type == 'competition'}fa-trophy{else}fa-child{/if} jsTooltip"
                                                title="{lang}wcf.acp.quizCreator.quiz.type.{@$quiz->type}{/lang}">
                                            </span>
                                        </li>

                                        <li class="columnSubject">
                                                <h3><a href="{$quiz->getLink()}">{$quiz->title}</a></h3>
                                                <small>{@$quiz->creationDate|time}</small>
                                        </li>

                                        <li class="columnStats">
                                                <dl class="plain statsDataList">
                                                        <dt>{lang}wcf.quizCreator.questions{/lang}</dt>
                                                        <dd>{@$quiz->questions|shortUnit}</dd>
                                                        <dt>{lang}wcf.quizCreator.played{/lang}</dt>
                                                        <dd>{@$quiz->played|shortUnit}</dd>
                                                </dl>
                                                <div class="messageGroupListStatsSimple" aria-label="{lang}wcf.quizCreator.questions{/lang}"><span class="icon icon16 fa-question-circle"></span> {@$quiz->questions|shortUnit}</div>
                                        </li>
                                        {if !$quiz->languageID|empty}
                                                <li class="columnIcon">
                                                        <a class="jsTooltip" href="{link controller='QuizList'}languageID={$quiz->languageID}{/link}" title="{lang}wcf.quizCreator.language{/lang}">
                                                                <img class="iconFlag" src="{$quiz->getLanguageIcon()}">
                                                        </a>
                                                </li>
                                        {/if}
                                        {event name='columns'}
                                </ol>
                        </li>
                {/foreach}
        </ol>
</div>
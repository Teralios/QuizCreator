{assign var="__title" value='wcf.acp.quizCreator.quiz.list'}
{capture assign="__javascript"}
    <script data-relocate="true">
        // base delete and toggle action
        $(function() {
            new WCF.Action.Delete('wcf\\data\\quiz\\QuizAction', '.jsQuizRow');
            new WCF.Action.Toggle('wcf\\data\\quiz\\QuizAction', '.jsQuizRow');
        });

        // reset cache - new action
        require(['Teralios/QuizCreator/Acp/ActionReset'], function(ActionReset) {
            ActionReset.init('wcf\\data\\quiz\\QuizAction', '.jsQuizRow');
        });
    </script>
{/capture}

{capture assign="__headerNavigation"}
    <nav class="contentHeaderNavigation">
            <ul>
                <li>
                    <a href="{link controller="quizAdd"}{/link}" class="button">
                        <span class="icon icon16 fa-plus"></span> <span>{lang}wcf.acp.quizCreator.quiz.add{/lang}</span>
                    </a>
                </li>

                {event name='contentHeaderNavigation'}
            </ul>
    </nav>
{/capture}

{capture assign="__contentBody"}
    {hascontent}
        <div class="paginationTop">
            {content}
                {pages print=true assign=pagesLinks controller="QuizList" link="pageNo=%d"}
            {/content}
        </div>
    {/hascontent}

    {if $objects|count}
        <div class="section tabularBox">
            <table class="table">
                <thead>
                    <tr>
                        <th class="columnID columnQuizID" colspan="2">{lang}wcf.global.objectID{/lang}</th>
                        <th class="columnTitle">{lang}wcf.global.title{/lang}</th>
                        {if $isMultiLingual}<th class="columnText">{lang}wcf.user.language{/lang}</th>{/if}
                        <th class="columnText">{lang}wcf.global.date{/lang}</th>
                        <th class="columnText">{lang}wcf.acp.quizCreation.questions{/lang}</th>
                        <th class="columnText">{lang}wcf.acp.quizCreator.goals{/lang}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$objects item=quiz}
                        {capture assign=quizLink}{link controller="QuizEdit" id=$quiz->quizID}{/link}{/capture}
                        <tr class="jsQuizRow">
                            <td class="columnIcon">
                                <span class="icon icon16 fa-{if $quiz->isActive}check-{/if}square-o jsToggleButton jsTooltip pointer"
                                      title="{lang}wcf.global.button.{if $quiz->isActive}disable{else}enable{/if}{/lang}"
                                      data-object-id="{@$quiz->quizID}">
                                </span>
                                <a href="{$quizLink}" title="{lang}wcf.global.button.edit{/lang}" class="jsTooltip">
                                    <span class="icon icon16 fa-pencil"></span>
                                </a>
                                {* We not need to check permission here, canManage is all. *}
                                <span class="icon icon16 fa-times jsDeleteButton jsTooltip pointer"
                                      title="{lang}wcf.global.button.delete{/lang}"
                                      data-object-id="{#$quiz->quizID}"
                                      data-confirm-message-html="{lang __encode=true}wcf.acp.quizCreator.delete.confirmMessage.quiz{/lang}">
                                </span>

                                <span class="icon icon16 fa-user-times jsResetMatchesButton jsTooltip pointer"
                                      title="{lang}wcf.acp.quizCreator.quiz.reset{/lang}"
                                      data-object-id="{#$quiz->quizID}"
                                      data-confirm-message-html="{lang __encode=true}wcf.acp.quizCreator.quiz.reset.confirmMessage{/lang}">
                                </span>

                                <a href="{link controller="QuizExport" id=$quiz->quizID}{/link}">
                                    <span class="icon icon16 fa-download jsTooltip pointer"
                                          title="{lang}wcf.acp.quizCreator.quiz.export{/lang}">
                                    </span>
                                </a>
                            </td>
                            <td class="columnID columnQuizID">{@$quiz->quizID}</td>
                            <td class="columnTitle">
                                <a href="{$quizLink}">{$quiz->title}</a>
                            </td>
                            {if $isMultiLingual}
                                <td class="columnText">
                                    {if !$quiz->getLanguageIcon()|empty}
                                        <img class="iconFlag jsTooltip"
                                             title="{lang}wcf.acp.quizCreator.quiz.language{/lang}"
                                             src="{$quiz->getLanguageIcon()}">
                                    {/if}
                                </td>
                            {/if}
                            <td class="columnText">{$quiz->creationDate|date}</td>
                            <td class="columnDigits">{#$quiz->questions}</td>
                            <td class="columnDigits">{#$quiz->goals}</td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>

        <footer class="contentFooter">
            {hascontent}
                <div class="paginationBottom">
                    {content}
                        {@$pagesLinks}
                    {/content}
                </div>
            {/hascontent}

            <nav class="contentFooterNavigation">
                <ul>
                    <li>
                        <a href="{link controller='QuizAdd'}{/link}" class="button">
                            <span class="icon icon16 fa-plus"></span> <span>{lang}wcf.acp.quizCreator.quiz.add{/lang}</span>
                        </a>
                    </li>

                    {event name='contentFooterNavigation'}
                </ul>
            </nav>
        </footer>
    {else}
        <p class="info">{lang}wcf.global.noItems{/lang}</p>
    {/if}
{/capture}

{include file='_qcBase'}
{include file='header' pageTitle='wcf.acp.quizMaker.'|concat:$action}

<script data-relocate="true">
    $(function() {
        new WCF.Action.Delete('wcf\\data\\quiz\\question\\QuestionAction', '.jsQuestionRow');
    });
</script>

{capture assign="navigationButtons"}
    {if !$formObject|is_null}
        <li>
            <a class="button" href="{link controller='QuestionAdd' id=$formObject->quizID}{/link}">
                <span class="icon icon16 fa-question-circle"></span> <span>{lang}wcf.quizMaker.question.add{/lang}</span>
            </a>
        </li>

        {if $formObject->type == "fun"}
            <li>
                <a class="button">
                    <span class="icon icon16 fa-trophy"></span> <span>{lang}wcf.quizMaker.stage.add{/lang}</span>
                </a>
            </li>
        {/if}
    {/if}

    <li><a href="{link controller='QuizList'}{/link}" class="button"><span class="icon icon16 fa-list"></span> <span>{lang}wcf.quizMaker.quiz.list{/lang}</span></a></li>
    {event name='navigationButtons'}
{/capture}

<header class="contentHeader">
    <div class="contentHeaderTitle">
        <h1 class="contentTitle">{if $action == 'add'}{lang}wcf.acp.quizMaker.quiz.add{/lang}{else}{lang}wcf.acp.quizMaker.quiz.edit{/lang}{/if}</h1>
    </div>

    <nav class="contentHeaderNavigation">
        <ul>
            {@$navigationButtons}
        </ul>
    </nav>
</header>

{if $createSuccess === true}<p class="success">{lang}wcf.acp.quizMaker.quiz.created{/lang}</p>{/if}

{@$form->getHtml()}

{if !$formObject|is_null && ($formObject->questions > 0 || $formObject->stages > 0)}
    <div class="section tabMenuContainer" data-active="{$activeTabMenuItem}" data-store="activeTabMenuItem" id="pageTabMenuContainer">
        <nav class="tabMenu">
            <ul>
                {if $questionList|isset && $questionList|count > 0}
                    <li><a href="{@$__wcf->getAnchor('questions')}">{lang}wcf.acp.quizMaker.question.list{/lang}</a></li>
                {/if}
                {if $formObject->stages > 0}
                    <li><a href="{@$__wcf->getAnchor('stages')}">{lang}wcf.acp.quizMaker.stage.list{/lang}</a></li>
                {/if}
                {event name='tabMenuTabs'}
            </ul>
        </nav>

        {if $questionList|isset && $questionList|count > 0}
            <div id="questions" class="tabMenuContent">
                <div class="section tabularBox">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="columnQuestionID" colspan="2">{lang}wcf.global.objectID{/lang}</th>
                                <th class="columnTitle">{lang}wcf.acp.quizMaker.question{/lang}</th>
                                <th class="columnDigits">{lang}wcf.acp.quizMaker.question.order{/lang}</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$questionList item=question}
                                <tr class="jsQuestionRow">
                                    {capture assign=questionLink}{link controller="QuestionEdit" id=$question->questionID}{/link}{/capture}
                                    <td class="columnIcon">
                                        <a href="{$questionLink}" title="{lang}wcf.global.button.edit{/lang}" class="jsTooltip">
                                            <span class="icon icon16 fa-pencil"></span>
                                        </a>
                                        {* We not need to check permission here, canManage is all. *}
                                        <span class="icon icon16 fa-times jsDeleteButton jsTooltip pointer"
                                              title="{lang}wcf.global.button.delete{/lang}"
                                              data-object-id="{@$question->questionID}"
                                              data-confirm-message-html="{lang __encode=true}wcf.acp.quizMaker.question.delete.confirmMessage{/lang}">
                                        </span>
                                    </td>
                                    <td class="columnID columnQuestionID">{#$question->questionID}</td>
                                    <td class="columnTitle">
                                        <a href="{$questionLink}">{$question->question}</a>
                                    </td>
                                    <td class="columnDigits">{$question->position}</td>
                                </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>
        {/if}

        {if $formObject->stages > 0}
            <div id="stages" class="tabMenuContent">
                <div class="section tabularBox">

                </div>
            </div>
        {/if}
    </div>

    <footer class="contentFooter">
        <nav class="contentFooterNavigation">
            <ul>
                {@$navigationButtons}
            </ul>
        </nav>
    </footer>
{/if}

{include file='footer'}
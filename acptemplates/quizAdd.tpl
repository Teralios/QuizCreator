{assign var="__formTitle" value='wcf.acp.quizCreator.quiz.'|concat:$action}

{capture assign="__formJavaScript"}
    <script data-relocate="true">
        // base delete action
        $(function() {
            new WCF.Action.Delete('wcf\\data\\quiz\\question\\QuestionAction', '.jsQuestionRow');
            new WCF.Action.Delete('wcf\\data\\quiz\\goal\\GoalAction', '.jsGoalRow');
        });

        // reset game caches
        require(['Teralios/QuizCreator/Acp/ActionReset'], function(ActionReset) {
            ActionReset.init('wcf\\data\\quiz\\QuizAction', '.jsResetMatchesButton', true);
        });
    </script>
{/capture}

{capture assign="__formNavigationButtons"}
    {if !$formObject|empty && $quiz|empty}
        {capture assign='__formTitleDescription'}{$formObject->getTitle()}{/capture}
        <li>
            <a class="button" href="{link controller='QuizQuestionAdd' quizID=$formObject->quizID}{/link}">
                <span class="icon icon16 fa-question-circle"></span> <span>{lang}wcf.acp.quizCreator.question.add{/lang}</span>
            </a>
        </li>
        <li>
            <a class="button" href="{link controller='QuizGoalAdd' quizID=$formObject->quizID}{/link}">
                <span class="icon icon16 fa-trophy"></span> <span>{lang}wcf.acp.quizCreator.goal.add{/lang}</span>
            </a>
        </li>
        <li>
            <button class="jsResetMatchesButton"
                    data-object-id="{#$formObject->quizID}"
                    data-confirm-message-html="{lang __encode=true quiz=$formObject}wcf.acp.quizCreator.reset.matches.confirm{/lang}">
                <span class="icon icon16 fa-user-times"></span> <span>{lang}wcf.acp.quizCreator.reset.matches{/lang}</span>
            </button>
        </li>
    {/if}
{/capture}

{capture assign="__formSuccessMessage"}
    {if $createSuccess|isset && $createSuccess === true}<p class="success">{lang}wcf.acp.quizCreator.quiz.created{/lang}</p>{/if}
{/capture}

{capture assign="__formContentHeader"}
    {if !$formObject|is_null && ($formObject->questions > 0 || $formObject->goals > 0)}
        <div class="section tabMenuContainer" data-active="{$activeTabMenuItem}" data-store="activeTabMenuItem" id="pageTabMenuContainer">
            <nav class="tabMenu">
                <ul>
                    <li><a href="{@$__wcf->getAnchor('quizData')}">{lang}wcf.acp.quizCreator.quiz.data{/lang}</a></li>
                    {if $questionList|isset && $questionList|count > 0}
                        <li><a href="{@$__wcf->getAnchor('questions')}">{lang}wcf.acp.quizCreator.question.list{/lang}</a></li>
                    {/if}
                    {if $formObject->goals > 0}
                        <li><a href="{@$__wcf->getAnchor('goals')}">{lang}wcf.acp.quizCreator.goal.list{/lang}</a></li>
                    {/if}
                    {event name='tabMenuTabs'}
                </ul>
            </nav>
            <div id="quizData" class="tabMenuContent">
    {/if}
{/capture}

{capture assign="__formContentFooter"}
    {if !$formObject|is_null && ($formObject->questions > 0 || $formObject->goals > 0)}
            </div>
            {if $questionList|isset && $questionList|count > 0}
                <div id="questions" class="tabMenuContent">
                    <div class="section tabularBox">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="columnID columnQuestionID" colspan="2">{lang}wcf.global.objectID{/lang}</th>
                                    <th class="columnTitle">{lang}wcf.acp.quizCreator.question{/lang}</th>
                                    <th class="columnDigits">{lang}wcf.acp.quizCreator.question.position{/lang}</th>
                                </tr>
                            </thead>
                            <tbody>
                                {foreach from=$questionList item=question}
                                    <tr class="jsQuestionRow">
                                        {capture assign=questionLink}{link controller="QuizQuestionEdit" id=$question->questionID}{/link}{/capture}
                                        <td class="columnIcon">
                                            <a href="{$questionLink}" title="{lang}wcf.global.button.edit{/lang}" class="jsTooltip">
                                                <span class="icon icon16 fa-pencil"></span>
                                            </a>
                                            <span class="icon icon16 fa-times jsDeleteButton jsTooltip pointer"
                                                  title="{lang}wcf.global.button.delete{/lang}"
                                                  data-object-id="{@$question->questionID}"
                                                  data-confirm-message-html="{lang __encode=true}wcf.acp.quizCreator.question.delete.confirmMessage{/lang}">
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

            {if $formObject->goals > 0}
                <div id="goals" class="tabMenuContent">
                    <div class="section tabularBox">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="columnID columnGoalID" colspan="2">{lang}wcf.global.objectID{/lang}</th>
                                    <th class="columnTitle">{lang}wcf.global.title{/lang}</th>
                                    <th class="columnDigits">{lang}wcf.acp.quizCreator.goal.points{/lang}</th>
                                </tr>
                            </thead>
                            <tbody>
                                {foreach from=$goalList item=goal}
                                    <tr class="jsGoalRow">
                                        {capture assign=goalLink}{link controller="QuizGoalEdit" id=$goal->goalID}{/link}{/capture}
                                        <td class="columnIcon">
                                            <a href="{$goalLink}" title="{lang}wcf.global.button.edit{/lang}" class="jsTooltip">
                                                <span class="icon icon16 fa-pencil"></span>
                                            </a>
                                            <span class="icon ico16 fa-times jsDeleteButton jsTooltip pointer"
                                                  title="{lang}wcf.global.button.delete{/lang}"
                                                  data-object-id="{@$goal->goalID}"
                                                  data-confirm-message-html="{lang __encode=true}wcf.acp.quizCreator.goal.delete.confirmMessage{/lang}">
                                            </span>
                                        </td>
                                        <td class="columnID columnGoalID">{#$goal->goalID}</td>
                                        <td class="columnTitle"><a href="{$goalLink}">{$goal->title}</a></td>
                                        <td class="columnDigits">{#$goal->points}</td>
                                    </tr>
                                {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>
            {/if}
        </div>
    {/if}
{/capture}

{include file='_quizFormBase'}
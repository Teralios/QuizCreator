{include file='header' pageTitle='wcf.acp.quizMaker.quiz.list'}

<script data-relocate="true">
    $(function() {
        new WCF.Action.Delete('wcf\\data\\quiz\\QuizAction', '.jsQuizRow');
        new WCF.Action.Toggle('wcf\\data\\quiz\\QuizAction', '.jsQuizRow');
    });
</script>
<header class="contentHeader">
    <div class="contentHeaderTitle">
        <h1 class="contentTitle">{lang}wcf.acp.menu.link.quiz.list{/lang}</h1>
    </div>

    <nav class="contentHeaderNavigation">
            <ul>
                <li><a href="{link controller="quizAdd"}{/link}" class="button"><span class="icon icon16 fa-plus"></span> <span>{lang}wcf.acp.quizMaker.add{/lang}</span></a></li>

                {event name='contentHeaderNavigation'}
            </ul>
    </nav>
</header>

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
                    <th class="columnQuizID" colspan="2">{lang}wcf.global.objectID{/lang}</th>
                    <th class="columnTitle">{lang}wcf.global.title{/lang}</th>
                    <th class="columnText">{lang}wcf.quizMaker.form.type{/lang}</th>
                    <th class="columnText">{lang}wcf.global.language{/lang}</th>
                    <th class="columnText">{lang}wcf.global.date{/lang}</th>
                    {event name="columnsHead"}
                </tr>
            </thead>
            <tbody>
                {foreach from=$objects item=quiz}
                    {* {capture var=quizLink}{/capture} do not work. So we must use this work arround *}
                    {assign var=quizLink value=''}
                    {capture append=quizLink}{link controller="QuizEdit" id=$quiz->quizID}{/link}{/capture}
                    <tr class="jsQuizRow">
                        <td class="columnIcon">
                            <span class="icon icon16 fa-{if $box->isActive}check-{/if}square-o jsToggleButton jsTooltip pointer" title="{lang}wcf.global.button.{if $box->isActive}disable{else}enable{/if}{/lang}" data-object-id="{@$quiz->quizID}"></span>

                            <a href="{$quizLink}" title="{lang}wcf.global.button.edit{/lang}" class="jsTooltip">
                                <span class="icon icon16 fa-pencil"></span>
                            </a>
                            {* We not need to check permission here, canManage is all. *}
                            <span class="icon icon16 fa-times jsDeleteButton jsTooltip pointer" title="{lang}wcf.global.button.delete{/lang}" data-object-id="{@$quiz->quizID}" data-confirm-message-html="{lang __encode=true}wcf.acp.quiz.delete.confirmMessage{/lang}"></span>
                        </td>
                        <td class="columnID columnQuizID">{@$quiz->quizID}</td>
                        <td class="columnTitle"><a href="{$quizLink}">{$quiz->title}</a></td>
                        <td class="columnText">{lang}wcf.acp.quizMaker.type.{@$quiz->type}{/lang}</td>
                        <td class="columnText"></td>
                        <td class="columnText">{$quiz->creationDate|date}</td>
                        {event name="columns"}
                    </tr>
                {/foreach}
            </tbody>
        </table>
    </div>

    <footer class="footerContent">
        {hascontent}
            <div class="paginationBottom">
                {content}
                    {@$pagesLinks}
                {/content}
            </div>
        {/hascontent}

        <nav class="contentFooterNavigation">
            <ul>
                <li><a href="{link controller='QuizAdd'}{/link}" class="button"><span class="icon icon16 fa-plus"></span> <span>{lang}wcf.acp.quizMaker.add{/lang}</span></a></li>

                {event name='contentFooterNavigation'}
            </ul>
        </nav>
    </footer>
{else}
    <p class="info">{lang}wcf.global.noItems{/lang}</p>
{/if}

{include file='footer'}
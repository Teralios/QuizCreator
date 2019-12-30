{include file='header' pageTitle='wcf.acp.quizMaker.quiz.'|concat:$action}

<header class="contentHeader">
    <div class="contentHeaderTitle">
        <h1 class="contentTitle">{if $action == 'add'}{lang}wcf.acp.quizMaker.quiz.add{/lang}{else}{lang}wcf.acp.quizMaker.quiz.edit{/lang}{/if}</h1>
    </div>

    <nav class="contentHeaderNavigation">
        <ul>
            <li><a href="{link controller='QuizList'}{/link}" class="button"><span class="icon icon16 fa-list"></span> <span>{lang}wcf.acp.menu.link.quizMaker.quiz.list{/lang}</span></a></li>

            {event name='contentHeaderNavigation'}
        </ul>
    </nav>
</header>

{@$form->getHtml()}

{include file='footer'}
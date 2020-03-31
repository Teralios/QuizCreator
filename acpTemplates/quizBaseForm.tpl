{include file='header' pageTitle=$formHeaderTitle}

{capture assign="navigationButtons"}
    {if !$quiz|is_null}
        <li>
            <a class="button" href="{link controller='QuizEdit' id=$quiz->quizID}{/link}">
                <span class="icon icon16 fa-question-circle"></span> <span>{lang}wcf.acp.quizMaker.quiz.back{/lang}</span>
            </a>
        </li>
    {/if}

    <li><a href="{link controller='QuizList'}{/link}" class="button"><span class="icon icon16 fa-list"></span> <span>{lang}wcf.acp.quizMaker.quiz.list{/lang}</span></a></li>
    {event name='navigationButtons'}
{/capture}

<header class="contentHeader">
    <div class="contentHeaderTitle">
        <h1 class="contentTitle">{$formTitle}</h1>
        <p class="contentHeaderDescription">{$quiz->title}</p>
    </div>

    <nav class="contentHeaderNavigation">
        <ul>
            {@$navigationButtons}
        </ul>
    </nav>
</header>

{@$form->getHtml()}

{include file='footer'}
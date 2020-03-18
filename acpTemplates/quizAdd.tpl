{include file='header' pageTitle='wcf.acp.quizMaker.'|concat:$action}

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

    <li><a href="{link controller='QuizList'}{/link}" class="button"><span class="icon icon16 fa-list"></span> <span>{lang}wcf.quizMaker.list{/lang}</span></a></li>
    {event name='navigationButtons'}
{/capture}

<header class="contentHeader">
    <div class="contentHeaderTitle">
        <h1 class="contentTitle">{if $action == 'add'}{lang}wcf.acp.quizMaker.add{/lang}{else}{lang}wcf.acp.quizMaker.edit{/lang}{/if}</h1>
    </div>

    <nav class="contentHeaderNavigation">
        <ul>
            {@$navigationButtons}
        </ul>
    </nav>
</header>

{@$form->getHtml()}

{if !$formObject|is_null && ($formObject->questions > 0 || $formObject->stages > 0)}
    <div class="section tabMenuContainer" data-active="{$activeTabMenuItem}" data-store="activeTabMenuItem" id="pageTabMenuContainer">
        <nav class="tabMenu">
            <ul>
                {if $formObject->questions > 0}
                    <li><a href="{@$__wcf->getAnchor('questions')}">{lang}wcf.acp.quizMaker.question.list{/lang}</a></li>
                {/if}
                {if $formObject->stages > 0 && $formObject->type == 'fun'}
                    <li><a href="{@$__wcf->getAnchor('stages')}">{lang}wcf.acp.quizMaker.stage.list{/lang}</a></li>
                {/if}
                {event name='tabMenuTabs'}
            </ul>
        </nav>

        {if $formObject->questions > 0}
            <div id="questions" class="tabMenuContent">
                <div class="section tabularBox">
                    <pre>{$questionList|var_dump}</pre>
                </div>
            </div>
        {/if}

        {if $formObject->stages > 0 && $formObject->type == 'fun'}
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
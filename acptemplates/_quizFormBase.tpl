{* __contentHeader *}
{capture assign="__contentHeader"}
    {if !$__formJavaScript|empty}
        {@$__formJavaScript}
    {/if}
    <header class="contentHeader">
        <div class="contentHeaderTitle">
            <h1 class="contentTitle">{lang}{$__formTitle}{/lang}</h1>
            {if !$__formTitleDescription|empty}
                <small>
                    {$__formTitleDescription}
                </small>
            {/if}
        </div>
        <nav class="contentHeaderNavigation">
            <ul>
                {if !$quiz|empty}
                    <li>
                        <a class="button" href="{link controller='QuizEdit' id=$quiz->quizID}{if !$__formAnchor|empty}{$__formAnchor}{/if}{/link}">
                            <span class="icon icon16 fa-question-circle"></span> <span>{lang}wcf.acp.quizCreator.quiz.back{/lang}</span>
                        </a>
                    </li>
                {/if}

                {if !$__formNavigationButtons|empty}
                    {@$__formNavigationButtons}
                {/if}
                <li><a href="{link controller='QuizList'}{/link}" class="button"><span class="icon icon16 fa-list"></span> <span>{lang}wcf.acp.quizCreator.quiz.list{/lang}</span></a></li>

                {event name='contentHeaderNavigation'}
            </ul>
        </nav>
    </header>
    {if !$__formSuccessMessage|empty}
        {@$__formSuccessMessage}
    {/if}
    {if !$__formSuccessMessage|empty}
        {@$__formContentHeader}
    {/if}
{/capture}

{* template *}
{if $isFrontend|empty}
    {include file='header' pageTitle=$__formTitle}
    {@$__contentHeader}
{else}
    {include file='header' pageTitle=$__formTitle contentHeader=$__contentHeader}
{/if}

{@$form->getHtml()}

{if !$__formContentFooter|empty}
    {@$__formContentFooter}
{/if}

{include file='footer'}
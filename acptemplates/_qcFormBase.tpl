{* navigation for header block *}
{capture assign="__headerNavigation"}
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
{/capture}

{* information block *}
{capture assign="__information"}
    {if !$__formSuccessMessage|empty}
        {@$__formSuccessMessage}
    {/if}
{/capture}

{* content *}
{capture assign="__contentBody"}
    {if !$__formHeader|empty}
        {@$__formHeader}
    {/if}

    {@$form->getHtml()}

    {if !$__formFooter|empty}
        {@$__formFooter}
    {/if}
{/capture}

{* include base template *}
{include file='_qcBase'}
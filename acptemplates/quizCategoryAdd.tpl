{assign var="__formTitle" value='wcf.acp.quizCreator.category.'|concat:$action}
{capture assign="__contentHeader"}
    <header class="contentHeader">
        <div class="contentHeaderTitle">
            <h1 class="contentTitle">{lang}{@$__formTitle}{/lang}</h1>
            {if !$__formTitleDescription|empty}
                <small>
                    {@$__formTitleDescription}
                </small>
            {/if}
        </div>
    </header>
{/capture}

{* template *}
{include file='header' pageTitle=$__formTitle}
{@$__contentHeader}

{@$form->getHtml()}

{include file='footer'}
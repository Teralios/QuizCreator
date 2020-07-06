{* sidebar *}
{capture assign='sidebarRight'}
    <section class="box">
        <h2 class="boxTitle">{lang}wcf.quizMaker.quizList.box.bestPlayers.quiz{/lang}</h2>

        <div class="boxContent">
            PLACEHOLDER
            {* @todo implement best players *}
        </div>
    </section>

    <section class="box">
        <h2 class="boxTitle">{lang}wcf.quizMaker.quizList.box.lastPlayers{/lang}</h2>

        <div class="boxContent">
            PLACEHOLDER
            {* @todo implement most played *}
        </div>
    </section>
{/capture}

{* variables *}
{assign var="pageTitle" value=$quiz->getTitle()}
{assign var="contentTitle" value=$quiz->getTitle()}
{capture assign="contentDescription"}
    <span
        class="icon icon16 {if $quiz->type == 'competition'}fa-trophy{else}fa-child{/if} jsTooltip"
        title="{lang}wcf.acp.quizMaker.quiz.type.{@$quiz->type}{/lang}">
    </span>
    {$quiz->description}
{/capture}

{* template *}
{include file='header'}



{include file='footer'}
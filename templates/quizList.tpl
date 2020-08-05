{* content header *}
{capture assign='headContent'}
    {if $pageNo < $pages}
        <link rel="next" href="{link controller='QuizList'}pageNo={@$pageNo+1}{/link}">
    {/if}
    {if $pageNo > 1}
        <link rel="prev" href="{link controller='QuizList'}{if $pageNo > 2}pageNo={@$pageNo-1}{/if}{/link}">
    {/if}
{/capture}

{* sidebar *}
{capture assign='sidebarRight'}
    <section class="box">
        <h2 class="boxTitle">{lang}wcf.quizMaker.quizList.box.bestPlayers.overall{/lang}</h2>

        <div class="boxContent">
            PLACEHOLDER
            {* @todo implement best players *}
        </div>
    </section>

    <section class="box">
        <h2 class="boxTitle">{lang}wcf.quizMaker.quizList.box.mostPlayed{/lang}</h2>

        <div class="boxContent">
            PLACEHOLDER
            {* @todo implement most played *}
        </div>
    </section>
{/capture}

{capture assign='linkParameters'}&sortField={$sortField}&sortOrder={$sortOrder}{if !$languageID|empty}&languageID={$languageID}{/if}{/capture}

{* variables *}
{assign var="showQuizMakerCopyright" value=true}

{* template *}
{include file='header'}

{hascontent}
    <div class="paginationTop">
        {content}
            {pages print=true assign='pagesLinks' controller='QuizList' link='pageNo=%d&$linkParameters'}
        {/content}
    </div>
{/hascontent}

{if $objects|count}
    <div class="section tabularBox messageGroupList">
        <ol class="tabularList">
            <li class="tabularListRow tabularListRowHead">
                <ol class="tabularListColumns">
                    <li class="columnSort">
                        <ul class="inlineList">
                            <li>
                                <a rel="nofollow" href="{link controller='QuizList'}pageNo={@$pageNo}&sortField={$sortField}&sortOrder={if $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">
                                    <span class="icon icon16 fa-sort-amount-{$sortOrder|strtolower} jsTooltip" title="{lang}wcf.global.sorting{/lang} ({lang}wcf.global.sortOrder.{if $sortOrder === 'ASC'}ascending{else}descending{/if}{/lang})"></span>
                                </a>
                            </li>
                            <li>
                                <div class="dropdown">
                                    <span class="dropdownToggle">{if $sortField == 'title'}{lang}wcf.global.title{/lang}{else}{lang}wcf.quizMaker.{$sortField}{/lang}{/if}</span>

                                    <ul class="dropdownMenu">
                                        {foreach from=$validSortFields item=_sortField}
                                            <li{if $_sortField === $sortField} class="active"{/if}>
                                                <a rel="nofollow" href="{link controller='QuizList'}pageNo={@$pageNo}{if !$languageID|empty}&languageID={$languageID}{/if}&sortField={$_sortField}&sortOrder={if $sortField === $_sortField}{if $sortOrder === 'DESC'}ASC{else}DESC{/if}{else}{$sortOrder}{/if}{/link}">
                                                    {if $_sortField == 'title'}
                                                        {lang}wcf.global.title{/lang}
                                                    {else}
                                                        {lang}wcf.quizMaker.{$_sortField}{/lang}
                                                    {/if}
                                                </a>
                                            </li>
                                        {/foreach}
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ol>
            </li>

            {foreach from=$objects item=quiz}
                <li class="tabularListRow">
                    <ol class="tabularListColumns">
                        <li class="columnIcon">
                            <span
                                    class="icon icon32 {if $quiz->type == 'competition'}fa-trophy{else}fa-child{/if} jsTooltip"
                                    title="{lang}wcf.acp.quizMaker.quiz.type.{@$quiz->type}{/lang}">
                            </span>
                        </li>

                        <li class="columnSubject">
                            <h3><a href="{$quiz->getLink()}">{$quiz->title}</a></h3>
                            <small>{@$quiz->creationDate|time}</small>
                        </li>

                        <li class="columnStats">
                            <dl class="plain statsDataList">
                                <dt>{lang}wcf.quizMaker.questions{/lang}</dt>
                                <dd>{@$quiz->questions|shortUnit}</dd>
                            </dl>
                            <dl class="plain statsDataList">
                                <dt>{lang}wcf.quizMaker.players{/lang}</dt>
                                <dd>{@$quiz->players|shortUnit}</dd>
                            </dl>
                            {if $quiz->playery > 0}
                                <dl class="plain statsDataList">
                                    <dt>{lang}wcf.quizMaker.averageScore{/lang}</dt>
                                    <dd>{($quiz->score / $quiz->players)|shortUnit}</dd>
                                </dl>
                            {/if}
                            <div class="messageGroupListStatsSimple" aria-label="{lang}wcf.quizMaker.questions{/lang}"><span class="icon icon16 fa-question-circle"></span> {@$quiz->questions|shortUnit}</div>
                        </li>
                        {if !$quiz->languageID|empty}
                            <li class="columnIcon">
                                <a class="jsTooltip" href="{link controller='QuizList'}languageID={$quiz->languageID}{/link}" title="{lang}wcf.quizMaker.language{/lang}">
                                    <img class="iconFlag" src="{$quiz->getLanguageIcon()}">
                                </a>
                            </li>
                        {/if}

                        {* @TODO implent last player and higehst score *}
                        {event name='columns'}
                    </ol>
                </li>
            {/foreach}
        </ol>
    </div>
{else}
    <p class="info" role="status">{lang}wcf.global.noItems{/lang}</p>
{/if}

<footer class="contentFooter">
    {hascontent}
        <div class="paginationBottom">
            {content}{@$pagesLinks}{/content}
        </div>
    {/hascontent}

    {hascontent}
        <nav class="contentFooterNavigation">
            <ul>
                {content}{event name='contentFooterNavigation'}{/content}
            </ul>
        </nav>
    {/hascontent}
</footer>

{include file='footer'}
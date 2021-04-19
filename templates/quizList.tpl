{* content header *}
{capture assign='headContent'}
    {if $pageNo < $pages}
        <link rel="next" href="{link controller='QuizList'}pageNo={@$pageNo+1}{/link}">
    {/if}
    {if $pageNo > 1}
        <link rel="prev" href="{link controller='QuizList'}{if $pageNo > 2}pageNo={@$pageNo-1}{/if}{/link}">
    {/if}
{/capture}

{* link *}
{capture assign='linkParameters'}&sortField={$sortField}&sortOrder={$sortOrder}{if !$languageID|empty}&languageID={$languageID}{/if}{/capture}

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
    <div class="section quizList">
        {foreach from=$objects item="quiz"}
            {assign var="media" value=$quiz->getMedia()}
            <div class="quiz{if $quiz->isActive} isActive{else} notActive{/if}" data-object-id="{#$quiz->quizID}">
                <a href="{$quiz->getLink()}">
                    <div class="quizInner{if !$quiz->isActive} quizNotActive{/if}">
                        <div class="quizBase">
                            <div class="quizBaseInner">
                                <div class="quizBaseIcon">
                                </div>
                            </div>
                        </div>
                        {if $media}
                            <div class="quizImage">
                                {@$media->getThumbnailTag('small')}
                            </div>
                        {/if}
                        <div class="quizTitle">
                            <h3>{$quiz->getTitle()}</h3>
                        </div>
                        <div class="quizInfo">
                            <div>
                                {if $quiz->hasPlayed()}
                                    <span class="icon icon16 fa-check jsTooltip" title="{lang}wcf.quizCreator.user.played{/lang}"></span>
                                    {else}
                                    <span class="icon icon16 fa-circle-o jsTooltip" title="{lang}wcf.quizCreator.user.noPlayed{/lang}"></span>
                                {/if}
                                <span class="small">{@$quiz->creationDate|time}</span>
                            </div>
                            <div>
                                <span class="jsTooltip" title="{lang}wcf.quizCreator.stats.questions{/lang}"><span class="icon icon16 fa-question-circle-o"></span> {#$quiz->questions}</span>
                                <span class="jsTooltip separatorLeft" title="{lang}wcf.quizCreator.stats.players{/lang}"><span class="icon icon16 fa-users"></span> {if $quiz->playery > 0}{$quiz->players}{else}0{/if}</span>
                                {if !$quiz->languageID|empty}
                                    <span class="separatorLeft"><img class="iconFlag jsTooltip" title="{lang}wcf.quizCreator.quiz.language{/lang}" src="{$quiz->getLanguageIcon()}"></span>
                                {/if}
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        {/foreach}
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

<script data-relocate="true">
    require(['WoltLabSuite/Core/Controller/Popover'], function(ControllerPopover) {
        ControllerPopover.init({
            className: 'quiz',
            dboAction: 'wcf\\data\\quiz\\QuizAction',
            identifier: 'de.teralios.quizCreator.quiz'
        });
    });
</script>

{include file='footer'}
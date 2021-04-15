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
    {* categories *}
    <section class="box">
        <h2 class="boxTitle">{lang}wcf.quizCreator.box.categories{/lang}</h2>
        <div class="boxContent">
            <ol class="boxMenu">
                {foreach from=$categoryList item=categoryItem}
                    {if $categoryItem->isVisibleInNestedList($activeCategory)}
                        <li class="boxMenuItem boxMenuItemDepth{@$categoryItem->getDepth()}{if $activeCategory && $activeCategory->categoryID == $categoryItem->categoryID} active{/if}" data-category-id="{@$categoryItem->categoryID}">
                           <a href="{@$categoryItem->getLink()}" class="boxMenuLink">
                                <span class="boxMenuLinkTitle">{$categoryItem->getTitle()}</span>
                            </a>
                        </li>
                    {/if}
                {/foreach}

                {if $activeCategory}
                    <li class="boxMenuResetFilter">
                        <a href="{link controller='QuizList'}{/link}" class="boxMenuLink">
                            <span class="boxMenuLinkTitle">{lang}wcf.global.button.resetFilter{/lang}</span>
                        </a>
                    </li>
                {/if}
            </ol>
        </div>
    </section>

    {* default boxes *}
    {include file='__quizSidebarBoxes'}
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
    <div class="quizList">
        {foreach from=$objects item="quiz"}
            {assign var="media" value=$quiz->getMedia()}
            <div class="quiz">
                <a href="{$quiz->getLink()}">
                    <div class="quizInner">
                        <div class="quizBase">
                            <div class="quizBaseInner">
                                <div class="quizBaseInnerIcon">
                                </div>
                            </div>
                        </div>
                        <div class="quizImage">
                            {@$media->getThumbnailTag('small')}
                        </div>
                        <div class="quizTitle">
                            {$quiz->title}
                        </div>
                        <div class="quizInfo">
                            Text
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

{include file='footer'}
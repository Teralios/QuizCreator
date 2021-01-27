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
    <b>TODO: New Quiz style.</b>
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
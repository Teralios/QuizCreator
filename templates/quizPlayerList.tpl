{* variables *}
{capture assign='pageTitle'}{$quiz->getTitle()}{/capture}
{capture assign='contentTitle'}{$quiz->getTitle()}{/capture}

{* template *}
{include file='header'}

{include file='__quizInformation'}

{hascontent}
    <div class="paginationTop">
        {content}
        {pages print=true assign='pagesLinks' controller='QuizPlayerList' id=$quiz->quizID link='pageNo=%d&$linkParameters'}
        {/content}
    </div>
{/hascontent}

<div class="section tabularList">
    <pre>
    {$objects|print_r}
    </pre>
</div>

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
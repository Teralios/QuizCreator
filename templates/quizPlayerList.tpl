{* variables *}
{capture assign='pageTitle'}{lang}wcf.quizCreator.quiz.playerList{/lang} {if $pageNo > 1}- {lang}wcf.page.pageNo{/lang} {$pageNo}{/if}- {$quiz->getTitle()}{/capture}
{capture assign='contentTitle'}{lang}wcf.quizCreator.quiz.playerList{/lang}{/capture}
{capture assign='contentDescription'}<a href="{link controller='Quiz' object=$quiz}{/link}">{$quiz->getTitle()}</a>{/capture}

{* template *}
{include file='header'}

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
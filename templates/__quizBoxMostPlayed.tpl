{if $mostPlayed !== null && $mostPlayed|count > 0}
    <ul class="sidebarItemList">
        {foreach from=$mostPlayed item=$quiz}
            {assign var="media" value=$quiz->getMedia()}
            <li class="box24">
                {if $media !== null}
                    {@$media->getElementTag(24)}
                {else}
                    <span class="icon icon24 fa-question-circle"></span>
                {/if}
                <div class="sidebarItemTitle">
                    <h3>{anchor object=$quiz->getDecoratedObject()}</h3>
                    <small>{lang}wcf.quizCreator.stats.played.detail{/lang}</small>
                </div>
            </li>
        {/foreach}
    </ul>
{/if}
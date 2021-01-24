{include file='header' pageTitle=$__title}

{if !$__javascript|empty}
    {@$__javascript}
{/if}

<header class="contentHeader">
    <div class="contentHeaderTitle">
        <h1 class="contentTitle">{lang}{@$__title}{/lang}</h1>
        {if !$__titleDescription|empty}
            <small>
                {@$__titleDescription}
            </small>
        {/if}
    </div>
    {if !$__headerNavigation|empty}
        {@$__headerNavigation}
    {/if}
</header>

{if !$__information|empty}
    {@$__information}
{/if}

{@$__contentBody}

{if !$__contentFooter|empty}
    {@$__contentFooter}
{/if}

{include file='footer'}
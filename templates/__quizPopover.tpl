<div class="box128">
    <a href="{$quiz->getLink()}" class="jsTooltip" title="{$quiz->getTitle()} ({lang}wcf.quizCreator.quiz.type.{@$quiz->type}{/lang}{if $quiz->isActive == 0} ({lang}wcf.acp.quizCreator.quiz.notActive{/lang}){/if})">
        {if $quiz->getMedia() !== null}
            {@$quiz->getMedia()->getElementTag(128)}
        {else}
            <span class="icon icon128 {if $quiz->isActive == 0}fa-pencil{else}{if $quiz->type == 'competition'}fa-trophy{else}fa-child{/if}{/if}"></span>
        {/if}
    </a>
    <div class="quizPreview">
        <header><h3>{anchor object=$quiz->getDecoratedObject()}</h3></header>
        <div class="htmlContent">
            {@$quiz->getPreview()}
        </div>
        <footer>
            <div>
                <span>{lang}wcf.quizCreator.stats.questions.detail{/lang}</span>
                <span class="separatorLeft">{lang}wcf.quizCreator.stats.played.detail{/lang}</span>
            </div>
        </footer>
    </div>
</div>

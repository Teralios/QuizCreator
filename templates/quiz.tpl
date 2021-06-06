{* variables *}
{capture assign='pageTitle'}{$quiz->getTitle()}{/capture}
{capture assign='quizAnchor'}{anchor object=$quiz}{/capture}
{assign var="__disableContentHeader" value=true}
{assign var="media" value=$quiz->getMedia()}

{* template *}
{include file='header'}
<div class="quizContainer">
    <div id="quiz" class="quiz {if $quiz->isActive}isActive{else}notActive{/if}" data-quiz-id="{#$quiz->quizID}">
        <div class="head">
            <div class="headInner">
                <div class="headIconContainer">
                    <div class="headIconContainerInner">
                        <div class="headIcon"></div>
                    </div>
                </div>
                <div class="headTitleContainer">
                    <h1>{$quiz->getTitle()}</h1>
                    <div class="titleStats">
                        <ul class="inlineList">
                            <li><span class="icon icon16 fa-clock-o"></span> {$quiz->creationDate|date}</li>
                            <li><span class="icon icon16 fa-question-circle-o"></span> {#$quiz->questions} {lang}wcf.quizCreator.stats.questions{/lang}</li>
                            <li itemprop="interactionStatistic" itemscope="" itemtype="http://schema.org/InteractionCounter"><span class="icon icon16 fa-gamepad"></span> {#$quiz->played} {lang}wcf.quizCreator.stats.played{/lang}</li>
                            <li itemprop="interactionStatistic" itemscope="" itemtype="http://schema.org/InteractionCounter"><span class="icon icon16 fa-users"></span> {#$quiz->players} {lang}wcf.quizCreator.stats.players{/lang}</li>
                        </ul>
                    </div>
                </div>
                {if $media !== null}
                    <div class="headImageContainer">
                        {@$media} {* here normal image tag is better for display adjustments *}
                    </div>
                    {if $media->caption}
                        <div class="headImageCaptionContainer">
                            <div class="imageCaption">
                                {if $media->captionEnableHtml}{@$media->caption}{else}{$media->caption}{/if}
                            </div>
                        </div>
                    {/if}
                {/if}
            </div>
        </div>
        <div class="description">
            {@$quiz->getDescription()}
        </div>
        <div class="game"></div>
        {if !$tags|empty}
            <div class="tags">
                <ul class="tagList">
                    {foreach from=$tags item=tag}
                        <li><a href="{link controller='Tagged' object=$tag}de.teralios.quizCreator.quiz{/link}" class="tag">{$tag}</a></li>
                    {/foreach}
                </ul>
            </div>
        {/if}
    </div>
</div>

<script type="application/json" id="js-QuizCreator-Language">
    {
        "wcf.quizCreator.game": "{'test'|encodeJSON}"
    }
</script>

{js application='wcf' file='QuizCreator'}

{include file='footer'}
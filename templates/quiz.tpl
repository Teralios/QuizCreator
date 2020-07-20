{* sidebar *}
{capture assign='sidebarRight'}
    <section class="box">
        <h2 class="boxTitle">{lang}wcf.quizMaker.quiz.box.bestPlayers{/lang}</h2>

        <div class="boxContent">
            PLACEHOLDER
            {* @todo implement best players *}
        </div>
    </section>

    <section class="box">
        <h2 class="boxTitle">{lang}wcf.quizMaker.quiz.box.lastPlayers{/lang}</h2>

        <div class="boxContent">
            PLACEHOLDER
            {* @todo implement most played *}
        </div>
    </section>
{/capture}

{* variables *}
{assign var="pageTitle" value=$quiz->getTitle()}
{assign var="contentTitle" value=$quiz->getTitle()}
{assign var="showQuizMakerCopyright" value=true}

{* template *}
{include file='header'}

<div class="section quiz" id="quiz{$quiz->quizID}" data-id="{$quiz->quizID}">
    <div class="info">
        {assign var="media" value=$quiz->getMedia()}
        {if !$media|is_null}
            <div class="image">
                {@$media}
            </div>
        {/if}
        <div class="description">
            {@$quiz->getDescription()}
        </div>
    </div>
</div>

<script data-relocate="true">
    require(['Teralios/QuizMaker/Quiz', 'Language'], function (Quiz, Language) {
        new Quiz(elById('quiz{$this->quizID}'));
    });
</script>


{include file='footer'}
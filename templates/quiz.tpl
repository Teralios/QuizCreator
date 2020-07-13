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

{* template *}
{include file='header'}

<div class="section quiz">
    <div class="quizHeader">
        {assign var="media" value=$quiz->getMedia()}
        {if !$media|is_null}
            <div class="quizImage">
                {@$media->getThumbnailTag('small')}
            </div>
        {/if}
        <div class="quizDescription">
            {$quiz->getDescription()}
        </div>
    </div>
    <div class="quizGame">
        <div class="quizGameHeader">
            <div class="questionCounter">Fragen: 1/10</div>
            <div class="questionTime">Zeit: 0:00</div>
            <div class="questionScore">+10 Punkte</div>
        </div>
        <div class="quizGameContent">
            <button>{lang}wcf.quizMaker.quiz.start{/lang}</button>

            <div class="question">Hier könnte ihre Frage stehen, na wie wäre es?</div>
            <ul class="answerList">
                <li><button class="answerA" disabled>Antwort 1</button></li>
                <li><button class="answerB" disabled>Antwort 2 Lange</button></li>
                <li><button class="answerC correctAnswer" disabled>Antwort 3 noch Länger</button></li>
                <li><button class="answerD wrongAnswer" disabled>Antwort 4 Test</button></li>
            </ul>
        </div>
        <div class="quizGameFooter"><button class="nextQuestion">nächste Frage</button></div>
    </div>
</div>


{include file='footer'}
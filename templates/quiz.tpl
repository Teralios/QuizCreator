{capture assign='sidebarRight'}
    <section class="box">
        <h2 class="boxTitle">{lang}wcf.quizMaker.quizList.box.bestPlayers.quiz{/lang}</h2>

        <div class="boxContent">
            PLACEHOLDER
            {* @todo implement best players *}
        </div>
    </section>

    <section class="box">
        <h2 class="boxTitle">{lang}wcf.quizMaker.quizList.box.lastPlayers{/lang}</h2>

        <div class="boxContent">
            PLACEHOLDER
            {* @todo implement most played *}
        </div>
    </section>
{/capture}

{include file='header' pageTitle=$quiz->getTitle()}

Quiz!

{include file='footer'}
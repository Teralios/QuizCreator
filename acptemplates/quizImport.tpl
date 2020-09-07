{assign var="__formTitle" value='wcf.acp.quizCreator.quiz.import'}

{include file='header' pageTitle=$__formTitle}

<header class="contentHeader">
    <div class="contentHeaderTitle">
        <h1 class="contentTitle">{lang}{@$__formTitle}{/lang}</h1>
    </div>
</header>

{@$form->getHtml()}

{include file='footer'}
{assign var="__formTitle" value='wcf.acp.quizMaker.goal.'|concat:$action}
{assign var="__formAnchor" value="#goals"}
{assign var="__formTitleDescription" value=$quiz->getTitle()}

{include file='_quizFormBase'}
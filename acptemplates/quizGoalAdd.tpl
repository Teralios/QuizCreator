{assign var="__formTitle" value='wcf.acp.quizCreator.goal.'|concat:$action}
{assign var="__formAnchor" value="#goals"}
{capture assign='__formTitleDescription'}{$formObject->getTitle()}{/capture}

{include file='_quizFormBase'}
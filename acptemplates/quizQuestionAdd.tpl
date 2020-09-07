{assign var="__formTitle" value='wcf.acp.quizCreator.question.'|concat:$action}
{assign var="__formAnchor" value="#questions"}
{capture assign='__formTitleDescription'}{$formObject->getTitle()}{/capture}

{include file='_quizFormBase'}
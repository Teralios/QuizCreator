{assign var="__title" value='wcf.acp.quizCreator.question.'|concat:$action}
{assign var="__formAnchor" value="#questions"}
{capture assign='__titleDescription'}{$quiz->getTitle()}{/capture}

{include file='_qcFormBase'}
{assign var="__title" value='wcf.acp.quizCreator.goal.'|concat:$action}
{assign var="__formAnchor" value="#goals"}
{capture assign='__titleDescription'}{$quiz->getTitle()}{/capture}

{include file='_qcFormBase'}
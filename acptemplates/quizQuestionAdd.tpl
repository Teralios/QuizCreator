{assign var="__formTitle" value='wcf.acp.quizCreator.question.'|concat:$action}
{assign var="__formAnchor" value="#questions"}
{assign var="__formTitleDescription" value=$quiz->getTitle()}

{include file='_quizFormBase'}
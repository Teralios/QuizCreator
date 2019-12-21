<?php

declare(strict_types=1);

namespace WCF\Quizmaker\Data\Quiz;

// imports
use WCF\Data\DatabaseObjectEditor;

class QuizEditor extends DatabaseObjectEditor
{
    protected static $baseClass = Quiz::class;
}

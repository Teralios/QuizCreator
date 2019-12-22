<?php

declare(strict_types=1);

namespace wcf\data\quiz;

// imports
use wcf\data\DatabaseObjectEditor;

class QuestionEditor extends DatabaseObjectEditor
{
    protected static $baseClass = Question::class;
}

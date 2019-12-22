<?php

declare(strict_types=1);

namespace wcf\data\quiz;

// imports
use wcf\data\AbstractDatabaseObjectAction;

class QuizAction extends AbstractDatabaseObjectAction
{
    protected $className = QuizEditor::class;
    protected $permissionsCreate = [];

    public function create()
    {
        $data = $this->parameters['data'];
        

        return parent::create();
    }
}

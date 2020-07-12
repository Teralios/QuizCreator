<?php

namespace wcf\data\quiz;

// imports
use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\IToggleAction;
use wcf\system\database\exception\DatabaseQueryException;
use wcf\system\database\exception\DatabaseQueryExecutionException;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\file\upload\UploadFile;
use wcf\system\WCF;

/**
 * Class QuizAction
 *
 * @package   de.teralios.quizMaker
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class QuizAction extends AbstractDatabaseObjectAction implements IToggleAction
{
    // inherit vars
    protected $className = QuizEditor::class;
    protected $permissionsCreate = ['admin.content.quizMaker.canManage'];
    protected $permissionsUpdate = ['admin.content.quizMaker.canManage'];
    protected $permissionsDelete = ['admin.content.quizMaker.canManage'];
    protected $permissionsToggle = ['admin.content.quizMaker.canManage'];
    protected $allowGuestAccess = ['getQuizData'];

    /**
     * @var Quiz
     */
    protected $quiz = null;

    /**
     * @inheritDoc
     */
    public function create()
    {
        // set timestamp
        $this->parameters['data']['creationDate'] = TIME_NOW;

        // create database entry
        return parent::create();
    }

    /**
     * @inheritDoc
     * @throws PermissionDeniedException
     */
    public function validateToggle()
    {
        WCF::getSession()->checkPermissions($this->permissionsToggle);
    }

    /**
     * @inheritDoc
     */
    public function toggle()
    {
        if (empty($this->objects)) {
            $this->readObjects();
        }

        foreach ($this->objects as $quiz) {
            $quiz->toggle();
        }
    }
}

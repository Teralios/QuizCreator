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
     * @throws DatabaseQueryException | DatabaseQueryExecutionException
     */
    public function create()
    {
        // set timestamp
        $this->parameters['data']['creationDate'] = TIME_NOW;

        // create database entry
        $quiz = parent::create();

        // save image
        if (count($this->parameters['image']) > 0) {
            $image = $this->parameters['image'][0];

            $this->saveImage($image, $quiz->getObjectID());
        }

        return $quiz;
    }

    /**
     * @inheritDoc
     * @throws DatabaseQueryException | DatabaseQueryExecutionException
     */
    public function update()
    {
        parent::update();

        $this->updateImages();
    }

    /**
     * @inheritDoc
     */
    public function delete()
    {
        $returnValue = parent::delete();

        // delete image files
        foreach ($this->objects as $quiz) {
            /** @var Quiz $quiz */
            $file = $quiz->getImage(false);
            if (!empty($file)) {
                /** @scrutinizer ignore-unhandled */@unlink($file);
            }
        }

        return $returnValue;
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

    /**
     * Save image.
     * @param UploadFile $image
     * @param int $quizID
     * @param bool $useCopy
     * @return string
     * @throws DatabaseQueryException | DatabaseQueryExecutionException
     */
    protected function saveImage(UploadFile $image, int $quizID, bool $useCopy = false): string
    {
        // process file
        $fileName = QuizEditor::getImageFileName($quizID, $image);
        $newFileLocation = WCF_DIR . $fileName;

        if ($useCopy === true) {
            copy($image->getLocation(), $newFileLocation);
        } else {
            rename($image->getLocation(), $newFileLocation);
            $image->setProcessed($newFileLocation);
        }

        // update quiz
        $sql = "UPDATE " . Quiz::getDatabaseTableName() . ' SET image = ? WHERE quizID = ?';
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([$fileName, $quizID]);

        return $newFileLocation;
    }

    /**
     * Update image.
     * @throws DatabaseQueryException | DatabaseQueryExecutionException
     */
    public function updateImages()
    {
        if (count($this->parameters['image_removedFiles'])) {
            $id = null;
            $image = $this->parameters['image_removedFiles'][0];

            foreach ($this->objects as $quiz) {
                if ($quiz->getImage(false) == $image->getLocation()) {
                    /** @scrutinizer ignore-unhandled */@unlink($image->getLocation());
                    $id = $quiz->getObjectID();
                    break;
                }
            }

            $sql = "UPDATE " . Quiz::getDatabaseTableName() . ' SET image = ? WHERE quizID = ?';
            $statement = WCF::getDB()->prepareStatement($sql);
            $statement->execute(['', $id]);
        }

        // current form only supports one file. "updateAction"
        if (count($this->parameters['image'])) {
            /** @var UploadFile $image */
            $image = $this->parameters['image'][0];
            $newFileLocation = '';

            // update images on objects.
            foreach ($this->objectIDs as $objectID) {
                $newFileLocation = $this->saveImage($image, $objectID, true);
            }

            // delete tmp file
            /** @scrutinizer ignore-unhandled */@unlink($image->getLocation());
            $image->setProcessed($newFileLocation);
        }
    }
}

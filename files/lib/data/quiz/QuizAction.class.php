<?php
namespace wcf\data\quiz;

// imports
use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\DatabaseObject;
use wcf\system\database\exception\DatabaseQueryExecutionException;
use wcf\system\database\exception\DatabaseQueryException;
use wcf\system\file\upload\UploadFile;
use wcf\system\WCF;
use wcf\util\FileUtil;
use wcf\util\ImageUtil;

class QuizAction extends AbstractDatabaseObjectAction
{
    /**
     * @var string
     */
    protected $className = QuizEditor::class;

    /**
     * @var array
     */
    protected $permissionsCreate = [];

    /**
     * @inheritDoc
     */
    public function create()
    {
        $quiz = parent::create();

        if (count($this->parameters['image']) > 0) {
            $image = $this->parameters['image'][0];

            $this->saveImage($image, $quiz->getObjectID());
        }

        return $quiz;
    }

    /**
     * @inheritDoc
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
            if (!empty($quiz->getImage(false))) {
                @unlink($quiz->getImage(false));
            }
        }

        return $returnValue;
    }

    /**
     * @param UploadFile $image
     * @param int $quizID
     * @param bool $useCopy
     * @return string
     * @throws DatabaseQueryException
     * @throws DatabaseQueryExecutionException
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
     * @throws DatabaseQueryException
     * @throws DatabaseQueryExecutionException
     */
    public function updateImages()
    {
        if (count($this->parameters['image_removedFiles'])) {
            $id = null;
            $image = $this->parameters['image_removedFiles'][0];

            foreach ($this->objects as $quiz) {
                if ($quiz->getImage(false) == $image->getLocation()) {
                    @unlink($image->getLocation());
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
            @unlink($image->getLocation());
            $image->setProcessed($newFileLocation);
        }
    }
}

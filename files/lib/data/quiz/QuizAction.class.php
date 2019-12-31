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
     * @return DatabaseObject
     * @throws DatabaseQueryException
     * @throws DatabaseQueryExecutionException
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

    public function update()
    {
        // @todo add uploaded image to parameters for update.
        parent::update();
        // @todo set processed to true for uploaded image.
    }

    /**
     * @param UploadFile $image
     * @param int $quizID
     * @throws DatabaseQueryException
     * @throws DatabaseQueryExecutionException
     */
    protected function saveImage(UploadFile $image, int $quizID)
    {
        // process file
        $fileName = QuizEditor::getImageFileName($quizID, $image);
        $newFileLocation = WCF_DIR . $fileName;
        rename($image->getLocation(), $newFileLocation);
        $image->setProcessed($newFileLocation);

        // update quiz
        $sql = "UPDATE " . Quiz::getDatabaseTableName() . ' SET image = ? WHERE quizID = ?';
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([$fileName, $quizID]);
    }
}

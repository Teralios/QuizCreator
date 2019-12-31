<?php

declare(strict_types=1);

namespace wcf\data\quiz;

// imports
use wcf\data\AbstractDatabaseObjectAction;
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
     * @return void|\wcf\data\DatabaseObject
     * @throws \wcf\system\database\exception\DatabaseQueryException
     * @throws \wcf\system\database\exception\DatabaseQueryExecutionException
     */
    public function create()
    {
        $quiz = parent::create();

        if (count($this->parameters['image']) > 0) {
            $image = $this->parameters['image'][0];

            $this->saveImage($image, $quiz->getOBjectID());
        }
    }

    /**
     * @param UploadFile $image
     * @param int $quizID
     * @throws \wcf\system\database\exception\DatabaseQueryException
     * @throws \wcf\system\database\exception\DatabaseQueryExecutionException
     */
    protected function saveImage(UploadFile $image, int $quizID)
    {
        $newFileName = 'quiz_' . $quizID . '.' . ImageUtil::getExtensionByMimeType(FileUtil::getMimeType($image->getLocation()));
        copy($image->getLocation(), WCF_DIR . Quiz::IMAGE_DIR . $newFileName);

        // update sql
        $sql = "UPDATE " . Quiz::getDatabaseTableName() . ' SET image = ? WHERE quizID = ?';
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([$newFileName, $quizID]);
    }
}

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
    protected $className = QuizEditor::class;
    protected $permissionsCreate = [];

    public function create()
    {
        $quiz = parent::create();

        if (count($this->parameters['image']) > 0) {
            $image = $this->parameters['image'][0];

            $this->saveImage($image, $quiz->getOBjectID());
        }
    }

    protected function saveImage(UploadFile $image, $quizID)
    {
        $newFileName = 'images/quizmaker/quiz_' . $quizID . '.' . ImageUtil::getExtensionByMimeType(FileUtil::getMimeType($image->getLocation()));

        // copy file
        copy($image->getLocation(), WCF_DIR . $newFileName);

        $sql = "UPDATE " . Quiz::getDatabaseTableName() . ' SET hasImage = ? WHERE quizID = ?';
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([1, $quizID]);
    }
}

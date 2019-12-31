<?php

namespace wcf\data\Quiz;

// imports
use wcf\data\DatabaseObject;
use wcf\system\WCF;

/**
 * Class QuizData
 *
 * @package   de.teralios.QuizMaker
 * @author    Teralios
 * @copyright ©2019 Teralios.de
 * @license   CC BY-SA 4.0 <https://creativecommons.org/licenses/by-sa/4.0/>
 *
 * @property-read int $quizID
 * @property-read string $title
 * @proberty-read string $description
 * @property-read string $type
 * @property-read string $image
 */
class Quiz extends DatabaseObject
{
    const IMAGE_DIR = 'images/quizmaker/';
    protected static $databaseTableName = 'quiz';
    protected static $databaseTableIndexName = 'quizID';

    public function getImage()
    {
        return (!empty($this->image)) ? [WCF::getPath() . Quiz::IMAGE_DIR . $this->image] : []; // compatibility with UploadField from form builder.
    }
}

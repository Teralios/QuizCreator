<?php
namespace wcf\data\Quiz;

// imports
use wcf\data\DatabaseObjectEditor;
use wcf\system\file\upload\UploadFile;
use wcf\util\FileUtil;
use wcf\util\ImageUtil;

/**
 * Class QuizEditor
 *
 * @package   de.teralios.QuizMaker
 * @author    Teralios
 * @copyright ©2019 Teralios.de
 * @license   CC BY-SA 4.0 <https://creativecommons.org/licenses/by-sa/4.0/>
 *
 * @method string getImage(bool $usePath)
 */
class QuizEditor extends DatabaseObjectEditor
{
    protected static $baseClass = Quiz::class;

    public function update(array $parameters = [])
    {
        // prepare images
        /** @var UploadFile $image */
        if (isset($parameters['image']) && $parameters['image'] instanceof UploadFile) {
            $image = $parameters['image'];

            // make a copy from uploaded image
            if ($image->getLocation() !== $this->getImage(false)) {
                $parameters['image'] = $this->updateImage($image);
            }
        } elseif (isset($parameters['removeImage']) && $this->getImage(false) == $parameters['removeImage']) {
            echo 'Test';
            exit;
            $this->deleteImage();
            $parameters['image'] = '';
            unset($parameters['removeImage']);
        }

        parent::update($parameters);
    }

    protected function updateImage(UploadFile $image): string
    {
        $newImage = static::getImageFileName($this->getObjectID(), $image);
        @copy($image->getLocation(), WCF_DIR . $newImage);

        return $newImage;
    }

    protected function deleteImage()
    {
        if (!empty($this->getImage())) {
            @unlink($this->getImage(false));
        }
    }

    public static function getImageFileName(int $quizID, UploadFile $image): string
    {
        return Quiz::IMAGE_DIR . 'quiz_' . $quizID . '.' . ImageUtil::getExtensionByMimeType(FileUtil::getMimeType($image->getLocation()));
    }
}

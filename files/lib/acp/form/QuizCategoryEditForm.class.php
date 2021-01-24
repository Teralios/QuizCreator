<?php

namespace wcf\acp\form;

use wcf\data\quiz\category\Category;
use wcf\system\exception\IllegalLinkException;

/**
 * Class QuizCategoryEditForm
 *
 * @package   de.teralios.de.teralios.quizCreator
 * @author    teralios
 * @copyright ©2021 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 * @since     1.5.0
 */
class QuizCategoryEditForm extends QuizCategoryAddForm
{
    // inherit vars
    public $formAction = 'edit';

    public function readParameters()
    {
        // read quiz
        $id = $_REQUEST['id'] ?? 0;
        $this->formObject = new Category((int) $id);
        if (!$this->formObject->categoryID) {
            throw new IllegalLinkException();
        }
    }
}

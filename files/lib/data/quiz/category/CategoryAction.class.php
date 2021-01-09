<?php

namespace wcf\data\quiz\category;

// imports
use wcf\data\AbstractDatabaseObjectAction;

/**
 * Class CategoryAction
 *
 * @package   de.teralios.de.teralios.quizCreator
 * @author    teralios
 * @copyright ©2021 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 * @since     1.1.0
 */
class CategoryAction extends AbstractDatabaseObjectAction
{
    // inherit variables
    protected $permissionsCreate = ['admin.content.quizCreator.canManage'];
    protected $permissionsDelete = ['admin.content.quizCreator.canManage'];
    protected $permissionsUpdate = ['admin.content.quizCreator.canManage'];
    protected $className = CategoryEditor::class;
}

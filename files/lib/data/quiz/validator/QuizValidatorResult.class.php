<?php

namespace wcf\data\quiz\validator;

/**
 * Class QuizValidatorResult
 *
 * @package    de.teralios.QuizCreator
 * @subpackage wcf\data\quiz\validator
 * @author     Karsten (Teralios) Achterrath
 * @copyright  ©2020 Teralios.de
 * @license    GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class QuizValidatorResult
{
    /**
     * @var string
     */
    protected $context = '';

    /**
     * @var string
     */
    protected $key = '';

    /**
     * @var int
     */
    protected $index = 0;

    /**
     * @var string
     */
    protected $type = 'missing';

    /**
     * QuizValidatorResult constructor.
     * @param string $context
     * @param string $key
     * @param int $index
     * @param string $type
     */
    protected function __construct(string $context, string $key, int $index, string $type = 'missing')
    {
        $this->context = $context;
        $this->key = $key;
        $this->index = $index;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getContext(): string
    {
        return $this->context;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return int
     */
    public function getIndex(): int
    {
        return $this->index;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $context
     * @param string $key
     * @param int $index
     * @return static
     */
    public static function missingKey(string $context, string $key, int $index = 0)
    {
        return new static($context, $key, $index, 'missing');
    }

    /**
     * @param string $context
     * @param string $key
     * @param int $index
     * @return static
     */
    public static function notAllowedKey(string $context, string $key, int $index = 0)
    {
        return new static($context, $key, $index, 'notAllowed');
    }


    /**
     * @return static
     */
    public static function emptyResult()
    {
        return new static('','',0, '');
    }
}

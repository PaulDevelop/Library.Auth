<?php

namespace Com\PaulDevelop\Library\Auth;

use Com\PaulDevelop\Library\Common\Base;

/**
 * Class Varialbe
 * @package De\Welt\JobPortal
 *
 * @property string           $Name
 * @property string           $Value
 */
class Variable extends Base
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $value;

    /**
     * @param string $name
     * @param string $value
     */
    public function __construct(
        $name = '', $value = ''
    ) {
        $this->name = $name;
        $this->value = $value;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }
}

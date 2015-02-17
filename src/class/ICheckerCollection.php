<?php

namespace Com\PaulDevelop\Library\Auth;

use Com\PaulDevelop\Library\Common\GenericCollection;

class ICheckerCollection extends GenericCollection
{
    public function __construct($initialValues = array(), $keyFieldName = '')
    {
        parent::__construct('Com\PaulDevelop\Library\Auth\IChecker', $initialValues, $keyFieldName);
    }
}

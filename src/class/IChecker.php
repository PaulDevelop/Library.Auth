<?php

namespace Com\PaulDevelop\Library\Auth;

use Com\PaulDevelop\Library\Persistence\Entity;

interface IChecker
{
    public function __construct(Entity $referenceValue = null);

    /**
     * @param int $id User id
     *
     * @return bool
     */
    public function check($id = 0);
}

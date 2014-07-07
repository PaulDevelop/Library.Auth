<?php

namespace Com\PaulDevelop\Library\Auth;

interface IRoleChecker
{
    /**
     * @param int $id
     *
     * @return bool
     */
    public function check($id = 0);
}

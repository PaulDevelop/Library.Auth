<?php

namespace Com\PaulDevelop\Library\Auth;

interface IRoleChecker
{
    /**
     * Check, whether the user has the given role.
     *
     * @param int    $id       User id
     * @param string $roleName Role name
     *
     * @return bool
     */
    public function check($id = 0, $roleName = '');
}

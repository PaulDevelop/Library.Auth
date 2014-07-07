<?php

namespace Com\PaulDevelop\Library\Auth;

use Com\PaulDevelop\Library\Persistence\Entity;

interface IAuthenticator
{
    /**
     * @param Entity $credentialEntity
     *
     * @return boolean
     */
    public function check(Entity $credentialEntity);
}

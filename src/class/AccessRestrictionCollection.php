<?php

namespace Com\PaulDevelop\Library\Auth;

use Com\PaulDevelop\Library\Common\GenericCollection;

class AccessRestrictionCollection extends GenericCollection
{
    public function __construct()
    {
        parent::__construct('De\Welt\JobPortal\AccessRestriction');
    }
}

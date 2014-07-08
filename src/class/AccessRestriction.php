<?php

namespace Com\PaulDevelop\Library\Auth;

use Com\PaulDevelop\Library\Common\Base;

/**
 * Class AccessRestriction
 * @package De\Welt\JobPortal
 *
 * @property string           $Pattern
 * @property IAuthenticator   $Authenticator
 * @property string           $RoleName
 * @property callback         $Callback
 * @property array            $ExceptionPaths
 */
class AccessRestriction extends Base
{
    /**
     * @var string
     */
    private $pattern;
    /**
     * @var IAuthenticator
     */
    private $authenticator;
    /**
     * @var string
     */
    private $roleName;
    /**
     * @var callback
     */
    private $callback;
    /**
     * @var array
     */
    private $exceptionPaths;

    /**
     * @param string         $pattern
     * @param IAuthenticator $authenticator
     * @param string         $roleName
     * @param callback       $callback
     * @param array          $exceptionPaths
     */
    public function __construct(
        $pattern = '',
        IAuthenticator $authenticator = null,
        $roleName = '',
        $callback = null,
        $exceptionPaths = array()
    ) {
        $this->pattern = $pattern;
        $this->authenticator = $authenticator;
        $this->roleName = $roleName;
        $this->callback = $callback;
        $this->exceptionPaths = $exceptionPaths;
    }

    public function getPattern()
    {
        return $this->pattern;
    }

    public function setPattern($pattern = '')
    {
        $this->pattern = $pattern;
    }

    public function getAuthenticator()
    {
        return $this->authenticator;
    }

    public function setAuthenticator(IAuthenticator $authenticator = null)
    {
        $this->authenticator = $authenticator;
    }

    public function getRoleName()
    {
        return $this->roleName;
    }

    public function setRoleName($roleName = '')
    {
        $this->roleName = $roleName;
    }

    public function getCallback()
    {
        return $this->callback;
    }

    public function setCallback($callback = null)
    {
        $this->callback = $callback;
    }

    public function getExceptionPaths()
    {
        return $this->exceptionPaths;
    }

    public function setExceptionPaths($exceptionPaths = array())
    {
        $this->exceptionPaths = $exceptionPaths;
    }
}

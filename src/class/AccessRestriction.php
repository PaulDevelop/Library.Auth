<?php

namespace Com\PaulDevelop\Library\Auth;

use Com\PaulDevelop\Library\Common\Base;

/**
 * Class AccessRestriction
 * @package De\Welt\JobPortal
 *
 * @property string         $Pattern
 * @property IAuthenticator $Authenticator
 * @property string         $RoleName
 * @property string         $LoginPath
 * @property array          $ExceptionPaths
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
     * @var string
     */
    private $loginPath;
    /**
     * @var array
     */
    private $exceptionPaths;

    /**
     * @param string         $pattern
     * @param IAuthenticator $authenticator
     * @param string         $roleName
     * @param string         $loginPath
     * @param array          $exceptionPaths
     */
    public function __construct(
        $pattern = '',
        IAuthenticator $authenticator = null,
        $roleName = '',
        $loginPath = '',
        $exceptionPaths = array()
    ) {
        $this->pattern = $pattern;
        $this->authenticator = $authenticator;
        $this->roleName = $roleName;
        $this->loginPath = $loginPath;
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

    public function getLoginPath()
    {
        return $this->loginPath;
    }

    public function setLoginPath($loginPath = null)
    {
        $this->loginPath = $loginPath;
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

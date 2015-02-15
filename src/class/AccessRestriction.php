<?php

namespace Com\PaulDevelop\Library\Auth;

use Com\PaulDevelop\Library\Common\Base;
use Com\PaulDevelop\Library\Persistence\Entity;

/**
 * Class AccessRestriction
 *
 * @package De\Welt\JobPortal
 *
 * @property string           $Pattern
 * @property IAuthenticator   $Authenticator
 * @property Entity           $Credentials
 * @property IRoleChecker     $RoleChecker
 * @property string           $RoleName
 * @property callback         $CallbackUrl
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
     * @var Entity
     */
    private $credentials;
    /**
     * @var IRoleChecker
     */
    private $roleChecker;
    /**
     * @var string
     */
    private $roleName;
    /**
     * @var string
     */
    private $callbackUrl;
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
     * @param Entity         $credentials
     * @param IRoleChecker   $roleChecker
     * @param string         $roleName
     * @param string         $callbackUrl
     * @param callback       $callback
     * @param array          $exceptionPaths
     */
    public function __construct(
        $pattern = '',
        IAuthenticator $authenticator = null,
        Entity $credentials = null,
        IRoleChecker $roleChecker = null,
        $roleName = '',
        $callbackUrl = '',
        $callback = null,
        $exceptionPaths = array()
    ) {
        $this->pattern = $pattern;
        $this->authenticator = $authenticator;
        $this->credentials = $credentials;
        $this->roleChecker = $roleChecker;
        $this->roleName = $roleName;
        $this->callbackUrl = $callbackUrl;
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

    public function getCredentials()
    {
        return $this->credentials;
    }

    public function setCredentials(Entity $credentials = null)
    {
        $this->credentials = $credentials;
    }

    public function getRoleChecker()
    {
        return $this->roleChecker;
    }

    public function setRoleChecker(IRoleChecker $roleChecker = null)
    {
        $this->roleChecker = $roleChecker;
    }

    public function getRoleName()
    {
        return $this->roleName;
    }

    public function setRoleName($roleName = '')
    {
        $this->roleName = $roleName;
    }

    public function getCallbackUrl()
    {
        return $this->callbackUrl;
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

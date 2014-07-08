<?php

namespace Com\PaulDevelop\Library\Auth;

use Com\PaulDevelop\Library\Common\Base;
use Com\PaulDevelop\Library\Persistence\Entity;

class Authorisator extends Base
{
    /**
     * @var AccessRestrictionCollection
     */
    private $collection;

    /**
     * @var IRoleChecker
     */
    private $roleChecker;

    public function __construct(IRoleChecker $roleChecker)
    {
        $this->roleChecker = $roleChecker;
        $this->collection = new AccessRestrictionCollection();
    }

    public function addAccessRestriction(AccessRestriction $accessRestriction = null)
    {
        $this->collection->add($accessRestriction);
    }

    //public function check(Request $request = null, Entity $credentials = null)
    public function check($url = '', Entity $credentials = null)
    {
        // init
        $result = false;

        // action
        foreach ($this->collection as $accessRestriction) {
            /** @var AccessRestriction $accessRestriction */
            if ($this->checkPattern($accessRestriction->Pattern, $url)) {
                // check exception
                if ($this->checkException(
                    $url,
                    $accessRestriction->ExceptionPaths
                )
                ) {
                    $result = true;
                } else {
                    if (($id = $accessRestriction->Authenticator->check($credentials)) !== false) {

                        // check, if user impersonates given role
                        if ($this->roleChecker->check($id, $accessRestriction->RoleName)) {
                            $result = true;
                        }
                    } else {
                        call_user_func($accessRestriction->Callback);
                    }
                }

                break;
            }
        }

        // return
        return $result;
    }

    private function checkException($url = '', $exceptionPaths = array())
    {
        // init
        $result = false;

        // action
        $url = trim($url, "\t\n\r\0\x0B/");

        foreach ($exceptionPaths as $exceptionPath) {
            $exceptionPath = trim($exceptionPath, "\t\n\r\0\x0B/");
            //if ($path == $exceptionPath) {
            if ($url == $exceptionPath) {
                $result = true;
                break;
            }
        }

        // return
        return $result;
    }

    private function checkPattern($pattern = '', $url = '')
    {
        // ^(subdomain\.)*domain(\/(folder\/)*(file\.ext)?)?$

        // pattern
        $pattern = trim($pattern, "\t\n\r\0\x0B/");
        if (($pos = strpos($pattern, '/*')) !== false) {
            $pattern = substr($pattern, 0, $pos);
        } else {
            $pattern = $pattern.'$';
        }

        if (($pos = strpos($pattern, '*.')) !== false) {
            $pattern = substr($pattern, $pos + 2);
        } else {
            $pattern = '^'.$pattern;
        }
        $pattern = str_replace('.', '\.', $pattern);
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = '/'.$pattern.'/';

        // path
        $url = trim($url, "\t\n\r\0\x0B/");

        // match
        $result = preg_match($pattern, $url);

        // return
        return $result;
    }
}

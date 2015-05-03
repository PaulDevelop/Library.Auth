<?php

namespace Com\PaulDevelop\Library\Auth;

use Com\PaulDevelop\Library\Common\Base;
use Com\PaulDevelop\Library\Persistence\Property;

class Authorisator extends Base
{
    /**
     * @var AccessRestrictionCollection
     */
    private $collection;

    /**
     * @var VariableCollection
     */
    private $variables;
//    /**
//     * @var IRoleChecker
//     */
//    private $roleChecker;

//    public function __construct(IRoleChecker $roleChecker, VariableCollection $variables = null)
    public function __construct(VariableCollection $variables = null)
    {
        //$this->roleChecker = $roleChecker;
        $this->variables = $variables == null ? new VariableCollection() : $variables;
        $this->collection = new AccessRestrictionCollection();
    }

    public function addAccessRestriction(AccessRestriction $accessRestriction = null)
    {
        if ($accessRestriction != null && $accessRestriction->Credentials != null) {
            /** @var Property $property */
            foreach ($accessRestriction->Credentials->Properties as $property) {
                if (preg_match('/%(.*?)%/', $property->Value, $matches)) {
                    $variableName = $matches[1];
                    $property->Value = $this->variables[$variableName]->Value;
                }
            }
        }
        $this->collection->add($accessRestriction);
    }

    //public function check(Request $request = null, Entity $credentials = null)
    public function check($url = '')//, Entity $credentials = null)
    {
        // init
        //$result = false;
        $result = true;

        // action
        $url = $this->replaceVariables($url);

        foreach ($this->collection as $accessRestriction) {
            $accessRestrictionPattern = $this->replaceVariables($accessRestriction->Pattern);

            /** @var AccessRestriction $accessRestriction */
            //if ($this->checkPattern($accessRestriction->Pattern, $url)) {
            if ($this->checkPattern($accessRestrictionPattern, $url)) {
                // check exception
                if (!$this->checkException(
                    $url,
                    $accessRestriction->ExceptionPaths
                )
                ) {
                    $id = 0;
//                    if (($id = $accessRestriction->Authenticator->check($credentials)) !== false) {
                    //if (($id = $accessRestriction->Authenticator->check($accessRestriction->Credentials)) !== 0) { // false
                    if ($accessRestriction->Authenticator != null
                        && $accessRestriction->Credentials != null
                        && ($id = $accessRestriction->Authenticator->check($accessRestriction->Credentials)) == 0 // !== 9
                    ) {
                        $result = false;
                        // check, if user impersonates given role
                        //if (!$this->roleChecker->check($id, $accessRestriction->RoleName)) {

//                        if (!$accessRestriction->RoleChecker->check($id, $accessRestriction->RoleName)) {
//                            $result = false;
//                        }
                    }

                    foreach ($accessRestriction->Checkers as $checker) {
                        /** @var IChecker $checker */
                        if (!$checker->check($id)) {
                            $result = false;
                            break;
                        }
                    }

                    if ($result == false) {
                        if ($accessRestriction->CallbackUrl != '') {
                            $accessRestrictionCallbackUrl = $this->replaceVariables($accessRestriction->CallbackUrl);
                            header('Location: '.$accessRestrictionCallbackUrl);
                        } else {
                            call_user_func($accessRestriction->Callback);
                        }
                    }
                    //else {
                    //    $result = $id;
                    //}
                }

                break;
            }
        }

        // return
        return $result;
    }

    private function replaceVariables($input = '')
    {
        // init
        $result = $input;

        // action
        /** @var Variable $variable */
        foreach ($this->variables as $variable) {
            $result = str_replace('%'.$variable->Name.'%', $variable->Value, $result);
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
            $exceptionPath = $this->replaceVariables(trim($exceptionPath, "\t\n\r\0\x0B/"));
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

        if (($pos = strpos($pattern, '*.')) !== false) {
            $pattern = substr($pattern, $pos + 2);
        } else {
            $pattern = '^'.$pattern;
        }

        //if (($pos = strpos($pattern, '/*')) !== false) {
        if (($pos = strpos($pattern, '*')) !== false) {
            $pattern = substr($pattern, 0, $pos);
        } else {
            $pattern = $pattern.'$';
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

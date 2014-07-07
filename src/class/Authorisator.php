<?php

namespace Com\PaulDevelop\Library\Auth;

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

    public function check(Request $request = null, Entity $credentials = null)
    {
        // init
        $result = false;

        // action
        foreach ($this->collection as $accessRestriction) {
            /** @var AccessRestriction $accessRestriction */
            if ($this->checkPattern($accessRestriction->Pattern, $request)) {
                // check exception
                if ($this->checkException(
                    $request,
                    array_merge(
                        array($accessRestriction->LoginPath),
                        $accessRestriction->ExceptionPaths
                    )
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
                        $base = $request->Input->Protocol.'://';
                        if ($request->Input->Subdomains != '') {
                            $base .= $request->Input->Subdomains.'.';
                        }
                        $base .= $request->Input->Domain;
                        header('Location: '.$base.'/'.$accessRestriction->LoginPath);
                        exit;
                    }
                }

                break;
            }
        }

        // return
        return $result;
    }

    private function checkException(Request $request = null, $exceptionPaths = array())
    {
        // init
        $result = false;

        // action
        $path = '';
        //if ($request->Input->Subdomains != '') {
        //    $path .= $request->Input->Subdomains.'.';
        //}
        //$path .= $request->Input->Domain;
        if ($request->StrippedPath != '') {
            $path .= '/'.$request->StrippedPath;
        }
        $path = trim($path, "\t\n\r\0\x0B/");

        foreach ($exceptionPaths as $exceptionPath) {
            $exceptionPath = trim($exceptionPath, "\t\n\r\0\x0B/");
            if ($path == $exceptionPath) {
                $result = true;
                break;
            }
        }

        // return
        return $result;
    }

    private function checkPattern($pattern = '', Request $request = null)
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
        $path = '';
        if ($request->Input->Subdomains != '') {
            $path .= $request->Input->Subdomains.'.';
        }
        $path .= $request->Input->Domain;
        if ($request->StrippedPath != '') {
            $path .= '/'.$request->StrippedPath;
        }
        $path = trim($path, "\t\n\r\0\x0B/");

        // match
        $result = preg_match($pattern, $path);

        // return
        return $result;
    }
}

<?php

namespace Com\PaulDevelop\Library\Auth;

use Com\PaulDevelop\Library\Persistence\Entity;
use Com\PaulDevelop\Library\Persistence\IPropertyCollection;
use Com\PaulDevelop\Library\Persistence\Property;

class PositiveRoleChecker implements IRoleChecker
{
    /**
     * @param int $id
     * @param string $name
     *
     * @return bool
     */
    public function check($id = 0, $name = '')
    {
        return true;
    }
}

class NegativeRoleChecker implements IRoleChecker
{
    /**
     * @param int    $id
     * @param string $name
     *
     * @return bool
     */
    public function check($id = 0, $name = '')
    {
        return false;
    }
}

class PositiveAuthenticator implements IAuthenticator
{
    /**
     * @param Entity $credentialEntity
     *
     * @return boolean
     */
    public function check(Entity $credentialEntity)
    {
        return true;
    }
}

class NegativeAuthenticator implements IAuthenticator
{
    /**
     * @param Entity $credentialEntity
     *
     * @return boolean
     */
    public function check(Entity $credentialEntity)
    {
        return false;
    }
}

class AuthorisatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testNoAuthenticationRequired()
    {
        $authorisator = new Authorisator(
            new PositiveRoleChecker()
        );

        $authorisator->addAccessRestriction(
            new AccessRestriction(
                'backend.welt.pauldevelop.com/*',
                new PositiveAuthenticator(),
                'Administrator',
                function () {
                    echo 'Authentication required';
                    //header('Location: backend.welt.pauldevelop.com/login/');
                    exit;
                },
                array(
                    'backend.welt.pauldevelop.com/login/',
                    'backend.welt.pauldevelop.com/login/process/'
                )
            )
        );

        $credentials = new Entity();
        $credentials->Properties = new IPropertyCollection();

        $credentials->Properties->add(new Property('id', 1), 'id');

        $url = 'welt.pauldevelop.com/';

        $this->assertEquals(true, $authorisator->check($url, $credentials));
    }
    /**
     * @test
     */
    public function testPositiveAuthenticationPositiveRoleCheck()
    {
        $authorisator = new Authorisator(
            new PositiveRoleChecker()
        );

        $authorisator->addAccessRestriction(
            new AccessRestriction(
                'backend.welt.pauldevelop.com/*',
                new PositiveAuthenticator(),
                'Administrator',
                function () {
                    echo 'Authentication required';
                    //header('Location: backend.welt.pauldevelop.com/login/');
                    exit;
                },
                array(
                    'backend.welt.pauldevelop.com/login/',
                    'backend.welt.pauldevelop.com/login/process/'
                )
            )
        );

        $credentials = new Entity();
        $credentials->Properties = new IPropertyCollection();

        $credentials->Properties->add(new Property('id', 1), 'id');

        $url = 'backend.welt.pauldevelop.com/';

        //if ($authorisator->check($url, $credentials)) {
        //    echo 'okay';
        //}
        //else {
        //    echo 'no access';
        //}

        $this->assertEquals(true, $authorisator->check($url, $credentials));
    }

    /**
     * @test
     */
    public function testPositiveAuthenticationNegativeRoleCheck()
    {
        $authorisator = new Authorisator(
            new NegativeRoleChecker()
        );

        $authorisator->addAccessRestriction(
            new AccessRestriction(
                'backend.welt.pauldevelop.com/*',
                new PositiveAuthenticator(),
                'Administrator',
                function () {
                    echo 'Authentication required';
                    //header('Location: backend.welt.pauldevelop.com/login/');
                    exit;
                },
                array(
                    'backend.welt.pauldevelop.com/login/',
                    'backend.welt.pauldevelop.com/login/process/'
                )
            )
        );

        $credentials = new Entity();
        $credentials->Properties = new IPropertyCollection();

        $credentials->Properties->add(new Property('id', 1), 'id');

        $url = 'backend.welt.pauldevelop.com/';

        $this->assertEquals(false, $authorisator->check($url, $credentials));
    }

    /**
     * @test
     */
    public function testNegativeAuthentication()
    {
        $authorisator = new Authorisator(
            new NegativeRoleChecker()
        );

        $authorisator->addAccessRestriction(
            new AccessRestriction(
                'backend.welt.pauldevelop.com/*',
                new NegativeAuthenticator(),
                'Administrator',
                function () {
                    echo 'Authentication required';
                    //return 'Authentication required';
                    //header('Location: backend.welt.pauldevelop.com/login/');
                    //exit;
                },
                array(
                    'backend.welt.pauldevelop.com/login/',
                    'backend.welt.pauldevelop.com/login/process/'
                )
            )
        );

        $credentials = new Entity();
        $credentials->Properties = new IPropertyCollection();

        $credentials->Properties->add(new Property('id', 1), 'id');

        $url = 'backend.welt.pauldevelop.com/';

        $this->assertEquals(false, $authorisator->check($url, $credentials));
    }
}

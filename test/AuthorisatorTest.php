<?php

namespace Com\PaulDevelop\Library\Auth;

use Com\PaulDevelop\Library\Persistence\Entity;
use Com\PaulDevelop\Library\Persistence\IPropertyCollection;
use Com\PaulDevelop\Library\Persistence\Property;

class TestRoleChecker implements IRoleChecker {
    /**
     * @param int $id
     *
     * @return bool
     */
    public function check($id = 0)
    {
        return true;
    }
}

class TestAuthenticator implements IAuthenticator {
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

class AuthorisatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testProcessSimpleTemplate()
    {
        $authorisator = new Authorisator(
            new TestRoleChecker()
        );

        $authorisator->addAccessRestriction(
            new AccessRestriction(
                'backend.welt.pauldevelop.com/*',
                new TestAuthenticator(),
                'Administrator',
                'login/',
                array(
                    'login',
                    'login/process'
                )
            )
        );

        $credentials = new Entity();
        $credentials->Properties = new IPropertyCollection();

        $credentials->Properties->add(new Property('id', 1), 'id');

        if ($authorisator->check($request, $credentials)) {

        }

        //$template = new Template();
        //$template->setTemplateFileName('test/_assets/templates/simple.template.pdt');
        //$this->assertEquals('Simple', trim($template->process()));
    }
}

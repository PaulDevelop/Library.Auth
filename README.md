pauldevelop/library-auth
========================

Commonly used classes to authenticate and authorize users and resources.

Manual
------

This library enables your application to  check whether a user has a specific role and if this user is allowed to access
certain parts of your application. For now it assumes, that your user objects are accessible via an integer id, and role
objects via a string containing the role's name.

### Usage

First create a new [Authorisator](src/class/Authorisator.php) object; the constructor takes an object implementing the 
[IRoleChecker](src/class/IRoleChecker.php) interface and a collection of variables.

```php
$variables = new VariableCollection();
$variables->add(new Variable('baseHost', 'example.com'));

$authorisator = new Authorisator(
    new MyRoleChecker(), // implements IRoleChecker
    $variables
);
```

The *IRoleChecker* interface defines a *check* method, which takes a user id and role name as parameters; an 
implementation must check whether the user with the id *$id* has the role with the name *$roleName*. You don't call the
*IRoleChecker*'s directly; it will be called from within the *Authorisator*'s check method.

A sample IRoleChecker implementation could look like this:

```php
class RoleChecker implements IRoleChecker
{
    /**
     * @var model\ImpersonationPeer
     */
    private $impersonationPeer;

    /**
     * @var model\RolePeer
     */
    private $rolePeer;

    public function __construct(model\ImpersonationPeer $impersonationPeer = null, model\RolePeer $rolePeer = null)
    {
        $this->impersonationPeer = $impersonationPeer;
        $this->rolePeer = $rolePeer;
    }

    public function check($id = 0, $roleName = '')
    {
        // init
        $result = false;

        // action
        $roles = $this->rolePeer->queryPath('role[@name='.$roleName.']#');
        if (count($roles) == 1) {
            /** @var model\Role $role */
            $role = $roles[0];
            $impersonations = $this->impersonationPeer->queryPath(
                'impersonation[@user='.$id.',@role='.$role->Id.']#'
            );
            if (count($impersonations) == 1) {
                $result = true;
            }
        }

        // return
        return $result;
    }
}
```

In this example, the constructor takes two peer objects, which access data from a data source like a database. The
roles are read by the *RolePeer*, while the information which user impersonates which role is read via the 
*ImpersonationPeer*. First, we query a role with $roleName; if we found a role, we also check, if the user $id 
impersonates the role $roleName. If yes, we return true, otherwise false.

The variables collection noted while instancing the *Authorisator* object contains variables, which can be used in the
patterns as described in the list below.

The next step is to add an access restriction, which is done by calling the *addAccessRestriction* method on the
*Authorisator* object. This method takes a [AccessRestriction](src/class/AccessRestriction.php) object as parameter, 
which has the following properties:
 
- pattern
  
    A pattern describing the resource's url. In this example we defined the variable *baseHost*, which we can use by
    surrounding the name with % signs (%baseUrl%). If you add a wildcard (*) at the end, all urls starting with the 
    given path are protected as well.

- authenticator
  
    An authenticator, which authenticates a user.

- roleName
  
    The name of the role a user must have.

- callbackUrl
  
    A url which is called, if the user is not allowed to access the resource.

- callback
  
    A function which is called when the callbackUrl is empty and if the user is not allowed to access the resource.

- exceptionPaths
  
    An array of resource url patterns, which must not be checked and therefore useful for example for the urls of login
    forms. You may use the *%baseHost%* variable.

To add a new access restriction to only allow users of the role 'Administrator' to access backend resources, call the
*addAccessRestriction* method as follows:

```php
$authorisator->addAccessRestriction(
    new AccessRestriction(
        'backend.%baseHost%/*',
        new DemoAuthenticator(),
        'Administrator',
        'http://backend.%baseHost%/login/',
        null,
        array(
            'backend.%baseHost%/login/',
            'backend.%baseHost%/login/process/'
        )
    )
);
```

Now it's time to actually check if a resource is accessible to an user. Do this with the check method of the 
*Authorisator* object:

```php
// setup credentials
$credentials = new Entity();
$credentials->Properties = new IPropertyCollection();
$result->Properties->add(new Property('name', $name), 'name');
$result->Properties->add(new Property('password', $password), 'password');

if ($authorisator->check($url, $credentials)) {
  // load protected content
}
```

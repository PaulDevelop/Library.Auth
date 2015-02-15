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
[IRoleChecker](src/class/IRoleChecker.php) interface.

```php
$authorisator = new Authorisator(
    new MyRoleChecker() // implements IRoleChecker
);
```

The *IRoleChecker* interface defines a *check* method, which takes a user id and role name as parameters; an 
implementation must check whether the user with the id *$id* has the role with the name *$roleName*. You don't call the
*IRoleChecker*'s directly; it is called from within the *Authorisator*'s check method.

The next step is to add an access restriction, which is done by calling the *addAccessRestriction* method on the
*Authorisator* object. This method takes a [AccessRestriction](src/class/AccessRestriction.php) object as parameter, 
which has the following properties:
 
- pattern
  
    A pattern describing the resource's url. You can use the variable *%baseHost%*, which will contain the server's
    second and top level domain (equals the domain without subdomains). Also you can add the wildcard (*) at the end;
    that way all urls "under" that path are protected as well.

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

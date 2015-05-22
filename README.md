# Mysql PDO Entity Repository
Here is a table wrapper class to using class as the entity of same table.

It have been written for PHP-MySQL which uses the PDO extension.


## Rules
1. create a table in database (for example person)
2. create class of Person, and extend it from class of BaseEntityRepository
3. table and class should have field and property of "id" as lower case.
5. Never change the value of "id" property.
6. All fields of tables should be implemented in class as a property.
7. It's possible to implement some properties in class that not exists in table.
8. Both of table fields and class properties, should have the same case-sensitive.
9. Implement __construct for class and call $this->Load .
10. Enjoy it.

##Implement view
create table person :
```sql
CREATE TABLE asteriskpanel.person (
  id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  username varchar(50) DEFAULT NULL,
  age varchar(50) DEFAULT NULL,
  name varchar(50) DEFAULT NULL,
  family varchar(50) DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE INDEX username (Username)
)

```
class person :
```php
include_once 'BaseEntityRepository.php';

class Person extends BaseEntityRepository
{
    public $id;
    public $username = '';
    public $age = '';
    public $name = '';
    public $family = '';

    public $i_am_not_exists_in_table = '';


    function __construct($username = null)
    {
        parent::__construct();
        if ($username != null) {
            $this->Load('username', $username);
        }
    }
}
```

## database parameters

Define database parameter

mysql/enums.php
```php
<?php

class Enums
{
    const HostName = 'localhost';
    const UserName = 'test_user';
    const Pass = '123';
    const DBName = 'dbname';
}

?>
```


## How to use CRUD operations
#### Insert into table
```php
<?php
	$username = 'user_for_test';
    $u = new Person();
    $u->username = $username;
    $u->age = 50;
    $u->name = 'ned';
    $u->family = 'stark';
    $u->Save();
?>
```




#### Select from table
```php
<?php
	$username = 'user_for_test';
    $u = new Person($username);
    var_dump($u);

 // or
    $u2 = new Person();
	$u2->Load('username', $username);
    var_dump($u);
?>
```


#### Update record in table
```php
<?php
	$username = 'user_for_test';
    $u = new Person($username);
    $u->age = 50;
    $u->name = 'Alex';
    $u->family = 'stef';
    $u->Update();
?>
```
#### Delete from table
```php
<?php
	$username = 'user_for_test';
    $u = new Person($username);
    $u->Delete();
?>
```


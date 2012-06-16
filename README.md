# Tomuss

## Installation via Composer

```
wget http://getcomposer.org/composer.phar
php composer.phar install
```

## Utilisation

```
php tomuss search /path/to/user.php
```

## User configuration (user.php)

```php
<?php

use Madalynn\Tomuss\User;

$user = new User('user', 'password');

// Storage
$storage = new Madalynn\Tomuss\Storage\FileStorage(__DIR__.'/cache');
$user->setStorage($storage);

// Notifiers
$growl = new Madalynn\Tomuss\Notifier\GrowlNotifier();
$user->addNotifier($growl);

return $user;
```
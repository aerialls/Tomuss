# Tomuss

## Installation via Composer

```
wget http://getcomposer.org/composer.phar
php composer.phar install
```

## Usage

```
php tomuss search /path/to/user.php
```

## User configuration (user.php)

The configuration is stored inside a custom file (`user.php`). If Tomuss has no
ideas where is the configuration file, it will looking for a `user.php` file
on your private home directory inside the `.tomuss` folder.

```php
<?php

use Madalynn\Tomuss\User;

$user = new User('user', 'password');

// Storage
$storage = new Madalynn\Tomuss\Storage\FilesystemStorage(__DIR__.'/cache');
$user->setStorage($storage);

// Notifiers
$growl = new Madalynn\Tomuss\Notifier\GrowlNotifier();
$user->addNotifier($growl);

return $user;
```
# BrandonlinU\\CvsBundle
A Symfony 4 and 5 compatible bundle for running deployment tasks in a production or QA (Quality Assurance) environment 
on an update of the source code with the help of webhooks, and the Symfony console.

## Before you go
This is a WIP (Work-In-Progress), so you must expect breaking changes with the release of a new version. This software
will try to stick with the semver conventions (trying to don't introduce backward-incompatible changes with the
release of new patch version), but I don't provide support for old versions.

## Installation
Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Applications that use Symfony Flex
Open a command console, enter your project directory and execute:

```shell
composer require brandonlinu/cvs-updater-bundler
```

That's all! You can jump right to "Configuration".

### Applications that don't use Symfony Flex
#### Step 1: Download the Bundle
Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```shell
composer require brandonlinu/cvs-updater-bundler
```

#### Step 2: Enable the Bundle
Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    BrandonlinU\CvsUpdaterBundler\BrandonlinUCvsUpdaterBundle::class => ['prod' => true],
];
```

## Configuration
TODO

## Licensing
This bundle is licensed under the GNU GPLv3. For a quick resume of the permissions with this license see the
[GNU GPLv3](https://choosealicense.com/licenses/gpl-3.0/) in [choosealicense.com](https://choosealicense.com).

See the [LICENSE](LICENSE.md) file for more details.

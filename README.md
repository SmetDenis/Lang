# JBZoo Lang  [![Build Status](https://travis-ci.org/JBZoo/Lang.svg?branch=master)](https://travis-ci.org/JBZoo/Lang)      [![Coverage Status](https://coveralls.io/repos/github/JBZoo/Lang/badge.svg?branch=master)](https://coveralls.io/github/JBZoo/Lang?branch=master)

Lightweight library for easy translation based on simple formats (php arrays, json, yml, ini)

[![License](https://poser.pugx.org/JBZoo/Lang/license)](https://packagist.org/packages/JBZoo/Lang)      [![Latest Stable Version](https://poser.pugx.org/JBZoo/Lang/v/stable)](https://packagist.org/packages/JBZoo/Lang) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/JBZoo/Lang/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/JBZoo/Lang/?branch=master)


## Install
```sh
composer require jbzoo/lang:"1.x-dev"  # Last version
composer require jbzoo/lang            # Stable version
```


## Usage

```php
require_once './vendor/autoload.php'; // composer autoload.php

// Get needed classes
use JBZoo\Lang\Lang;

// Create
$lang = new Lang('en');                             // Pass language code (only two chars!)

// Paths, modules, overload
$lang->load('./somepath/glob/');                    // ./somepath/glob/langs/en.php
$lang->load('./somepath/glob/', 'module_name');     // ./somepath/glob/langs/en.module_name.php
$lang->load('./somepath/module/', 'module_name');   // ./somepath/module/langs/en.module_name.php (overload previous)

// Other formats
$lang->load('./somepath/glob/', 'my_mod', 'php');   // ./somepath/glob/langs/en.my_mod.php
$lang->load('./somepath/glob/', 'my_mod', 'json');  // ./somepath/glob/langs/en.my_mod.json
$lang->load('./somepath/glob/', 'my_mod', 'ini');   // ./somepath/glob/langs/en.my_mod.ini
$lang->load('./somepath/glob/', 'my_mod', 'yml');   // ./somepath/glob/langs/en.my_mod.yml  (Symfony/Yaml)

// Traslate
$lang->translate('message_key');                // Only global
$lang->translate('module_name.message_key');    // Check module "module_name" and after that global path
```


## Speed of one(!) call method `translate()`
[PHP 5.6.x](https://travis-ci.org/JBZoo/Lang/jobs/110844247#L470) - Minimum time ~0.05ms

[PHP 7.0.x](https://travis-ci.org/JBZoo/Lang/jobs/110844248#L475) - Minimum time ~0.004ms


## Unit tests and check code style
```sh
composer update-all
composer test
```


## License

MIT

# Bav

A small library for validating german bank account numbers. It provides classes which can be easily integrated into existing projects and is build for PHP 5.3+.

[![Build Status](https://travis-ci.org/jschaedl/Bav.png)](https://travis-ci.org/jschaedl/Bav) 
[![Latest Unstable Version](https://poser.pugx.org/jschaedl/Bav/v/stable.png)](https://packagist.org/packages/jschaedl/Bav) 
[![Latest Unstable Version](https://poser.pugx.org/jschaedl/Bav/v/unstable.png)](https://packagist.org/packages/jschaedl/Bav) 
[![Total Downloads](https://poser.pugx.org/jschaedl/Bav/downloads.png)](https://packagist.org/packages/jschaedl/Bav) 
[![Dependencies Status](https://d2xishtp1ojlk0.cloudfront.net/d/12894297)](http://depending.in/jschaedl/Bav)

## Installation
To install jschaedl/bav install Composer first, if you haven't already 

```
curl -sS https://getcomposer.org/installer | php
```

Then just add the following to your composer.json file:

```js
// composer.json
{
	"require": {
		"jschaedl/bav": "1.4"
    }
}
```

Then, you can install the new dependencies by running Composer’s update command from the directory where your `composer.json` file is located:

```sh
# install
$ php composer.phar install
# update
$ php composer.phar update jschaedl/bav

# or you can simply execute composer command if you set it to
# your PATH environment variable
$ composer install
$ composer update jschaedl/bav
```

You can see this library on [Packagist](https://packagist.org/packages/jschaedl/bav).

Composer installs autoloader at `./vendor/autoload.php`. If you use jschaedl/bav in your php script, add:

```php
require_once 'vendor/autoload.php';
```

Or you can use git clone command:

```sh
# HTTP
$ git clone https://github.com/jschaedl/Bav.git
# SSH
$ git clone git@github.com:jschaedl/Bav.git
```

---

## Usage example

```php

use Bav\Backend\Parser\BankDataParser;
use Bav\Backend\BankDataResolver;
use Bav\Encoder\EncoderFactory;

$bankDataFile = 'blz_2013_12_09_txt.txt';

$encoder = EncoderFactory::create(Bav::DEFAULT_ENCODING);

$parser = new BankDataParser($bankDataFile);
$parser->setEncoder($encoder);       

$bav = new Bav();
$bav->setBankDataResolver(new BankDataResolver($parser));

$bank = $bav->getBank('20090500');
$agency = $bank->getMainAgency();
$this->assertEquals('netbank', $agency->getName());
$this->assertEquals('000000', $agency->getIbanRule());

$bank = $bav->getBank('58561250');
$this->assertEquals('58564788', $bank->getBankId());

$bank = $bav->getBank('20090500');
$this->assertTrue($bank->isValid('1359100'));

```

---
 
## How to contribute
If you want to fix some bugs or want to enhance some functionality, please fork the master branch and create your own development branch. 
Then fix the bug you found or add your enhancements and make a pull request. Please commit your changes in tiny steps and add a detailed description on every commit. 

### Unit Testing

All pull requests must be accompanied by passing unit tests. This repository uses phpunit and Composer. You must run `composer install` to install this package's dependencies before the unit tests will run. You can run the test via:

```
phpunit -c tests/phpunit.xml tests/
```

---

## License and authors

This project is free and under GPL (see gpl.txt). So do what ever you want. But it would be nice to leave a note about the authors. 

The authors of the original project who gave the idea to this project are Björn Wilmsmann and Markus Malkusch. 

Responsible for this project is Jan Schädlich.


## Requirements

You may have:
* PHP 5.3.0 or greater
* mbstring or iconv

BAV works with unicode encoding. Your PHP must have support compiled in to either the mb_* or the iconv_* functions. If these functions are missing BAV works only with the ISO-8859-15 encoding.


[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/jschaedl/bav/trend.png)](https://bitdeli.com/free "Bitdeli Badge")


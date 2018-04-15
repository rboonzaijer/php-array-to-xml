# PhpArrayToXml

Convert an array to XML with PHP

[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/refactorstudio/php-array-to-xml/master.svg?style=flat-square)](https://travis-ci.org/refactorstudio/php-array-to-xml)

## Usage

Basic example:
```php
use RefactorStudio\PhpArrayToXml\PhpArrayToXml;

$converter = new PhpArrayToXml();

$result = $converter->toXmlString(['title' => 'My Products']);
```

Output:
```xml
<?xml version="1.0" encoding="UTF-8"?>
<root><title>My Products</title></root>
```

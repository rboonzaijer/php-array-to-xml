<h1 align="center">PhpArrayToXml</h1>

<p align="center">Convert an array to XML with PHP</p>

<p align="center">
  <a href="https://travis-ci.org/refactorstudio/php-array-to-xml"><img src="https://img.shields.io/travis/refactorstudio/php-array-to-xml/master.svg?style=flat-square" alt="Build Status"></a>
  <a href="https://scrutinizer-ci.com/g/refactorstudio/php-array-to-xml/"><img src="https://img.shields.io/scrutinizer/coverage/g/refactorstudio/php-array-to-xml.svg?style=flat-square" alt="Coverage Status"></a>
  <a href="https://scrutinizer-ci.com/g/refactorstudio/php-array-to-xml/"><img src="https://img.shields.io/scrutinizer/g/refactorstudio/php-array-to-xml.svg?style=flat-square" alt="Code Quality"></a>
  <a href="LICENSE.md"><img src="https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square" alt="License"></a>
</p>



## Install

```
composer require refactorstudio/php-array-to-xml
```



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



## Output Format (Prettify)
> `->setFormatOutput(bool $value = false)`

> Alias: `->prettify()` is the same as typing: `->setFormatOutput(true)`

```php
$array = [
  'title' => 'My Products',
  'pricing' => 'Pricing'
];
```

Default:
```xml
<?xml version="1.0" encoding="UTF-8"?>
<root><title>My Products</title><pricing>Pricing</pricing></root>
```

Usage:
```php
$result = $converter->setFormatOutput(true)->toXmlString($array);

// or use the alias:
$result = $converter->prettify()->toXmlString($array);
```

Result:
```xml
<?xml version="1.0" encoding="UTF-8"?>
<root>
  <title>My Products</title>
  <pricing>Pricing</pricing>
</root>
```



## Custom root name
> `->setCustomRootName(string $value = 'root')`

```php
$result = $converter->setCustomRootName('data')->toXmlString();
```

Result:
```xml
<?xml version="1.0" encoding="UTF-8"?>
<data>
  ...
</data>
```



## Custom tag name
> Custom tag names are used when an array has no key names
>
> `->setCustomTagName(string $value = 'node')`

```php
$array = [
  'title' => 'My Products',
  'products' => [
    [
      'name' => 'Raspberry Pi 3',
      'price' => 39.99
    ],
    [
      'name' => 'Arduino Uno Rev3',
      'price' => 19.99
    ]
  ]
];
```

Default (prettified):
```xml
<?xml version="1.0" encoding="UTF-8"?>
<root>
  <title>My Products</title>
  <products>
    <node>
      <name>Raspberry Pi 3</name>
      <price>39.99</price>
    </node>
    <node>
      <name>Arduino Uno Rev3</name>
      <price>19.99</price>
    </node>
  </products>
</root>
```

Usage:
```php
$xml_string = $converter->setCustomTagName('item')->toXmlString($array);
```

Result (prettified):
```xml
<?xml version="1.0" encoding="UTF-8"?>
<root>
  <title>My Products</title>
  <products>
    <item>
      <name>Raspberry Pi 3</name>
      <price>39.99</price>
    </item>
    <item>
      <name>Arduino Uno Rev3</name>
      <price>19.99</price>
    </item>
  </products>
</root>
```



## XML version
> `->setVersion(string $value = '1.0')`

```php
$xml_string = $converter->setVersion('1.1')->toXmlString(['test']);
```

Result (prettified):
```xml
<?xml version="1.1" encoding="UTF-8"?>
<root>
  <node>test</node>
</root>
```



## XML encoding
> `->setEncoding(string $value = 'UTF-8')`

```php
$xml_string = $converter->setEncoding('ISO-8859-1')->toXmlString(['test']);
```

Result (prettified):
```xml
<?xml version="1.0" encoding="ISO-8859-1"?>
<root>
  <node>test</node>
</root>
```



## Tag separator
> Set the value for the separator that will be used to replace special characters in tag names
>
> `->setSeparator(string $value = '_')`

```php
$array = [
  'some of these keys have' => 'My Value 1',
  'spaces in them' => 'My Value 2',
];
```

Default (prettified):
```xml
<?xml version="1.0" encoding="UTF-8"?>
<root>
  <some_of_these_keys_have>My Value 1</some_of_these_keys_have>
  <spaces_in_them>My Value 2</spaces_in_them>
</root>
```

Usage:
```php
$xml_string = $converter->setSeparator('-')->toXmlString($array);
```

Result (prettified):
```xml
<?xml version="1.0" encoding="UTF-8"?>
<root>
  <some-of-these-keys-have>My Value 1</some-of-these-keys-have>
  <spaces-in-them>My Value 2</spaces-in-them>
</root>
```



## Transform tag names
> Transform tag names to uppercase/lowercase
>
> `->setTransformTags(string $value = null)`

```php
$array = [
  'This' => [
    'Is' => [
      'an',
      'Example'
    ]
  ]
];
```

Default (prettified):
```xml
<?xml version="1.0" encoding="UTF-8"?>
<root>
  <This>
    <Is>
      <node>an</node>
      <node>Example</node>
    </Is>
  </This>
</root>
```

Usage (lowercase):
```php
$xml_string = $converter->setTransformTags('lowercase')->toXmlString($array);
```

Result (prettified):
```xml
<?xml version="1.0" encoding="UTF-8"?>
<root>
  <this>
    <is>
      <node>an</node>
      <node>Example</node>
    </is>
  </this>
</root>
```

Usage (uppercase):
```php
$xml_string = $converter->setTransformTags('uppercase')->toXmlString($array);
```

Result (prettified):
```xml
<?xml version="1.0" encoding="UTF-8"?>
<ROOT>
  <THIS>
    <IS>
      <NODE>an</NODE>
      <NODE>Example</NODE>
    </IS>
  </THIS>
</ROOT>
```


Usage (uppercase, but with custom tag names, which will not be transformed):
```php
$xml_string = $converter
              ->setTransformTags('uppercase')
              ->setCustomRootName('MyRoot')
              ->setCustomTagName('MyCustomTag')
              ->toXmlString($array);
```

Result (prettified):
```xml
<?xml version="1.0" encoding="UTF-8"?>
<MyRoot>
  <THIS>
    <IS>
      <MyCustomTag>an</MyCustomTag>
      <MyCustomTag>Example</MyCustomTag>
    </IS>
  </THIS>
</MyRoot>
```



## Set numeric tag suffix
> If this is not null, it appends the numeric array key to the tag name, with the value as separator.
>
> `->setNumericTagSuffix(string $value = null)`

```php
$array = [
  'this',
  'is',
  'an'
  [
    'example',
    'using',
    'numeric tag suffix',
  ],
];
```

Default (prettified):
```xml
<?xml version="1.0" encoding="UTF-8"?>
<root>
  <node>this</node>
  <node>is</node>
  <node>an</node>
  <node>
    <node>example</node>
    <node>using</node>
    <node>numeric tag suffix</node>
  </node>
</root>
```

Usage:
```php
$xml_string = $converter->setNumericTagSuffix('_')->toXmlString($array);
```

Result (prettified):
```xml
<?xml version="1.0" encoding="UTF-8"?>
<root>
  <node_0>this</node_0>
  <node_1>is</node_1>
  <node_2>an</node_2>
  <node_3>
    <node_0>example</node_0>
    <node_1>using</node_1>
    <node_2>numeric tag suffix</node_2>
  </node_3>
</root>
```



## Cast boolean values
> By default boolean values from the array will be cast to the string 'true' or 'false'. You can choose to cast it to any (string) value you like. This method only works on real boolean values, so strings with the value 'true' and 'false' are untouched.
>
> `->setCastBooleanValueTrue(string $value = 'true')`

> `->setCastBooleanValueFalse(string $value = 'false')`

```php
$array = [
  'StringTrue' => 'true',
  'StringFalse' => 'false',
  'BooleanTrue' => true,
  'BooleanFalse' => false
];
```

Default (prettified):
```xml
<?xml version="1.0" encoding="UTF-8"?>
<root>
  <StringTrue>true</StringTrue>
  <StringFalse>false</StringFalse>
  <BooleanTrue>true</BooleanTrue>
  <BooleanFalse>false</BooleanFalse>
</root>
```

Usage:
```php
$xml_string = $converter->setCastBooleanTrue('Yes')->setCastBooleanFalse('No')->toXmlString($array);
```

Result (prettified):
```xml
<?xml version="1.0" encoding="UTF-8"?>
<root>
  <StringTrue>true</StringTrue>
  <StringFalse>false</StringFalse>
  <BooleanTrue>Yes</BooleanTrue>
  <BooleanFalse>No</BooleanFalse>
</root>
```



## Cast NULL values
> By default null values from the array will have no value in the XML, so the tag looks something like this: `<MyTag/>`. You can choose to cast it to any (string) value you like. This method only works on real 'null' values, so strings with the value `'null'` or empty strings `''` are untouched.
>
> `->setCastNullValue(null|string $value = null)`

```php
$array = [
  'StringNull' => 'null',
  'StringEmpty' => '',
  'RealNull' => null
];
```

Default (prettified):
```xml
<?xml version="1.0" encoding="UTF-8"?>
<root>
  <StringNull>null</StringNull>
  <StringEmpty/>
  <RealNull/>
</root>
```

Usage:
```php
$xml_string = $converter->setCastNullValue('__NULL__')->setCastBooleanFalse('No')->toXmlString($array);
```

Result (prettified):
```xml
<?xml version="1.0" encoding="UTF-8"?>
<root>
  <StringNull>null</StringNull>
  <StringEmpty/>
  <RealNull>__NULL__</RealNull>
</root>
```

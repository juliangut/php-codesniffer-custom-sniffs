[![PHP version](https://img.shields.io/badge/PHP-%3E%3D7.4-8892BF.svg?style=flat-square)](http://php.net)
[![Latest Version](https://img.shields.io/packagist/v/juliangut/php-codesniffer-custom-sniffs.svg?style=flat-square)](https://packagist.org/packages/juliangut/php-codesniffer-custom-sniffs)
[![License](https://img.shields.io/github/license/juliangut/php-codesniffer-custom-sniffs.svg?style=flat-square)](https://github.com/juliangut/php-codesniffer-custom-sniffs/blob/master/LICENSE)

[![Total Downloads](https://img.shields.io/packagist/dt/juliangut/php-codesniffer-custom-sniffs.svg?style=flat-square)](https://packagist.org/packages/juliangut/php-codesniffer-custom-sniffs/stats)
[![Monthly Downloads](https://img.shields.io/packagist/dm/juliangut/php-codesniffer-custom-sniffs.svg?style=flat-square)](https://packagist.org/packages/juliangut/php-codesniffer-custom-sniffs/stats)

# php-codesniffer-custom-sniffs

Custom sniffs for [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer/)

## Installation

### Composer

```
composer require --dev juliangut/php-codesniffer-custom-sniffs
```

## Usage

It is recommended to also install [dealerdirect/phpcodesniffer-composer-installer](https://github.com/PHPCSStandards/composer-installer) to avoid needing to declare paths in your ruleset definition file

### Complete ruleset

```xml
<?xml version="1.0"?>
<ruleset name="MyProject">
    <rule ref="JgutCodingStandard"/>

    <!-- if dealerdirect/phpcodesniffer-composer-installer is not installed-->
    <rule ref="vendor/juliangut/php-codesniffer-custom-sniffs/src/JgutCodingStandard/ruleset.xml" /><!-- path relative to your ruleset definition file -->
</ruleset>
```

### Only certain sniffs

```xml
<?xml version="1.0"?>
<ruleset name="MyProject">
    <!-- if dealerdirect/phpcodesniffer-composer-installer is not installed-->
    <config name="installed_paths" value="../../juliangut/php-codesniffer-custom-sniffs"/><!-- path relative to PHPCS source location on vendor directory -->

    <rule ref="JgutCodingStandard.NamingConventions.CamelCapsFunctionName" />
    <rule ref="JgutCodingStandard.NamingConventions.CamelCapsVariableName">
        <properties>
            <property name="strict" value="false" />
        </properties>
    </rule>
</ruleset>
```

## Sniffs

### NamingConventions

#### CamelCapsFunctionNameSniff

Function and method names should be written un camelCaps

```diff
 <?php
 
 class Foo
 {
-    public function max_length(): int
+    public function maxLength(): int
     {
     }
 }
```

##### Configuration

__strict__  (bool), Set to false in order to allow two consecutive uppercase letters (CamelCaps format does not allow two uppercase letters next to each other)

#### CamelCapsVariableNameSniff

Variable and property names should be written un camelCaps

```diff
 <?php
 
 class Foo
 {
-    private bool $max_length = 255;
+    private bool $maxLength = 255;
 }
```

##### Configuration

__strict__  (bool), Set to false in order to allow two consecutive uppercase letters (CamelCaps format does not allow two uppercase letters next to each other)

## Contributing

Found a bug or have a feature request? [Please open a new issue](https://github.com/juliangut/php-codesniffer-custom-sniffs/issues). Have a look at existing issues before.

See file [CONTRIBUTING.md](https://github.com/juliangut/php-codesniffer-custom-sniffs/blob/master/CONTRIBUTING.md)

## License

See file [LICENSE](https://github.com/juliangut/php-codesniffer-custom-sniffs/blob/master/LICENSE) included with the source code for a copy of the license terms.

# SBOMinator Library

## What it is
This library can be used as a multi-channel dependency tracker.
It can parse the following files:
- Composer Lockfiles
- NPM Lockfiles
- CycloneDX SBOMs
- SPDX SBOMs

## How it works
The library uses a parser interface to parse the files.
You can then use the parser to get the dependency tree of the file.
Dependencies are represented as a tree structure, with each node containing the name of the dependency and its version.

## How to use it
### Installation
Run `composer require sbominator/sbominator` to install the library.

### Usage
#### Load up the parser of your choice.

```php
use SBOMinator\Parser\ComposerParser;

$parser = new ComposerParser();
```

#### Parse a file that the parser supports.

You can pass a file path to the parser:
```php
$parser->loadFromFile('composer.lock');
```

You can also pass the contents of a file as string to the parser:
```php
$parser->loadFromString(file_get_contents('composer.lock'));
```

##### Retrive the Dependency Tree

```php
$dependencyTree = $parser->parseDependencies();
```
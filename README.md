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

Run `composer require sbominator/sbom-lib` to install the library.

### With ddev

For easier development across machines, you can use ddev to run the project locally. You can install ddev by following the instructions at https://ddev.readthedocs.io/en/stable/#installation.
After checking out the project, you can run `ddev start` to start the project. Use `ddev ssh` to get a shell in the running container. You can then run `composer install` in the shell to install the dependencies.

### Usage
### Using the scanner
#### Load up the scanner of your choice.

```php
use SBOMinator\Scanner\FileScanner;

/*
 * You can pass the maximum depth of the subdirectories to scan (default is 10)
 * and an array of file extensions to scan for. (If you want to work with all available parsers, you can use the default ['json', 'lock'])
 */ 

$fileScanner = new FileScanner(10, ['json', 'lock']);
```

#### Scan a directory
Use the scanForDependencies() method to scan a directory for files that the scanner supports.

```php
$dependencies = $fileScanner->scanForDependencies(getcwd());
```

### Using the parser
#### Load up the parser of your choice.

```php
use SBOMinator\Parser\ComposerParser;

$parser = new ComposerParser();

// You can omit dev packages if you want by calling withoutDevPackages() on the parser.
$parser = (new ComposerParser())->withoutDevPackages();
```

#### Parse a file that the parser supports

You can pass a file path to the parser:

```php
$parser->loadFromFile('composer.lock');
```

You can also pass the contents of a file as string to the parser:

```php
$parser->loadFromString(file_get_contents('composer.lock'));
```

##### Retrieve the Dependency Tree

```php
$dependencyTree = $parser->parseDependencies();
```

## Contributing

please see [CONTRIBUTING.md](CONTRIBUTING.md) for more information.

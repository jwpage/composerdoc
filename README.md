# Composerdoc Command Line Utility

[![Build Status](https://travis-ci.org/jwpage/composerdoc.png)](https://travis-ci.
org/jwpage/composerdoc)

Composerdoc is a tool that allows you to dump a listing of the required packages
within your project to a markdown format for documentation purposes.

## Installation

Add this to your `composer.json` by running 
`composer.phar require jwpage/composerdoc --dev`.

Or alternatively, as Composerdoc is a documentation tool, you can install it separately
and run it with the `--path` option.

    echo -n '{ "require": { "jwpage/composerdoc": "*" } }' > composer.json
    composer.phar install

## Usage

### `composerdoc dump`

To generate composerdoc output: 

    ./vendor/bin/composerdoc --path <path_to_composer.json_dir>

To include dev requirements: 

    ./vendor/bin/composerdoc --dev

To include requirements of requirements: 
    
    ./vendor/bin/composerdoc --sub

### `composerdoc check`

You can use composerdoc to check if a README file has the latest composerdoc information.

    ./vendor/bin/composerdoc --path <README.markdown>

This command takes the same options as the `composerdoc dump` command.

### `composerdoc update`

You can also automatically update a README file with the latest composerdoc information in one command.

    ./vendor/bin/composerdoc --path <README.markdown>

This command takes the same options as the `composerdoc dump` command.

## Example Output

Required Packages

* composer/composer: Package Manager: <http://getcomposer.org/>
    * justinrainbow/json-schema: A library to validate a json schema.: <https://github.com/justinrainbow/json-schema>
    * seld/jsonlint: JSON Linter: none
    * symfony/console: Symfony Console Component: <http://symfony.com>
    * symfony/finder: Symfony Finder Component: <http://symfony.com>
    * symfony/process: Symfony Process Component: <http://symfony.com>
* symfony/console: Symfony Console Component: <http://symfony.com>

Dev Packages

* phpunit/phpunit: The PHP Unit Testing framework.: <http://www.phpunit.de/>
    * phpunit/php-file-iterator: FilterIterator implementation that filters files based on a list of suffixes.: <http://www.phpunit.de/>
    * phpunit/php-text-template: Simple template engine.: <https://github.com/sebastianbergmann/php-text-template/>
    * phpunit/php-code-coverage: Library that provides collection, processing, and rendering functionality for PHP code coverage information.: <https://github.com/sebastianbergmann/php-code-coverage>
    * phpunit/php-timer: Utility class for timing: <http://www.phpunit.de/>
    * phpunit/phpunit-mock-objects: Mock Object library for PHPUnit: <https://github.com/sebastianbergmann/phpunit-mock-objects/>
    * symfony/yaml: Symfony Yaml Component: <http://symfony.com>

## Running Tests

First, install PHPUnit with `composer.phar install --dev`, then run 
`./vendor/bin/phpunit`.
[![version](https://img.shields.io/badge/version-1.3.0-green.svg)](https://github.com/huttopia/console-bundle)
[![symfony](https://img.shields.io/badge/symfony/frameworkbundle-^2.3%20||%20^3.0%20||%20^4.0||%20^5.0-blue.svg)](https://symfony.com)
[![symfony](https://img.shields.io/badge/symfony/console-^2.3%20||%20^3.0%20||%20^4.0%20||%20^5.0-blue.svg)](https://symfony.com)
![Lines](https://img.shields.io/badge/code%20lines-954-green.svg)
![Total Downloads](https://poser.pugx.org/huttopia/console-bundle/downloads)

# ConsoleBundle

Allow to exclude some commands.

For example, if you don't want to have _doctrine:schema:update_ command in _prod_ env: now you can :).

Add configuration to _doctrine:schema:update_ to get queries for more than one database per connection.

[Changelog](changelog.md)

# Installation

```bash
composer require huttopia/console-bundle ^1.3
```

Replace parts of `bin/console`:
```php
# Replace use Symfony\Bundle\FrameworkBundle\Console\Application; by this one
use Huttopia\ConsoleBundle\Application;

# Add this line before $input = new ArgvInput();
$allCommands = \Huttopia\ConsoleBundle\CommandOption\AllCommandsOption::parseAllCommandsOption($argv);
$input = new ArgvInput();

# Replace Application creation (it should be the last 2 lines of your bin/console)
// $application = new Application($kernel);
// $application->run($input);
(new Application($kernel))
    ->setAllCommands($allCommands)
    ->run($input);
```

## Symfony <= 3
```php
# app/AppKernel.php
class AppKernel
{
    public function registerBundles()
    {
        $bundles = [
            new \Huttopia\ConsoleBundle\ConsoleBundle()
        ];
    }
}
```

## Symfony >= 4

```yaml
# config/bundles.php
return [
    Huttopia\ConsoleBundle\ConsoleBundle::class => ['all' => true]
];
```

# Exclude commands

```yaml
# Symfony <= 3: app/config/config.yml
# Symfony >= 4: config/packages/console_bundle.yaml
console:
    excluded:
        - 'foo:bar:baz'
        - 'bar:foo:baz'
```

# Hide parts of command list

When you call `bin/console` or `bin/console list`, you see the list of commands.

Output is cut in 4 parts:
 * Symfony version, environment and debug mode state
 * Help for usage syntax
 * Help for options available with all commands
 * Commands list

You can configure at what verbosity level each part will be shown.

Verbosity level could be `0`, `1` (`-v`), `2` (`-vv`) or `3` (`-vvv`).
```yaml
# Symfony <= 3: app/config/config.yml
# Symfony >= 4: config/packages/console_bundle.yaml
console:
    list:
        symfonyVersionVerbosityLevel: 1
        usageVerbosityLevel: 1
        optionsVerbosityLevel: 1
        availableCommandsVerbosityLevel: 0
```

# Colorise some commands

When you call `bin/console` or `bin/console list`, you see the list of commands.

You can change the color of each part of the command name and description:

```
console:
    list:
        output:
            # See https://symfony.com/doc/current/console/coloring.html
            styles:
                foo:
                    foreground: cyan # 1st parameter of new OutputFormatterStyle()
                    background: green # 2nd parameter of new OutputFormatterStyle()
                    options: [bold, underscore] # 3rd parameter of new OutputFormatterStyle()
            commands:
                generate:benchmark: "<foo>%%s</>%%s%%s" # 1st %s is command name, 2nd is spaces between name and description and 3rd is the description
            highlights: # Shortcut for "<highlight>%%s</>%%s%%s" who will write command name in blue instead of green
                - 'generate:benchmark'
                - 'generate:default-installation'
``` 

# doctrine:schema:update for more than one database

_doctrine:schema:update_ has a major problem for us: only one database per connection is managed.

In our projects, we have more than one database per connection, so _doctrine:schema:update_ don't show queries for all our databases.
 
_UpdateDatabaseSchemaCommand_ replace _doctrine:schema:update_ and call old _doctrine:schema:update_ for all configured databases!

## Configuration

```yaml
# Symfony <= 3: app/config/config.yml
# Symfony >= 4: config/packages/console_bundle.yaml
console:
    databases:
        - database_name_1
        - database_name_2
```

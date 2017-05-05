[![version](https://img.shields.io/badge/version-1.0.0-green.svg)](https://github.com/huttopia/console-bundle/tree/1.0.0)
[![symfony](https://img.shields.io/badge/symfony/symfony-^2.3%20||%20^3.0-blue.svg)](https://symfony.com/)
![Lines](https://img.shields.io/badge/code%20lines-171-green.svg)
![Total Downloads](https://poser.pugx.org/huttopia/console-bundle/downloads)

# ConsoleBundle

Allow to exclude some commands

For example, you don't want to have _doctrine:schema:update_ command in _prod_ env : now you can :).

# Installation

```bash
composer require huttopia/console-bundle ^1.0
```

```php
# app/AppKernel.php

    public function registerBundles()
    {
        $bundles = [
            new \Huttopia\ConsoleBundle\ConsoleBundle()
        ];
    }
```

Replace _use Symfony\Bundle\FrameworkBundle\Console\Application;_ by _use Huttopia\ConsoleBundle\Application;_ in _bin/console_

# Configuration

```yaml
# app/config/config.yml

console:
    excluded:
        - 'foo:bar:baz'
        - 'bar:foo:baz'
```

### [1.3.2](../../compare/1.3.1...1.3.2) - 2020-03-31

- Fix `console.list.output.highlights` command with `-` in their name.

### [1.3.1](../../compare/1.3.0...1.3.1) - 2020-03-27

- Fix `highlight` output style.
- Fix command name with `-` who was not colored.

### [1.3.0](../../compare/1.2.0...1.3.0) - 2020-03-26

- [Steevan](https://github.com/steevanb) Change `Application::$allCommands` default value from true to false.
- [Steevan](https://github.com/steevanb) Add `console.list.output.styles`, `console.list.output.commands` and `console.list.output.highlights` configurations.

### [1.2.0](../../compare/1.1.3...1.2.0) - 2020-03-19

- [Steevan](https://github.com/steevanb) Add `--all-commands` to ignore removed commands configuration.
- [Steevan](https://github.com/steevanb) Symfony 5 dependencies allowed.
- [Steevan](https://github.com/steevanb) Add `list.symfonyVersionVerbosityLevel` configuration.
- [Steevan](https://github.com/steevanb) Add `list.usageVerbosityLevel` configuration.
- [Steevan](https://github.com/steevanb) Add `list.optionsVerbosityLevel` configuration.
- [Steevan](https://github.com/steevanb) Add `list.availableCommandsVerbosityLevel` configuration.
- [Steevan](https://github.com/steevanb) Move source code files into `src` directory.

### [1.1.3](../../compare/1.1.2...1.1.3) - 2018-09-05

- [ZeMarine](https://github.com/ZeMarine) Documentation fixes
- [ZeMarine](https://github.com/ZeMarine) Add dependency with `symfony/console`.
- [ZeMarine](https://github.com/ZeMarine) Add return typehint for `Application::add(): ?Command`.

### [1.1.2](../../compare/1.1.1...1.1.2) - 2018-08-24

- [ZeMarine](https://github.com/ZeMarine) Add compatibility with `symfony/framework-bundle ^4.0`.

### [1.1.1](../../compare/1.1.0...1.1.1) - 2016-05-29

- [Steevan](https://github.com/steevanb) Fix _Application::getExcludedCommands()_ method name.
- [Steevan](https://github.com/steevanb) Fix _UpdateAllDatabaseSchemaCommand_ when _doctrine:schema:db-update_ fail.

### [1.1.0](../../compare/1.0.0...1.1.0) - 2016-05-06

- [Steevan](https://github.com/steevanb) Add _doctrine:schema:db-update DATABASE_ command, to update only DATABASE.
- [Steevan](https://github.com/steevanb) Add replacement for _doctrine:schema:update_, to update all configured databases in console.databases.

### 1.0.0 - 2016-05-05

- [Steevan](https://github.com/steevanb) Add _console.excluded_ configuration to _bin/console_, to excluded commands.

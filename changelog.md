### Next release

- [Steevan](https://github.com/steevanb) Add `--all-commands` to ignore removed commands configuration.
- [Steevan](https://github.com/steevanb) Symfony 5 dependencies allowed.
- [Steevan](https://github.com/steevanb) Add `list.symfonyVersionVerbosityLevel` configuration.
- [Steevan](https://github.com/steevanb) Add `list.usageVerbosityLevel` configuration.
- [Steevan](https://github.com/steevanb) Add `list.optionsVerbosityLevel` configuration.
- [Steevan](https://github.com/steevanb) Add `list.availableCommandsVerbosityLevel` configuration.

### [1.1.1](../../compare/1.1.0...1.1.1) - 2016-05-29

- [Steevan](https://github.com/steevanb) Fix _Application::getExcludedCommands()_ method name.
- [Steevan](https://github.com/steevanb) Fix _UpdateAllDatabaseSchemaCommand_ when _doctrine:schema:db-update_ fail.

### [1.1.0](../../compare/1.0.0...1.1.0) - 2016-05-06

- [Steevan](https://github.com/steevanb) Add _doctrine:schema:db-update DATABASE_ command, to update only DATABASE.
- [Steevan](https://github.com/steevanb) Add replacement for _doctrine:schema:update_, to update all configured databases in console.databases.

### 1.0.0 - 2016-05-05

- [Steevan](https://github.com/steevanb) Add _console.excluded_ configuration to _bin/console_, to excluded commands.

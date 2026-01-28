# Changelog

All notable changes to `opensid/laravel-ci3` will be documented in this file.

## [1.0.0] - 2025-01-XX

### Added

- **Complete Laravel-CI3 Integration Package**
  - CodeIgniterFallback middleware for routing fallback
  - CodeIgniterServiceProvider for CI3 bootstrapping
  - CI3RouteServiceProvider for route registration
  - LaravelBridge trait for Laravel helper access in CI3

- **Configuration System**
  - Publishable `config/ci3.php` configuration
  - Environment variable support (CI3_SYSTEM_PATH, CI3_APPLICATION_PATH, etc.)
  - Configurable paths (system_path, application_path, modules_path)
  - Environment mapping (Laravel env → CI3 env)
  - Auto-load helpers configuration

- **CI3 Services**
  - CI3Bootstrap service for complete CI3 initialization
  - CI3Instance service for singleton CI3 instance
  - DebugBar service for Laravel DebugBar integration

- **Module Support**
  - Automatic module route loading via OpenSID\ModuleRouter
  - Module path configuration
  - Module hooks registration

- **Session Bridge**
  - Seamless session sharing between Laravel and CI3
  - MY_Session override for Laravel session integration
  - Prevent double session initialization

- **Response Handling**
  - Support for Laravel Response objects
  - Support for Laravel View objects
  - Support for CI3 traditional output
  - Automatic DebugBar injection into responses

- **Helper Functions**
  - LaravelBridge trait provides 15+ helper methods in CI3 controllers
  - config(), cache(), log(), user(), validate(), etc.
  - Laravel Facades for CI3 (LSession, LCache, LLog, LDB, LConfig)

- **Documentation**
  - Comprehensive README with installation and usage guide
  - CI3_SETUP.md with complete CI3 project setup guide
  - Helper file templates with examples
  - Configuration examples and best practices

### Changed

- Migrated all integration components from app/* to package
- Changed logging from \Log:: to logger() helper
- Made all paths configurable via config file

### Fixed

- Fixed ParseError in CodeIgniterFallback
- Fixed session initialization issues
- Fixed config merging errors
- Fixed namespace references after migration

### Removed

- Removed hard-coded paths from CI3Bootstrap
- Removed direct Log facade usage

## [0.1.0] - Initial Development

### Added

- Initial package structure
- Basic CI3 bootstrapping
- Simple middleware implementation

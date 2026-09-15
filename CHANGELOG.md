# Changelog

## v5.0.0

- Upgrade to Filament v5, Livewire 4 and Laravel 12 / 13 (PHP 8.2+).
- Requires `tomatophp/filament-developer-gate` ^5.0.
- The page route middleware now comes from the `middlewares` config key instead of a hardcoded `auth` + `verified` list (#12 by @mgralikowski).
- Command output is shown in a readable, monospaced and escaped block (#13 by @mgralikowski).
- Fix 404 after installation: the developer gate logout and redirect now follow the current panel path instead of a hardcoded `admin/developer-gate` (#10).
- Security: only commands listed in the `commands` config can be run, any other registered command is refused with a 403; only known arguments/options of the command are passed through.
- Security: the `local` flag is now enforced on page access, not only on the navigation item.
- New plugin options: `->authorize(bool|Closure)`, `->developerGate(bool)` and `->onlyLocal(bool)`.
- New `defer.filters` / `defer.columns` config keys (idea from #18 by @JGeraeds).
- Removed the no-op nwidart module detection.
- Added a Pest test suite.

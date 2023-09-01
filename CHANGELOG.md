# Changelog
All notable changes to **Gandi Registrar for WHMCS** are documented in this *changelog*.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and **Gandi Registrar for WHMCS** adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [5.2.0] - Not Yet Released
### Added
- Global constant `GANDI_ELEMENTS` now contains `external` key which indicates DNS are external.
### Fixed
- Fixes wrong registration min duration for domain having discount.

## [5.1.0] - 2023-08-04
### Added
- Management of multivalued records.
- A global constant `GANDI_ELEMENTS` is now defined to propagate domain information in hooks.
- A smarty `{$lockable}` variable is now assigned for domain details templates: boolean value indicating if TLD supports registrar locking.

### Changed
- DNS records updates are now done via PUT verb (full zone replacement) which is faster.
- TXT records with same names are now allowed.
- The modal boxes are now compatible with Safari.

## [5.0.0] - 2022-12-30
Initial release, starting at 5.0.0 to match supported Gandi API version. 
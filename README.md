# Gandi Registrar for WHMCS

Use Gandi.net as registrar in WHMCS.

![PHP 7.x Compatibility](https://img.shields.io/badge/PHP-7.x-7c86b4?style=flat-square) ![WHCMS 8.5.x Compatibility](https://img.shields.io/badge/WHMCS-8.5.x-96be4f?style=flat-square)

## Features
### Global
- DNS support for self managed and Gandi LiveDNS
- Automatic query caching
### Per TLD 
- x
### Per domain
- Registration operations: **_supported_**
- Renewal operations: **_supported_**
- Transfer operations: **_supported_**
- Transfer protection (lock/unlock): **_supported_**
- EPP management: **_supported_**
- WHOIS privacy protection: **_supported_** (see following note about IDProtect)
- Delete operations: **_not supported_**
- Release operations: **_not supported_**

> 💡️️ The WHMCS IDProtect operations are not implemented in this module: by default, all anonymization options are enabled for TLDs supporting it, whatever is specified for each individual TLD.

## How to install
1. In your WHCMS installation, in the `modules/registrars`, create a `gandi` directory.
2. Download the last [release](https://github.com/Hosterra/whmcs-gandi/releases).
3. Unzip & copy all files in the `gandi` directory.
4. Activate the addon in the **_Setup->Domain Registrars_** section of WHMCS admin.

## How to configure


## Contributing

Before submitting an issue or a pull request, please read the [contribution guidelines](CONTRIBUTING.md).

> ⚠️ The `master` branch is the current development state of the module. If you want a stable, production-ready version, please pick the last official [release](https://github.com/Hosterra/whmcs-gandi/releases).

## Attribution
Gandi Registrar for WHMCS is a fork of [Gandi/whmcs_Gandi-module](https://github.com/Gandi/whmcs_Gandi-module) which has been archived during spring 2022. Thank you, Gandi, for starting such a module! 

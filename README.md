# Gandi Registrar for WHMCS

Use Gandi.net as registrar in WHMCS.

![PHP 7.x Compatibility](https://img.shields.io/badge/PHP-7.x-7c86b4?style=flat-square) ![WHCMS 8.5.x Compatibility](https://img.shields.io/badge/WHMCS-8.5.x-96be4f?style=flat-square)

## Features
### Global
- DNS selection between self managed and Gandi LiveDNS
- Organisation selection (with individual, company and reseller supported types)
### Per domain
- Registration
- Renewal
- Transfer
- Protection (lock/unlock)
- EPP management

## How to install
1. In your WHCMS installation, in the `modules/registrars`, create a `gandi` directory.
2. Download the last [release](https://github.com/Hosterra/whmcs-gandi/releases).
3. Unzip & copy all files in the `gandi` directory.
4. Activate the addon in the **_Setup->Domain Registrars_** section of WHMCS admin.

## How to configure
There's currently 2 levels of settings:
### Standard settings
### Advanced settings and tweaks
In the file `gandi/config.php` you will find some way to tweak the behavior of Gandi Registrar for WHMCS. To do so, just change values in the global array named `GANDI_REGISTRAR_OPTIONS`. The meaning of the keys are:
- `allowNameserversChange` - _(boolean)_ - If false, it will fully prevent the customer to change the name servers. It is only useful if you choose Gandi LiveDNS as DNS.

## Contributing

Before submitting an issue or a pull request, please read the [contribution guidelines](CONTRIBUTING.md).

> ⚠️ The `master` branch is the current development state of the module. If you want a stable, production-ready version, please pick the last official [release](https://github.com/Hosterra/whmcs-gandi/releases).

## Attribution
Gandi Registrar for WHMCS is a fork of [Gandi/whmcs_Gandi-module](https://github.com/Gandi/whmcs_Gandi-module) which has been archived during spring 2022. Thank you, Gandi, for starting such a module! 

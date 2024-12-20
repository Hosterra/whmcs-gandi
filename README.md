THIS REPOSITORY IS NOW ARCHIVED - YOU CAN THANK GANDI AND ITS OUTRAGEOUS PRICING POLICY FOR PERMANENTLY DEMOTIVATING ALL SMALL BUSINESSES TO WORK WITH/FOR THEM…

# Gandi Registrar for WHMCS

Use Gandi.net as registrar in WHMCS.

![PHP 8.x Compatibility](https://img.shields.io/badge/PHP-8.x-7c86b4?style=flat-square) ![WHCMS 8.7.x Compatibility](https://img.shields.io/badge/WHMCS-8.7.x-96be4f?style=flat-square)

## Features
- TLD prices import: **_supported_**
- TLD grace and redemption import: **_supported_**
- Domain registration operations: **_supported_**
- Domain renewal operations: **_supported_**
- Domain transfer operations: **_supported_**
- Domain transfer protection (lock/unlock): **_supported_**
- Domain EPP management: **_supported_**
- Name servers management: **_supported_**
- DNNSEC operations: **_supported_** (only zsk)
- DNS records creation, update and deletion: **_supported_**
- DNS templates for registration and transfer: **_supported_**
- DNS snapshots creation, restoration and deletion: **_supported_**
- DNS ttl management: **_supported_** (needs template customization)
- Extended DNS record types: **_supported_** (needs template customization)
- WHOIS privacy protection: **_supported_** (see following note about ID Protect)
- Domain delete operations: **_not supported_**
- Domain release operations: **_not supported_**
- Vanity name servers: **_not supported_**

> 💡️️ The WHMCS IDProtect operations are not implemented in this module: by default, all anonymization options are enforced to `true` for TLDs supporting it, whatever is specified for each individual TLD. There is no reason why privacy should be optional!

## How to install
1. In your WHCMS installation, in the `modules/registrars/`, create a `gandi` directory.
2. Download the latest [release](https://github.com/Hosterra/whmcs-gandi/releases).
3. Unzip & copy all files in the `gandi` directory.
4. Make a symbolic link named `resources/domains/additionalfields.php` pointing to `modules/registrars/gandi/resources/domains/additionalfields.php`.
5. Activate the addon in the **_Setup->Domain Registrars_** section of WHMCS admin.

## How to configure
In the **_Setup->Domain Registrars_** section of WHMCS admin, you have access to the following settings:
- _API key_: set it your own, personal, API key for Gandi APIs.
- _Organization_: select the organization to which domains operations will be billed.
- _Registration / transfer templates_: chose the DNS templates to apply after registration / transfer success (these templates are set in your gandi reseler account).
- _DNS to use_: if you choose Gandi Live DNS, all domain registrations will specify Gandi LiveDNS as DNS and all DNSSEC and snapshots options will be available to your customers (see note below).
- _DNS record types_: choose extended to take full advantage of Gandi LiveDNS (needs template customization)
- _Security, privacy & performance_: check to display a summary in the domain details page of the client area.

> 💡️️ Gandi Registrar for WHMCS allows you to delegate your DNS handling to Gandi LiveDNS. To do so, you have to select "Gandi LiveDNS" in this module settings and have `livedns.gandi.net` as default name servers in WHMCS main domains config. Note `livedns.gandi.net` is not an existing host, it's just the easiest way to fill some gaps in the WHMCS APIs.

## Languages

To have this module's interface in your own language, you have to copy the content of the right file located in `/resources/lang/` directory and paste it in your main WHMCS language file.

If you have translated these strings in a not yet supported language, please [share it](CONTRIBUTING.md) :heart: 

## Uncovered TLDs
All [TLDs managed by Gandi](https://www.gandi.net/en/domain/tld) are registrable except the following:
- TLDs reserved for Gandi Corporate Services (see [list](resources/domains/corporateservices.php));
- TLDs that require heavy development work to be integrated into the WHMCS workflow (see [list](resources/domains/excluded.php)).

## Contributing

Before submitting an issue or a pull request, please read the [contribution guidelines](CONTRIBUTING.md).

> ⚠️ The `master` branch is the current development state of the module. If you want a stable, production-ready version, please pick the last official [release](https://github.com/Hosterra/whmcs-gandi/releases).

## Attribution
Gandi Registrar for WHMCS is a fork of [Gandi/whmcs_Gandi-module](https://github.com/Gandi/whmcs_Gandi-module) which has been archived during spring 2022. Thank you, Gandi, for starting such a module! 

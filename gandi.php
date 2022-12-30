<?php

if ( ! defined( "WHMCS" ) ) {
	die( "This file cannot be accessed directly" );
}

define( 'GANDI_REGISTRAR_PRODUCT_NAME', 'Gandi Registrar' );
define( 'GANDI_REGISTRAR_PRODUCT_URL', 'https://github.com/Hosterra/whmcs-gandi' );
define( 'GANDI_REGISTRAR_API_VERSION', '5' );
define( 'GANDI_REGISTRAR_VERSION', '5.0.0' );
define( 'GANDI_RESOURCE_DIR', dirname( __FILE__ ) . '/resources/' );
define( 'GANDI_CONTACT_TYPES', [ 'individual', 'company', 'association', 'publicbody', 'reseller' ] );
define( 'GANDI_CALL_LONG_WAIT', 15 );       // Gandi may take some times to process API calls, so let's wait before refreshing
define( 'GANDI_CALL_SHORT_WAIT', 5 );       // Gandi may take some times to process API calls, so let's wait before refreshing
define( 'GANDI_LOG_CACHE', false );


use WHMCS\Carbon;
use WHMCS\Domain\TopLevel\ImportItem;
use WHMCS\Results\ResultsList;
use WHMCS\Domain\Domain as iDomain;
use WHMCS\Domain\Registrar\Domain;
use WHMCS\Domains\DomainLookup\ResultsList as LookupResultsList;
use WHMCS\Domains\DomainLookup\SearchResult;
use WHMCS\Module\Registrar\Gandi\domainAPI;
use WHMCS\Module\Registrar\Gandi\LiveDNS;
use WHMCS\Module\Registrar\Gandi\ERRPolicy;
use WHMCS\Module\Registrar\Gandi\DNSSEC;
use WHMCS\Module\Registrar\Gandi\dnsSnaphot;

require_once dirname( __FILE__ ) . '/lib/LiveDNS.php';

/**
 * Define module related metadata
 *
 * Provide some module information including the display name and API Version to
 * determine the method of decoding the input values.
 *
 * @return array
 */
function gandi_MetaData() {
	return [
		'DisplayName' => GANDI_REGISTRAR_PRODUCT_NAME . ' v' . GANDI_REGISTRAR_VERSION,
		'APIVersion'  => '1.1',
	];
}

/**
 * Verify if a template file is in use.
 *
 * @return bool
 */
function gandi_IsTemplatedBy( string $template ) {
	$test = '';
	if ( is_array( $GLOBALS ) && array_key_exists( 'ca', $GLOBALS ) && $GLOBALS['ca'] instanceof WHMCS\ClientArea && method_exists( $GLOBALS['ca'], 'getTemplateFile' ) ) {
		$test = $GLOBALS['ca']->getTemplateFile();
		if ( is_scalar( $test ) ) {
			$test = (string) $test;
		} else {
			$test = '';
		}
	}

	return ( $template === $test );

}

/**
 * Get TLDs details for specific action
 *
 * @return array
 */
function gandi_GetTLDs( $params, $action ) {
	try {
		$api      = new domainAPI( $params['apiKey'], $params['organization'] );
		$response = $api->getTLDPrices( $action );
		if ( ( isset( $response->code ) && 202 !== (int) $response->code ) || isset( $response->errors ) ) {
			return [];
		}
		$result                     = [];
		$result['misc']['currency'] = (string) $response->currency;
		$result['tlds']             = [];
		foreach ( $response->products as $product ) {
			if ( 'available' === (string) $product->status ) {
				$p    = 0.0;
				$minY = 10;
				$maxY = 1;
				foreach ( $product->prices as $price ) {
					switch ( $action ) {
						case 'create':
							if ( 'golive' === (string) $price->options->phase && ! $price->discount ) {
								$p    = max( $p, (float) $price->price_before_taxes );
								$minY = min( $minY, $price->min_duration );
								$maxY = max( $maxY, $price->max_duration );
							}
							break;
						case 'renew':
						case 'transfer':
						case 'restore':
							if ( ! $price->discount ) {
								$p = max( $p, (float) $price->price_before_taxes );
							}
							break;
					}
				}
				if ( 0.0 < $p ) {
					$result['tlds'][ $product->name ][ $action ] = $p;
					if ( 'create' === $action ) {
						$result['tlds'][ $product->name ]['minY'] = $minY;
						$result['tlds'][ $product->name ]['maxY'] = $maxY;
					}
				}
			}
		}

		return $result;
	} catch ( \Exception $e ) {
		return [];
	}
}

/**
 * Clean and normalize contact for outputting it
 *
 * @return array
 */
function gandi_NormalizeContactOutput( $contact ) {
	if ( array_key_exists( 'phone', $contact ) ) {
		$contact['Phone'] = $contact['phone'];
	}
	if ( array_key_exists( 'type', $contact ) ) {
		if ( is_int( $contact['type'] ) && $contact['type'] < count( GANDI_CONTACT_TYPES ) ) {
			$contact['type'] = GANDI_CONTACT_TYPES[ $contact['type'] ];
		}
	} else {
		$contact['type'] = GANDI_CONTACT_TYPES[0];
	}
	if ( ! in_array( $contact['type'], GANDI_CONTACT_TYPES ) ) {
		$contact['type'] = GANDI_CONTACT_TYPES[0];
	}
	$sortedContact = [];
	foreach (
		[
			'type',
			'orgname',
			'given',
			'family',
			'email',
			'Phone',
			'streetaddr',
			'city',
			'zip',
			'country'
		] as $key
	) {
		$sortedContact[ $key ] = $contact[ $key ] ?? '';
	}

	return $sortedContact;
}

/**
 * Clean and normalize contact before saving
 *
 * @return array
 */
function gandi_NormalizeContactInput( $contact ) {
	$items = [];
	foreach (
		[
			'type'       => [],
			'orgname'    => [ 'Organisation Name', 'Company Name' ],
			'given'      => [ 'First Name' ],
			'family'     => [ 'Last Name' ],
			'email'      => [ 'Email', 'Email Address' ],
			'Phone'      => [ 'Phone Number' ],
			'streetaddr' => [ 'Address', 'Address 1' ],
			'city'       => [ 'City' ],
			'zip'        => [ 'Postcode', 'ZIP', 'ZIP Code' ],
			'country'    => [ 'Country' ]
		] as $key => $subst
	) {
		$items[ strtolower( $key ) ] = $contact[ $key ] ?? '';
		if ( '' === $items[ strtolower( $key ) ] ) {
			foreach ( $subst as $try ) {
				if ( array_key_exists( $try, $contact ) && '' !== $contact[ $try ] ) {
					$items[ strtolower( $key ) ] = $contact[ $try ];
					break;
				}
			}
		}
	}
	if ( '' === $items['orgname'] ) {
		$items['type'] = 'individual';
	}

	return $items;
}

/**
 * Modify contacts fields and translations before rendering forms
 *
 * @return array
 */
function gandi_ModifyContacts( $vars ) {
	$contactdetailstranslations = [];
	foreach (
		[
			'type',
			'orgname',
			'given',
			'family',
			'email',
			'Phone',
			'streetaddr',
			'city',
			'zip',
			'country'
		] as $key
	) {
		$contactdetailstranslations[ $key ] = Lang::Trans( 'gandiadmin.contact.' . $key );
	}
	$contacttypestranslations = [];
	foreach ( [ 'owner', 'technical', 'admin', 'billing' ] as $key ) {
		$contacttypestranslations[ $key ] = Lang::Trans( 'gandiadmin.contact.' . $key );
	}
	$entitytranslations = [];
	foreach ( [ 'individual', 'company', 'association', 'publicbody' ] as $key ) {
		$entitytranslations[ $key ] = Lang::Trans( 'gandiadmin.entity.' . $key );
	}
	$filename = GANDI_RESOURCE_DIR . 'countries/countrycodes.php';
	if ( file_exists( $filename ) ) {
		include $filename;
	} else {
		$country_list = [];
	}

	return [
		'contactdetailstranslations' => $contactdetailstranslations,
		'contacttypestranslations'   => $contacttypestranslations,
		'entitytranslations'         => $entitytranslations,
		'countrylist'                => $country_list,
	];
}

/**
 * Perfom post process after registration or inbound transfer
 *
 * @return array
 */
function gandi_DomainPostProcess( $vars ) {
	if ( $vars['params']['domain'] ) {
		$domain = iDomain::where( 'domain', $vars['params']['domain'] )->first();
		if ( $domain ) {
			$domain->hasDnsManagement = true;
			$domain->save();
		}
	}
}

/**
 * Define registrar configuration options.
 *
 * The values you return here define what configuration options
 * we store for the module. These values are made available to
 * each module function.
 *
 * You can store an unlimited number of configuration settings.
 * The following field types are supported:
 *  * Text
 *  * Password
 *  * Yes/No Checkboxes
 *  * Dropdown Menus
 *  * Radio Buttons
 *  * Text Areas
 *
 * @return array
 */
function gandi_getConfigArray( $params ) {
	if ( array_key_exists( 'apiKey', $params ) && '' !== $params['apiKey'] ) {
		try {
			$api              = new domainAPI( $params['apiKey'], $params['organization'] ?? '' );
			$organizationList = [];
			$organizations    = $api->getOrganizations();
			if ( is_array( $organizations ) ) {
				foreach ( $organizations as $organization ) {
					$organizationsList[ $organization->id ] = $organization->name;
				}
			}
			$templateList = [];
			$templates    = $api->getTemplates();
			if ( is_array( $templates ) ) {
				foreach ( $templates as $template ) {
					$templatesList[ $template->id ] = $template->name;
				}
			}
		} catch ( Exception $e ) {
			// How/where to log?
		}

		return [
			'FriendlyName' => [
				'Type'  => 'System',
				'Value' => GANDI_REGISTRAR_PRODUCT_NAME,
			],
			'apiKey'       => [
				'FriendlyName' => Lang::Trans( 'gandiadmin.apikey' ),
				'Type'         => 'password',
				'Size'         => '100',
			],
			'organization' => [
				'FriendlyName' => Lang::Trans( 'gandiadmin.organization' ),
				'Type'         => 'dropdown',
				'Options'      => $organizationsList,
			],
			'registertemplate' => [
				'FriendlyName' => Lang::Trans( 'gandiadmin.registertemplate' ),
				'Type'         => 'dropdown',
				'Options'      => $templatesList,
			],
			'transfertemplate' => [
				'FriendlyName' => Lang::Trans( 'gandiadmin.transfertemplate' ),
				'Type'         => 'dropdown',
				'Options'      => $templatesList,
			],
			'dns'          => [
				'FriendlyName' => Lang::Trans( 'gandiadmin.dns.name' ),
				'Type'         => 'dropdown',
				'Options'      => [
					'livedns' => Lang::Trans( 'gandiadmin.dns.livedns' ),
					'whmcs'   => Lang::Trans( 'gandiadmin.dns.whmcs' ),
				],
			],
			'recordset'    => [
				'FriendlyName' => Lang::Trans( 'gandiadmin.recordset.name' ),
				'Type'         => 'dropdown',
				'Options'      => [
					'standard' => Lang::Trans( 'gandiadmin.recordset.standard' ),
					'extended' => Lang::Trans( 'gandiadmin.recordset.extended' ),
				],
			],
			'secprev'      => [
				'FriendlyName' => Lang::Trans( 'gandiadmin.secprev.name' ),
				'Type'         => 'yesno',
				'Description'  => Lang::Trans( 'gandiadmin.secprev.check' ),
			],
			'version'      => [
				'FriendlyName' => GANDI_REGISTRAR_PRODUCT_NAME . ' module v' . GANDI_REGISTRAR_VERSION,
				'Type'         => 'text',
				'Size'         => '100',
				'Disabled'     => true,
				'Placeholder'  => 'Sponsored by Hosterra - https://hosterra.eu',
			],
		];
	}

	return [
		'FriendlyName' => [
			'Type'  => 'System',
			'Value' => GANDI_REGISTRAR_PRODUCT_NAME,
		],
		'apiKey'       => [
			'FriendlyName' => Lang::Trans( 'gandiadmin.apikey' ),
			'Type'         => 'password',
			'Size'         => '100',
		],
		'version'      => [
			'FriendlyName' => GANDI_REGISTRAR_PRODUCT_NAME . ' module v' . GANDI_REGISTRAR_VERSION,
			'Type'         => 'text',
			'Size'         => '100',
			'Disabled'     => true,
			'Placeholder'  => Lang::Trans( 'gandiadmin.sponsor' ),
		],
	];
}

/**
 * Check Domain Availability.
 *
 * Determine if a domain or group of domains are available for
 * registration or transfer.
 *
 * @param array $params common module parameters
 *
 * @return \WHMCS\Domains\DomainLookup\ResultsList An ArrayObject based collection of \WHMCS\Domains\DomainLookup\SearchResult results
 * @throws Exception Upon domain availability check failure.
 *
 * @see \WHMCS\Domains\DomainLookup\ResultsList
 *
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 * @see \WHMCS\Domains\DomainLookup\SearchResult
 */
function gandi_CheckAvailability( $params ) {
	try {
		$results = new LookupResultsList();
		$sld     = $params['sld'];
		$api     = new domainAPI( $params['apiKey'], $params['organization'] );
		foreach ( $params['tlds'] as $tld ) {
			$tld          = str_replace( '.', '', $tld );
			$searchResult = new SearchResult( $sld, $tld );
			$domain       = $sld . '.' . $tld;
			$availability = $api->getDomainAvailability( $domain );
			if ( 'available' === $availability ) {
				$status = SearchResult::STATUS_NOT_REGISTERED;
			} else {
				$status = SearchResult::STATUS_REGISTERED;
			}
			$searchResult->setStatus( $status );
			$results->append( $searchResult );
		}

		return $results;
	} catch ( \Exception $e ) {
		return new LookupResultsList();
	}
}

/**
 * Get current tld pricing
 *
 * @return WHMCS\Results\ResultsList
 */
function gandi_GetTldPricing( $params ) {
	$filename = GANDI_RESOURCE_DIR . 'domains/tlds.php';
	if ( file_exists( $filename ) ) {
		include $filename;
	} else {
		$enabled_tlds = [];
	}
	$currency    = 'USD';
	$results     = new ResultsList;
	$creation    = gandi_GetTLDs( $params, 'create' );
	$renewal     = gandi_GetTLDs( $params, 'renew' );
	$transfer    = gandi_GetTLDs( $params, 'transfer' );
	$restoration = gandi_GetTLDs( $params, 'restore' );
	if ( array_key_exists( 'misc', $creation ) && array_key_exists( 'currency', $creation['misc'] ) ) {
		$currency = $creation['misc']['currency'];
	}
	if ( array_key_exists( 'tlds', $creation ) && is_array( $creation['tlds'] ) ) {
		foreach ( $creation['tlds'] as $tld => $price ) {
			if ( ! in_array( $tld, $enabled_tlds ) ) {
				continue;
			}
			if ( array_key_exists( 'create', $price ) && array_key_exists( 'minY', $price ) && array_key_exists( 'maxY', $price ) ) {
				$item = new ImportItem;
				$item->setExtension( $tld );
				$item->setCurrency( $currency );
				$item->setEppRequired( true );
				$item->setMinYears( $price['minY'] );
				$item->setMaxYears( $price['maxY'] );
				$item->setRegisterPrice( $price['create'] );
				if ( array_key_exists( 'tlds', $renewal ) && is_array( $renewal['tlds'] ) && array_key_exists( $tld, $renewal['tlds'] ) && is_array( $renewal['tlds'][ $tld ] ) && array_key_exists( 'renew', $renewal['tlds'][ $tld ] ) ) {
					$item->setRenewPrice( $renewal['tlds'][ $tld ]['renew'] );
					$item->setGraceFeePrice( $renewal['tlds'][ $tld ]['renew'] );
					$item->setGraceFeeDays( ERRPolicy::getGrace( $tld ) );
				}
				if ( array_key_exists( 'tlds', $transfer ) && is_array( $transfer['tlds'] ) && array_key_exists( $tld, $transfer['tlds'] ) && is_array( $transfer['tlds'][ $tld ] ) && array_key_exists( 'transfer', $transfer['tlds'][ $tld ] ) ) {
					$item->setTransferPrice( $transfer['tlds'][ $tld ]['transfer'] );
				}
				if ( array_key_exists( 'tlds', $restoration ) && is_array( $restoration['tlds'] ) && array_key_exists( $tld, $restoration['tlds'] ) && is_array( $restoration['tlds'][ $tld ] ) && array_key_exists( 'restore', $restoration['tlds'][ $tld ] ) ) {
					$item->setRedemptionFeePrice( $restoration['tlds'][ $tld ]['restore'] );
					$item->setRedemptionFeeDays( ERRPolicy::getRedemption( $tld ) );
				}
				$results[] = $item;
			}
		}
	}

	return $results;
}

/**
 * Get the current WHOIS Contact Information.
 *
 * Should return a multi-level array of the contacts and name/address
 * fields that be modified.
 *
 * @param array $params common module parameters
 *
 * @return array
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 */
function gandi_GetContactDetails( $params ) {
	$sld    = $params['sld'];
	$tld    = $params['tld'];
	$domain = $sld . '.' . $tld;
	try {
		$api      = new domainAPI( $params['apiKey'], $params['organization'] );
		$info     = $api->getDomainInfo( $domain );
		$contacts = $info->contacts ?? $api->getDomainContacts( $domain );

		return [
			'Owner'     => gandi_NormalizeContactOutput( (array) $contacts->owner ),
			'Technical' => gandi_NormalizeContactOutput( (array) $contacts->tech ),
			'Billing'   => gandi_NormalizeContactOutput( (array) $contacts->bill ),
			'Admin'     => gandi_NormalizeContactOutput( (array) $contacts->admin ),
		];
	} catch ( \Exception $e ) {
		return [
			'error' => $e->getMessage(),
		];
	}
}

/**
 * Update the WHOIS Contact Information for a given domain.
 *
 * Called when a change of WHOIS Information is requested within WHMCS.
 * Receives an array matching the format provided via the `GetContactDetails`
 * method with the values from the users input.
 *
 * @param array $params common module parameters
 *
 * @return array
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 */
function gandi_SaveContactDetails( array $params ) {
	$sld    = $params['sld'];
	$tld    = $params['tld'];
	$domain = $sld . '.' . $tld;
	try {
		$contacts = [];
		foreach ( [ 'Technical', 'Billing', 'Admin' ] as $contact ) {
			$contacts[ $contact ] = gandi_NormalizeContactInput( $params['contactdetails'][ $contact ] );
		}
		$api      = new domainAPI( $params['apiKey'], $params['organization'] );
		$response = $api->updateDomainContacts( $domain, $contacts );
		if ( ( isset( $response->code ) && 202 !== (int) $response->code ) || isset( $response->errors ) ) {
			return [
				'error' => json_encode( $response )
			];
		}
		$api->invalidateCache( $domain . '/contacts' );
		sleep( GANDI_CALL_LONG_WAIT );

		return [
			'success' => 'success',
		];
	} catch ( \Exception $e ) {
		return [
			'error' => $e->getMessage(),
		];
	}
}

/**
 * Fetch current nameservers.
 *
 * This function should return an array of nameservers for a given domain.
 *
 * @param array $params common module parameters
 *
 * @return array
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 */
function gandi_GetNameservers( $params ) {
	if ( gandi_IsTemplatedBy( 'clientareadomaincontactinfo' ) ) {
		return [];
	}
	$sld    = $params['sld'];
	$tld    = $params['tld'];
	$domain = $sld . '.' . $tld;
	try {
		$api     = new domainAPI( $params['apiKey'], $params['organization'] );
		$info    = $api->getDomainInfo( $domain );
		$request = $info->nameservers ?? $api->getDomainNameservers( $domain );
		if ( ! is_array( $request ) ) {
			return [
				'success' => false
			];
		}
		$response = [ 'ns1' => 'livedns.gandi.net' ];
		if ( 'livedns' !== $params['dns'] || ! LiveDNS::isCorrect( $request ) ) {
			foreach ( $request as $k => $v ) {
				$index                     = $k + 1;
				$response[ 'ns' . $index ] = $v;
			}
		}

		return $response;
	} catch ( \Exception $e ) {
		return [
			'error' => $e->getMessage(),
		];
	}
}

/**
 * Save nameserver changes.
 *
 * This function should submit a change of nameservers request to the
 * domain registrar.
 *
 * @param array $params common module parameters
 *
 * @return array
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 */
function gandi_SaveNameservers( $params ) {
	$sld         = $params['sld'];
	$tld         = $params['tld'];
	$domain      = $sld . '.' . $tld;
	$nameservers = [];
	if ( $params['ns1'] ) {
		$nameservers[] = $params['ns1'];
	}
	if ( $params['ns2'] ) {
		$nameservers[] = $params['ns2'];
	}
	foreach ( $nameservers as $k => $v ) {
		if ( LiveDNS::isCorrect( [ $v ] ) ) {
			unset( $nameservers[ $k ] );
		}
	}
	$nameservers = array_filter( $nameservers );
	try {
		$api       = new domainAPI( $params['apiKey'], $params['organization'] );
		$snapshots = dnsSnaphot::instance( $params['apiKey'], $domain );
		$test      = (array) $api->getLiveDNSInfo( $domain );
		if ( 'livedns' === $params['dns'] && LiveDNS::isCorrect( $nameservers ) ) {
			$request = $api->enableLiveDNS( $domain );
			if ( 'livedns' !== ( $test['current'] ?? '-' ) ) {
				$snap = $snapshots->getMostRecent();
				if ( '-' !== $snap['id'] ) {
					$snapshots->restoreNow( $snap['id'] );
				}
			}
		} else {
			if ( 'livedns' === ( $test['current'] ?? '-' ) ) {
				$snapshots->takeNow( 'NS switching' );
			}
			$request = $api->updateDomainNameservers( $domain, $nameservers );
		}
		if ( ( isset( $request->code ) && $request->code != 202 && $request->code != 409 ) || isset( $request->errors ) ) {
			throw new \Exception( json_encode( $request ) );
		}
		$domain_def = iDomain::where( 'domain', $domain )->first();
		if ( 'livedns' === $params['dns'] && LiveDNS::isCorrect( $nameservers ) ) {
			$domain_def->hasDnsManagement = true;
			$domain_def->save();
		} else {
			$domain_def->hasDnsManagement = false;
			$domain_def->save();
		}
		$api->invalidateCache( $domain . '/nameservers' );
		sleep( GANDI_CALL_LONG_WAIT );

		return [
			'success' => true,
		];
	} catch ( \Exception $e ) {
		return [
			'error' => $e->getMessage(),
		];
	}
}

/**
 * Get registrar lock status.
 *
 * Also known as Domain Lock or Transfer Lock status.
 *
 * @param array $params common module parameters
 *
 * @return string|array Lock status or error message
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 */
function gandi_GetRegistrarLock( $params ) {
	$sld    = $params['sld'];
	$tld    = $params['tld'];
	$domain = $sld . '.' . $tld;
	try {
		$api      = new domainAPI( $params['apiKey'], $params['organization'] );
		$response = $api->getDomainInfo( $domain );
		if ( ( isset( $response->code ) && 202 !== (int) $response->code ) || isset( $response->errors ) ) {
			return [
				'error' => json_encode( $response )
			];
		}
		if ( is_array( $response->status ) ) {
			if ( in_array( 'clientTransferProhibited', $response->status ) && $response->can_tld_lock ) {
				return 'locked';
			}

			return 'unlocked';
		} else {
			return [
				'error' => 'No information about lock/unlock status'
			];
		}
	} catch ( \Exception $e ) {
		return [
			'error' => $e->getMessage(),
		];
	}
}

/**
 * Set registrar lock status.
 *
 * @param array $params common module parameters
 *
 * @return array
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 */
function gandi_SaveRegistrarLock( $params ) {
	$sld        = $params['sld'];
	$tld        = $params['tld'];
	$domain     = $sld . '.' . $tld;
	$lockStatus = $params['lockenabled'];
	try {
		$api      = new domainAPI( $params['apiKey'], $params['organization'] );
		$response = $api->setLockDomain( $domain, 'locked' === $lockStatus );
		if ( ( isset( $response->code ) && 202 !== (int) $response->code ) || isset( $response->errors ) ) {
			return [
				'error' => json_encode( $response )
			];
		}
		sleep( GANDI_CALL_LONG_WAIT );

		return [
			'success' => 'success',
		];
	} catch ( \Exception $e ) {
		return [
			'error' => $e->getMessage(),
		];
	}
}

/**
 * Request EEP Code.
 *
 * Supports both displaying the EPP Code directly to a user or indicating
 * that the EPP Code will be emailed to the registrant.
 *
 * @param array $params common module parameters
 *
 * @return array
 *
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 */
function gandi_GetEPPCode( $params ) {
	$sld    = $params['sld'];
	$tld    = $params['tld'];
	$domain = $sld . '.' . $tld;
	try {
		$api     = new domainAPI( $params['apiKey'], $params['organization'] );
		$request = $api->getDomainInfo( $domain );

		return [
			'eppcode' => $request->authinfo,
		];

	} catch ( \Exception $e ) {
		return [
			'error' => $e->getMessage(),
		];
	}
}

/**
 * Resend reachability mail for contact validation
 *
 * @param array $params common module parameters
 *
 * @return array
 * @see ?
 *
 */
function gandi_ResendIRTPVerificationEmail( $params ) {
	$sld    = $params['sld'];
	$tld    = $params['tld'];
	$domain = $sld . '.' . $tld;
	try {
		$api      = new domainAPI( $params['apiKey'], $params['organization'] );
		$response = $api->resendReachabilityMail( $domain );
		if ( ( isset( $response->code ) && 202 < (int) $response->code ) ) {
			return [
				'error' => json_encode( $response )
			];
		}

		return [
			'success' => true
		];
	} catch ( \Exception $e ) {
		return [
			'error' => $e->getMessage(),
		];
	}
}

/**
 * Get DNS Records for DNS Host Record Management.
 *
 * @param array $params common module parameters
 *
 * @return array DNS Host Records
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 */
function gandi_GetDNS( $params ) {
	$filename = GANDI_RESOURCE_DIR . 'dns/recordtypes.php';
	if ( file_exists( $filename ) ) {
		include $filename;
	} else {
		$allowed_recordtypes = [ 'standard' => [], 'extended' => [] ];
	}
	$sld    = $params['sld'];
	$tld    = $params['tld'];
	$domain = $sld . '.' . $tld;
	try {
		$liveDns     = new LiveDNS( $params['apiKey'] );
		$records     = $liveDns->getLiveDnsRecords( $domain );
		$hostRecords = [];
		foreach ( $records as $record ) {
			if ( ! in_array( $record->rrset_type, $allowed_recordtypes[ $params['recordset'] ] ) ) {
				continue;
			}
			if ( 1 < count( $record->rrset_values ) ) {
				foreach ( $record->rrset_values as $k => $v ) {
					$entry = [
						'hostname' => $record->rrset_name,
						'type'     => $record->rrset_type,
						'address'  => $record->rrset_values[ $k ],
						'recid'    => $record->rrset_ttl ?? 300,
					];
					if ( $record->rrset_type == 'MX' ) {
						$valueArray        = explode( ' ', $record->rrset_values[ $k ] );
						$entry['priority'] = $valueArray[0];
						$entry['address']  = $valueArray[1];
					}
					$hostRecords[] = $entry;
				}
			} else {
				$entry = [
					'hostname' => $record->rrset_name,
					'type'     => $record->rrset_type,
					'address'  => $record->rrset_values[0],
					'recid'    => $record->rrset_ttl ?? 300,
				];
				if ( $record->rrset_type == 'MX' ) {
					$valueArray        = explode( ' ', $record->rrset_values[0] );
					$entry['priority'] = $valueArray[0];
					$entry['address']  = $valueArray[1];
				}
				$hostRecords[] = $entry;
			}
		}

		return $hostRecords;
	} catch ( \Exception $e ) {
		return [
			'error' => $e->getMessage(),
		];
	}
}

/**
 * Update DNS Host Records.
 *
 * @param array $params common module parameters
 *
 * @return array
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 */
function gandi_SaveDNS( $params ) {
	$sld    = $params['sld'];
	$tld    = $params['tld'];
	$domain = $sld . '.' . $tld;
	try {
		$whmcsRecords = $params['dnsrecords'];
		$liveDns      = new LiveDNS( $params['apiKey'] );
		$gandiRecords = $liveDns->getLiveDnsRecords( $domain );
		foreach ( $whmcsRecords as $index => $whmcsRecord ) {
			if ( is_array( $gandiRecords ) && isset( $gandiRecords[ $index ] ) ) {
				$entryToDelete = $gandiRecords[ $index ];
				$liveDns->deleteRecord( $domain, $entryToDelete );
			}
			if ( '' === $whmcsRecord['hostname'] ) {
				continue;
			}
			$response = $liveDns->addRecord( $domain, $whmcsRecord );
			if ( 404 === $response->code ) {
				return [
					'error' => "LiveDNS not enabled",
				];
			}
		}
		$liveDns->invalidateCache( '/records' );

		return [
			'success' => 'success',
		];
	} catch ( \Exception $e ) {
		return [
			'error' => $e->getMessage(),
		];
	}
}

/**
 * Register a Nameserver.
 *
 * Adds a child nameserver for the given domain name.
 *
 * @param array $params common module parameters
 *
 * @return array
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 */
function gandi_RegisterNameserver( $params ) {
	$sld    = $params['sld'];
	$tld    = $params['tld'];
	$domain = $sld . '.' . $tld;
	try {
		$api        = new domainAPI( $params['apiKey'], $params['organization'] );
		$nameserver = explode( '.', $params['nameserver'] );
		$request    = $api->registerNameserver( $domain, $nameserver[0], $params['ipaddress'] );
		if ( ( isset( $request->code ) && 202 !== $request->code ) || isset( $request->errors ) ) {
			throw new \Exception( json_encode( $request ) );
		}

		return [
			'success' => 'success',
		];
	} catch ( \Exception $e ) {
		return [
			'error' => $e->getMessage(),
		];
	}
}

/**
 * Modify a Nameserver.
 *
 * Modifies the IP of a child nameserver.
 *
 * @param array $params common module parameters
 *
 * @return array
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 */
function gandi_ModifyNameserver( $params ) {
	$sld    = $params['sld'];
	$tld    = $params['tld'];
	$domain = $sld . '.' . $tld;
	try {
		$api        = new domainAPI( $params['apiKey'], $params['organization'] );
		$nameserver = explode( '.', $params['nameserver'] );
		$request    = $api->updateNameserver( $domain, $nameserver[0], $params['newipaddress'] );
		if ( ( isset( $request->code ) && 202 !== $request->code ) || isset( $request->errors ) ) {
			throw new \Exception( json_encode( $request ) );
		}

		return [
			'success' => 'success',
		];
	} catch ( \Exception $e ) {
		return [
			'error' => $e->getMessage(),
		];
	}
}

/**
 * Delete a Nameserver.
 *
 * @param array $params common module parameters
 *
 * @return array
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 */
function gandi_DeleteNameserver( $params ) {
	$sld    = $params['sld'];
	$tld    = $params['tld'];
	$domain = $sld . '.' . $tld;
	try {
		$api        = new domainAPI( $params['apiKey'], $params['organization'] );
		$nameserver = explode( '.', $params['nameserver'] );
		$request    = $api->deleteNameserver( $domain, $nameserver[0] );
		if ( ( isset( $request->code ) && 202 !== $request->code ) || isset( $request->errors ) ) {
			throw new \Exception( json_encode( $request ) );
		}

		return [
			'success' => 'success',
		];
	} catch ( \Exception $e ) {
		return [
			'error' => $e->getMessage(),
		];
	}
}

/**
 * Sync Domain Status & Expiration Date.
 *
 * Domain syncing is intended to ensure domain status and expiry date
 * changes made directly at the domain registrar are synced to WHMCS.
 * It is called periodically for a domain.
 *
 * @param array $params common module parameters
 *
 * @return array
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 */
function gandi_Sync( $params ) {
	$sld    = $params['sld'];
	$tld    = $params['tld'];
	$domain = $sld . '.' . $tld;
	try {
		$api     = new domainAPI( $params['apiKey'], $params['organization'] );
		$request = $api->getDomainInfo( $domain );
		$code    = 200;
		if ( isset( $request->code ) ) {
			$code = $request->code;
		}
		if ( in_array( $code, [ 403, 404 ] ) ) {
			return [
				'transferredAway' => true
			];
		}
		if ( ! in_array( $code, [ 401, 403, 404 ] ) ) {
			return [
				'expirydate'      => date( 'Y-m-d', strtotime( $request->dates->registry_ends_at ) ),
				'active'          => true,
				'expired'         => strtotime( $request->dates->registry_ends_at ) < time(),
				'transferredAway' => false,
			];
		}
	} catch ( \Exception $e ) {
		return [
			'error' => $e->getMessage(),
		];
	}
}

/**
 * Incoming Domain Transfer Sync.
 *
 * Check status of incoming domain transfers and notify end-user upon
 * completion. This function is called daily for incoming domains.
 *
 * @param array $params common module parameters
 *
 * @return array
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 */
function gandi_TransferSync( $params ) {
	$sld    = $params['sld'];
	$tld    = $params['tld'];
	$domain = $sld . '.' . $tld;
	try {
		$api     = new domainAPI( $params['apiKey'], $params['organization'] );
		$request = $api->getDomainInfo( $domain );
		if ( 403 === $request->code || 404 === $request->code ) { // not finished
			return [
				'completed' => false,
				'failed'    => false
			];
		}

		return [
			'expirydate' => date( 'Y-m-d', strtotime( $request->dates->registry_ends_at ) ),
			'completed'  => true,
			'failed'     => false,
			'reason'     => '',
			'error'      => ''
		];
	} catch ( \Exception $e ) {
		return [
			'failed'    => true,
			'completed' => false,
			'reason'    => 'Transfer Error',
			'error'     => $e->getMessage()
		];
	}
}

/**
 * Register a domain.
 *
 * Attempt to register a domain with the domain registrar.
 *
 * This is triggered when the following events occur:
 * * Payment received for a domain registration order
 * * When a pending domain registration order is accepted
 * * Upon manual request by an admin user
 *
 * @param array $params common module parameters
 *
 * @return array
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 */
function gandi_RegisterDomain( $params ) {
	$sld                = $params['sld'];
	$tld                = $params['tld'];
	$domain             = $sld . '.' . $tld;
	$registrationPeriod = $params['regperiod'];
	$nameservers        = [];
	if ( 'whmcs' === $params['dns'] ) {
		$nameservers = [
			$params['ns1'],
			$params['ns2'],
			$params['ns3'],
			$params['ns4'],
			$params['ns5']
		];
	}
	$contacts          = [];
	$contacts['owner'] = [
		'firstname'            => $params['firstname'],
		'lastname'             => $params['lastname'],
		'email'                => $params['email'],
		'address'              => $params['address1'],
		'city'                 => $params['city'],
		'postcode'             => $params['postcode'],
		'countrycode'          => $params['countrycode'],
		'countryname'          => $params['countryname'],
		'phonenumber'          => $params['phonenumber'],
		'phonecountrcCode'     => $params['phonecc'],
		'phonenumberformatted' => $params['phonenumberformatted'],
		'orgname'              => $params['companyname'],
		'language'             => ( empty( $params['language'] ) ) ? $GLOBALS['CONFIG']['Language'] : $params['language']
	];
	try {
		$api          = new domainAPI( $params['apiKey'], $params['organization'] );
		$availability = $api->getDomainAvailability( $domain );
		if ( $availability !== 'available' ) {
			return [
				'error' => $availability
			];
		}
		$response = $api->registerDomain( $domain, $contacts, $nameservers, $registrationPeriod, $params['additionalfields'] ?? [], $params['registertemplate'] );
		if ( ( isset( $response->code ) && 202 !== (int) $response->code ) || isset( $response->errors ) ) {
			return [
				'error' => json_encode( $response )
			];
		}

		return [
			'success' => 'success',
		];
	} catch ( \Exception $e ) {
		return [
			'error' => $e->getMessage(),
		];
	}
}

/**
 * Initiate domain transfer.
 *
 * Attempt to create a domain transfer request for a given domain.
 *
 * This is triggered when the following events occur:
 * * Payment received for a domain transfer order
 * * When a pending domain transfer order is accepted
 * * Upon manual request by an admin user
 *
 * @param array $params common module parameters
 *
 * @return array
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 */
function gandi_TransferDomain( $params ) {
	$sld                = $params['sld'];
	$tld                = $params['tld'];
	$domain             = $sld . '.' . $tld;
	$registrationPeriod = $params['regperiod'];
	$authCode           = $params['transfersecret'];
	$nameservers        = [];
	if ( 'whmcs' === $params['dns'] ) {
		$nameservers = [
			$params['ns1'],
			$params['ns2'],
			$params['ns3'],
			$params['ns4'],
			$params['ns5']
		];
	}
	$contacts          = [];
	$contacts['owner'] = [
		'firstname'            => $params['firstname'],
		'lastname'             => $params['lastname'],
		'email'                => $params['email'],
		'address'              => $params['address1'] . $params['address2'],
		'city'                 => $params['city'],
		'postcode'             => $params['postcode'],
		'countrycode'          => $params['countrycode'],
		'countryname'          => $params['countryname'],
		'phonenumber'          => $params['phonenumber'],
		'phonecountrcCode'     => $params['phonecc'],
		'phonenumberformatted' => $params['phonenumberformatted'],
		'orgname'              => $params['companyname'],
		'language'             => ( empty( $params['language'] ) ) ? $GLOBALS['CONFIG']['Language'] : $params['language']
	];
	try {
		$api      = new domainAPI( $params['apiKey'], $params['organization'] );
		$response = $api->transferDomain( $domain, $contacts, $nameservers, $registrationPeriod, $authCode, $params['additionalfields'] ?? [], $params['transfertemplate'] );
		if ( ( isset( $response->code ) && 202 !== (int) $response->code ) || isset( $response->errors ) ) {
			return [
				'error' => json_encode( $response )
			];
		}

		return [
			'success' => 'success',
		];
	} catch ( \Exception $e ) {
		return [
			'error' => $e->getMessage(),
		];
	}
}

/**
 * Renew a domain.
 *
 * Attempt to renew/extend a domain for a given number of years.
 *
 * This is triggered when the following events occur:
 * * Payment received for a domain renewal order
 * * When a pending domain renewal order is accepted
 * * Upon manual request by an admin user
 *
 * @param array $params common module parameters
 *
 * @return array
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 */
function gandi_RenewDomain( $params ) {
	$sld                = $params['sld'];
	$tld                = $params['tld'];
	$domain             = $sld . '.' . $tld;
	$registrationPeriod = $params['regperiod'];
	try {
		$api      = new domainAPI( $params['apiKey'], $params['organization'] );
		$response = $api->renewDomain( $domain, $registrationPeriod );
		if ( ( isset( $response->code ) && 202 !== (int) $response->code ) || isset( $response->errors ) ) {
			return [
				'error' => json_encode( $response )
			];
		}

		return [
			'success' => 'success',
		];
	} catch ( \Exception $e ) {
		return [
			'error' => $e->getMessage(),
		];
	}
}

/**
 * Client Area Custom Button Array.
 *
 * Allows you to define additional actions your module supports.
 * In this example, we register a Push Domain action which triggers
 * the `gandi_push` function when invoked.
 *
 * @return array
 */
function gandi_Dnssec( $params ) {
	$sld    = $params['sld'];
	$tld    = $params['tld'];
	$domain = $sld . '.' . $tld;
	$dnssec = DNSSEC::instance( $params['apiKey'], $params['organization'], $domain );
	if ( filter_input( INPUT_POST, 'addKey' ) ) {
		$dnssec->enable();
		sleep( GANDI_CALL_LONG_WAIT );
		$dnssec->reset();
	}
	if ( filter_input( INPUT_POST, 'rmKey' ) ) {
		$dnssec->disable();
		sleep( GANDI_CALL_LONG_WAIT );
		$dnssec->reset();
	}
	$rmKey  = false;
	$addKey = false;
	$keys   = [];
	if ( $dnssec->isActivable() ) {
		$desc = Lang::trans( 'gandi.dnssec.nokey' );
		if ( $dnssec->isActivated() ) {
			$desc  = Lang::trans( 'gandi.dnssec.yeskey' );
			$rmKey = true;
			foreach ( $dnssec->getKeys() as $key ) {
				$digest = $key->ds ?? '-';
				while ( 0 < strpos( $digest, ' ' ) ) {
					$digest = substr( $digest, strpos( $digest, ' ' ) + 1 );
				}
				$keys[] = [
					'id'        => $key->id ?? '-',
					'type'      => [
						'name'  => Lang::trans( 'gandi.dnssec.type' ),
						'value' => ( 257 == $key->type ? 'ksk' : 'zsk' )
					],
					'algorithm' => [
						'name'  => Lang::trans( 'gandi.dnssec.algorithm' ),
						'value' => $key->algorithm ? ( 13 == $key->algorithm ? 'ECDSA Curve P-256 w/ SHA-256' : '-' ) : '-'
					],
					'digest'    => [
						'name'  => Lang::trans( 'gandi.dnssec.digest' ),
						'value' => $digest
					],
					'public'    => [
						'name'  => Lang::trans( 'gandi.dnssec.public' ),
						'value' => $key->public_key ?? '-'
					],
				];
			}
		} else {
			$addKey = true;
		}
	} else {
		$desc = Lang::trans( 'gandi.dnssec.nonokey' );
	}

	return [
		'templatefile' => 'dnssec',
		'vars'         => [
			'desc'   => $desc,
			'rmKey'  => $rmKey,
			'addKey' => $addKey,
			'keys'   => $keys,
			'fields' => [ 'type', 'algorithm' ],
		],
	];
}

/**
 * Client Area Custom Button Array.
 *
 * Allows you to define additional actions your module supports.
 * In this example, we register a Push Domain action which triggers
 * the `gandi_push` function when invoked.
 *
 * @return array
 */
function gandi_Snapshot( $params ) {
	$sld       = $params['sld'];
	$tld       = $params['tld'];
	$domain    = $sld . '.' . $tld;
	$error     = null;
	$success   = null;
	$snaps     = [];
	$details   = null;
	$snapshots = dnsSnaphot::instance( $params['apiKey'], $domain );
	$sub       = filter_input( INPUT_POST, 'sub' );
	$id        = filter_input( INPUT_POST, 'snapid' );
	if ( ! isset( $id ) ) {
		$id = filter_input( INPUT_GET, 'snapid' );
	}
	$ids = filter_input_array( INPUT_POST )['domids'] ?? [];


	$list = ! $snapshots->isValid( $id );
	if ( $sub && 'addSnapshot' === $sub ) {
		if ( $snapshots->takeNow( $id ) ) {
			$success = Lang::trans( 'gandi.snapshot.createsuccess' );
		} else {
			$error = Lang::trans( 'gandi.snapshot.createerror' );
		}
		$list = true;
		sleep( GANDI_CALL_SHORT_WAIT );
		$snapshots->reset();
	} elseif ( $sub && 'deleteSnapshot' === $sub ) {
		if ( $snapshots->deleteNow( $id ) ) {
			$success = Lang::trans( 'gandi.snapshot.deletesuccess' );
		} else {
			$error = Lang::trans( 'gandi.snapshot.deleteerror' );
		}
		$list = true;
		sleep( GANDI_CALL_SHORT_WAIT );
		$snapshots->reset();
	} elseif ( $sub && 'bulkDelete' === $sub ) {
		$count = $snapshots->bulkdeleteNow( $ids );
		if ( 0 < $count ) {
			$success = sprintf( Lang::trans( 'gandi.snapshot.bulkdeletesuccess' ), $count );
		} else {
			$error = Lang::trans( 'gandi.snapshot.bulkdeleteerror' );
		}
		$list = true;
		sleep( GANDI_CALL_SHORT_WAIT );
		$snapshots->reset();
	} elseif ( $sub && 'restoreSnapshot' === $sub ) {
		if ( $snapshots->restoreNow( $id ) ) {
			$success = Lang::trans( 'gandi.snapshot.restoresuccess' );
		} else {
			$error = Lang::trans( 'gandi.snapshot.restoreerror' );
		}
		$list = true;
	} elseif ( ! $list ) {
		$details               = $snapshots->getDetails( $id );
		$details['statustext'] = Lang::trans( 'gandi.snapshot.' . ( $details['status'] ?? 'error' ) );
	}
	if ( $list ) {
		$snaps = [];
		foreach ( $snapshots->getList() as $snap ) {
			$snap['statustext'] = Lang::trans( 'gandi.snapshot.' . $snap['statusClass'] );
			$snaps[]            = $snap;
		}
	}

	return [
		'templatefile' => 'snapshot',
		'vars'         => [
			'action'    => '/clientarea.php?action=domaindetails&modop=custom&a=Snapshot',
			'domainid'  => $params['domainid'],
			'list'      => $list,
			'error'     => $error,
			'success'   => $success,
			'snapshots' => $snaps,
			'snapshot'  => $details,

			'SnapshotsStatuses' => [
				[
					'statusClass' => 'auto',
					'statustext'  => Lang::trans( 'gandi.snapshot.auto' ),
				],
				[
					'statusClass' => 'manual',
					'statustext'  => Lang::trans( 'gandi.snapshot.manual' ),
				],
			]
		],
	];
}

/**
 * Client Area Custom Button Array.
 *
 * Allows you to define additional actions your module supports.
 * In this example, we register a Push Domain action which triggers
 * the `gandi_push` function when invoked.
 *
 * @return array
 */
function gandi_ClientAreaCustomButtonArray() {
	return [
		'snapshot' => 'Snapshot',
		'DNSSEC'   => 'Dnssec',
	];
}


/**
 * Client Area Output.
 *
 * This function renders output to the domain details interface within
 * the client area. The return should be the HTML to be output.
 *
 * @param array $params common module parameters
 *
 * @return string HTML Output
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 */
function gandi_ClientArea( $params ) {
	if ( ! $params['secprev'] || ( array_key_exists( 'registrar', $params ) && 'gandi' !== $params['registrar'] ) ) {
		return '';
	}
	$sld       = $params['sld'];
	$tld       = $params['tld'];
	$domain    = $sld . '.' . $tld;
	$idprotect = Lang::trans( 'gandi.infopanel.idprotectyes' );
	$lock      = Lang::trans( 'gandi.infopanel.noinfo' );
	$dns       = Lang::trans( 'gandi.infopanel.noinfo' );
	try {
		$api      = new domainAPI( $params['apiKey'], $params['organization'] );
		$response = $api->getDomainInfo( $domain );
		if ( ( isset( $response->code ) && 202 !== (int) $response->code ) || isset( $response->errors ) ) {
			throw new \Exception( 'Information not available' );
		}
		if ( is_array( $response->status ) ) {
			if ( ! $response->can_tld_lock ) {
				$lock = Lang::trans( 'gandi.infopanel.locknono' );
			} elseif ( in_array( 'clientTransferProhibited', $response->status ) ) {
				$lock = Lang::trans( 'gandi.infopanel.lockyes' );
			} else {
				$lock = Lang::trans( 'gandi.infopanel.lockno' );
			}
		}
		if ( is_array( $response->nameservers ) ) {
			if ( LiveDNS::isCorrect( $response->nameservers ) ) {
				$dns = Lang::trans( 'gandi.dns.livedns' );
			} else {
				$dns = Lang::trans( 'gandi.dns.external' );
			}
		}
	} catch ( \Exception $e ) {

	}
	$dnssec = DNSSEC::instance( $params['apiKey'], $params['organization'], $domain );
	if ( $dnssec->isActivable() ) {
		$sec = Lang::trans( 'gandi.infopanel.dnssecno' );
		if ( $dnssec->isActivated() ) {
			$sec = Lang::trans( 'gandi.infopanel.dnssecyes' );
		}
	} else {
		$sec = Lang::trans( 'gandi.infopanel.dnssecnono' );
	}
	$output = '<div class="panel panel-default">';
	$output .= ' <div class="panel-heading"><h3 class="panel-title">' . Lang::trans( 'gandi.infopanel.secprev' ) . '</h3></div>';
	$output .= ' <ul class="list-info list-info-50 list-info-bordered">';
	$output .= '  <li><span class="list-info-title">' . Lang::trans( 'gandi.infopanel.idprotect' ) . '</span><span class="list-info-text"><span>' . $idprotect . '</span></span></li>';
	$output .= '  <li><span class="list-info-title">' . Lang::trans( 'gandi.infopanel.lock' ) . '</span><span class="list-info-text"><span>' . $lock . '</span></span></li>';
	$output .= '  <li><span class="list-info-title">' . Lang::trans( 'gandi.infopanel.dnssec' ) . '</span><span class="list-info-text"><span>' . $sec . '</span></span></li>';
	$output .= ' </ul>';
	$output .= '</div>';
	$output .= '<div class="panel panel-default">';
	$output .= ' <div class="panel-heading"><h3 class="panel-title">' . Lang::trans( 'gandi.infopanel.perf' ) . '</h3></div>';
	$output .= ' <ul class="list-info list-info-50 list-info-bordered">';
	$output .= '  <li><span class="list-info-title">' . Lang::trans( 'gandi.dns.name' ) . '</span><span class="list-info-text"><span>' . $dns . '</span></span></li>';
	$output .= ' </ul>';
	$output .= '</div>';

	return $output;
}


// HOOKS

add_hook( 'ClientAreaPageDomainContacts', 1, 'gandi_ModifyContacts' );

add_hook( 'ClientAreaPageBulkDomainManagement', 1, 'gandi_ModifyContacts' );

add_hook( 'AfterRegistrarRegistration', 1, 'gandi_DomainPostProcess' );

add_hook( 'AfterRegistrarTransfer', 1, 'gandi_DomainPostProcess' );



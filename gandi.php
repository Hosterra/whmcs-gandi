<?php

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

use WHMCS\Domains\DomainLookup\ResultsList;
use WHMCS\Domains\DomainLookup\SearchResult;
use WHMCS\Module\Registrar\Gandi\ApiClient;
use WHMCS\Module\Registrar\Gandi\LiveDNS;

require_once dirname(__FILE__) . '/lib/LiveDNS.php';

/**
 * Define module related metadata
 *
 * Provide some module information including the display name and API Version to
 * determine the method of decoding the input values.
 *
 * @return array
 */
function gandi_MetaData()
{
    return [
	    'DisplayName' => 'Gandi Registrar',
	    'APIVersion' => '1.1',
    ];
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
    try{
        $api              = new ApiClient( $params["apiKey"] );
        $organizationList = [];
        $organizations    = $api->getOrganizations();
        if( is_array( $organizations ) ){
            foreach( $organizations as $organization ){
                $organizationsList[$organization->id] = $organization->name;
            }
        }
    } catch ( Exception $e ) {
		// How/where to log?
    }
    return [
        'FriendlyName' => [
            'Type' => 'System',
            'Value' => 'Gandi Registrar (v5 API)',
        ],
        'apiKey' => [
            'FriendlyName' => 'Api Key',
            'Type' => 'password',
            'Size' => '100',
        ],
        'accountType' => [
            'FriendlyName' => 'Account type',
            'Type' => 'dropdown',
            'Options' => [
                'individual' => 'Pay as individual',
                'organization' => 'Pay as another organization',
                'reseller' => 'Reseller',
            ],
        ],
        'organization' => [
             'FriendlyName' => 'Organization',
             'Type' => 'dropdown',
             'Options' => $organizationsList,
         ],
        'dns' => [
	        'FriendlyName' => 'DNS',
	        'Type' => 'dropdown',
	        'Options' => [
		        'livedns' => 'Gandi LiveDNS',
		        'whmcs' => 'Proprietary',
	        ],
        ],

    ];
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
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 * @return array
 */
function gandi_RegisterDomain( $params ) {
    if ( 'individual' === $params['accountType'] ){
        $organization = '';
    } else {
        $organization = $params['organization'];
    }
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
        'firstname' => $params['firstname'],
        'lastname' => $params['lastname'],
        'email' => $params['email'],
        'address' => $params['address1'],
        'city' => $params['city'],
        'postcode' =>  $params['postcode'],
        'countrycode' => $params['countrycode'],
        'countryname' => $params['countryname'],
        'phonenumber' => $params['phonenumber'],
        'phonecountrcCode' => $params['phonecc'],
        'phonenumberformatted' => $params['phonenumberformatted'],
        'orgname' => $params['companyname'],
        'language' => ( empty( $params['language'] ) ) ? $GLOBALS['CONFIG']['Language'] : $params['language']
    ];
    try {
        $api          = new ApiClient( $params['apiKey'] );
        $availability = $api->getDomainAvailability( $domain );
        if ( $availability !== 'available' ) {
            return [
                'error' => $availability
            ];
        }
        $response = $api->registerDomain( $domain, $contacts, $nameservers, $registrationPeriod, $organization );
        if ( ( isset( $response->code ) && 202 !== (int) $response->code ) || isset( $response->errors ) ) {
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
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 * @return array
 */
function gandi_TransferDomain( $params ) {
	if ( 'individual' === $params['accountType'] ){
		$organization = '';
	} else {
		$organization = $params['organization'];
	}
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
		'firstname' => $params['firstname'],
		'lastname' => $params['lastname'],
		'email' => $params['email'],
		'address' => $params['address1'],
		'city' => $params['city'],
		'postcode' =>  $params['postcode'],
		'countrycode' => $params['countrycode'],
		'countryname' => $params['countryname'],
		'phonenumber' => $params['phonenumber'],
		'phonecountrcCode' => $params['phonecc'],
		'phonenumberformatted' => $params['phonenumberformatted'],
		'orgname' => $params['companyname'],
		'language' => ( empty( $params['language'] ) ) ? $GLOBALS['CONFIG']['Language'] : $params['language']
	];
	try {
		$api      = new ApiClient( $params['apiKey'] );
        $response = $api->transferDomain( $domain, $contacts, $nameservers, $registrationPeriod, $authCode, $organization );
	    if ( ( isset( $response->code ) && 202 !== (int) $response->code ) || isset( $response->errors ) ) {
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
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 * @return array
 */
function gandi_RenewDomain( $params ) {
	if ( 'individual' === $params['accountType'] ){
		$organization = '';
	} else {
		$organization = $params['organization'];
	}
	$sld                = $params['sld'];
	$tld                = $params['tld'];
	$domain             = $sld . '.' . $tld;
	$registrationPeriod = $params['regperiod'];
	try {
		$api      = new ApiClient( $params['apiKey'] );
        $response = $api->renewDomain( $domain, $registrationPeriod, $organization );
		if ( ( isset( $response->code ) && 202 !== (int) $response->code ) || isset( $response->errors ) ) {
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
 * Fetch current nameservers.
 *
 * This function should return an array of nameservers for a given domain.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 * @return array
 */
function gandi_GetNameservers($params) {
    $sld = $params['sld'];
    $tld = $params['tld'];
    $domain = $sld . '.' . $tld;
	try {
		$api          = new ApiClient( $params['apiKey'] );
        $request = $api->getDomainNameservers( $domain );
        if (!is_array($request)) {
            return [
                'success' => false
            ];
        }
        $response = [
        ];
        foreach ($request as $k => $v) {
            $index = $k + 1;
            $response['ns' . $index] = $v;
        }
        return $response;
    } catch ( \Exception $e ) {
        return array(
            'error' => $e->getMessage(),
        );
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
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 * @return array
 */
function gandi_SaveNameservers($params)
{

    // submitted nameserver values
    $nameservers = [];
    if ($params['ns1']) {
        $nameservers[] = $params['ns1'];
    }
    if ($params['ns2']) {
        $nameservers[] = $params['ns2'];
    }
    if ($params['ns3']) {
        $nameservers[] = $params['ns3'];
    }
    if ($params['ns4']) {
        $nameservers[] = $params['ns4'];
    }
    if ($params['ns5']) {
        $nameservers[] = $params['ns5'];
    }
    try {
        $apiKey = $params['API Key'];
        $sld = $params['sld'];
        $tld = $params['tld'];
        $domain = $sld . '.' . $tld;
        $api = new ApiClient($params["apiKey"]);
        $request = $api->updateDomainNameservers($domain, $nameservers);
        logModuleCall('Gandi Registrar', __FUNCTION__, $nameservers, serialize($request));
        if ((isset($request->code) && $request->code != 202)|| isset($request->errors)) {
            throw new Exception(json_encode($request));
        }

        return array(
            'success' => true,
        );
    } catch ( \Exception $e ) {
        return array(
            'error' => $e->getMessage(),
        );
    }
}

/**
 * Get the current WHOIS Contact Information.
 *
 * Should return a multi-level array of the contacts and name/address
 * fields that be modified.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 * @return array
 */
function gandi_GetContactDetails($params)
{
    try {
        $apiKey = $params['API Key'];
        $sld = $params['sld'];
        $tld = $params['tld'];
        $domain = $sld . '.' . $tld;
        $api = new ApiClient($params["apiKey"]);
        $contacts = $api->getDomainContacts($domain);
        $owner = (array) $contacts->owner;
        unset($owner['extra_parameters']);
        unset($owner['type']);
        unset($owner['data_obfuscated']);
        unset($owner['mail_obfuscated']);
        $admin = (array) $contacts->admin;
        unset($admin['extra_parameters']);
        unset($admin['type']);
        unset($admin['data_obfuscated']);
        unset($admin['mail_obfuscated']);
        unset($admin['same_as_owner']);
        $billing = (array) $contacts->bill;
        unset($billing['extra_parameters']);
        unset($billing['type']);
        unset($billing['data_obfuscated']);
        unset($billing['mail_obfuscated']);
        unset($billing['same_as_owner']);
        $tech = (array) $contacts->tech;
        unset($tech['extra_parameters']);
        unset($tech['type']);
        unset($tech['data_obfuscated']);
        unset($tech['mail_obfuscated']);
        unset($tech['same_as_owner']);

        return array(
            'Owner' => $owner,
            'Technical' => $tech,
            'Billing' => $billing,
            'Admin' => $admin
        );
    } catch ( \Exception $e ) {
        return array(
            'error' => $e->getMessage(),
        );
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
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 * @return array
 */
function gandi_SaveContactDetails($params)
{
    try {
        $apiKey = $params['API Key'];
        $sld = $params['sld'];
        $tld = $params['tld'];
        $domain = $sld . '.' . $tld;
        $api = new ApiClient($params["apiKey"]);
        $request = $api->updateDomainContacts($domain, $params['contactdetails']);
        if ((isset($request->code) && $request->code != 202)|| isset($request->errors)) {
            return array(
             'error' => $request->message,
            );
        }
        sleep(5);
        return array(
            'success' => true,
        );
    } catch ( \Exception $e ) {
        return array(
            'error' => $e->getMessage(),
        );
    }
}

/**
 * Check Domain Availability.
 *
 * Determine if a domain or group of domains are available for
 * registration or transfer.
 *
 * @param array $params common module parameters
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 * @see \WHMCS\Domains\DomainLookup\SearchResult
 * @see \WHMCS\Domains\DomainLookup\ResultsList
 *
 * @throws Exception Upon domain availability check failure.
 *
 * @return \WHMCS\Domains\DomainLookup\ResultsList An ArrayObject based collection of \WHMCS\Domains\DomainLookup\SearchResult results
 */
function gandi_CheckAvailability($params)
{
    try {
        $results = new ResultsList();
        $sld = $params['sld'];
        $api = new ApiClient($params["apiKey"]);
        foreach ($params['tlds'] as $tld) {
            $tld = str_replace('.', '', $tld);
            $searchResult = new SearchResult($sld, $tld);
            $domain = $sld . '.' . $tld;
            $availability = $api->getDomainAvailability($domain);
            if ($availability == 'available') {
                $status = SearchResult::STATUS_NOT_REGISTERED;
            } else {
                $status = SearchResult::STATUS_REGISTERED;
            }
            $searchResult->setStatus($status);
            $results->append($searchResult);
        }
        return $results;
    } catch ( \Exception $e ) {
        return array(
            'error' => $e->getMessage(),
        );
    }
}


/**
 * Get DNS Records for DNS Host Record Management.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 * @return array DNS Host Records
 */
function gandi_GetDNS($params)
{
    try {
        $apiKey = $params['API Key'];
        $sld = $params['sld'];
        $tld = $params['tld'];
        $domain = $sld . '.' . $tld;

        $gandi = new ApiClient($params["apiKey"]);
        $liveDns = new LiveDNS($params["apiKey"]);
        $records = $liveDns->getLiveDnsRecords($domain);
        $hostRecords = array();
        foreach ($records as $record) {
            if (!in_array($record->rrset_type, ['A','AAAA','MXE','MX','CNAME','TXT','URL','FRAME'])) {
                continue; // Only allow supported WHMCS types
            }
            if (count($record->rrset_values) > 1) {
                foreach ($record->rrset_values as $k => $v) {
                    $entry = array(
                         "hostname" => $record->rrset_name, // eg. www
                         "type" => $record->rrset_type, // eg. A
                         "address" => $record->rrset_values[$k], // eg. 10.0.0.1
                     );
                    if ($record->rrset_type == 'MX') {
                        $valueArray = explode(' ', $record->rrset_values[$k]);
                        $entry['priority'] = $valueArray[0];
                        $entry['address'] = $valueArray[1];
                    }
                    $hostRecords[] = $entry;
                }
            } else {
                $entry = array(
                    "hostname" => $record->rrset_name, // eg. www
                    "type" => $record->rrset_type, // eg. A
                    "address" => $record->rrset_values[0], // eg. 10.0.0.1
                );
                if ($record->rrset_type == 'MX') {
                    $valueArray = explode(' ', $record->rrset_values[0]);
                    $entry['priority'] = $valueArray[0];
                    $entry['address'] = $valueArray[1];
                }
                $hostRecords[] = $entry;
            }
        }
        return $hostRecords;
    } catch ( \Exception $e ) {
        return array(
            'error' => $e->getMessage(),
        );
    }
}

/**
 * Update DNS Host Records.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 * @return array
 */
function gandi_SaveDNS($params)
{
    try {
        $apiKey = $params['API Key'];
        $sld = $params['sld'];
        $tld = $params['tld'];
        $domain = $sld . '.' . $tld;
        $whmcsRecords = $params['dnsrecords'];
        $gandi = new ApiClient($params["apiKey"]);
        $liveDns = new LiveDNS($params["apiKey"]);
        $gandiRecords = $liveDns->getLiveDnsRecords($domain);
        foreach ($whmcsRecords as $index => $whmcsRecord) {
            if (is_array($gandiRecords)  && isset($gandiRecords[$index])) {
                $entryToDelete = $gandiRecords[$index];
                $liveDns->deleteRecord($domain, $entryToDelete); // Clear entry
            }
            $response = $liveDns->addRecord($domain, $whmcsRecord); // Add entry
            if ($response->code == 404) {
                return array(
                    'error' => "LiveDNS not enabled",
                );
            }
        }
        return array(
            'success' => 'success',
        );
    } catch ( \Exception $e ) {
        return array(
            'error' => $e->getMessage(),
        );
    }
}


/**
 * Register a Nameserver.
 *
 * Adds a child nameserver for the given domain name.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 * @return array
 */
function gandi_RegisterNameserver($params)
{
    try {
        $apiKey = $params['API Key'];
        $sld = $params['sld'];
        $tld = $params['tld'];
        $domain = $sld . '.' . $tld;
        $api = new ApiClient($params["apiKey"]);
        $nameserver = explode('.', $params['nameserver']);
        $request = $api->registerNameserver($domain, $nameserver[0], $params['ipaddress']);
        if ((isset($request->code) && $request->code != 202)|| isset($request->errors)) {
            throw new Exception(json_encode($request));
        }

        return array(
            'success' => 'success',
        );
    } catch ( \Exception $e ) {
        return array(
            'error' => $e->getMessage(),
        );
    }
}

/**
 * Modify a Nameserver.
 *
 * Modifies the IP of a child nameserver.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 * @return array
 */
function gandi_ModifyNameserver($params)
{
    $apiKey = $params['API Key'];
    $sld = $params['sld'];
    $tld = $params['tld'];
    $domain = $sld . '.' . $tld;
    $api = new ApiClient($params["apiKey"]);
    $nameserver = explode('.', $params['nameserver']);
    $request = $api->updateNameserver($domain, $nameserver[0], $params['newipaddress']);
    if ((isset($request->code) && $request->code != 202)|| isset($request->errors)) {
        throw new Exception(json_encode($request));
    }
    return array(
        'success' => 'success',
    );
}

/**
 * Delete a Nameserver.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 * @return array
 */
function gandi_DeleteNameserver($params)
{
    $apiKey = $params['API Key'];
    $sld = $params['sld'];
    $tld = $params['tld'];
    $domain = $sld . '.' . $tld;
    $api = new ApiClient($params["apiKey"]);
    $nameserver = explode('.', $params['nameserver']);
    $request = $api->deleteNameserver($domain, $nameserver[0]);
    if ((isset($request->code) && $request->code != 202)|| isset($request->errors)) {
        throw new Exception(json_encode($request));
    }

    return array(
        'success' => 'success',
    );
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
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 * @return array
 */
function gandi_Sync($params)
{
    try {
        $domain = $params['domain'];
        $api = new ApiClient($params["apiKey"]);
        $request = $api->getDomainInfo($domain);
        $code = 200; // default code because not set by API when everything is fine
        if (isset($request->code)) {
            $code = $request->code;
        }
        if (in_array($code, [403, 404])) { // Transfered away
            return array(
                'transferredAway' => true
            );
        }
        if (!in_array($code, [401, 403, 404])) {
            $expired = (strtotime($request->dates->registry_ends_at) < time())?true:false;
            return array(
                'expirydate' => date("Y-m-d", strtotime($request->dates->registry_ends_at)),
                'active' => true, // Return true if the domain is active
                'expired' => $expired,
                'transferredAway' => false
            );
        }
    } catch ( \Exception $e ) {
        return array(
            'error' => $e->getMessage(),
        );
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
function gandi_ClientAreaCustomButtonArray()
{
    return array(
    );
}

/**
 * Client Area Allowed Functions.
 *
 * Only the functions defined within this function or the Client Area
 * Custom Button Array can be invoked by client level users.
 *
 * @return array
 */
function gandi_ClientAreaAllowedFunctions()
{
    return array(
    );
}


/**
 * Client Area Output.
 *
 * This function renders output to the domain details interface within
 * the client area. The return should be the HTML to be output.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 * @return string HTML Output
 */
function gandi_ClientArea($params)
{
    $output = '';

    return $output;
}

/**
 * Request EEP Code.
 *
 * Supports both displaying the EPP Code directly to a user or indicating
 * that the EPP Code will be emailed to the registrant.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 * @return array
 *
 */
function gandi_GetEPPCode($params)
{
    try {
        $domain = $params['domainname'];
        $api = new ApiClient($params["apiKey"]);
        $request = $api->getDomainInfo($domain);
        return array(
                'eppcode' => $request->authinfo,
            );
        
    } catch ( \Exception $e ) {
        return array(
            'error' => $e->getMessage(),
        );
    }

}

/**
 * Sync Transfer Status & Expiration Date.
 *
 * Transfer syncing is intended to ensure transfer status and expiry date
 * changes made directly at the domain registrar are synced to WHMCS.
 * It is called periodically for a transfer.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/domain-registrars/module-parameters/
 *
 * @return array
 */
function gandi_TransferSync($params)
{
    try {
        $domain = $params['domain'];
        $api = new ApiClient($params["apiKey"]);
        $request = $api->getDomainInfo($domain);
        if ($request->code == 403 || $request->code == 404) { // not finished
            return array(
                'completed' => false,
                'failed' => false
            );
        }
        if ($request->code != 404) {
            $expired = (strtotime($request->dates->registry_ends_at) < time())?true:false;
            return array(
                'expirydate' => date("Y-m-d", strtotime($request->dates->registry_ends_at)),
                'completed' => true, // Return true if the transfer is completed
                'failed' => false,
                'reason' => '',
                'error' => ''
            );
        }
    } catch ( \Exception $e ) {
        return array(
            'failed' => true,
            'completed' => false,
            'reason' => 'Transfer Error',
            'error' => $e->getMessage()
        );
    }
}

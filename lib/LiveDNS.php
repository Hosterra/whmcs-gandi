<?php

namespace WHMCS\Module\Registrar\Gandi;

class LiveDNS {
	const ENDPOINT = 'https://api.gandi.net/v5/livedns/';
	private $apiKey;
	private $registrar = GANDI_REGISTRAR_PRODUCT_NAME;
	private static $cache = [];

	public static function isCorrect( $nameservers ) {
		foreach ( $nameservers as $v ) {
			if ( ( '' !== $v ) && ( ! str_ends_with( $v, '.gandi.net' ) ) ) {
				return false;
			}
		}

		return true;
	}

	public function __construct( $apiKey ) {
		$this->apiKey = $apiKey;
	}

	public function invalidateCache( $scope = 'all' ) {
		if ( 'all' === $scope ) {
			self::$cache = [];
		} else {
			$cache       = self::$cache;
			self::$cache = [];
			foreach ( $cache as $k => $v ) {
				if ( ! str_contains( $k, $scope ) ) {
					self::$cache[ $k ] = $v;
				}
			}
		}
		if ( GANDI_LOG_CACHE ) {
			logModuleCall( $this->registrar, __FUNCTION__, 'scope: ' . $scope, 'done' );
		}
	}

	/*
	*
	* Return the LiveDNS record list.
	*
	* @param string $domain
	* @return array
	*
	*/
	public function getLiveDnsRecords( string $domain ) {
		$url      = $this::ENDPOINT . "/domains/{$domain}/records";
		$response = $this->sendOrGetCached( $url, "GET" );
		logModuleCall( $this->registrar, __FUNCTION__, $domain, $response );

		return $response;
	}

	/*
	*
	* Delete a LiveDNS record.
	*
	* @param string $domain
	* @param object $record
	* @return array
	*
	*/
	public function deleteRecord( string $domain, $record ) {
		$url      = $this::ENDPOINT . "/domains/{$domain}/records/{$record->rrset_name}/{$record->rrset_type}";
		$response = $this->sendOrGetCached( $url, "DELETE" );
		logModuleCall( $this->registrar, __FUNCTION__, [ $domain, $record ], $response );

		return $response;
	}

	/*
	*
	* Create a LiveDNS record.
	*
	* @param string $domain
	* @param array $record
	* @return array
	*
	*/
	public function addRecord( string $domain, $record ) {
		$url    = $this::ENDPOINT . "/domains/{$domain}/records";
		$params = [
			'rrset_name' => $record['hostname'],
			'rrset_type' => $record['type'],
		];
		if ( $record['recid'] ) {
			$params['rrset_ttl'] = (int) $record['recid'];
		}
		if ( $record['type'] == 'MX' ) {
			$params['rrset_values'] = [ "{$record['priority']} {$record['address']}" ];
		} else {
			$params['rrset_values'] = [ $record['address'] ];
		}
		$response = $this->sendOrGetCached( $url, "POST", $params );
		logModuleCall( $this->registrar, __FUNCTION__, [ $domain, $params ], $response );

		return $response;
	}

	/*
	*
	* Get DNSSEC keys.
	*
	* @param string $domain
	* @return array
	*
	*/
	public function getDnssecKeys( string $domain ) {
		$url      = $this::ENDPOINT . "/domains/{$domain}/keys";
		$response = $this->sendOrGetCached( $url, "GET" );
		logModuleCall( $this->registrar, __FUNCTION__, $domain, $response );

		return $response;
	}

	/*
	*
	* Get DNSSEC key details.
	*
	* @param string $domain
	* @param string $key
	* @return array
	*
	*/
	public function getDnssecKeyDetails( string $domain, $key ) {
		$url      = $this::ENDPOINT . "/domains/{$domain}/keys/{$key}";
		$response = $this->sendOrGetCached( $url, "GET" );
		logModuleCall( $this->registrar, __FUNCTION__, $domain, $response );

		return $response;
	}

	/*
	*
	* Delete DNSSEC key.
	*
	* @param string $domain
	* @param string $key
	* @return array
	*
	*/
	public function deleteDnssecKey( string $domain, $key ) {
		$url      = $this::ENDPOINT . "/domains/{$domain}/keys/{$key}";
		$response = $this->sendOrGetCached( $url, "DELETE" );
		logModuleCall( $this->registrar, __FUNCTION__, $domain, $response );

		return $response;
	}

	/*
	*
	* Adds a DNSSEC key.
	*
	* @param string $domain
	* @param int $type / Key flags (ZSK=256, KSK=257)
	* @return array
	*
	*/
	public function addDnssecKey( string $domain, $type ) {
		$url      = $this::ENDPOINT . "/domains/{$domain}/keys";
		$params   = [
			'flags' => $type
		];
		$response = $this->sendOrGetCached( $url, "POST", $params );
		logModuleCall( $this->registrar, __FUNCTION__, [ $domain, $params ], $response );

		return $response;
	}

	/*
	*
	* Get snapshots.
	*
	* @param string $domain
	* @return array
	*
	*/
	public function getSnapshots( string $domain ) {
		$url      = $this::ENDPOINT . "/domains/{$domain}/snapshots";
		$response = $this->sendOrGetCached( $url, "GET" );
		logModuleCall( $this->registrar, __FUNCTION__, $domain, $response );

		return $response;
	}

	/*
	*
	* Get snapshot details.
	*
	* @param string $domain
	* @param int $id
	* @return array
	*
	*/
	public function getSnapshotDetails( string $domain, $id ) {
		$url      = $this::ENDPOINT . "/domains/{$domain}/snapshots/{$id}";
		$response = $this->sendOrGetCached( $url, "GET" );
		logModuleCall( $this->registrar, __FUNCTION__, $domain, $response );

		return $response;
	}

	/*
	*
	* Delete snapshot.
	*
	* @param string $domain
	* @param int $id
	* @return array
	*
	*/
	public function deleteSnapshot( string $domain, $id ) {
		$url      = $this::ENDPOINT . "/domains/{$domain}/snapshots/{$id}";
		$response = $this->sendOrGetCached( $url, "DELETE" );
		logModuleCall( $this->registrar, __FUNCTION__, $domain, $response );

		return $response;
	}

	/*
	*
	* Create a snapshot
	*
	* @param string $domain
	* @param array $data
	* @return array
	*
	*/
	public function restoreSnapshot( string $domain, $data ) {
		$url      = $this::ENDPOINT . "/domains/{$domain}/records";
		$params   = [
			'items'          => $data,
			'remove_apex_ns' => false
		];
		$response = $this->sendOrGetCached( $url, "PUT", $params );
		logModuleCall( $this->registrar, __FUNCTION__, [ $domain, $params ], $response );

		return $response;
	}

	/*
	*
	* Create a snapshot
	*
	* @param string $domain
	* @param string $name
	* @return array
	*
	*/
	public function createSnapshot( string $domain, $name ) {
		$url    = $this::ENDPOINT . "/domains/{$domain}/snapshots";
		$params = [];
		if ( '' !== $name ) {
			$params = [
				'name' => $name
			];
		}
		$response = $this->sendOrGetCached( $url, "POST", $params );
		logModuleCall( $this->registrar, __FUNCTION__, [ $domain, $params ], $response );

		return $response;
	}

	private function sendOrGetCached( $url, $method = 'GET', $post = [], $timeout = 30 ) {
		if ( 'GET' !== $method ) {
			return $this->sendRequest( $url, $method, $post, $timeout );
		}
		if ( ! array_key_exists( $url, self::$cache ) ) {
			self::$cache[ $url ] = $this->sendRequest( $url, $method, $post, $timeout );
		}

		return self::$cache[ $url ];
	}

	/*
	*
	* Send request.
	*
	* @param string $url
	* @param string $method
	* @param string $url
	* @param array $post
	* @param integer $timeout
	* @return array
	*
	*/
	private function sendRequest( $url, $method = 'GET', $body = [], $timeout = 30 ) {
		$curl = curl_init();
		curl_setopt_array( $curl, [
			CURLOPT_PORT           => '0',
			CURLOPT_URL            => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => "",
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => $timeout,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => $method,
			CURLOPT_HTTPHEADER     => [
				"authorization: Apikey {$this->apiKey}",
				"content-type: application/json"
			],
			CURLOPT_USERAGENT      => 'WHMCS/1.3'
		] );
		if ( in_array( $method, [ 'POST', 'PUT', 'PATCH', 'DELETE' ] ) ) {
			curl_setopt_array( $curl, [ CURLOPT_CUSTOMREQUEST => $method ] );
		}
		if ( in_array( $method, [ 'POST', 'PUT', 'PATCH' ] ) && 0 < count( $body ) ) {
			curl_setopt_array( $curl, [ CURLOPT_POSTFIELDS => json_encode( $body ) ] );
		}
		$response = json_decode( curl_exec( $curl ) );
		curl_close( $curl );
		if ( GANDI_LOG_CACHE ) {
			logModuleCall( $this->registrar, __FUNCTION__, 'HTTP ' . curl_getinfo( $curl, CURLINFO_RESPONSE_CODE ) . ' / ' . $method . ' ' . $url, 'cached: no' );
		}

		return $response;
	}
}

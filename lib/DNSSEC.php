<?php

namespace WHMCS\Module\Registrar\Gandi;

use WHMCS\Module\Registrar\Gandi\domainAPI;
use WHMCS\Module\Registrar\Gandi\LiveDNS;

class DNSSEC {
	private $domain;
	private $apiKey;
	private $sharingId;
	private $domainApi;
	private $livednsApi;
	private $keys = null;
	private $isactivable = null;
	private $isactivated = null;
	private static $instance = null;

	public static function instance( $apiKey, $sharingId, $domain ) {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self( $apiKey, $sharingId, $domain );
		}

		return self::$instance;
	}


	public function __construct( $apiKey, $sharingId, $domain ) {
		$this->apiKey     = $apiKey;
		$this->sharingId  = $sharingId;
		$this->domain     = $domain;
		$this->domainApi  = new domainAPI( $this->apiKey, $this->sharingId );
		$this->livednsApi = new LiveDNS( $this->apiKey );
	}

	/*
	*
	* Verify DNSSEC status.
	*
	*/
	private function checkStatus() {
		try {
			$response = $this->domainApi->getLiveDNSInfo( $this->domain );
			if ( ( isset( $response->code ) && 200 !== (int) $response->code ) ) {
				throw new \Exception( 'Information not available' );
			}
			if ( 'livedns' !== $response->current ) {
				throw new \Exception( 'Not a LiveDNS domain' );
			}
			$this->isactivable = $response->livednssec_available;
		} catch ( \Exception $e ) {
			$this->isactivable = false;
			$this->isactivated = false;
			$this->keys        = [];
		}
		if ( $this->isactivable ) {
			try {
				$response = $this->livednsApi->getDnssecKeys( $this->domain );
				if ( isset( $response->code ) && 200 !== (int) $response->code ) {
					throw new \Exception( 'Information not available' );
				}
				$this->keys = [];
				foreach ( (array) $response as $key ) {
					if ( 'active' === (string) $key->status ) {
						$dnskey = $this->livednsApi->getDnssecKeyDetails( $this->domain, $key->id );
						if ( ( isset( $dnskey->code ) && 200 !== (int) $dnskey->code ) || isset( $dnskey->errors ) ) {
							continue;
						}
						$this->keys[] = $dnskey;
					}
				}
				$this->isactivated = ( 0 < count( $this->keys ) );
			} catch ( \Exception $e ) {
				$this->isactivated = false;
				$this->keys        = [];
			}
		}
	}

	/*
	*
	* Reset status and invalidate cache.
	*
	*/
	public function reset() {
		$this->domainApi->invalidateCache( '/livedns' );
		$this->livednsApi->invalidateCache( '/keys' );
		$this->keys        = null;
		$this->isactivable = null;
		$this->isactivated = null;
	}

	/*
	*
	* Get the DNSSEC keys list.
	*
	* @return array
	*
	*/
	public function getKeys() {
		if ( ! isset( $this->keys ) ) {
			$this->checkStatus();
		}

		return $this->keys;
	}

	/*
	*
	* Verify if DNSSEC is activable.
	*
	* @return boolean
	*
	*/
	public function isActivable() {
		if ( ! isset( $this->isactivable ) ) {
			$this->checkStatus();
		}

		return $this->isactivable;
	}

	/*
	*
	* Verify if DNSSEC is activated.
	*
	* @return boolean
	*
	*/
	public function isActivated() {
		if ( ! isset( $this->isactivated ) ) {
			$this->checkStatus();
		}

		return $this->isactivated;
	}

	/*
	*
	* Enable DNSSEC.
	*
	* @return boolean
	*
	*/
	public function enable() {
		try {
			$this->disable();
			$response = $this->livednsApi->addDnssecKey( $this->domain, 257 );
			if ( ( isset( $response->code ) && 201 !== (int) $response->code ) || isset( $response->errors ) ) {
				throw new \Exception( 'Error while adding DNSSEC key.' );
			}
			sleep( GANDI_CALL_LONG_WAIT );
			foreach ( (array) $this->getKeys() as $key ) {
				if ( $key->id ) {
					try {
						$this->domainApi->setDNSSEC( $this->domain, $key->public_key, $key->algorithm, ( 257 == $key->flags ? 'ksk' : 'zsk' ) );
					} catch ( \Exception $e ) {
						return false;
					}

					return true;
				}
			}

			return false;
		} catch ( \Exception $e ) {
			return false;
		}
	}

	/*
	*
	* Disable DNSSEC.
	*
	* @return boolean
	*
	*/
	public function disable() {
		foreach ( $this->getKeys() as $key ) {
			if ( $key->id ) {
				try {
					$this->livednsApi->deleteDnssecKey( $this->domain, $key->id );
				} catch ( \Exception $e ) {
					return false;
				}
			}
		}

		return true;
	}


}

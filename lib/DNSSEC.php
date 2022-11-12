<?php

namespace WHMCS\Module\Registrar\Gandi;

use WHMCS\Module\Registrar\Gandi\domainAPI;

class DNSSEC {
	private $domain;
	private $apiKey;
	private $sharingId;
	private $keys = null;
	private $isactivable = null;
	private $isactivated = null;


	public function __construct( $apiKey, $sharingId, $domain ) {
		$this->apiKey = $apiKey;
		$this->sharingId = $sharingId;
		$this->domain = $domain;
	}

	/*
	*
	* Verify DNSSEC status.
	*
	*/
	private function checkStatus() {
		$api = new domainAPI( $this->apiKey, $this->sharingId );
		try {
			$response = $api->getLiveDNSInfo( $this->domain );
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
			$this->keys = [];
		}
		if ( $this->isactivable ) {
			try {
				$response = $api->getDNSSEC( $this->domain );
				if ( isset( $response->code ) ) {
					throw new \Exception( 'Information not available' );
				}
				$this->keys = (array) $response;
				$this->isactivated = ( 0 < count( $this->keys ) );
			} catch ( \Exception $e ) {
				$this->isactivated = false;
				$this->keys = [];
			}
		}
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
	* Verify if DNSSEC is activated.
	*
	* @return boolean
	*
	*/
	public function enable() {
		$api = new domainAPI( $this->apiKey, $this->sharingId );
		try {
			$response = $api->setDNSSEC( $this->domain );
			if ( ( isset( $response->code ) && 202 !== (int) $response->code ) || isset( $response->errors ) ) {
				throw new \Exception( 'Information not available' );
			}
			return true;
		} catch ( \Exception $e ) {
			return false;
		}
	}


}

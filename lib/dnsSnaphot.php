<?php

namespace WHMCS\Module\Registrar\Gandi;

use WHMCS\Module\Registrar\Gandi\domainAPI;
use WHMCS\Module\Registrar\Gandi\LiveDNS;
use WHMCS\Carbon;

class dnsSnaphot {
	private $domain;
	private $apiKey;
	private $livednsApi;
	private $snapshots = null;


	public function __construct( $apiKey, $domain ) {
		$this->apiKey     = $apiKey;
		$this->domain     = $domain;
		$this->livednsApi = new LiveDNS( $this->apiKey );
	}

	/*
	*
	* Get the snapshots list.
	*
	* @return array
	*
	*/
	public function getList() {
		if ( ! isset( $this->snapshots ) ) {
			$response        = $this->livednsApi->getSnapshots( $this->domain );
			$this->snapshots = [];
			foreach ( (array) $response as $snap ) {
				$s                 = [];
				$s['id']           = $snap->id;
				$s['name']         = $snap->name;
				$s['date']         = Carbon::parse( $snap->created_at )->format( 'Y-m-d H:m:i' );
				$s['statusClass']  = $snap->automatic ? 'auto' : 'manual';
				$this->snapshots[] = $s;
			}
		}

		return $this->snapshots;
	}

	/*
	*
	* Verify if an ID exist
	*
	* @return bool
	*
	*/
	public function isValid( $id = '' ) {
		if ( ! isset( $this->snapshots ) ) {
			$this->getList();
		}
		foreach ( $this->snapshots as $snap ) {
			if ( $id === $snap['id'] ) {
				return true;
			}
		}

		return false;
	}

	/*
	*
	* Verify if an ID exist
	*
	* @return array
	*
	*/
	public function getDetails( $id = '' ) {
		$result = [
			'id'   => $id,
			'name' => '-',
			'date' => '-',
			'status' => 'error',
			'data' => [],
		];
		if ( ! $this->isValid( $id ) ) {
			return $result;
		}
		$response = $this->livednsApi->getSnapshotDetails( $this->domain, $id );
		if ( ( isset( $response->code ) && 200 !== (int) $response->code ) ) {
			return $result;
		}
		$snap = (array) $response;
		$result['name']   = $snap['name'] ?? '-';
		if ( array_key_exists( 'automatic', $snap ) ) {
			$result['status'] = $snap['automatic'] ? 'auto' : 'manual';
		}
		if ( array_key_exists( 'created_at', $snap ) ) {
			$result['date'] = Carbon::parse( $snap['created_at'] )->format( 'Y-m-d H:m:i' );
		}
		if ( array_key_exists( 'zone_data', $snap ) ) {
			foreach ( $snap['zone_data'] as $s ) {
				$result['data'][] = (array) $s;
			}
		}

		return $result;
	}

	/*
	*
	*Take a snapshot.
	*
	* @return bool
	*
	*/
	public function takeNow( $name = '' ) {
		$response = $this->livednsApi->createSnapshot( $this->domain, $name );
		if ( isset( $response->code ) && 200 !== (int) $response->code ) {
			return false;
		}

		return true;
	}


}

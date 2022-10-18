<?php

if (!defined("WHMCS")) {
	die("This file cannot be accessed directly");
}

define( 'GANDI_REGISTRAR_PRODUCT_NAME', 'Gandi Registrar' );
define( 'GANDI_REGISTRAR_PRODUCT_URL', 'https://github.com/Hosterra/whmcs-gandi' );
define( 'GANDI_REGISTRAR_API_VERSION', '5' );
define( 'GANDI_REGISTRAR_VERSION', '5.0.0' );

define( 'GANDI_REGISTRAR_OPTIONS', [
	'allowNameserversChange' => false,  // Allows to change name servers by user in customer's area
] );
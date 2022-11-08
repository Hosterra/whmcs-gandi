<?php
/*
 **********************************************************************
 *         Reconfigure Additional Domain Fields for Gandi             *
 **********************************************************************
 */

$fragments = explode( '/', dirname(__FILE__) );
$path = '';
for ( $i = 0; $i < count( $fragments ) - 1; $i++ ) {
	$path .= $fragments[$i] . '/';
}
$filename = $path . 'countries/countrycodes.php';
if ( file_exists( $filename ) ) {
	include $filename;
} else {
	$country_list = [];
}

$filename = $path . 'domains/tlds.php';
if ( file_exists( $filename ) ) {
	include $filename;
} else {
	$enabled_tlds = [];
}

// NEEDED VARS
$lt = [];
foreach ( [ 'unknown', 'individual', 'company', 'association', 'publicbody'] as $type ) {
	$lt[] = $type . '|' . Lang::trans( 'gandi.legaltype.' . $type );
}
$ca_lt = [];
foreach ( [ 'CCT', 'RES', 'ABO', 'LGR'] as $type ) {
	$ca_lt[] = $type . '|' . Lang::trans( 'gandi.CA.' . $type );
}
$ca_bt = [];
foreach ( [ 'corporation', 'trust', 'government', 'education', 'unincorporated-association', 'hospital', 'trade-union', 'political-party', 'library-archive-museum', 'other' ] as $type ) {
	$ca_bt[] = $type . '|' . Lang::trans( 'gandi.CA.' . $type );
}
$eu_countries = [ 'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'EL', 'ES', 'FI', 'FR', 'GR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PL', 'PT', 'RO', 'SE', 'SI', 'SK', 'AX', 'GF', 'GP', 'MQ', ];
$eu_ct = [];
foreach ( $eu_countries as $country ) {
	if ( array_key_exists( $country, $country_list ) ) {
		$eu_ct[] = $country . '|' . $country_list[$country]['name'];
	}
}
$uk_bt = [];
foreach ( [ 'LTD', 'PLC', 'LLP', 'IP', 'SCH', 'FCORP', 'RCHAR', 'PTNR', 'STRA', 'CRC', 'FOTHER', 'OTHER' ] as $type ) {
	$uk_bt[] = $type . '|' . Lang::trans( 'gandi.UK.' . $type );
}
$nexus = [];
foreach ( [ 'C11', 'C12', 'C21', 'C31', 'C32' ] as $type ) {
	$nexus[] = $type . '|' . Lang::trans( 'gandi.US.' . $type );
}
$us_pp = [];
foreach ( [ 'P1', 'P2', 'P3', 'P4', 'P5' ] as $type ) {
	$us_pp[] = $type . '|' . Lang::trans( 'gandi.US.' . $type );
}
$legaltype = [
	'Name'    => 'x-gandi-legal-type',
	"LangVar" => "x-gandi-legal-type",
	'Type'    => 'dropdown',
	'Options' => implode( ',', $lt ),
	'Default' => 'unknown',
	'Required' => true,
];

/////////////////////////////////
// .*
foreach ( $enabled_tlds as $tld ) {
	$additionaldomainfields[$tld][] = $legaltype;
}

/////////////////////////////////
// .AERO
$additionaldomainfields['.aero'][] = array('Name' => '.AERO ID', "Remove" => true);
$additionaldomainfields['.aero'][] = array('Name' => '.AERO Key', "Remove" => true);
$additionaldomainfields['.aero'][] = array('Name' => 'x-aero_ens_authid', "LangVar" => "x-aero_ens_authid", 'Type' => 'text', 'Size' => '20', 'Required' => true);
$additionaldomainfields['.aero'][] = array('Name' => 'x-aero_ens_authkey', "LangVar" => "x-aero_ens_authkey", 'Type' => 'text', 'Size' => '20');

/////////////////////////////////
// .APP
$additionaldomainfields['.app'][] = array('Name' => '.APP SSL Agreement', "Remove" => true);

/////////////////////////////////
// .ASIA
$additionaldomainfields[".asia"][] = array("Name" => "Legal Type", "Remove" => true);
$additionaldomainfields[".asia"][] = array("Name" => "Identity Form", "Remove" => true);
$additionaldomainfields[".asia"][] = array("Name" => "Identity Number", "Remove" => true);

/////////////////////////////////
// .AU
$additionaldomainfields[".com.au"][] = array("Name" => "Registrant Name", "Remove" => true);
$additionaldomainfields[".com.au"][] = array("Name" => "Registrant ID", "Remove" => true);
$additionaldomainfields[".com.au"][] = array("Name" => "Registrant ID Type", "Remove" => true);
$additionaldomainfields[".com.au"][] = array("Name" => "Eligibility Name", "Remove" => true);
$additionaldomainfields[".com.au"][] = array("Name" => "Eligibility ID", "Remove" => true);
$additionaldomainfields[".com.au"][] = array("Name" => "Eligibility ID Type", "Remove" => true);
$additionaldomainfields[".com.au"][] = array("Name" => "Eligibility Type", "Remove" => true);
$additionaldomainfields[".com.au"][] = array("Name" => "Eligibility Reason", "Remove" => true);
$additionaldomainfields[".com.au"][] = array("Name" => "x-au_registrant_id_number", "LangVar" => "x-au_registrant_id_number", "Type" => "text", "Size" => "20", "Default" => "", "Required" => true,);
$additionaldomainfields[".com.au"][] = array("Name" => "x-au_registrant_id_type", "LangVar" => "autldregidtype", "Type" => "dropdown", "Options" => "ABN,ACN,Business Registration Number", "Default" => "ABN",);

$additionaldomainfields[".net.au"][] = array("Name" => "Registrant Name", "Remove" => true);
$additionaldomainfields[".net.au"][] = array("Name" => "Registrant ID", "Remove" => true);
$additionaldomainfields[".net.au"][] = array("Name" => "Registrant ID Type", "Remove" => true);
$additionaldomainfields[".net.au"][] = array("Name" => "Eligibility Name", "Remove" => true);
$additionaldomainfields[".net.au"][] = array("Name" => "Eligibility ID", "Remove" => true);
$additionaldomainfields[".net.au"][] = array("Name" => "Eligibility ID Type", "Remove" => true);
$additionaldomainfields[".net.au"][] = array("Name" => "Eligibility Type", "Remove" => true);
$additionaldomainfields[".net.au"][] = array("Name" => "Eligibility Reason", "Remove" => true);
$additionaldomainfields[".net.au"][] = array("Name" => "x-au_registrant_id_number", "LangVar" => "x-au_registrant_id_number", "Type" => "text", "Size" => "20", "Default" => "", "Required" => true,);
$additionaldomainfields[".net.au"][] = array("Name" => "x-au_registrant_id_type", "LangVar" => "autldregidtype", "Type" => "dropdown", "Options" => "ABN,ACN,Business Registration Number", "Default" => "ABN",);

$additionaldomainfields[".org.au"][] = array("Name" => "Registrant Name", "Remove" => true);
$additionaldomainfields[".org.au"][] = array("Name" => "Registrant ID", "Remove" => true);
$additionaldomainfields[".org.au"][] = array("Name" => "Registrant ID Type", "Remove" => true);
$additionaldomainfields[".org.au"][] = array("Name" => "Eligibility Name", "Remove" => true);
$additionaldomainfields[".org.au"][] = array("Name" => "Eligibility ID", "Remove" => true);
$additionaldomainfields[".org.au"][] = array("Name" => "Eligibility ID Type", "Remove" => true);
$additionaldomainfields[".org.au"][] = array("Name" => "Eligibility Type", "Remove" => true);
$additionaldomainfields[".org.au"][] = array("Name" => "Eligibility Reason", "Remove" => true);
$additionaldomainfields[".org.au"][] = array("Name" => "x-au_registrant_id_number", "LangVar" => "x-au_registrant_id_number", "Type" => "text", "Size" => "20", "Default" => "", "Required" => true,);
$additionaldomainfields[".org.au"][] = array("Name" => "x-au_registrant_id_type", "LangVar" => "autldregidtype", "Type" => "dropdown", "Options" => "ABN,ACN,Business Registration Number", "Default" => "ABN",);

$additionaldomainfields[".asn.au"][] = array("Name" => "Registrant Name", "Remove" => true);
$additionaldomainfields[".asn.au"][] = array("Name" => "Registrant ID", "Remove" => true);
$additionaldomainfields[".asn.au"][] = array("Name" => "Registrant ID Type", "Remove" => true);
$additionaldomainfields[".asn.au"][] = array("Name" => "Eligibility Name", "Remove" => true);
$additionaldomainfields[".asn.au"][] = array("Name" => "Eligibility ID", "Remove" => true);
$additionaldomainfields[".asn.au"][] = array("Name" => "Eligibility ID Type", "Remove" => true);
$additionaldomainfields[".asn.au"][] = array("Name" => "Eligibility Type", "Remove" => true);
$additionaldomainfields[".asn.au"][] = array("Name" => "Eligibility Reason", "Remove" => true);
$additionaldomainfields[".asn.au"][] = array("Name" => "x-au_registrant_id_number", "LangVar" => "x-au_registrant_id_number", "Type" => "text", "Size" => "20", "Default" => "", "Required" => true,);
$additionaldomainfields[".asn.au"][] = array("Name" => "x-au_registrant_id_type", "LangVar" => "autldregidtype", "Type" => "dropdown", "Options" => "ABN,ACN,Business Registration Number", "Default" => "ABN",);

$additionaldomainfields[".id.au"][] = array("Name" => "Registrant Name", "Remove" => true);
$additionaldomainfields[".id.au"][] = array("Name" => "Registrant ID", "Remove" => true);
$additionaldomainfields[".id.au"][] = array("Name" => "Registrant ID Type", "Remove" => true);
$additionaldomainfields[".id.au"][] = array("Name" => "Eligibility Name", "Remove" => true);
$additionaldomainfields[".id.au"][] = array("Name" => "Eligibility ID", "Remove" => true);
$additionaldomainfields[".id.au"][] = array("Name" => "Eligibility ID Type", "Remove" => true);
$additionaldomainfields[".id.au"][] = array("Name" => "Eligibility Type", "Remove" => true);
$additionaldomainfields[".id.au"][] = array("Name" => "Eligibility Reason", "Remove" => true);
$additionaldomainfields[".id.au"][] = array("Name" => "x-au_registrant_id_number", "LangVar" => "x-au_registrant_id_number", "Required" => true,);
$additionaldomainfields[".id.au"][] = array("Name" => "x-au_registrant_id_type", "LangVar" => "autldregidtype", "Type" => "dropdown", "Options" => "ABN,ACN,Business Registration Number", "Default" => "ABN",);

/////////////////////////////////
// .BARCELONA
$additionaldomainfields[".barcelona"][] = array("Name" => "x-barcelona_intendeduse", "LangVar" => "x-barcelona_intendeduse", "Type" => "text", "Size" => "40", "Default" => "");

/////////////////////////////////
// .BIO
$additionaldomainfields['.bio'][] = array('Name' => '.BIO SSL Agreement', "Remove" => true);

/////////////////////////////////
// .CA
$additionaldomainfields[".ca"][] = array("Name" => "Legal Type", "Remove" => true);
$additionaldomainfields[".ca"][] = array("Name" => "CIRA Agreement", "Remove" => true);
$additionaldomainfields[".ca"][] = array("Name" => "WHOIS Opt-out", "Remove" => true);
$additionaldomainfields[".ca"][] = array("Name" => "x-ca_business_entity_type", "LangVar" => "x-ca_business_entity_type", "Type" => "dropdown", "Options" => implode( ',', $ca_bt ), 'Default' => 'other', 'Required' => true );
$additionaldomainfields[".ca"][] = array("Name" => "x-ca_legaltype", "LangVar" => "x-ca_legaltype", "Type" => "dropdown", "Options" => implode( ',', $ca_lt ), 'Default' => 'CCT', 'Required' => true );
$additionaldomainfields[".ca"][] = array("Name" => "x-ca_official_rep_capacity", "LangVar" => "x-ca_official_rep_capacity", "Type" => "text", "Size" => "40", 'Required' => [ 'x-ca_legaltype' => [ 'LGR' ] ], "Default" => "");
$additionaldomainfields[".ca"][] = array("Name" => "x-ca_official_rep", "LangVar" => "x-ca_official_rep", "Type" => "text", "Size" => "40", 'Required' => [ 'x-ca_legaltype' => [ 'LGR' ] ], "Default" => "");

/////////////////////////////////
// .CAT
$additionaldomainfields[".ca"][] = array("Name" => "x-cat_intendeduse", "LangVar" => "x-cat_intendeduse", "Type" => "text", "Size" => "40", "Default" => "");

/////////////////////////////////
// .COOP
$additionaldomainfields[".coop"][] = array("Name" => "Contact Name", "Remove" => true);
$additionaldomainfields[".coop"][] = array("Name" => "Contact Company", "Remove" => true);
$additionaldomainfields[".coop"][] = array("Name" => "Contact Email", "Remove" => true);
$additionaldomainfields[".coop"][] = array("Name" => "Address 1", "Remove" => true);
$additionaldomainfields[".coop"][] = array("Name" => "Address 2", "Remove" => true);
$additionaldomainfields[".coop"][] = array("Name" => "City", "Remove" => true);
$additionaldomainfields[".coop"][] = array("Name" => "State", "Remove" => true);
$additionaldomainfields[".coop"][] = array("Name" => "ZIP Code", "Remove" => true);
$additionaldomainfields[".coop"][] = array("Name" => "Country", "Remove" => true);
$additionaldomainfields[".coop"][] = array("Name" => "Phone CC", "Remove" => true);
$additionaldomainfields[".coop"][] = array("Name" => "Phone", "Remove" => true);

/////////////////////////////////
// .CN
$additionaldomainfields[".cn"][] = array("Name" => "cnhosting", "Remove" => true);
$additionaldomainfields[".cn"][] = array("Name" => "cnhregisterclause", "Remove" => true);

/////////////////////////////////
// .DE
$additionaldomainfields[".de"][] = array("Name" => "Tax ID", "Remove" => true);
$additionaldomainfields[".de"][] = array("Name" => "Address Confirmation", "Remove" => true);
$additionaldomainfields[".de"][] = array("Name" => "Agree to DE Terms", "Remove" => true);

/////////////////////////////////
// .DEV
$additionaldomainfields['.dev'][] = array('Name' => '.DEV SSL Agreement', "Remove" => true);

/////////////////////////////////
//.EU
$additionaldomainfields['.eu'][] = array("Name" => 'Entity Type', "Remove" => true );
$additionaldomainfields['.eu'][] = array("Name" => 'EU Country of Citizenship', "Remove" => true );
$additionaldomainfields[".eu"][] = array("Name" => "x-eu_country_of_citizenship", "LangVar" => "x-eu_country_of_citizenship", "Type" => "dropdown", "Options" => implode( ',', $eu_ct ), 'Default' => 'FR', 'Required' => true );

/////////////////////////////////
// .EUS
$additionaldomainfields[".eus"][] = array("Name" => "x-eus_intendeduse", "LangVar" => "x-eus_intendeduse", "Type" => "text", "Size" => "40", "Default" => "");

// .FR
$additionaldomainfields[".fr"][] = array("Name" => "Legal Type", "Remove" => true);
$additionaldomainfields[".fr"][] = array("Name" => "Info", "Remove" => true);
$additionaldomainfields[".fr"][] = array("Name" => "Birthdate", "Remove" => true);
$additionaldomainfields[".fr"][] = array("Name" => "Birthplace City", "Remove" => true);
$additionaldomainfields[".fr"][] = array("Name" => "Birthplace Country", "Remove" => true);
$additionaldomainfields[".fr"][] = array("Name" => "Birthplace Postcode", "Remove" => true);
$additionaldomainfields[".fr"][] = array("Name" => "SIRET Number", "Remove" => true);
$additionaldomainfields[".fr"][] = array("Name" => "DUNS Number", "Remove" => true);
$additionaldomainfields[".fr"][] = array("Name" => "VAT Number", "Remove" => true);
$additionaldomainfields[".fr"][] = array("Name" => "Trademark Number", "Remove" => true);

$additionaldomainfields[".re"][] = array("Name" => "Legal Type", "Remove" => true);
$additionaldomainfields[".re"][] = array("Name" => "Info", "Remove" => true);
$additionaldomainfields[".re"][] = array("Name" => "Birthdate", "Remove" => true);
$additionaldomainfields[".re"][] = array("Name" => "Birthplace City", "Remove" => true);
$additionaldomainfields[".re"][] = array("Name" => "Birthplace Country", "Remove" => true);
$additionaldomainfields[".re"][] = array("Name" => "Birthplace Postcode", "Remove" => true);
$additionaldomainfields[".re"][] = array("Name" => "SIRET Number", "Remove" => true);
$additionaldomainfields[".re"][] = array("Name" => "DUNS Number", "Remove" => true);
$additionaldomainfields[".re"][] = array("Name" => "VAT Number", "Remove" => true);
$additionaldomainfields[".re"][] = array("Name" => "Trademark Number", "Remove" => true);

$additionaldomainfields[".pm"][] = array("Name" => "Legal Type", "Remove" => true);
$additionaldomainfields[".pm"][] = array("Name" => "Info", "Remove" => true);
$additionaldomainfields[".pm"][] = array("Name" => "Birthdate", "Remove" => true);
$additionaldomainfields[".pm"][] = array("Name" => "Birthplace City", "Remove" => true);
$additionaldomainfields[".pm"][] = array("Name" => "Birthplace Country", "Remove" => true);
$additionaldomainfields[".pm"][] = array("Name" => "Birthplace Postcode", "Remove" => true);
$additionaldomainfields[".pm"][] = array("Name" => "SIRET Number", "Remove" => true);
$additionaldomainfields[".pm"][] = array("Name" => "DUNS Number", "Remove" => true);
$additionaldomainfields[".pm"][] = array("Name" => "VAT Number", "Remove" => true);
$additionaldomainfields[".pm"][] = array("Name" => "Trademark Number", "Remove" => true);

$additionaldomainfields[".tf"][] = array("Name" => "Legal Type", "Remove" => true);
$additionaldomainfields[".tf"][] = array("Name" => "Info", "Remove" => true);
$additionaldomainfields[".tf"][] = array("Name" => "Birthdate", "Remove" => true);
$additionaldomainfields[".tf"][] = array("Name" => "Birthplace City", "Remove" => true);
$additionaldomainfields[".tf"][] = array("Name" => "Birthplace Country", "Remove" => true);
$additionaldomainfields[".tf"][] = array("Name" => "Birthplace Postcode", "Remove" => true);
$additionaldomainfields[".tf"][] = array("Name" => "SIRET Number", "Remove" => true);
$additionaldomainfields[".tf"][] = array("Name" => "DUNS Number", "Remove" => true);
$additionaldomainfields[".tf"][] = array("Name" => "VAT Number", "Remove" => true);
$additionaldomainfields[".tf"][] = array("Name" => "Trademark Number", "Remove" => true);

$additionaldomainfields[".wf"][] = array("Name" => "Legal Type", "Remove" => true);
$additionaldomainfields[".wf"][] = array("Name" => "Info", "Remove" => true);
$additionaldomainfields[".wf"][] = array("Name" => "Birthdate", "Remove" => true);
$additionaldomainfields[".wf"][] = array("Name" => "Birthplace City", "Remove" => true);
$additionaldomainfields[".wf"][] = array("Name" => "Birthplace Country", "Remove" => true);
$additionaldomainfields[".wf"][] = array("Name" => "Birthplace Postcode", "Remove" => true);
$additionaldomainfields[".wf"][] = array("Name" => "SIRET Number", "Remove" => true);
$additionaldomainfields[".wf"][] = array("Name" => "DUNS Number", "Remove" => true);
$additionaldomainfields[".wf"][] = array("Name" => "VAT Number", "Remove" => true);
$additionaldomainfields[".wf"][] = array("Name" => "Trademark Number", "Remove" => true);

$additionaldomainfields[".yt"][] = array("Name" => "Legal Type", "Remove" => true);
$additionaldomainfields[".yt"][] = array("Name" => "Info", "Remove" => true);
$additionaldomainfields[".yt"][] = array("Name" => "Birthdate", "Remove" => true);
$additionaldomainfields[".yt"][] = array("Name" => "Birthplace City", "Remove" => true);
$additionaldomainfields[".yt"][] = array("Name" => "Birthplace Country", "Remove" => true);
$additionaldomainfields[".yt"][] = array("Name" => "Birthplace Postcode", "Remove" => true);
$additionaldomainfields[".yt"][] = array("Name" => "SIRET Number", "Remove" => true);
$additionaldomainfields[".yt"][] = array("Name" => "DUNS Number", "Remove" => true);
$additionaldomainfields[".yt"][] = array("Name" => "VAT Number", "Remove" => true);
$additionaldomainfields[".yt"][] = array("Name" => "Trademark Number", "Remove" => true);

/////////////////////////////////
// .GAL
$additionaldomainfields[".gal"][] = array("Name" => "x-gal_intendeduse", "LangVar" => "x-gal_intendeduse", "Type" => "text", "Size" => "40", "Default" => "");

/////////////////////////////////
// .IT
$additionaldomainfields[".it"][] = array("Name" => "Legal Type", "Remove" => true);
$additionaldomainfields[".it"][] = array("Name" => "Tax ID", "Remove" => true);
$additionaldomainfields[".it"][] = array("Name" => "Publish Personal Data", "Remove" => true);
$additionaldomainfields[".it"][] = array("Name" => "Accept Section 3 of .IT registrar contract", "Remove" => true);
$additionaldomainfields[".it"][] = array("Name" => "Accept Section 5 of .IT registrar contract", "Remove" => true);
$additionaldomainfields[".it"][] = array("Name" => "Accept Section 6 of .IT registrar contract", "Remove" => true);
$additionaldomainfields[".it"][] = array("Name" => "Accept Section 7 of .IT registrar contract", "Remove" => true);

/////////////////////////////////
// .JOBS
$additionaldomainfields['.jobs'][] = array('Name' => 'Website', "Remove" => true);
$additionaldomainfields[".jobs"][] = array("Name" => "x-jobs_website", "LangVar" => "x-jobs_website", "Type" => "text", "Size" => "80", "Default" => "");

/////////////////////////////////
// .LT
$additionaldomainfields[".lt"][] = array("Name" => "x-lt_registrant_legal_id", "LangVar" => "x-lt_registrant_legal_id", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

/////////////////////////////////
// .LTDA
$additionaldomainfields[".ltda"][] = array("Name" => "x-ltda_authority", "LangVar" => "x-ltda_authority", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);
$additionaldomainfields[".ltda"][] = array("Name" => "x-ltda_licensenumber", "LangVar" => "x-ltda_licensenumber", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

/////////////////////////////////
// .MADRID
$additionaldomainfields[".madrid"][] = array("Name" => "x-madrid_intendeduse", "LangVar" => "x-madrid_intendeduse", "Type" => "text", "Size" => "40", "Default" => "");

/////////////////////////////////
// .PRO
$additionaldomainfields[".pro"][] = array("Name" => "Profession", "Remove" => true);
$additionaldomainfields[".pro"][] = array("Name" => "License Number", "Remove" => true);
$additionaldomainfields[".pro"][] = array("Name" => "Authority", "Remove" => true);
$additionaldomainfields[".pro"][] = array("Name" => "Authority Website", "Remove" => true);

/////////////////////////////////
// .NU
$additionaldomainfields[".nu"][] = array("Name" => "Identification Number", "Remove" => true);
$additionaldomainfields[".nu"][] = array("Name" => "VAT Number", "Remove" => true);
$additionaldomainfields[".nu"][] = array("Name" => "x-nu_registrant_idnumber", "LangVar" => "x-nu_registrant_idnumber", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);
$additionaldomainfields[".nu"][] = array("Name" => "x-nu_registrant_vatid", "LangVar" => "x-nu_registrant_vatid", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

/////////////////////////////////
// .PL
$additionaldomainfields['.pl'][] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.pc.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.miasta.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.atm.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.rel.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.gmina.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.szkola.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.sos.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.media.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.edu.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.auto.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.agro.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.turystyka.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.gov.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.aid.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.nieruchomosci.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.com.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.priv.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.tm.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.travel.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.info.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.org.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.net.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.sex.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.sklep.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.powiat.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.mail.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.realestate.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.shop.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.mil.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.nom.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.gsm.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.tourism.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.targi.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);
$additionaldomainfields['.biz.pl'] = array('Name'	=> 'Publish Contact in .PL WHOIS', "Remove" => true);

/////////////////////////////////
// .QUEBEC
$additionaldomainfields[".quebec"][] = array("Name" => "Intended Use", "Remove" => true);
$additionaldomainfields[".quebec"][] = array("Name" => "Info", "Remove" => true);
$additionaldomainfields[".quebec"][] = array("Name" => "x-quebec_intendeduse", "LangVar" => "x-quebec_intendeduse", "Type" => "text", "Size" => "40", "Default" => "");

/////////////////////////////////
// .RADIO
$additionaldomainfields[".radio"][] = array("Name" => "x-radio_intendeduse", "LangVar" => "x-radio_intendeduse", "Type" => "text", "Size" => "40", "Default" => "");

/////////////////////////////////
// .RO
$additionaldomainfields['.ro'][] = array('Name'	=> 'CNPFiscalCode', "Remove" => true);
$additionaldomainfields['.ro'][] = array('Name'	=> 'Registration Number', "Remove" => true);
$additionaldomainfields['.ro'][] = array('Name'	=> 'Registrant Type', "Remove" => true);
$additionaldomainfields['.ro'][] = array("Name" => "x-ro_registrant_idnumber", "LangVar" => "x-ro_registrant_idnumber", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

$additionaldomainfields['.arts.ro'][] = array('Name'	=> 'CNPFiscalCode', "Remove" => true);
$additionaldomainfields['.arts.ro'][] = array('Name'	=> 'Registration Number', "Remove" => true);
$additionaldomainfields['.arts.ro'][] = array('Name'	=> 'Registrant Type', "Remove" => true);
$additionaldomainfields['.arts.ro'][] = array("Name" => "x-ro_registrant_idnumber", "LangVar" => "x-ro_registrant_idnumber", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

$additionaldomainfields['.co.ro'][] = array('Name'	=> 'CNPFiscalCode', "Remove" => true);
$additionaldomainfields['.co.ro'][] = array('Name'	=> 'Registration Number', "Remove" => true);
$additionaldomainfields['.co.ro'][] = array('Name'	=> 'Registrant Type', "Remove" => true);
$additionaldomainfields['.co.ro'][] = array("Name" => "x-ro_registrant_idnumber", "LangVar" => "x-ro_registrant_idnumber", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

$additionaldomainfields['.com.ro'][] = array('Name'	=> 'CNPFiscalCode', "Remove" => true);
$additionaldomainfields['.com.ro'][] = array('Name'	=> 'Registration Number', "Remove" => true);
$additionaldomainfields['.com.ro'][] = array('Name'	=> 'Registrant Type', "Remove" => true);
$additionaldomainfields['.com.ro'][] = array("Name" => "x-ro_registrant_idnumber", "LangVar" => "x-ro_registrant_idnumber", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

$additionaldomainfields['.firm.ro'][] = array('Name'	=> 'CNPFiscalCode', "Remove" => true);
$additionaldomainfields['.firm.ro'][] = array('Name'	=> 'Registration Number', "Remove" => true);
$additionaldomainfields['.firm.ro'][] = array('Name'	=> 'Registrant Type', "Remove" => true);
$additionaldomainfields['.firm.ro'][] = array("Name" => "x-ro_registrant_idnumber", "LangVar" => "x-ro_registrant_idnumber", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

$additionaldomainfields['.info.ro'][] = array('Name'	=> 'CNPFiscalCode', "Remove" => true);
$additionaldomainfields['.info.ro'][] = array('Name'	=> 'Registration Number', "Remove" => true);
$additionaldomainfields['.info.ro'][] = array('Name'	=> 'Registrant Type', "Remove" => true);
$additionaldomainfields['.info.ro'][] = array("Name" => "x-ro_registrant_idnumber", "LangVar" => "x-ro_registrant_idnumber", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

$additionaldomainfields['.nom.ro'][] = array('Name'	=> 'CNPFiscalCode', "Remove" => true);
$additionaldomainfields['.nom.ro'][] = array('Name'	=> 'Registration Number', "Remove" => true);
$additionaldomainfields['.nom.ro'][] = array('Name'	=> 'Registrant Type', "Remove" => true);
$additionaldomainfields['.nom.ro'][] = array("Name" => "x-ro_registrant_idnumber", "LangVar" => "x-ro_registrant_idnumber", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

$additionaldomainfields['.nt.ro'][] = array('Name'	=> 'CNPFiscalCode', "Remove" => true);
$additionaldomainfields['.nt.ro'][] = array('Name'	=> 'Registration Number', "Remove" => true);
$additionaldomainfields['.nt.ro'][] = array('Name'	=> 'Registrant Type', "Remove" => true);
$additionaldomainfields['.nt.ro'][] = array("Name" => "x-ro_registrant_idnumber", "LangVar" => "x-ro_registrant_idnumber", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

$additionaldomainfields['.org.ro'][] = array('Name'	=> 'CNPFiscalCode', "Remove" => true);
$additionaldomainfields['.org.ro'][] = array('Name'	=> 'Registration Number', "Remove" => true);
$additionaldomainfields['.org.ro'][] = array('Name'	=> 'Registrant Type', "Remove" => true);
$additionaldomainfields['.org.ro'][] = array("Name" => "x-ro_registrant_idnumber", "LangVar" => "x-ro_registrant_idnumber", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

$additionaldomainfields['.rec.ro'][] = array('Name'	=> 'CNPFiscalCode', "Remove" => true);
$additionaldomainfields['.rec.ro'][] = array('Name'	=> 'Registration Number', "Remove" => true);
$additionaldomainfields['.rec.ro'][] = array('Name'	=> 'Registrant Type', "Remove" => true);
$additionaldomainfields['.rec.ro'][] = array("Name" => "x-ro_registrant_idnumber", "LangVar" => "x-ro_registrant_idnumber", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

$additionaldomainfields['.ro.ro'][] = array('Name'	=> 'CNPFiscalCode', "Remove" => true);
$additionaldomainfields['.ro.ro'][] = array('Name'	=> 'Registration Number', "Remove" => true);
$additionaldomainfields['.ro.ro'][] = array('Name'	=> 'Registrant Type', "Remove" => true);
$additionaldomainfields['.ro.ro'][] = array("Name" => "x-ro_registrant_idnumber", "LangVar" => "x-ro_registrant_idnumber", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

$additionaldomainfields['.store.ro'][] = array('Name'	=> 'CNPFiscalCode', "Remove" => true);
$additionaldomainfields['.store.ro'][] = array('Name'	=> 'Registration Number', "Remove" => true);
$additionaldomainfields['.store.ro'][] = array('Name'	=> 'Registrant Type', "Remove" => true);
$additionaldomainfields['.store.ro'][] = array("Name" => "x-ro_registrant_idnumber", "LangVar" => "x-ro_registrant_idnumber", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

$additionaldomainfields['.tm.ro'][] = array('Name'	=> 'CNPFiscalCode', "Remove" => true);
$additionaldomainfields['.tm.ro'][] = array('Name'	=> 'Registration Number', "Remove" => true);
$additionaldomainfields['.tm.ro'][] = array('Name'	=> 'Registrant Type', "Remove" => true);
$additionaldomainfields['.tm.ro'][] = array("Name" => "x-ro_registrant_idnumber", "LangVar" => "x-ro_registrant_idnumber", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

$additionaldomainfields['.www.ro'][] = array('Name'	=> 'CNPFiscalCode', "Remove" => true);
$additionaldomainfields['.www.ro'][] = array('Name'	=> 'Registration Number', "Remove" => true);
$additionaldomainfields['.www.ro'][] = array('Name'	=> 'Registrant Type', "Remove" => true);
$additionaldomainfields['.www.ro'][] = array("Name" => "x-ro_registrant_idnumber", "LangVar" => "x-ro_registrant_idnumber", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

/////////////////////////////////
// .RU
$additionaldomainfields['.ru'][] = array('Name'	=> 'Registrant Type', "Remove" => true);
$additionaldomainfields['.ru'][] = array('Name'	=> 'Individuals Birthday', "Remove" => true);
$additionaldomainfields['.ru'][] = array('Name'	=> 'Individuals Passport Number', "Remove" => true);
$additionaldomainfields['.ru'][] = array('Name' => 'Individuals Passport Issuer', "Remove" => true);
$additionaldomainfields['.ru'][] = array('Name'	=> 'Individuals Passport Issue Date', "Remove" => true);
$additionaldomainfields['.ru'][] = array('Name'	=> 'Individuals: Whois Privacy', "Remove" => true);
$additionaldomainfields['.ru'][] = array('Name'	=> 'Russian Organizations Taxpayer Number 1', "Remove" => true);
$additionaldomainfields['.ru'][] = array('Name'	=> 'Russian Organizations Territory-Linked Taxpayer Number 2', "Remove" => true);

$additionaldomainfields['.xn--p1ai'][] = array('Name'	=> 'Registrant Type', "Remove" => true);
$additionaldomainfields['.xn--p1ai'][] = array('Name'	=> 'Individuals Birthday', "Remove" => true);
$additionaldomainfields['.xn--p1ai'][] = array('Name'	=> 'Individuals Passport Number', "Remove" => true);
$additionaldomainfields['.xn--p1ai'][] = array('Name' => 'Individuals Passport Issuer', "Remove" => true);
$additionaldomainfields['.xn--p1ai'][] = array('Name'	=> 'Individuals Passport Issue Date', "Remove" => true);
$additionaldomainfields['.xn--p1ai'][] = array('Name'	=> 'Individuals: Whois Privacy', "Remove" => true);
$additionaldomainfields['.xn--p1ai'][] = array('Name'	=> 'Russian Organizations Taxpayer Number 1', "Remove" => true);
$additionaldomainfields['.xn--p1ai'][] = array('Name'	=> 'Russian Organizations Territory-Linked Taxpayer Number 2', "Remove" => true);

/////////////////////////////////
// .SCOT
$additionaldomainfields[".scot"][] = array("Name" => "Intended Use", "Remove" => true);
$additionaldomainfields[".scot"][] = array("Name" => "Info", "Remove" => true);
$additionaldomainfields['.scot'][] = array('Name' => 'Terms & Conditions', "Remove" => true);
$additionaldomainfields[".scot"][] = array("Name" => "x-scot_intendeduse", "LangVar" => "x-scot_intendeduse", "Type" => "text", "Size" => "40", "Default" => "");

/////////////////////////////////
// .SE
$additionaldomainfields['.se'][] = array('Name' => 'Identification Number', "Remove" => true);
$additionaldomainfields['.se'][] = array('Name'	=> 'VAT', "Remove" => true);
$additionaldomainfields[".se"][] = array("Name" => "x-se_ident_number", "LangVar" => "x-se_ident_number", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);
$additionaldomainfields[".se"][] = array("Name" => "x-se_registrant_vatid", "LangVar" => "x-se_registrant_vatid", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

$additionaldomainfields['.tm.se'][] = array('Name' => 'Identification Number', "Remove" => true);
$additionaldomainfields['.tm.se'][] = array('Name'	=> 'VAT', "Remove" => true);
$additionaldomainfields[".tm.se"][] = array("Name" => "x-se_ident_number", "LangVar" => "x-se_ident_number", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);
$additionaldomainfields[".tm.se"][] = array("Name" => "x-se_registrant_vatid", "LangVar" => "x-se_registrant_vatid", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

$additionaldomainfields['.org.se'][] = array('Name' => 'Identification Number', "Remove" => true);
$additionaldomainfields['.org.se'][] = array('Name'	=> 'VAT', "Remove" => true);
$additionaldomainfields[".org.se"][] = array("Name" => "x-se_ident_number", "LangVar" => "x-se_ident_number", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);
$additionaldomainfields[".org.se"][] = array("Name" => "x-se_registrant_vatid", "LangVar" => "x-se_registrant_vatid", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

$additionaldomainfields['.pp.se'][] = array('Name' => 'Identification Number', "Remove" => true);
$additionaldomainfields['.pp.se'][] = array('Name'	=> 'VAT', "Remove" => true);
$additionaldomainfields[".pp.se"][] = array("Name" => "x-se_ident_number", "LangVar" => "x-se_ident_number", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);
$additionaldomainfields[".pp.se"][] = array("Name" => "x-se_registrant_vatid", "LangVar" => "x-se_registrant_vatid", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

$additionaldomainfields['.parti.se'][] = array('Name' => 'Identification Number', "Remove" => true);
$additionaldomainfields['.parti.se'][] = array('Name'	=> 'VAT', "Remove" => true);
$additionaldomainfields[".parti.se"][] = array("Name" => "x-se_ident_number", "LangVar" => "x-se_ident_number", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);
$additionaldomainfields[".parti.se"][] = array("Name" => "x-se_registrant_vatid", "LangVar" => "x-se_registrant_vatid", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

$additionaldomainfields['.presse.se'][] = array('Name' => 'Identification Number', "Remove" => true);
$additionaldomainfields['.presse.se'][] = array('Name'	=> 'VAT', "Remove" => true);
$additionaldomainfields[".presse.se"][] = array("Name" => "x-se_ident_number", "LangVar" => "x-se_ident_number", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);
$additionaldomainfields[".presse.se"][] = array("Name" => "x-se_registrant_vatid", "LangVar" => "x-se_registrant_vatid", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

/////////////////////////////////
// .SG
$additionaldomainfields[".sg"][] = array("Name" => "RCB Singapore ID", "Remove" => true);
$additionaldomainfields[".sg"][] = array("Name" => "Registrant Type", "Remove" => true);
$additionaldomainfields['.sg'][] = array('Name' => 'Admin Personal ID', "Remove" => true);
$additionaldomainfields[".sg"][] = array("Name" => "x-sg_idnumber", "LangVar" => "x-sg_idnumber", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

$additionaldomainfields[".com.sg"][] = array("Name" => "RCB Singapore ID", "Remove" => true);
$additionaldomainfields[".com.sg"][] = array("Name" => "Registrant Type", "Remove" => true);
$additionaldomainfields['.com.sg'][] = array('Name' => 'Admin Personal ID', "Remove" => true);
$additionaldomainfields[".com.sg"][] = array("Name" => "x-sg_idnumber", "LangVar" => "x-sg_idnumber", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

$additionaldomainfields[".edu.sg"][] = array("Name" => "RCB Singapore ID", "Remove" => true);
$additionaldomainfields[".edu.sg"][] = array("Name" => "Registrant Type", "Remove" => true);
$additionaldomainfields['.edu.sg'][] = array('Name' => 'Admin Personal ID', "Remove" => true);
$additionaldomainfields[".edu.sg"][] = array("Name" => "x-sg_idnumber", "LangVar" => "x-sg_idnumber", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

$additionaldomainfields[".net.sg"][] = array("Name" => "RCB Singapore ID", "Remove" => true);
$additionaldomainfields[".net.sg"][] = array("Name" => "Registrant Type", "Remove" => true);
$additionaldomainfields['.net.sg'][] = array('Name' => 'Admin Personal ID', "Remove" => true);
$additionaldomainfields[".net.sg"][] = array("Name" => "x-sg_idnumber", "LangVar" => "x-sg_idnumber", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

$additionaldomainfields[".org.sg"][] = array("Name" => "RCB Singapore ID", "Remove" => true);
$additionaldomainfields[".org.sg"][] = array("Name" => "Registrant Type", "Remove" => true);
$additionaldomainfields['.org.sg'][] = array('Name' => 'Admin Personal ID', "Remove" => true);
$additionaldomainfields[".org.sg"][] = array("Name" => "x-sg_idnumber", "LangVar" => "x-sg_idnumber", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

$additionaldomainfields[".per.sg"][] = array("Name" => "RCB Singapore ID", "Remove" => true);
$additionaldomainfields[".per.sg"][] = array("Name" => "Registrant Type", "Remove" => true);
$additionaldomainfields['.per.sg'][] = array('Name' => 'Admin Personal ID', "Remove" => true);
$additionaldomainfields[".per.sg"][] = array("Name" => "x-sg_idnumber", "LangVar" => "x-sg_idnumber", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

/////////////////////////////////
// .SPORT
$additionaldomainfields[".sport"][] = array("Name" => "x-sport_intendeduse", "LangVar" => "x-sport_intendeduse", "Type" => "text", "Size" => "40", "Default" => "");

/////////////////////////////////
// .SRL
$additionaldomainfields[".srl"][] = array("Name" => "x-srl_authority", "LangVar" => "x-srl_authority", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);
$additionaldomainfields[".srl"][] = array("Name" => "x-srl_licensenumber", "LangVar" => "x-srl_licensenumber", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

/////////////////////////////////
// .SWISS
$additionaldomainfields['.swiss'][] = array('Name' => 'Core Intended Use', "Remove" => true);
$additionaldomainfields['.swiss'][] = array('Name' => 'Registrant Enterprise ID', "Remove" => true);
$additionaldomainfields[".swiss"][] = array("Name" => "x-swiss_enterpriseid", "LangVar" => "x-swiss_enterpriseid", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);
$additionaldomainfields[".swiss"][] = array("Name" => "x-swiss_intendeduse", "LangVar" => "x-swiss_intendeduse", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

/////////////////////////////////
// .TRAVEL
$additionaldomainfields['.travel'][] = array('Name' => '.TRAVEL UIN Code', "Remove" => true);
$additionaldomainfields['.travel'][] = array('Name' => '.TRAVEL Usage Agreement', "Remove" => true);

/////////////////////////////////
// .TW
$additionaldomainfields[".tw"][] = array("Name" => "x-tw_company_number", "LangVar" => "x-tw_company_number", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

/////////////////////////////////
// .UK
$additionaldomainfields[".uk"][] = array("Name" => "Legal Type", "Remove" => true);
$additionaldomainfields['.uk'][] = array('Name' => 'Company ID Number', "Remove" => true);
$additionaldomainfields[".uk"][] = array("Name" => "Registrant Name", "Remove" => true);
$additionaldomainfields[".uk"][] = array("Name" => "WHOIS Opt-out", "Remove" => true);
$additionaldomainfields[".uk"][] = array("Name" => "x-uk_contact_type", "LangVar" => "x-uk_contact_type", "Type" => "dropdown", "Options" => implode( ',', $uk_bt ), 'Required' => true );
$additionaldomainfields[".uk"][] = array("Name" => "x-uk_co_no", "LangVar" => "x-uk_co_no", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

$additionaldomainfields[".co.uk"][] = array("Name" => "Legal Type", "Remove" => true);
$additionaldomainfields['.co.uk'][] = array('Name' => 'Company ID Number', "Remove" => true);
$additionaldomainfields[".co.uk"][] = array("Name" => "Registrant Name", "Remove" => true);
$additionaldomainfields[".co.uk"][] = array("Name" => "WHOIS Opt-out", "Remove" => true);
$additionaldomainfields[".co.uk"][] = array("Name" => "x-uk_contact_type", "LangVar" => "x-uk_contact_type", "Type" => "dropdown", "Options" => implode( ',', $uk_bt ), 'Required' => true );
$additionaldomainfields[".co.uk"][] = array("Name" => "x-uk_co_no", "LangVar" => "x-uk_co_no", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

$additionaldomainfields[".net.uk"][] = array("Name" => "Legal Type", "Remove" => true);
$additionaldomainfields['.net.uk'][] = array('Name' => 'Company ID Number', "Remove" => true);
$additionaldomainfields[".net.uk"][] = array("Name" => "Registrant Name", "Remove" => true);
$additionaldomainfields[".net.uk"][] = array("Name" => "WHOIS Opt-out", "Remove" => true);
$additionaldomainfields[".net.uk"][] = array("Name" => "x-uk_contact_type", "LangVar" => "x-uk_contact_type", "Type" => "dropdown", "Options" => implode( ',', $uk_bt ), 'Required' => true );
$additionaldomainfields[".net.uk"][] = array("Name" => "x-uk_co_no", "LangVar" => "x-uk_co_no", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

$additionaldomainfields[".org.uk"][] = array("Name" => "Legal Type", "Remove" => true);
$additionaldomainfields['.org.uk'][] = array('Name' => 'Company ID Number', "Remove" => true);
$additionaldomainfields[".org.uk"][] = array("Name" => "Registrant Name", "Remove" => true);
$additionaldomainfields[".org.uk"][] = array("Name" => "WHOIS Opt-out", "Remove" => true);
$additionaldomainfields[".org.uk"][] = array("Name" => "x-uk_contact_type", "LangVar" => "x-uk_contact_type", "Type" => "dropdown", "Options" => implode( ',', $uk_bt ), 'Required' => true );
$additionaldomainfields[".org.uk"][] = array("Name" => "x-uk_co_no", "LangVar" => "x-uk_co_no", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

$additionaldomainfields[".plc.uk"][] = array("Name" => "Legal Type", "Remove" => true);
$additionaldomainfields['.plc.uk'][] = array('Name' => 'Company ID Number', "Remove" => true);
$additionaldomainfields[".plc.uk"][] = array("Name" => "Registrant Name", "Remove" => true);
$additionaldomainfields[".plc.uk"][] = array("Name" => "WHOIS Opt-out", "Remove" => true);
$additionaldomainfields[".plc.uk"][] = array("Name" => "x-uk_contact_type", "LangVar" => "x-uk_contact_type", "Type" => "dropdown", "Options" => implode( ',', $uk_bt ), 'Required' => true );
$additionaldomainfields[".plc.uk"][] = array("Name" => "x-uk_co_no", "LangVar" => "x-uk_co_no", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

$additionaldomainfields[".ltd.uk"][] = array("Name" => "Legal Type", "Remove" => true);
$additionaldomainfields['.ltd.uk'][] = array('Name' => 'Company ID Number', "Remove" => true);
$additionaldomainfields[".ltd.uk"][] = array("Name" => "Registrant Name", "Remove" => true);
$additionaldomainfields[".ltd.uk"][] = array("Name" => "WHOIS Opt-out", "Remove" => true);
$additionaldomainfields[".ltd.uk"][] = array("Name" => "x-uk_contact_type", "LangVar" => "x-uk_contact_type", "Type" => "dropdown", "Options" => implode( ',', $uk_bt ), 'Required' => true );
$additionaldomainfields[".ltd.uk"][] = array("Name" => "x-uk_co_no", "LangVar" => "x-uk_co_no", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

$additionaldomainfields[".me.uk"][] = array("Name" => "Legal Type", "Remove" => true);
$additionaldomainfields['.me.uk'][] = array('Name' => 'Company ID Number', "Remove" => true);
$additionaldomainfields[".me.uk"][] = array("Name" => "Registrant Name", "Remove" => true);
$additionaldomainfields[".me.uk"][] = array("Name" => "WHOIS Opt-out", "Remove" => true);
$additionaldomainfields[".me.uk"][] = array("Name" => "x-uk_contact_type", "LangVar" => "x-uk_contact_type", "Type" => "dropdown", "Options" => implode( ',', $uk_bt ), 'Required' => true );
$additionaldomainfields[".me.uk"][] = array("Name" => "x-uk_co_no", "LangVar" => "x-uk_co_no", "Type" => "text", "Size" => "40", "Default" => "", "Required" => true);

/////////////////////////////////
// .TEL
$additionaldomainfields[".tel"][] = array("Name" => "Legal Type", "Remove" => true);
$additionaldomainfields[".tel"][] = array("Name" => "WHOIS Opt-out", "Remove" => true);

/////////////////////////////////
// .US
$additionaldomainfields[".us"][] = array("Name" => "Nexus Category", "Remove" => true);
$additionaldomainfields[".us"][] = array("Name" => "Nexus Country", "Remove" => true);
$additionaldomainfields[".us"][] = array("Name" => "Application Purpose", "Remove" => true);
$additionaldomainfields[".us"][] = array("Name" => "x-us_nexus_category", "LangVar" => "x-us_nexus_category", "Type" => "dropdown", "Options" => implode( ',', $nexus ), 'Required' => true );
$additionaldomainfields[".us"][] = array("Name" => "x-us_app_purpose", "LangVar" => "x-us_app_purpose", "Type" => "dropdown", "Options" => implode( ',', $us_pp ), 'Required' => true );

$additionaldomainfields[".us.com"][] = array("Name" => "Nexus Category", "Remove" => true);
$additionaldomainfields[".us.com"][] = array("Name" => "Nexus Country", "Remove" => true);
$additionaldomainfields[".us.com"][] = array("Name" => "Application Purpose", "Remove" => true);
$additionaldomainfields[".us.com"][] = array("Name" => "x-us_nexus_category", "LangVar" => "x-us_nexus_category", "Type" => "dropdown", "Options" => implode( ',', $nexus ), 'Required' => true );
$additionaldomainfields[".us.com"][] = array("Name" => "x-us_app_purpose", "LangVar" => "x-us_app_purpose", "Type" => "dropdown", "Options" => implode( ',', $us_pp ), 'Required' => true );

$additionaldomainfields[".us.org"][] = array("Name" => "Nexus Category", "Remove" => true);
$additionaldomainfields[".us.org"][] = array("Name" => "Nexus Country", "Remove" => true);
$additionaldomainfields[".us.org"][] = array("Name" => "Application Purpose", "Remove" => true);
$additionaldomainfields[".us.org"][] = array("Name" => "x-us_nexus_category", "LangVar" => "x-us_nexus_category", "Type" => "dropdown", "Options" => implode( ',', $nexus ), 'Required' => true );
$additionaldomainfields[".us.org"][] = array("Name" => "x-us_app_purpose", "LangVar" => "x-us_app_purpose", "Type" => "dropdown", "Options" => implode( ',', $us_pp ), 'Required' => true );

/////////////////////////////////
// .VOTE
$additionaldomainfields['.vote'] = array('Name' => 'Agreement', "Remove" => true);

/////////////////////////////////
// .VOTO
$additionaldomainfields['.voto'] = array('Name' => 'Agreement', "Remove" => true);
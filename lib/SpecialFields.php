<?php

namespace WHMCS\Module\Registrar\Gandi;

class SpecialFields {

	/*
	 * Additional domain fields. Checked 2022-10-21
	 */
    private static $domainFields = [
	    'x-aero_ens_authid',
	    'x-aero_ens_authkey',
	    'x-au_registrant_id_number',
	    'x-au_registrant_id_type',
	    // gandi corporate => 'x-az_admin_pp_id',
	    // gandi corporate => 'x-az_billing_pp_id',
	    // gandi corporate => 'x-az_registrant_company_number',
	    // gandi corporate => 'x-az_registrant_pp_id',
	    // gandi corporate => 'x-az_tech_pp_id',
	    'x-barcelona_intendeduse',
	    'x-barcelona_trademarkid',
	    'x-ca_business_entity_type',
	    'x-ca_legaltype',
	    'x-ca_official_rep',
	    'x-ca_official_rep_capacity',
	    // deprecated => 'x-ca_owner_name',
	    'x-cat_intendeduse',
	    // deprecated => 'x-coop_sponsor',
	    // gandi corporate => 'x-cy_reg_identity_no',
	    'x-document_country',
	    'x-document_type',
	    'x-document_value',
	    'x-es_admin_identification',                // Excluded
	    'x-es_admin_tipo_identification',           // Excluded
	    'x-es_bill_identification',                 // Excluded
	    'x-es_bill_tipo_identification',            // Excluded
	    // deprecated => 'x-es_identification',
	    // deprecated => 'x-es_legalform',
	    'x-es_owner_identification',                // Excluded
	    'x-es_owner_legalform',                     // Excluded
	    'x-es_owner_tipo_identification',           // Excluded
	    'x-es_tech_identification',                 // Excluded
	    'x-es_tech_tipo_identification',            // Excluded
	    // deprecated => 'x-es_tipo_identification',
	    'x-eu_country_of_citizenship',
	    'x-eus_intendeduse',
	    'x-fi_business_number',                     // Excluded
	    'x-fi_ident_number',                        // Excluded
	    'x-fi_isfinish',                            // Excluded
	    'x-fi_organization_type',                   // Excluded
	    'x-gal_intendeduse',
	    'x-hk_registrant_birth_date',               // Excluded
	    'x-hk_registrant_document_number',          // Excluded
	    'x-hk_registrant_document_origin_country',  // Excluded
	    'x-hk_registrant_document_type',            // Excluded
	    'x-hk_registrant_other_document_type',      // Excluded
	    // gandi corporate => 'x-hr_ident_number',
	    // gandi corporate => 'x-hr_non_eu_pm_business_number',
	    // gandi corporate => 'x-hu_idnumber',
	    // gandi corporate => 'x-hu_owner_vatid',
	    'x-ie_registrant_cro_number',               // Excluded
	    'x-ie_registrant_supporting_number',        // Excluded
	    'x-ie_registrant_type',                     // Excluded
	    // gandi corporate => 'x-il_registrant_company_type',
	    'x-it_nationality',                         // Excluded
	    'x-it_pin',                                 // Excluded
	    'x-it_registrant_entity_type',              // Excluded
	    'x-jobs_website',
	    // gandi corporate => 'x-kr_reg_identity_no',
	    'x-lt_registrant_legal_id',
	    'x-ltda_authority',
	    'x-ltda_licensenumber',
	    // deprecated => 'x-lv_idnumber',
	    'x-madrid_intendeduse',
	    'x-my_admin_contact_password',              // Excluded
	    'x-my_admin_contact_username',              // Excluded
	    'x-my_business_number',                     // Excluded
	    'x-my_organization_type',                   // Excluded
	    'x-no_registrant_identity',                 // Excluded
	    'x-nu_registrant_idnumber',
	    'x-nu_registrant_vatid',
	    'x-nyc_extcontact',                         // Excluded
	    'x-pro_authority',                          // Excluded
	    'x-pro_authoritywebsite',                   // Excluded
	    'x-pro_licensenumber',                      // Excluded
	    'x-pro_profession',                         // Excluded
	    // What the hell is that??? => 'x-promopixel_company_serial',
	    'x-pt_arbitration',                 //todo
	    'x-pt_registrant_vatid',            //todo
	    'x-pt_roid',                        //todo
	    'x-pt_tech_vatid',                  //todo
	    'x-quebec_intendeduse',
	    'x-radio_intendeduse',
	    'x-ro_registrant_idnumber',
	    // gandi corporate => 'x-rs_company_number',
	    // gandi corporate => 'x-rs_reg_identity_no',
	    // gandi corporate => 'x-rs_reg_tax_no',
	    // deprecated => 'x-ru_registrant_birth_date',
	    // deprecated => 'x-ru_registrant_kpp',
	    // deprecated => 'x-ru_registrant_passport_data',
	    // deprecated => 'x-ru_registrant_tin',
	    'x-scot_intendeduse',
	    'x-se_ident_number',
	    'x-se_registrant_vatid',
	    'x-sg_idnumber',
	    'x-sport_intendeduse',
	    'x-srl_authority',
	    'x-srl_licensenumber',
	    'x-swiss_enterpriseid',
	    'x-swiss_intendeduse',
	    // deprecated => 'x-travel_uin',
	    'x-tw_company_number',
	    'x-uk_co_no',
	    'x-uk_contact_type',
	    'x-us_app_purpose',
	    'x-us_nexus_category',
	    'x-xxx_membership_contact',                 // Excluded
	    'x-xxx_sponsored_community',                // Excluded
    ];

	/*
	 * Additional contact fields. Checked 2022-10-21
	 */
	private static $contactFields = [
		'birth_city',
		'birth_country',
		'birth_date',
		'birth_department',
		'brand_number',
		'duns',
		'waldec',
		'x-aero_ens_authid',
		'x-aero_ens_authkey',
		'x-au_registrant_id_number',
		'x-au_registrant_id_type',
		// gandi corporate => 'x-az_admin_pp_id',
		// gandi corporate => 'x-az_billing_pp_id',
		// gandi corporate => 'x-az_registrant_company_number',
		// gandi corporate => 'x-az_registrant_pp_id',
		// gandi corporate => 'x-az_tech_pp_id',
		'x-barcelona_intendeduse',
		'x-barcelona_trademarkid',
		'x-ca_business_entity_type',
		'x-ca_legaltype',
		'x-ca_official_rep',
		'x-ca_official_rep_capacity',
		// deprecated => 'x-ca_owner_name',
		'x-cat_intendeduse',
		// deprecated => 'x-coop_sponsor',
		// gandi corporate => 'x-cy_reg_identity_no',
		'x-document_country',
		'x-document_type',
		'x-document_value',
		'x-es_admin_identification',
		'x-es_admin_tipo_identification',
		'x-es_bill_identification',
		'x-es_bill_tipo_identification',
		// deprecated => 'x-es_identification',
		// deprecated => 'x-es_legalform',
		'x-es_owner_identification',
		'x-es_owner_legalform',
		'x-es_owner_tipo_identification',
		'x-es_tech_identification',
		'x-es_tech_tipo_identification',
		// deprecated => 'x-es_tipo_identification',
		'x-eu_country_of_citizenship',
		'x-eus_intendeduse',
		'x-fi_business_number',
		'x-fi_ident_number',
		'x-fi_isfinish',
		'x-fi_organization_type',
		'x-gal_intendeduse',
		'x-hk_registrant_birth_date',
		'x-hk_registrant_document_number',
		'x-hk_registrant_document_origin_country',
		'x-hk_registrant_document_type',
		'x-hk_registrant_other_document_type',
		'x-hr_ident_number',
		'x-hr_non_eu_pm_business_number',
		'x-hu_idnumber',
		'x-hu_owner_vatid',
		'x-ie_registrant_cro_number',
		'x-ie_registrant_supporting_number',
		'x-ie_registrant_type',
		'x-il_registrant_company_type',
		'x-it_nationality',
		'x-it_pin',
		'x-it_registrant_entity_type',
		// deprecated => 'x-jobs_website',
		'x-kr_reg_identity_no',
		'x-lt_registrant_legal_id',
		'x-ltda_authority',
		'x-ltda_licensenumber',
		'x-lv_idnumber',
		'x-madrid_intendeduse',
		'x-my_admin_contact_password',
		'x-my_admin_contact_username',
		'x-my_business_number',
		'x-my_organization_type',
		'x-no_registrant_identity',
		'x-nu_registrant_idnumber',
		'x-nu_registrant_vatid',
		'x-nyc_extcontact',
		'x-pro_authority',
		'x-pro_authoritywebsite',
		'x-pro_licensenumber',
		'x-pro_profession',
		'x-promopixel_company_serial',
		'x-pt_arbitration',
		'x-pt_registrant_vatid',
		'x-pt_roid',
		'x-pt_tech_vatid',
		'x-quebec_intendeduse',
		'x-radio_intendeduse',
		'x-ro_registrant_idnumber',
		'x-rs_company_number',
		'x-rs_reg_identity_no',
		'x-rs_reg_tax_no',
		'x-ru_registrant_birth_date',
		'x-ru_registrant_kpp',
		'x-ru_registrant_passport_data',
		'x-ru_registrant_tin',
		'x-scot_intendeduse',
		'x-se_ident_number',
		'x-se_registrant_vatid',
		'x-sg_idnumber',
		'x-sport_intendeduse',
		'x-srl_authority',
		'x-srl_licensenumber',
		'x-swiss_enterpriseid',
		'x-swiss_intendeduse',
		// deprecated => 'x-travel_uin',
		'x-tw_company_number',
		'x-uk_co_no',
		'x-uk_contact_type',
		'x-us_app_purpose',
		'x-us_nexus_category',
		'x-xxx_membership_contact',
		'x-xxx_sponsored_community',
	];
}

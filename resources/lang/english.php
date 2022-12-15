<?php
/**
 * WHMCS Language File
 * French (fr)
 *
 * Please Not: These language files are overwritten during software updates
 * and therefore editing of these files directly is not advised. Instead we
 * recommend that you use overrides to customise the text displayed in a way
 * which will be safely preserved through the upgrade process.
 *
 * For instructions on overrides, please visit:
 * https://developers.whmcs.com/languages/overrides/
 */

if (!defined("WHMCS")) die("This file cannot be accessed directly");

$_LANG['locale'] = "en_GB";

$_LANG['gandiadmin']['apikey'] = "API key";
$_LANG['gandiadmin']['organization'] = "Organization";
$_LANG['gandiadmin']['recordset']['name'] = "DNS record types";
$_LANG['gandiadmin']['recordset']['standard'] = "Standard - only WHMCS supported types";
$_LANG['gandiadmin']['recordset']['extended'] = "Extended - all LiveDNS supported types";
$_LANG['gandiadmin']['secprev']['name'] = "Security, privacy & performance";
$_LANG['gandiadmin']['secprev']['check'] = "Display in domain overview page";
$_LANG['gandiadmin']['dnssec']['name'] = "DNNSEC";
$_LANG['gandiadmin']['dnssec']['check'] = "Allow customers to manage DNSSEC";
$_LANG['gandiadmin']['snapshot']['name'] = "DNS snapshots";
$_LANG['gandiadmin']['snapshot']['check'] = "Allow customers to manage snaphots (experimental)";
$_LANG['gandiadmin']['dns']['name'] = "DNS to use";
$_LANG['gandiadmin']['dns']['livedns'] = "Gandi LiveDNS";
$_LANG['gandiadmin']['dns']['whmcs'] = "Other";
$_LANG['gandiadmin']['contact']['type'] = "Entity type";
$_LANG['gandiadmin']['contact']['orgname'] = "Name of the company, association or organization";
$_LANG['gandiadmin']['contact']['given'] = "First name";
$_LANG['gandiadmin']['contact']['family'] = "Last name";
$_LANG['gandiadmin']['contact']['email'] = "Email";
$_LANG['gandiadmin']['contact']['Phone'] = "Phone number";
$_LANG['gandiadmin']['contact']['streetaddr'] = "Street address";
$_LANG['gandiadmin']['contact']['city'] = "City";
$_LANG['gandiadmin']['contact']['zip'] = "Zip code";
$_LANG['gandiadmin']['contact']['country'] = "Country";
$_LANG['gandiadmin']['contact']['owner'] = "Owner";
$_LANG['gandiadmin']['contact']['billing'] = "Billing";
$_LANG['gandiadmin']['contact']['technical'] = "Technical";
$_LANG['gandiadmin']['contact']['admin'] = "Admin";
$_LANG['gandiadmin']['entity']['individual'] = "Individual";
$_LANG['gandiadmin']['entity']['company'] = "Company";
$_LANG['gandiadmin']['entity']['association'] = "Association / NGO";
$_LANG['gandiadmin']['entity']['publicbody'] = "Public body";
$_LANG['gandi']['spinnertmessage'] = "This operation may take some time.<br/>Please be patient…";
$_LANG['gandi']['legaltype']['unknown'] = "Not specified";
$_LANG['gandi']['legaltype']['individual'] = "Individual";
$_LANG['gandi']['legaltype']['company'] = "Company";
$_LANG['gandi']['legaltype']['association'] = "Association / NGO";
$_LANG['gandi']['legaltype']['publicbody'] = "Public body";
$_LANG['gandi']['dns']['name'] = "Domain names servers";
$_LANG['gandi']['dns']['ttl'] = "TTL";
$_LANG['gandi']['dns']['livedns'] = "Hosterra Anycast / LiveDNS";
$_LANG['gandi']['dns']['external'] = "External";
$_LANG['gandi']['snapshot']['name'] = "DNS snapshots";
$_LANG['gandi']['snapshot']['desc'] = "DNS snapshots are backup copies of the domain's DNS records. You can restore a snapshot at any time to fully replace the current DNS records with those contained in the snapshot.";
$_LANG['gandi']['snapshot']['snap'] = "Take a snapshot";
$_LANG['gandi']['snapshot']['auto'] = "Automatic";
$_LANG['gandi']['snapshot']['manual'] = "User";
$_LANG['gandi']['snapshot']['snapname'] = "Snapshot";
$_LANG['gandi']['snapshot']['snaptrigger'] = "Trigger";
$_LANG['gandi']['snapshot']['snapdate'] = "Date";
$_LANG['gandi']['snapshot']['alltriggers'] = "All triggers";
$_LANG['gandi']['snapshot']['selected'] = "selected snapshot(s)";
$_LANG['gandi']['snapshot']['create'] = "Create";
$_LANG['gandi']['snapshot']['restore'] = "Restaore";
$_LANG['gandi']['snapshot']['new'] = "New snapshot";
$_LANG['gandi']['snapshot']['newdesc'] = "To create a new snapshot of the current DNS records, give it a name:";
$_LANG['gandi']['snapshot']['createsuccess'] = "Snapshot successfully created.";
$_LANG['gandi']['snapshot']['createerror'] = "The snapshot could not be created.";
$_LANG['gandi']['snapshot']['deletesuccess'] = "Snapshot successfully deleted.";
$_LANG['gandi']['snapshot']['deleteerror'] = "The snapshot could not be deleted.";
$_LANG['gandi']['snapshot']['restoresuccess'] = "Snapshot successfully restored.";
$_LANG['gandi']['snapshot']['restoreerror'] = "The snapshot could not be restored.";
$_LANG['gandi']['snapshot']['bulkdeletesuccess'] = "%s snapshot successfully restored.";
$_LANG['gandi']['snapshot']['bulkdeleteerror'] = "Some snapshots could not be deleted.";
$_LANG['gandi']['snapshot']['delete'] = "Delete";
$_LANG['gandi']['snapshot']['error'] = "Error";
$_LANG['gandi']['snapshot']['areyousure'] = "Are you sure?";
$_LANG['gandi']['snapshot']['confirmdelete'] = "Do you want to permanently delete this snapshot? This action cannot be undone.";
$_LANG['gandi']['snapshot']['confirmbulkdelete'] = "Do you want to permanently delete all selected snapshots? This action cannot be undone.";
$_LANG['gandi']['snapshot']['confirmrestore'] = "Do you really want to restore this snapshot? Its contents will replace the current DNS records.";
$_LANG['gandi']['infopanel']['secprev'] = "Security & Privacy";
$_LANG['gandi']['infopanel']['perf'] = "Performance";
$_LANG['gandi']['infopanel']['noinfo'] = "Information not available";
$_LANG['gandi']['infopanel']['idprotect'] = "WHOIS protection and obfuscation";
$_LANG['gandi']['infopanel']['idprotectyes'] = "Activated";
$_LANG['gandi']['infopanel']['idprotectno'] = "Not supported by this TLD";
$_LANG['gandi']['infopanel']['lock'] = "Transfer lock";
$_LANG['gandi']['infopanel']['lockyes'] = "Activated";
$_LANG['gandi']['infopanel']['lockno'] = "Not activated";
$_LANG['gandi']['infopanel']['locknono'] = "Not supported by this TLD";
$_LANG['gandi']['infopanel']['dnssec'] = "Names servers protection (DNSSEC)";
$_LANG['gandi']['infopanel']['dnssecyes'] = "Activated";
$_LANG['gandi']['infopanel']['dnssecno'] = "Supported but not activated";
$_LANG['gandi']['infopanel']['dnssecnono'] = "Not supported";
$_LANG['gandi']['dnssec']['title'] = "DNSSEC";
$_LANG['gandi']['dnssec']['yeskey'] = "DNSSEC is activated on this domain.";
$_LANG['gandi']['dnssec']['nokey'] = "DNSSEC is not activated on this domain.";
$_LANG['gandi']['dnssec']['nonokey'] = "DNSSEC is not activable on this domain.";
$_LANG['gandi']['dnssec']['enable'] = "Activate DNSSEC";
$_LANG['gandi']['dnssec']['disable'] = "Deactivate DNSSEC";
$_LANG['gandi']['dnssec']['activation'] = "You can activate it by clicking on the following button, a key will be automatically generated for you.<br/>Please note that this operation can take up to 15 seconds…";
$_LANG['gandi']['dnssec']['deactivation'] = "You can deactivate DNSSEC by clicking on the following button, the key will be deleted.<br/>Please note that this operation can take up to 15 seconds…";
$_LANG['gandi']['dnssec']['key'] = "Key";
$_LANG['gandi']['dnssec']['type'] = "Type";
$_LANG['gandi']['dnssec']['algorithm'] = "Algorithm";
$_LANG['gandi']['dnssec']['public'] = "Public key";
$_LANG['gandi']['dnssec']['digest'] = "Digest";
$_LANG['gandi']['dnssec']['tag'] = "Tag";
$_LANG['gandi']['CA']['CCT'] = "Canadian citizen";
$_LANG['gandi']['CA']['RES'] = "Permanent resident of Canada";
$_LANG['gandi']['CA']['ABO'] = "Aboriginal peoples";
$_LANG['gandi']['CA']['LGR'] = "Legal representative of a Canadian citizen or permanent resident of Canada";
$_LANG['gandi']['CA']['corporation'] = "Corporation";
$_LANG['gandi']['CA']['trust'] = "Trust established in Canada";
$_LANG['gandi']['CA']['government'] = "Governement";
$_LANG['gandi']['CA']['education'] = "Canadian educational institution";
$_LANG['gandi']['CA']['unincorporated-association'] = "Canadian unincorporated association";
$_LANG['gandi']['CA']['hospital'] = "Canadian hospital";
$_LANG['gandi']['CA']['trade-union'] = "Canadian trade union";
$_LANG['gandi']['CA']['political-party'] = "Canadian political party";
$_LANG['gandi']['CA']['library-archive-museum'] = "Canadian library archive or museum";
$_LANG['gandi']['CA']['other'] = "Other";
$_LANG['gandi']['UK']['LTD'] = 'English limited company';
$_LANG['gandi']['UK']['PLC'] = 'English public limited company';
$_LANG['gandi']['UK']['LLP'] = 'English limited liability partnership';
$_LANG['gandi']['UK']['IP'] = 'English industrial or insurance company';
$_LANG['gandi']['UK']['SCH'] = 'English school';
$_LANG['gandi']['UK']['FCORP'] = 'Non-English company';
$_LANG['gandi']['UK']['RCHAR'] = 'English registered non-profit association';
$_LANG['gandi']['UK']['PTNR'] = 'English partnership';
$_LANG['gandi']['UK']['STRA'] = 'English self-employed';
$_LANG['gandi']['UK']['CRC'] = 'English company incorporated by Royal Decree';
$_LANG['gandi']['UK']['FOTHER'] = 'Other non-English entity (e.g. school or club or association…)';
$_LANG['gandi']['UK']['OTHER'] = 'Other English entity (e.g. club or association or university…)';
$_LANG['gandi']['US']['C11'] = 'C11 - United States Citizen';
$_LANG['gandi']['US']['C12'] = 'C12 - Permanent Resident of the United States';
$_LANG['gandi']['US']['C21'] = 'C21 - A U.S.-based organization formed within the United States of America';
$_LANG['gandi']['US']['C31'] = 'C31 - A foreign entity or organization that has a bona fide presence in the United States of America';
$_LANG['gandi']['US']['C32'] = "C32 - An entity or Organisation that has an office or other facility in the United States";
$_LANG['gandi']['US']['P1'] = 'Commercial use';
$_LANG['gandi']['US']['P2'] = 'Non-commercial activity or club or association or religious organization';
$_LANG['gandi']['US']['P3'] = 'Personal use';
$_LANG['gandi']['US']['P4'] = 'For educational purposes';
$_LANG['gandi']['US']['P5'] = 'For governmental purposes';
$_LANG['x-gandi-legal-type'] = "Legal status of the owner";
$_LANG['x-gandi-privacy'] = "Privacy";
$_LANG['x-gandi-privacy-description'] = "I understand that Whois privacy and email obfuscation will be applied to this domain only if the option is available from the provider.";
$_LANG['x-gandi-acks'] = "Delegation";
$_LANG['x-gandi-acks-description'] = 'I understand that <a target="_blank" href="https://hosterra.eu">Hosterra SAS</a> acts as a <a target="_blank" href="https://gandi.net">Gandi SAS</a> domain reseller. Nevertheless, I understand that under the terms of the contract agreed between the two parties, <a target="_blank" href="https://hosterra.eu">Hosterra SAS</a> remains my sole contact regarding the management of domains registered or transferred to Hosterra.';
$_LANG['x-gandi-terms'] = "Terms & conditions";
$_LANG['x-gandi-terms-description'] = 'I confirm that I have read, understood and I agree to <ul><li>the Hosterra <a target="_blank" href="https://hosterra.eu">domains terms and conditions</a>;</li><li>the domains name registration <a target="_blank" href="https://www.gandi.net/en/contracts/tlds">special conditions</a> that may apply to this domain.</li></ul>';
$_LANG['x-aero_ens_authid'] = "AERO ID (see https://information.aero)";
$_LANG['x-aero_ens_authkey'] = "AERO Key (see https://information.aero)";
$_LANG['x-au_registrant_id_number'] = "Registrant's identification number (ID card or passport)";
$_LANG['x-au_registrant_id_type'] = "Registrant's type";
$_LANG['x-ca_business_entity_type'] = "Entity type";
$_LANG['x-ca_legaltype'] = "Registrant";
$_LANG['x-ca_official_rep'] = "Name of the legal representative";
$_LANG['x-ca_official_rep_capacity'] = "Capacity of the legal representative";
$_LANG['x-cat_intendeduse'] = "Intended use";
$_LANG['x-cy_reg_identity_no'] = "Registrant's identification number (ID card or passport)";
$_LANG['x-eu_country_of_citizenship'] = "EU country of citizenship";
$_LANG['x-eus_intendeduse'] = "Intended use";
$_LANG['x-gal_intendeduse'] = "Intended use";
$_LANG['x-jobs_website'] = "Website associated with these recruitment activities";
$_LANG['x-lt_registrant_legal_id'] = "Registrant company or association legal ID";
$_LANG['x-ltda_authority'] = "Name of the authority with which the company is registered";
$_LANG['x-ltda_licensenumber'] = "Registration number";
$_LANG['x-madrid_intendeduse'] = "Intended use";
$_LANG['x-nu_registrant_idnumber'] = "Registrant's identification number (ID card or passport)";
$_LANG['x-nu_registrant_vatid'] = "VAT number";
$_LANG['x-quebec_intendeduse'] = "Intended use";
$_LANG['x-radio_intendeduse'] = "Intended use";
$_LANG['x-ro_registrant_idnumber'] = "Registrant's identification number (ID card or passport)";
$_LANG['x-scot_intendeduse'] = "Intended use";
$_LANG['x-se_ident_number'] = "Registrant's identification number (ID card or passport)";
$_LANG['x-se_registrant_vatid'] = "VAT number";
$_LANG['x-sg_idnumber'] = "Registrant's identification number (CorpPass, SignPass or Company ID)";
$_LANG['x-sport_intendeduse'] = "Intended use";
$_LANG['x-srl_authority'] = "Name of the authority with which the company is registered";
$_LANG['x-srl_licensenumber'] = "Registration number";
$_LANG['x-swiss_enterpriseid'] = "Company's identification number (IDE/UID/IDI)";
$_LANG['x-swiss_intendeduse'] = "Intended use";
$_LANG['x-tw_company_number'] = "Company registration number";
$_LANG['x-uk_contact_type'] = "Entity type";
$_LANG['x-uk_co_no'] = "Company or DfES UK school registration number";
$_LANG['x-us_nexus_category'] = "Nexus category";
$_LANG['x-us_app_purpose'] = "Application purpose";


////////// End of french language file. Do not place any translation strings below this line!

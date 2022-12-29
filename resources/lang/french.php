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

$_LANG['locale'] = "fr_FR";

$_LANG['gandiadmin']['apikey'] = "Clé d’API";
$_LANG['gandiadmin']['organization'] = "Organisation";
$_LANG['gandiadmin']['recordset']['name'] = "Types d’enregistrements DNS";
$_LANG['gandiadmin']['recordset']['standard'] = "Standard - uniquement les types pris en charge par WHMCS";
$_LANG['gandiadmin']['recordset']['extended'] = "Étendu - Tous les types pris en charge par LiveDNS";
$_LANG['gandiadmin']['secprev']['name'] = "Securité, confidentialité & performance";
$_LANG['gandiadmin']['secprev']['check'] = "Afficher dans la page d’aperçu du domaine";
$_LANG['gandiadmin']['dnssec']['name'] = "DNNSEC";
$_LANG['gandiadmin']['dnssec']['check'] = "Autoriser les clients à le gérer";
$_LANG['gandiadmin']['snapshot']['name'] = "Instantanés DNS";
$_LANG['gandiadmin']['snapshot']['check'] = "Autoriser les client à les gérer (expérimental)";
$_LANG['gandiadmin']['dns']['name'] = "DNS à utiliser";
$_LANG['gandiadmin']['dns']['livedns'] = "Gandi LiveDNS";
$_LANG['gandiadmin']['dns']['whmcs'] = "Autre";
$_LANG['gandiadmin']['contact']['type'] = "Type d’entité";
$_LANG['gandiadmin']['contact']['orgname'] = "Nom de l’entreprise, l’association ou l’organisme";
$_LANG['gandiadmin']['contact']['given'] = "Prénom";
$_LANG['gandiadmin']['contact']['family'] = "Nom de famille";
$_LANG['gandiadmin']['contact']['email'] = "E-mail";
$_LANG['gandiadmin']['contact']['Phone'] = "Téléphone";
$_LANG['gandiadmin']['contact']['streetaddr'] = "Addresse";
$_LANG['gandiadmin']['contact']['city'] = "Ville";
$_LANG['gandiadmin']['contact']['zip'] = "Code postal";
$_LANG['gandiadmin']['contact']['country'] = "Pays";
$_LANG['gandiadmin']['contact']['owner'] = "Propriétaire";
$_LANG['gandiadmin']['contact']['billing'] = "Facturation";
$_LANG['gandiadmin']['contact']['technical'] = "Technique";
$_LANG['gandiadmin']['contact']['admin'] = "Administratif";
$_LANG['gandiadmin']['entity']['individual'] = "Particulier";
$_LANG['gandiadmin']['entity']['company'] = "Entreprise";
$_LANG['gandiadmin']['entity']['association'] = "Association / ONG";
$_LANG['gandiadmin']['entity']['publicbody'] = "Organisme public";
$_LANG['gandi']['spinnertmessage'] = "Cette opération peut prendre un certain temps.<br/>Merci de patienter…";
$_LANG['gandi']['legaltype']['unknown'] = "Non spécifié";
$_LANG['gandi']['legaltype']['individual'] = "Particulier";
$_LANG['gandi']['legaltype']['company'] = "Entreprise";
$_LANG['gandi']['legaltype']['association'] = "Association / ONG";
$_LANG['gandi']['legaltype']['publicbody'] = "Organisme public";
$_LANG['gandi']['dns']['name'] = "Serveurs de noms de domaine";
$_LANG['gandi']['dns']['ttl'] = "TTL";
$_LANG['gandi']['dns']['livedns'] = "Hosterra Anycast / LiveDNS";
$_LANG['gandi']['dns']['external'] = "Externe";
$_LANG['gandi']['snapshot']['name'] = "Instantanés DNS";
$_LANG['gandi']['snapshot']['desc'] = "Les instantanés DNS sont des copies de sauvegarde des enregistrements DNS du domaine. Vous pouvez restaurer à tout moment un instantané pour remplacer intégralement les enregistrements DNS actuels par ceux contenu dans l’instantané.";
$_LANG['gandi']['snapshot']['snap'] = "Prendre un instantané";
$_LANG['gandi']['snapshot']['auto'] = "Automatique";
$_LANG['gandi']['snapshot']['manual'] = "Utilisateur";
$_LANG['gandi']['snapshot']['snapname'] = "Instantané";
$_LANG['gandi']['snapshot']['snaptrigger'] = "Déclencheur";
$_LANG['gandi']['snapshot']['snapdate'] = "Date";
$_LANG['gandi']['snapshot']['alltriggers'] = "Tous les déclencheurs";
$_LANG['gandi']['snapshot']['selected'] = "instantané(s) sélectionné(s)";
$_LANG['gandi']['snapshot']['create'] = "Créer";
$_LANG['gandi']['snapshot']['restore'] = "Restaurer";
$_LANG['gandi']['snapshot']['new'] = "Nouvel instantané";
$_LANG['gandi']['snapshot']['newdesc'] = "Pour créer un nouvel instantané des enregistrements DNS actuels, donnez-lui un nom :";
$_LANG['gandi']['snapshot']['createsuccess'] = "Instantané correctement créé.";
$_LANG['gandi']['snapshot']['createerror'] = "L’instantané n’a pa pu être créé.";
$_LANG['gandi']['snapshot']['deletesuccess'] = "Instantané correctement supprimé.";
$_LANG['gandi']['snapshot']['deleteerror'] = "L’instantané n’a pa pu être supprimé.";
$_LANG['gandi']['snapshot']['restoresuccess'] = "Instantané correctement restauré.";
$_LANG['gandi']['snapshot']['restoreerror'] = "L’instantané n’a pa pu être restauré.";
$_LANG['gandi']['snapshot']['bulkdeletesuccess'] = "%s instantané(s) correctement supprimé(s).";
$_LANG['gandi']['snapshot']['bulkdeleteerror'] = "Certains instantanés n’ont pa pu être supprimés.";
$_LANG['gandi']['snapshot']['delete'] = "Supprimer";
$_LANG['gandi']['snapshot']['error'] = "Erreur";
$_LANG['gandi']['snapshot']['areyousure'] = "Êtes-vous sûr·e ?";
$_LANG['gandi']['snapshot']['confirmdelete'] = "Voulez-vous définitivement supprimer cet instantané ? Cette action ne peut pas être annulée.";
$_LANG['gandi']['snapshot']['confirmbulkdelete'] = "Voulez-vous définitivement supprimer tous les instantanés sélectionnés ? Cette action ne peut pas être annulée.";
$_LANG['gandi']['snapshot']['confirmrestore'] = "Voulez-vous vraiment restaurer cet instantané ? Son contenu va remplacer les enregistrements DNS actuels.";
$_LANG['gandi']['infopanel']['lock'] = "Protection contre le transfert";
$_LANG['gandi']['infopanel']['lockyes'] = "Activée";
$_LANG['gandi']['infopanel']['lockno'] = "Non activée";
$_LANG['gandi']['infopanel']['locknono'] = "Non prise en charge par ce TLD";
$_LANG['gandi']['infopanel']['secprev'] = "Securité & Confidentialité";
$_LANG['gandi']['infopanel']['perf'] = "Performance";
$_LANG['gandi']['infopanel']['noinfo'] = "Information non disponible";
$_LANG['gandi']['infopanel']['idprotect'] = "Protection et obfuscation WHOIS";
$_LANG['gandi']['infopanel']['idprotectyes'] = "Activée";
$_LANG['gandi']['infopanel']['idprotectno'] = "Non prise en charge par ce TLD";
$_LANG['gandi']['infopanel']['dnssec'] = "Protection des serveurs de noms (DNSSEC)";
$_LANG['gandi']['infopanel']['dnssecyes'] = "Activé";
$_LANG['gandi']['infopanel']['dnssecno'] = "Pris en charge mais non activé";
$_LANG['gandi']['infopanel']['dnssecnono'] = "Non pris en charge";
$_LANG['gandi']['dnssec']['title'] = "DNSSEC";
$_LANG['gandi']['dnssec']['yeskey'] = "DNSSEC est activé sur ce domaine.";
$_LANG['gandi']['dnssec']['nokey'] = "DNSSEC n’est pas activé sur ce domaine.";
$_LANG['gandi']['dnssec']['nonokey'] = "DNSSEC n’est pas activable sur ce domaine.";
$_LANG['gandi']['dnssec']['enable'] = "Activer DNSSEC";
$_LANG['gandi']['dnssec']['disable'] = "Désactiver DNSSEC";
$_LANG['gandi']['dnssec']['activation'] = "Vous pouvez l’activer en cliquant sur le bouton suivant, une clé sera automatiquement générée pour vous.";
$_LANG['gandi']['dnssec']['deactivation'] = "Vous pouvez désactiver DNSSEC en cliquant sur le bouton suivant, la clé sera supprimée.";
$_LANG['gandi']['dnssec']['key'] = "Clé";
$_LANG['gandi']['dnssec']['type'] = "Type";
$_LANG['gandi']['dnssec']['algorithm'] = "Algorithme";
$_LANG['gandi']['dnssec']['public'] = "Clé publique";
$_LANG['gandi']['dnssec']['digest'] = "Digest";
$_LANG['gandi']['dnssec']['tag'] = "Balise";
$_LANG['gandi']['CA']['CCT'] = "Citoyen canadien";
$_LANG['gandi']['CA']['RES'] = "Résident permament canadien";
$_LANG['gandi']['CA']['ABO'] = "Peuples autochtones du Canada";
$_LANG['gandi']['CA']['LGR'] = "Représentant légal d’un citoyen ou résident permanent canadien";
$_LANG['gandi']['CA']['corporation'] = "Entreprise";
$_LANG['gandi']['CA']['trust'] = "Fiducie établie au Canada";
$_LANG['gandi']['CA']['government'] = "Gouvernement";
$_LANG['gandi']['CA']['education'] = "Établissement d’enseignement canadien";
$_LANG['gandi']['CA']['unincorporated-association'] = "Association canadienne non constituée en société";
$_LANG['gandi']['CA']['hospital'] = "Hôpital canadien";
$_LANG['gandi']['CA']['trade-union'] = "Syndicat canadien";
$_LANG['gandi']['CA']['political-party'] = "Parti politique canadien";
$_LANG['gandi']['CA']['library-archive-museum'] = "Bibliothèque ou musée canadiens";
$_LANG['gandi']['CA']['other'] = 'Autre';
$_LANG['gandi']['UK']['LTD'] = 'Société anglaise à responsabilité limitée';
$_LANG['gandi']['UK']['PLC'] = 'Société anglaise de droit public à responsabilité limitée';
$_LANG['gandi']['UK']['LLP'] = 'Partnership anglais à responsabilité limitée';
$_LANG['gandi']['UK']['IP'] = 'Société industrielle ou d’assurance anglaise';
$_LANG['gandi']['UK']['SCH'] = 'École anglaise';
$_LANG['gandi']['UK']['FCORP'] = 'Société non-anglaise';
$_LANG['gandi']['UK']['RCHAR'] = 'Association anglaise enregistrée à but non lucratif';
$_LANG['gandi']['UK']['PTNR'] = 'Partnership anglais';
$_LANG['gandi']['UK']['STRA'] = 'Auto-entrepreneur anglais';
$_LANG['gandi']['UK']['CRC'] = 'Société anglaise constituée par décret Royal';
$_LANG['gandi']['UK']['FOTHER'] = 'Autre entité non-anglaise (ex : école ou club ou association…)';
$_LANG['gandi']['UK']['OTHER'] = 'Autre entité anglaise (ex : club ou association ou université…)';
$_LANG['gandi']['US']['C11'] = 'C11 - Citoyen des États-Unis';
$_LANG['gandi']['US']['C12'] = 'C12 - Résident permanent des États-Unis';
$_LANG['gandi']['US']['C21'] = 'C21 - Une organisation basée aux États-Unis et formée aux États-Unis d’Amérique';
$_LANG['gandi']['US']['C31'] = 'C31 - Une entité ou une organisation étrangère qui a une présence réelle aux États-Unis d’Amérique';
$_LANG['gandi']['US']['C32'] = 'C32 - Une entité ou une organisation qui a un bureau ou une autre installation aux États-Unis';
$_LANG['gandi']['US']['P1'] = 'Utilisation à des fins commerciales';
$_LANG['gandi']['US']['P2'] = 'Activité non commerciale ou club ou association ou organisation religieuse';
$_LANG['gandi']['US']['P3'] = 'Utilisation personnelle';
$_LANG['gandi']['US']['P4'] = 'À but éducationnel';
$_LANG['gandi']['US']['P5'] = 'À but gouvernemental';
$_LANG['x-gandi-legal-type'] = "Statut juridique du propriétaire ";
$_LANG['x-gandi-privacy'] = "Confidentialité";
$_LANG['x-gandi-privacy-description'] = "Je comprends que la confidentialité du Whois et l’obscurcissement d’email seront appliqués à ce domaine uniquement si l’option est disponible auprès du fournisseur.";
$_LANG['x-gandi-acks'] = "Délégation";
$_LANG['x-gandi-acks-description'] = 'Je comprends que <a target="_blank" href="https://hosterra.eu">Hosterra SAS</a> agit en tant que revendeur de domaines de <a target="_blank" href="https://gandi.net">Gandi SAS</a>. Néanmoins, je comprends qu’aux termes du contrat convenu entre les deux parties, <a target="_blank" href="https://hosterra.eu">Hosterra SAS</a> reste mon seul interlocuteur concernant la gestion des domaines enregistrés ou transférés chez Hosterra. ';
$_LANG['x-gandi-terms'] = "Conditions générales";
$_LANG['x-gandi-terms-description'] = 'Je confirme que j’ai lu, compris et que j’accepte <ul><li>les <a target="_blank" href="https://hosterra.eu">conditions générales relatives aux domaines</a> Hosterra;</li><li>les <a target="_blank" href="https://www.gandi.net/fr/contracts/tlds">conditions particulières</a> qui peuvent s’appliquer à ce domaine.</li></ul>';
$_LANG['x-aero_ens_authid'] = "AERO ID (see https://information.aero) ";
$_LANG['x-aero_ens_authkey'] = "AERO Key (see https://information.aero) ";
$_LANG['x-au_registrant_id_number'] = "Numéro d’identification du déclarant (carte d’identité ou passeport) ";
$_LANG['x-au_registrant_id_type'] = "Type de déclarant ";
$_LANG['x-ca_business_entity_type'] = "Type d’entité ";
$_LANG['x-ca_legaltype'] = "Déclarant ";
$_LANG['x-ca_official_rep'] = "Nom du représentant légal ";
$_LANG['x-ca_official_rep_capacity'] = "Qualité du représentant légal ";
$_LANG['x-cat_intendeduse'] = "Utilisation prévue ";
$_LANG['x-cy_reg_identity_no'] = "Numéro d’identification du déclarant (carte d’identité ou passeport) ";
$_LANG['x-eu_country_of_citizenship'] = "Pays de citoyenneté dans l’UE ";
$_LANG['x-eus_intendeduse'] = "Utilisation prévue ";
$_LANG['x-gal_intendeduse'] = "Utilisation prévue ";
$_LANG['x-jobs_website'] = "Site web associé à ces activités de recrutement ";
$_LANG['x-lt_registrant_legal_id'] = "Identification légale de la société ou de l’association du déclarant ";
$_LANG['x-ltda_authority'] = "Nom de l’autorité auprès de laquelle la société est enregistrée ";
$_LANG['x-ltda_licensenumber'] = "Numéro d’enregistrement ";
$_LANG['x-madrid_intendeduse'] = "Utilisation prévue ";
$_LANG['x-nu_registrant_idnumber'] = "Numéro d’identification du déclarant (carte d’identité ou passeport) ";
$_LANG['x-nu_registrant_vatid'] = "Numéro de TVA ";
$_LANG['x-quebec_intendeduse'] = "Utilisation prévue ";
$_LANG['x-radio_intendeduse'] = "Utilisation prévue ";
$_LANG['x-ro_registrant_idnumber'] = "Numéro d’identification du déclarant (carte d’identité ou passeport) ";
$_LANG['x-scot_intendeduse'] = "Utilisation prévue ";
$_LANG['x-se_ident_number'] = "Numéro d’identification du déclarant (carte d’identité ou passeport) ";
$_LANG['x-se_registrant_vatid'] = "Numéro de TVA ";
$_LANG['x-sg_idnumber'] = "Numéro d’identification du déclarant (CorpPass, SignPass ou identifiant de l’entreprise) ";
$_LANG['x-sport_intendeduse'] = "Utilisation prévue ";
$_LANG['x-srl_authority'] = "Nom de l’autorité auprès de laquelle la société est enregistrée ";
$_LANG['x-srl_licensenumber'] = "Numéro d’enregistrement ";
$_LANG['x-swiss_enterpriseid'] = "Numéro d’identification de la société (IDE/UID/IDI) ";
$_LANG['x-swiss_intendeduse'] = "Utilisation prévue ";
$_LANG['x-tw_company_number'] = "Numéro d’enregistrement de la société ";
$_LANG['x-uk_contact_type'] = "Type d’entité ";
$_LANG['x-uk_co_no'] = "Numéro d’enregistrement de l’entreprise ou de l’école au Royaume-Uni (DfES) ";
$_LANG['x-us_nexus_category'] = "Catégorie Nexus ";
$_LANG['x-us_app_purpose'] = "Utilisation prévue ";


////////// End of french language file. Do not place any translation strings below this line!
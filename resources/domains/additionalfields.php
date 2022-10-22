<?php
/*
 **********************************************************************
 *         Additional Domain Fields for Gandi                         *
 **********************************************************************
 */

// .FR
unset( $additionaldomainfields[".fr"] );
$additionaldomainfields[".fr"][] = array("Name" => "Legal Type", "LangVar" => "fr_legaltype", "Type" => "dropdown", "Options" => "Individual,Company", "Default" => "Individual",);
$additionaldomainfields[".fr"][] = array("Name" => "Info", "LangVar" => "fr_info", "Type" => "display", "Default" =>
    "{$_LANG['enomfrregistration']['Heading']}
        <ul>
            <li><strong>{$_LANG['enomfrregistration']['French Individuals']['Name']}</strong>: {$_LANG['enomfrregistration']['French Individuals']['Requirements']}</li>
            <li><strong>{$_LANG['enomfrregistration']['EU Non-French Individuals']['Name']}</strong>: {$_LANG['enomfrregistration']['EU Non-French Individuals']['Requirements']}</li>
            <li><strong>{$_LANG['enomfrregistration']['French Companies']['Name']}</strong>: {$_LANG['enomfrregistration']['French Companies']['Requirements']}</li>
            <li><strong>{$_LANG['enomfrregistration']['EU Non-French Companies']['Name']}</strong>: {$_LANG['enomfrregistration']['EU Non-French Companies']['Requirements']}</li>
        </ul>
        <em>{$_LANG['enomfrregistration']['Non-EU Warning']}</em>",);
$additionaldomainfields[".fr"][] = array("Name" => "Birthdate", 'LangVar' => 'fr_indbirthdate', "Type" => "text","Size" => "16","Default" => "1900-01-01","Required" => false);
$additionaldomainfields[".fr"][] = array("Name" => "Birthplace City", 'LangVar' => 'fr_indbirthcity', "Type" => "text","Size" => "25","Default" => "","Required" => false);
$additionaldomainfields[".fr"][] = array("Name" => "Birthplace Country", 'LangVar' => 'fr_indbirthcountry', "Type" => "text", "Size" => "2", "Default" => "", "Required" => false, "Description" => "Please enter your country code (eg. FR, IT, etc...)");
$additionaldomainfields[".fr"][] = array("Name" => "Birthplace Postcode", 'LangVar' => 'fr_indbirthpostcode', "Type" => "text","Size" => "6","Default" => "","Required" => false);
$additionaldomainfields[".fr"][] = array("Name" => "SIRET Number", 'LangVar' => 'fr_cosiret', "Type" => "text","Size" => "50","Default" => "","Required" => false);
$additionaldomainfields[".fr"][] = array("Name" => "DUNS Number", 'LangVar' => 'fr_coduns', "Type" => "text","Size" => "50","Default" => "","Required" => false);
$additionaldomainfields[".fr"][] = array("Name" => "VAT Number", 'LangVar' => 'fr_vat', "Type" => "text","Size" => "50","Default" => "","Required" => false);
$additionaldomainfields[".fr"][] = array("Name" => "Trademark Number", 'LangVar' => 'fr_trademarknumber', "Type" => "text","Size" => "50","Default" => "","Required" => false);

$additionaldomainfields[".re"] = $additionaldomainfields[".fr"];
$additionaldomainfields[".pm"] = $additionaldomainfields[".fr"];
$additionaldomainfields[".tf"] = $additionaldomainfields[".fr"];
$additionaldomainfields[".wf"] = $additionaldomainfields[".fr"];
$additionaldomainfields[".yt"] = $additionaldomainfields[".fr"];

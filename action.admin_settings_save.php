<?php
/**
 * Admin Settings Save Action for MAS_CGBlogToLISE
 * Based on MAS_Disqus and MAS_AIAssistant pattern
 */

if (!function_exists('cmsms')) exit;

if (!$this->VisibleToAdminUser()) {
    return $this->DisplayErrorPage($id, $params, $returnid, $this->Lang('accessdenied'));
}

// Handle showing donations tab again
if (isset($params["showdonationstab"]) && $params["showdonationstab"] == "1") {
    // Remove the preference to show the donations tab again
    $this->RemovePreference("hidedonationstab");
    $msg = $this->Lang('settingsupdated');
} else {
    // Hide the donations tab
    $this->SetPreference("hidedonationstab", $this->GetVersion());
    $msg = $this->Lang('settingsupdated');
}

// put mention into the admin log
$this->Audit(0, 
    $this->Lang('friendlyname'), 
    $this->Lang('prefsupdated'));

// redirect back to admin with message
$this->Redirect($id, 'defaultadmin', $returnid, array('msg' => $msg, 'activetab' => 'adminsettings'));

?>


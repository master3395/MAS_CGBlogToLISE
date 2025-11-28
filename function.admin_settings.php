<?php
/**
 * Admin Settings Function for MAS_CGBlogToLISE
 * Based on MAS_Disqus and MAS_AIAssistant pattern
 */

if (!function_exists('cmsms')) exit;

if (!$this->VisibleToAdminUser()) {
    return;
}

// Check if donations tab is currently hidden
$donationsHidden = ($this->GetPreference("hidedonationstab") == $this->GetVersion());

echo '<div style="border:1px solid #CCC; padding:10px; margin:10px 0;">';
echo '<h3>' . $this->Lang('tab_adminsettings') . '</h3>';

echo $this->CreateFormStart($id, "admin_settings_save", $returnid);
echo '<p>';
echo '<label for="showdonationstab">' . $this->Lang('showdonationstab') . '</label><br/>';
echo $this->CreateInputCheckbox($id, "showdonationstab", "1", !$donationsHidden);
echo '</p>';

echo '<p>';
echo $this->CreateInputSubmit($id, "submit", $this->Lang("submit"));
echo '</p>';
echo $this->CreateFormEnd();

echo '</div>';
?>


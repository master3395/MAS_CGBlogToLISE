<?php
/**
 * Donations Function for MAS_CGBlogToLISE
 * Based on MAS_Disqus and MAS_AIAssistant pattern
 */

if (!function_exists('cmsms')) exit;

if (!$this->VisibleToAdminUser()) {
    return;
}

// Get Smarty instance
$smarty = cmsms()->GetSmarty();

// Assign to Smarty
$smarty->assign('module', $this);
$smarty->assign('id', $id);
$smarty->assign('params', $params);
$smarty->assign('returnid', $returnid);
$smarty->assign("formstart", $this->CreateFormStart($id, "defaultadmin", $returnid));
$smarty->assign("formend", $this->CreateFormEnd());
$smarty->assign("hidesubmit", $this->CreateInputSubmit($id, "hidedonationssubmit", $this->Lang("hidedonationssubmit")));
$smarty->assign("donationstext", $this->Lang("donationstext"));
$smarty->assign("sponsorstext", $this->Lang("sponsors"));

// Display template
echo $this->ProcessTemplate('donations.tpl');
?>

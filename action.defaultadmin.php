<?php
/**
 * Default Admin Interface for MAS_CGBlogToLISE
 * Compatible with PHP 7.4-8.6
 * Uses CMSMS native tabs like MAS_Disqus and MAS_AIAssistant
 */

if (!isset($gCms)) {
    exit;
}

if (!$this->VisibleToAdminUser()) {
    echo '<div class="pageoverflow"><p class="pagetext">Access denied. Please check your permissions.</p></div>';
    return;
}

// Handle hiding donations tab
if (isset($params["hidedonationssubmit"])) {
    $this->SetPreference("hidedonationstab", $this->GetVersion());
}

// Handle success/error messages
if (!empty($params['msg'])) {
    echo $this->ShowMessage($this->Lang($params['msg']));
}

if (!empty($params['error'])) {
    echo $this->ShowErrors(array($this->Lang($params['error'])));
}

// Get current tab
$activetab = isset($params["activetab"]) ? $params["activetab"] : "";

// Start tabs
echo $this->StartTabHeaders();
echo $this->SetTabHeader("migrate", $this->Lang('tab_migrate'), ($activetab == "" || $activetab == "migrate"));
echo $this->SetTabHeader("adminsettings", $this->Lang('tab_adminsettings'), ($activetab == "adminsettings"));
if ($this->ShowDonationsTab()) {
    echo $this->SetTabHeader("donations", $this->Lang('tab_donations'), ($activetab == "donations"));
}
echo $this->EndTabHeaders();
echo $this->StartTabContent();

// Migration tab
echo $this->StartTab("migrate");
echo '<h2>' . $this->Lang('migration_title') . '</h2>';
echo '<p>' . $this->Lang('migration_description') . '</p>';

// Check prerequisites
echo '<h3>' . $this->Lang('migration_prerequisites') . '</h3>';
echo '<p>' . $this->Lang('migration_prerequisites_desc') . '</p>';
echo '<ul>';

$cgblog_available = $this->IsCGBlogAvailable();
$lise_available = $this->IsLISEAvailable();

echo '<li>' . $this->Lang('migration_prerequisites_cgblog') . ': ';
echo $cgblog_available ? '<strong style="color: green;">✓ Installed</strong>' : '<strong style="color: red;">✗ Not Installed</strong>';
echo '</li>';

echo '<li>' . $this->Lang('migration_prerequisites_lise') . ': ';
echo $lise_available ? '<strong style="color: green;">✓ Installed</strong>' : '<strong style="color: red;">✗ Not Installed</strong>';
echo '</li>';

$stats = array('articles' => 0, 'categories' => 0, 'fielddefs' => 0, 'fieldvals' => 0);
if ($cgblog_available) {
    try {
        $stats = $this->GetCGBlogStats();
    } catch (Exception $e) {
        // Stats failed, use empty array
    }
}
$has_data = ($stats['articles'] > 0 || $stats['categories'] > 0 || $stats['fielddefs'] > 0);

echo '<li>' . $this->Lang('migration_prerequisites_data') . ': ';
echo $has_data ? '<strong style="color: green;">✓ Data Found</strong>' : '<strong style="color: red;">✗ No Data</strong>';
echo '</li>';
echo '</ul>';

if (!$cgblog_available || !$lise_available) {
    echo '<div class="pageoverflow"><p class="pagetext" style="color: red;">Please install CGBlog and LISE modules before using this migration tool.</p></div>';
} elseif (!$has_data) {
    echo '<div class="pageoverflow"><p class="pagetext" style="color: red;">No CGBlog data found to migrate.</p></div>';
} else {
    // Statistics
    echo '<h3>' . $this->Lang('statistics_title') . '</h3>';
    echo '<ul>';
    echo '<li>' . $this->Lang('statistics_articles') . ': ' . $stats['articles'] . '</li>';
    echo '<li>' . $this->Lang('statistics_categories') . ': ' . $stats['categories'] . '</li>';
    echo '<li>' . $this->Lang('statistics_fielddefs') . ': ' . $stats['fielddefs'] . '</li>';
    echo '<li>' . $this->Lang('statistics_fieldvals') . ': ' . $stats['fieldvals'] . '</li>';
    echo '</ul>';
    
    // Load migration form
    try {
        $function_file = $this->GetModulePath() . '/function.admin_migratetab.php';
        if (file_exists($function_file)) {
            require_once($function_file);
        }
    } catch (Exception $e) {
        echo '<div class="pageoverflow"><p class="pagetext" style="color: red;">Error loading migration interface: ' . htmlspecialchars($e->getMessage()) . '</p></div>';
    } catch (Error $e) {
        echo '<div class="pageoverflow"><p class="pagetext" style="color: red;">Fatal Error: ' . htmlspecialchars($e->getMessage()) . '</p></div>';
    }
}

echo $this->EndTab();

// Admin Settings tab
echo $this->StartTab("adminsettings");
include(dirname(__FILE__) . "/function.admin_settings.php");
echo $this->EndTab();

// Donations tab
if ($this->ShowDonationsTab()) {
    echo $this->StartTab("donations");
    include(dirname(__FILE__) . "/function.donations.php");
    echo $this->EndTab();
}

echo $this->EndTabContent();
?>

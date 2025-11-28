<?php
/**
 * Uninstallation Method for MAS_CGBlogToLISE
 * Compatible with PHP 7.4-8.6
 */

if (!function_exists('cmsms')) exit;

// Remove permission
$this->RemovePermission('Use MAS_CGBlogToLISE');

// Note: We keep logs in case module is reinstalled
// If you want to completely remove everything, uncomment the following:

// $module_path = $this->GetModulePath();
// $logs_dir = cms_join_path($module_path, 'logs');
// if (is_dir($logs_dir)) {
//     $files = glob($logs_dir . '/*');
//     foreach ($files as $file) {
//         if (is_file($file)) {
//             @unlink($file);
//         }
//     }
//     @rmdir($logs_dir);
// }

// Audit log
$this->Audit(0, $this->Lang('friendlyname'), $this->Lang('uninstalled'));

?>


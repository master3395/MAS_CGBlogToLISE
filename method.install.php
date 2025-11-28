<?php
/**
 * Installation Method for MAS_CGBlogToLISE
 * Compatible with PHP 7.4-8.6
 */

if (!function_exists('cmsms')) exit;

// Register as plugin module
$this->RegisterModulePlugin(true);

// Create permission
$this->CreatePermission('Use MAS_CGBlogToLISE', 'Use MAS CGBlog to LISE Migration module');

// Grant permission to Admin group (group_id = 1) by default
$db = cmsms()->GetDb();
$group_id = 1; // Admin group
$permission_name = 'Use MAS_CGBlogToLISE';
$perm_id = $db->GetOne('SELECT permission_id FROM ' . CMS_DB_PREFIX . 'permissions WHERE permission_name = ?', array($permission_name));
if ($perm_id) {
    $exists = $db->GetOne('SELECT permission_id FROM ' . CMS_DB_PREFIX . 'group_perms WHERE permission_id = ? AND group_id = ?', array($perm_id, $group_id));
    if (!$exists) {
        $db->Execute('INSERT INTO ' . CMS_DB_PREFIX . 'group_perms (group_id, permission_id) VALUES (?, ?)', array($group_id, $perm_id));
    }
}

// Create logs directory if it doesn't exist
$module_path = $this->GetModulePath();
$logs_dir = cms_join_path($module_path, 'logs');
if (!is_dir($logs_dir)) {
    @mkdir($logs_dir, 0755, true);
    // Set correct ownership for logs directory
    @chown($logs_dir, 'newst3922');
    @chgrp($logs_dir, 'newst3922');
}

// Audit log
$this->Audit(0, $this->Lang('friendlyname'), $this->Lang('installed', $this->GetVersion()));

?>


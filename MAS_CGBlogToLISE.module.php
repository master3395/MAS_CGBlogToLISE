<?php
/**
 * MAS_CGBlogToLISE Module for CMSMS
 * 
 * Migrates data from CGBlog module to LISE instances
 * 
 * @package MAS_CGBlogToLISE
 * @author master3395
 * @copyright 2025 master3395
 * @license GPL v3
 * @version 1.0.0
 */

if (!function_exists('cmsms')) exit;

class MAS_CGBlogToLISE extends CMSModule
{
    // Preference constants
    const PREF_FRIENDLYNAME = 'friendlyname';
    const PREF_SHOW_DONATIONS = 'mas_show_donations';
    
    function GetName() { 
        return 'MAS_CGBlogToLISE'; 
    }
    
    function GetFriendlyName() { 
        $friendlyname = trim($this->GetPreference(self::PREF_FRIENDLYNAME));
        if (!$friendlyname) {
            $friendlyname = $this->Lang('friendlyname');
        }
        return $friendlyname; 
    }
    
    function GetVersion() { 
        return '1.0.0'; 
    }
    
    function MinimumCMSVersion() { 
        return '2.2.0'; 
    }
    
    function GetAuthor() { 
        return 'master3395'; 
    }
    
    function GetAuthorEmail() { 
        return 'info@newstargeted.com'; 
    }
    
    function GetChangeLog() { 
        return $this->Lang('changelog'); 
    }
    
    function GetHelp() { 
        return $this->Lang('help'); 
    }
    
    function GetAdminDescription() { 
        return $this->Lang('moddescription'); 
    }
    
    function IsPluginModule() { 
        return true; 
    }
    
    function HasAdmin() { 
        return true; 
    }
    
    function GetAdminSection() {
        return 'extensions';
    }
    
    function LazyLoadFrontend() { 
        return true; 
    }
    
    function LazyLoadAdmin() { 
        return true; 
    }
    
    function AllowSmartyCaching() { 
        return false;
    }
    
    function InitializeFrontend()
    {
        $this->RestrictUnknownParams();
    }
    
    function InitializeAdmin()
    {
        $this->CreateParameter('action', 'defaultadmin', 'Action to perform');
    }
    
    function VisibleToAdminUser()
    {
        return $this->CheckPermission('Modify Site Preferences');
    }
    
    /**
     * Check if CGBlog module is installed and available
     */
    public function IsCGBlogAvailable()
    {
        try {
            $modops = cmsms()->GetModuleOperations();
            $installed = $modops->GetInstalledModules();
            return in_array('CGBlog', $installed);
        } catch (Exception $e) {
            return false;
        } catch (Error $e) {
            return false;
        }
    }
    
    /**
     * Check if LISE module is installed and available
     */
    public function IsLISEAvailable()
    {
        try {
            $modops = cmsms()->GetModuleOperations();
            $installed = $modops->GetInstalledModules();
            return in_array('LISE', $installed);
        } catch (Exception $e) {
            return false;
        } catch (Error $e) {
            return false;
        }
    }
    
    /**
     * Get CGBlog module instance
     */
    public function GetCGBlogModule()
    {
        if (!$this->IsCGBlogAvailable()) {
            return null;
        }
        return cmsms()->GetModuleInstance('CGBlog');
    }
    
    /**
     * Get LISE module instance
     */
    public function GetLISEModule()
    {
        if (!$this->IsLISEAvailable()) {
            return null;
        }
        return cmsms()->GetModuleInstance('LISE');
    }
    
    /**
     * Get list of available LISE instances
     */
    public function GetLISEInstances()
    {
        try {
            if (!$this->IsLISEAvailable()) {
                return array();
            }
            
            $lise = $this->GetLISEModule();
            if (!$lise || !is_object($lise)) {
                return array();
            }
            
            if (!method_exists($lise, 'ListModules')) {
                return array();
            }
            
            $result = $lise->ListModules();
            return is_array($result) ? $result : array();
        } catch (Exception $e) {
            return array();
        } catch (Error $e) {
            return array();
        } catch (Throwable $e) {
            return array();
        }
    }
    
    /**
     * Get CGBlog statistics
     */
    public function GetCGBlogStats()
    {
        $stats = array(
            'articles' => 0,
            'categories' => 0,
            'fielddefs' => 0,
            'fieldvals' => 0
        );
        
        if (!$this->IsCGBlogAvailable()) {
            return $stats;
        }
        
        try {
            $db = cmsms()->GetDb();
            if (!$db) {
                return $stats;
            }
            
            $stats['articles'] = (int)$db->GetOne('SELECT COUNT(*) FROM ' . cms_db_prefix() . 'module_cgblog');
            $stats['categories'] = (int)$db->GetOne('SELECT COUNT(*) FROM ' . cms_db_prefix() . 'module_cgblog_categories');
            $stats['fielddefs'] = (int)$db->GetOne('SELECT COUNT(*) FROM ' . cms_db_prefix() . 'module_cgblog_fielddefs');
            $stats['fieldvals'] = (int)$db->GetOne('SELECT COUNT(*) FROM ' . cms_db_prefix() . 'module_cgblog_fieldvals');
        } catch (Exception $e) {
            // Return empty stats on error
        } catch (Error $e) {
            // Return empty stats on error
        } catch (Throwable $e) {
            // Return empty stats on error
        }
        
        return $stats;
    }
    
    /**
     * Validate migration prerequisites
     */
    public function ValidateMigrationPrerequisites()
    {
        $errors = array();
        
        if (!$this->IsCGBlogAvailable()) {
            $errors[] = $this->Lang('error_cgblog_not_installed');
        }
        
        if (!$this->IsLISEAvailable()) {
            $errors[] = $this->Lang('error_lise_not_installed');
        }
        
        return $errors;
    }
    
    /**
     * Show donations tab
     */
    function ShowDonationsTab() {
        return ($this->GetPreference("hidedonationstab") != $this->GetVersion());
    }
}


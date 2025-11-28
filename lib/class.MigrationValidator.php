<?php
/**
 * Migration Validator Class
 * Validates prerequisites and data integrity before migration
 */

if (!defined('CMS_VERSION')) exit;

class MigrationValidator
{
    private $module;
    private $errors;
    private $warnings;
    
    public function __construct($module)
    {
        $this->module = $module;
        $this->errors = array();
        $this->warnings = array();
    }
    
    /**
     * Validate all prerequisites
     */
    public function ValidatePrerequisites()
    {
        $this->errors = array();
        $this->warnings = array();
        
        // Check CGBlog availability
        if (!$this->module->IsCGBlogAvailable()) {
            $this->errors[] = $this->module->Lang('error_cgblog_not_installed');
            return false;
        }
        
        // Check LISE availability
        if (!$this->module->IsLISEAvailable()) {
            $this->errors[] = $this->module->Lang('error_lise_not_installed');
            return false;
        }
        
        // Check for data
        $stats = $this->module->GetCGBlogStats();
        if ($stats['articles'] == 0 && $stats['categories'] == 0 && $stats['fielddefs'] == 0) {
            $this->errors[] = $this->module->Lang('error_no_data');
            return false;
        }
        
        // Warnings for missing data types
        if ($stats['articles'] == 0) {
            $this->warnings[] = $this->module->Lang('warning_no_articles');
        }
        if ($stats['categories'] == 0) {
            $this->warnings[] = $this->module->Lang('warning_no_categories');
        }
        if ($stats['fielddefs'] == 0) {
            $this->warnings[] = $this->module->Lang('warning_no_fielddefs');
        }
        
        return true;
    }
    
    /**
     * Validate instance name and generate default if empty
     * Returns the validated/generated instance name or false on error
     */
    public function ValidateInstanceName($instance_name, $check_exists = true)
    {
        // Generate default name if empty
        if (empty($instance_name)) {
            $instance_name = $this->GenerateDefaultInstanceName();
        }
        
        // Check for valid characters (alphanumeric only)
        if (!preg_match('/^[a-zA-Z0-9]+$/', $instance_name)) {
            $this->errors[] = $this->module->Lang('error_instance_invalid');
            return false;
        }
        
        // Check if instance already exists and find available name
        if ($check_exists) {
            $lise = $this->module->GetLISEModule();
            if ($lise) {
                $instances = $this->module->GetLISEInstances();
                $counter = 1;
                $original_name = $instance_name;
                $validated_name = $instance_name;
                
                // If name exists, append number until we find available name
                while (true) {
                    $name_exists = false;
                    foreach ($instances as $instance) {
                        $name = str_replace('LISE', '', $instance->module_name);
                        if (strtolower($name) == strtolower($validated_name)) {
                            $name_exists = true;
                            break;
                        }
                    }
                    
                    if (!$name_exists) {
                        break;
                    }
                    
                    // Name exists, try with counter
                    $validated_name = $original_name . $counter;
                    $counter++;
                }
                
                return $validated_name;
            }
        }
        
        return $instance_name;
    }
    
    /**
     * Generate default instance name
     */
    public function GenerateDefaultInstanceName()
    {
        // Generate name based on CGBlog and timestamp
        $base_name = 'CGBlogMigrated';
        $timestamp = date('YmdHis');
        return $base_name . $timestamp;
    }
    
    /**
     * Validate existing instance
     */
    public function ValidateExistingInstance($instance_id)
    {
        $lise = $this->module->GetLISEModule();
        if (!$lise) {
            $this->errors[] = $this->module->Lang('error_lise_not_installed');
            return false;
        }
        
        $instances = $this->module->GetLISEInstances();
        $found = false;
        foreach ($instances as $instance) {
            if ($instance->module_id == $instance_id) {
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            $this->errors[] = $this->module->Lang('error_instance_not_found');
            return false;
        }
        
        return true;
    }
    
    /**
     * Get errors
     */
    public function GetErrors()
    {
        return $this->errors;
    }
    
    /**
     * Get warnings
     */
    public function GetWarnings()
    {
        return $this->warnings;
    }
    
    /**
     * Check if validation passed
     */
    public function IsValid()
    {
        return empty($this->errors);
    }
}


<?php
/**
 * Migration Engine Class
 * Core migration logic for CGBlog to LISE
 */

if (!defined('CMS_VERSION')) exit;

class MigrationEngine
{
    private $module;
    private $db;
    private $instance_name;
    private $instance_friendlyname;
    private $use_existing;
    private $existing_instance_id;
    private $migrate_articles;
    private $migrate_categories;
    private $migrate_fielddefs;
    private $migrate_fieldvals;
    private $results;
    private $errors;
    private $warnings;
    private $log_file = null;
    
    public function __construct($module)
    {
        $this->module = $module;
        
        // Don't initialize DB here - do it lazily when needed
        $this->results = array(
            'articles' => 0,
            'categories' => 0,
            'fielddefs' => 0,
            'fieldvals' => 0
        );
        $this->errors = array();
        $this->warnings = array();
        $this->db = null; // Initialize lazily
    }
    
    /**
     * Get database connection (lazy initialization)
     */
    private function GetDb()
    {
        if ($this->db === null) {
            $this->db = cmsms()->GetDb();
        }
        return $this->db;
    }
    
    /**
     * Setup logging (lazy initialization)
     */
    private function SetupLogging()
    {
        if ($this->log_file === null) {
            $module_path = $this->module->GetModulePath();
            $logs_dir = cms_join_path($module_path, 'logs');
            if (!is_dir($logs_dir)) {
                @mkdir($logs_dir, 0755, true);
                // Set correct ownership for logs directory
                @chown($logs_dir, 'newst3922');
                @chgrp($logs_dir, 'newst3922');
            }
            $this->log_file = cms_join_path($logs_dir, 'migration_' . date('Y-m-d_H-i-s') . '.log');
        }
    }
    
    /**
     * Set migration parameters
     */
    public function SetParameters($params)
    {
        $this->instance_name = isset($params['instance_name']) ? trim($params['instance_name']) : '';
        $this->instance_friendlyname = isset($params['instance_friendlyname']) ? trim($params['instance_friendlyname']) : '';
        $this->use_existing = isset($params['use_existing']) && $params['use_existing'] == 1;
        $this->existing_instance_id = isset($params['existing_instance_id']) ? (int)$params['existing_instance_id'] : 0;
        $this->migrate_articles = isset($params['migrate_articles']) && $params['migrate_articles'] == 1;
        $this->migrate_categories = isset($params['migrate_categories']) && $params['migrate_categories'] == 1;
        $this->migrate_fielddefs = isset($params['migrate_fielddefs']) && $params['migrate_fielddefs'] == 1;
        $this->migrate_fieldvals = isset($params['migrate_fieldvals']) && $params['migrate_fieldvals'] == 1;
    }
    
    /**
     * Execute migration
     */
    public function Execute()
    {
        try {
            $this->Log('Starting migration from CGBlog to LISE...');
            
            // Validate prerequisites
            $validator = new MigrationValidator($this->module);
            if (!$validator->ValidatePrerequisites()) {
                $this->errors = $validator->GetErrors();
                $error_msg = implode(', ', $this->errors);
                $this->Log('Migration failed: ' . $error_msg);
                return false;
            }
            
            // Determine target instance
            if ($this->use_existing) {
                if (!$validator->ValidateExistingInstance($this->existing_instance_id)) {
                    $this->errors = $validator->GetErrors();
                    return false;
                }
                $instances = $this->module->GetLISEInstances();
                foreach ($instances as $inst) {
                    if ($inst->module_id == $this->existing_instance_id) {
                        $this->instance_name = str_replace('LISE', '', $inst->module_name);
                        break;
                    }
                }
            } else {
                // Validate and auto-generate instance name if empty
                $validated_name = $validator->ValidateInstanceName($this->instance_name, true);
                if ($validated_name === false) {
                    $this->errors = $validator->GetErrors();
                    return false;
                }
                // Use validated/generated name (may have been auto-generated or modified)
                $this->instance_name = $validated_name;
                
                // Auto-generate friendly name if empty
                if (empty($this->instance_friendlyname)) {
                    $this->instance_friendlyname = 'CGBlog Migrated - ' . date('Y-m-d H:i:s');
                }
                // Create new instance
                if (!$this->CreateLISEInstance()) {
                    return false;
                }
            }
            
            $this->warnings = $validator->GetWarnings();
            
            // Start transaction
            $db = $this->GetDb();
            $db->StartTrans();
            
            try {
                // Migrate categories first (needed for relationships)
                if ($this->migrate_categories) {
                    $this->MigrateCategories();
                }
                
                // Migrate field definitions
                if ($this->migrate_fielddefs) {
                    $this->MigrateFieldDefs();
                }
                
                // Migrate articles
                if ($this->migrate_articles) {
                    $this->MigrateArticles();
                }
                
                // Migrate field values
                if ($this->migrate_fieldvals && $this->migrate_articles) {
                    $this->MigrateFieldVals();
                }
                
                // Commit transaction
                $db->CompleteTrans();
                
                // Log successful migration with statistics
                $stats_msg = sprintf(
                    'Migration completed successfully: %d articles, %d categories, %d field definitions, %d field values migrated',
                    $this->results['articles'],
                    $this->results['categories'],
                    $this->results['fielddefs'],
                    $this->results['fieldvals']
                );
                $this->Log($stats_msg);
                
                return true;
                
            } catch (Exception $e) {
                $db->FailTrans();
                $this->errors[] = $this->module->Lang('error_migration_failed', $e->getMessage());
                $this->Log('Migration failed: ' . $e->getMessage());
                return false;
            }
            
        } catch (Exception $e) {
            $this->errors[] = $this->module->Lang('error_migration_failed', $e->getMessage());
            $this->Log('Migration error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create new LISE instance
     */
    private function CreateLISEInstance()
    {
        $this->Log('Creating new LISE instance: ' . $this->instance_name);
        
        $lise = $this->module->GetLISEModule();
        if (!$lise) {
            $this->errors[] = $this->module->Lang('error_lise_not_installed');
            return false;
        }
        
        // Use LISE cloner to create instance from a base template
        // We'll clone from the first available LISE instance, or create base structure
        $instances = $this->module->GetLISEInstances();
        
        if (empty($instances)) {
            // No existing instances - we need to create base structure manually
            return $this->CreateLISEInstanceFromScratch();
        } else {
            // Clone from first available instance
            $base_instance = reset($instances);
            $base_name = $base_instance->module_name;
            $new_name = 'LISE' . $this->instance_name;
            
            try {
                // Load LISECloner class
                $lise_path = cms_join_path(cmsms()->GetConfig()['root_path'], 'modules', 'LISE', 'lib', 'class.LISECloner.php');
                if (!file_exists($lise_path)) {
                    $this->errors[] = 'LISECloner class not found';
                    return false;
                }
                require_once($lise_path);
                
                $cloner = new LISECloner($base_name, $new_name);
                $full_name = $cloner->Run();
                
                // Update instance preferences
                $modops = cmsms()->GetModuleOperations();
                $new_mod = $modops->get_module_instance($full_name, NULL, TRUE);
                if ($new_mod) {
                    $new_mod->SetPreference('friendlyname', $this->instance_friendlyname ?: $this->instance_name);
                }
                
                $this->instance_name = str_replace('LISE', '', $full_name);
                $this->Log('LISE instance created successfully: ' . $full_name);
                return true;
            } catch (Exception $e) {
                $this->errors[] = $this->module->Lang('error_migration_failed', $e->getMessage());
                $this->Log('Failed to create instance: ' . $e->getMessage());
                return false;
            }
        }
    }
    
    /**
     * Create LISE instance from scratch (fallback)
     */
    private function CreateLISEInstanceFromScratch()
    {
        // This is a complex operation - for now, we'll require at least one LISE instance
        $this->errors[] = 'Cannot create LISE instance: No base LISE instances available. Please create at least one LISE instance first.';
        return false;
    }
    
    /**
     * Get table prefix for instance
     */
    private function GetTablePrefix()
    {
        // If using existing instance, get the actual module name
        if ($this->use_existing && $this->existing_instance_id) {
            $lise = $this->module->GetLISEModule();
            if ($lise) {
                $instances = $this->module->GetLISEInstances();
                foreach ($instances as $inst) {
                    if ($inst->module_id == $this->existing_instance_id) {
                        $instance_alias = strtolower($inst->module_name);
                        return cms_db_prefix() . 'module_' . $instance_alias . '_';
                    }
                }
            }
        }
        
        // For new instances
        $instance_alias = strtolower('LISE' . $this->instance_name);
        return cms_db_prefix() . 'module_' . $instance_alias . '_';
    }
    
    /**
     * Migrate categories
     */
    private function MigrateCategories()
    {
        $this->SetupLogging();
        $this->Log('Migrating categories...');
        $db = $this->GetDb();
        $cgblog_cats = DataMapper::GetCGBlogCategories($db);
        $table_prefix = $this->GetTablePrefix();
        $category_table = $table_prefix . 'category';
        
        $category_map = array(); // Map old ID to new ID
        
        // First pass: insert all categories with temporary parent_id
        foreach ($cgblog_cats as $cat) {
            $data = DataMapper::PrepareCategoryData($cat);
            
            // Insert category (parent_id will be updated in second pass)
            $query = 'INSERT INTO ' . $category_table . ' (name, description, parent_id, position) VALUES (?, ?, ?, ?)';
            $db->Execute($query, array(
                $data['name'],
                $data['description'],
                null, // Set to null initially
                $data['position']
            ));
            
            $new_id = $db->Insert_ID();
            $category_map[$cat['id']] = $new_id;
            $this->results['categories']++;
        }
        
        // Second pass: update parent_id references with new IDs
        foreach ($cgblog_cats as $cat) {
            if ($cat['parent_id'] && isset($category_map[$cat['parent_id']]) && isset($category_map[$cat['id']])) {
                $new_id = $category_map[$cat['id']];
                $new_parent_id = $category_map[$cat['parent_id']];
                $query = 'UPDATE ' . $category_table . ' SET parent_id = ? WHERE category_id = ?';
                $db->Execute($query, array($new_parent_id, $new_id));
            }
        }
        
        // Store category map for article relationships
        $this->category_map = $category_map;
        $this->Log('Migrated ' . $this->results['categories'] . ' categories', false); // Don't audit every step
    }
    
    /**
     * Migrate field definitions
     */
    private function MigrateFieldDefs()
    {
        $this->SetupLogging();
        $this->Log('Migrating field definitions...');
        $db = $this->GetDb();
        $cgblog_fields = DataMapper::GetCGBlogFieldDefs($db);
        $table_prefix = $this->GetTablePrefix();
        $fielddef_table = $table_prefix . 'fielddef';
        
        $fielddef_map = array(); // Map old ID to new ID
        
        foreach ($cgblog_fields as $field) {
            $data = DataMapper::PrepareFieldDefData($field);
            
            // Insert field definition
            $query = 'INSERT INTO ' . $fielddef_table . ' (alias, type, position, public) VALUES (?, ?, ?, ?)';
            $db->Execute($query, array(
                $data['alias'],
                $data['type'],
                $data['position'],
                $data['public']
            ));
            
            $new_id = $db->Insert_ID();
            $fielddef_map[$field['id']] = $new_id;
            $this->results['fielddefs']++;
        }
        
        // Store fielddef map for later use
        $this->fielddef_map = $fielddef_map;
        $this->Log('Migrated ' . $this->results['fielddefs'] . ' field definitions', false); // Don't audit every step
    }
    
    /**
     * Migrate articles
     */
    private function MigrateArticles()
    {
        $this->SetupLogging();
        $this->Log('Migrating articles...');
        $db = $this->GetDb();
        $cgblog_articles = DataMapper::GetCGBlogArticles($db);
        $table_prefix = $this->GetTablePrefix();
        $item_table = $table_prefix . 'item';
        $item_category_table = $table_prefix . 'item_categories';
        
        $article_map = array(); // Map old ID to new ID
        $position = 0;
        
        // Get category map if categories were migrated
        $category_map = array();
        if ($this->migrate_categories && isset($this->category_map)) {
            $category_map = $this->category_map;
        }
        
        foreach ($cgblog_articles as $article) {
            $data = DataMapper::PrepareArticleData($article, $db);
            $data['position'] = $position++;
            
            // Insert article
            $query = 'INSERT INTO ' . $item_table . ' (title, data, summary, create_date, modified_date, active, url, alias, position) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
            $db->Execute($query, array(
                $data['title'],
                $data['data'],
                $data['summary'],
                $data['create_date'],
                $data['modified_date'],
                $data['active'],
                $data['url'],
                $data['alias'],
                $data['position']
            ));
            
            $new_id = $db->Insert_ID();
            $article_map[$article['cgblog_id']] = $new_id;
            $this->results['articles']++;
            
            // Migrate category relationships
            if ($this->migrate_categories && !empty($category_map)) {
                $categories = DataMapper::GetCGBlogArticleCategories($db, $article['cgblog_id']);
                foreach ($categories as $old_cat_id) {
                    if (isset($category_map[$old_cat_id])) {
                        $new_cat_id = $category_map[$old_cat_id];
                        $query = 'INSERT INTO ' . $item_category_table . ' (item_id, category_id) VALUES (?, ?)';
                        $db->Execute($query, array($new_id, $new_cat_id));
                    }
                }
            }
        }
        
        // Store article map for field values
        $this->article_map = $article_map;
        $this->Log('Migrated ' . $this->results['articles'] . ' articles', false); // Don't audit every step
    }
    
    /**
     * Migrate field values
     */
    private function MigrateFieldVals()
    {
        if (!isset($this->article_map) || !isset($this->fielddef_map)) {
            return;
        }
        
        $this->SetupLogging();
        $this->Log('Migrating field values...');
        $db = $this->GetDb();
        $table_prefix = $this->GetTablePrefix();
        $fieldval_table = $table_prefix . 'fieldval';
        
        foreach ($this->article_map as $old_article_id => $new_article_id) {
            $fieldvals = DataMapper::GetCGBlogFieldVals($db, $old_article_id);
            
            foreach ($fieldvals as $fieldval) {
                if (!isset($this->fielddef_map[$fieldval['fielddef_id']])) {
                    continue; // Skip if fielddef wasn't migrated
                }
                
                $new_fielddef_id = $this->fielddef_map[$fieldval['fielddef_id']];
                
                $query = 'INSERT INTO ' . $fieldval_table . ' (item_id, fielddef_id, value) VALUES (?, ?, ?)';
                $db->Execute($query, array(
                    $new_article_id,
                    $new_fielddef_id,
                    $fieldval['value']
                ));
                
                $this->results['fieldvals']++;
            }
        }
        
        $this->Log('Migrated ' . $this->results['fieldvals'] . ' field values', false); // Don't audit every step
    }
    
    /**
     * Log message to both file and admin log
     */
    private function Log($message, $audit = true)
    {
        $this->SetupLogging();
        $timestamp = date('Y-m-d H:i:s');
        $log_entry = "[$timestamp] $message\n";
        @file_put_contents($this->log_file, $log_entry, FILE_APPEND);
        
        // Also log to CMSMS Admin Log
        if ($audit && method_exists($this->module, 'Audit')) {
            $this->module->Audit(0, $this->module->Lang('friendlyname'), $message);
        }
    }
    
    /**
     * Get results
     */
    public function GetResults()
    {
        return $this->results;
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
}


<?php
/**
 * Migration Data Handler Class
 * Handles data migration operations for CGBlog to LISE
 */

if (!defined('CMS_VERSION')) exit;

class MigrationDataHandler
{
    private $module;
    private $db;
    private $table_prefix;
    private $category_map;
    private $fielddef_map;
    private $article_map;
    private $results;
    private $log_file;
    private $log_callback;
    
    public function __construct($module, $db, $table_prefix, &$results, $log_file, $log_callback)
    {
        $this->module = $module;
        $this->db = $db;
        $this->table_prefix = $table_prefix;
        $this->results = &$results;
        $this->log_file = $log_file;
        $this->log_callback = $log_callback;
        $this->category_map = array();
        $this->fielddef_map = array();
        $this->article_map = array();
    }
    
    /**
     * Migrate categories
     */
    public function MigrateCategories()
    {
        $this->Log('Migrating categories...');
        $cgblog_cats = DataMapper::GetCGBlogCategories($this->db);
        $category_table = $this->table_prefix . 'category';
        
        $category_map = array(); // Map old ID to new ID
        
        // First pass: insert all categories with temporary parent_id
        foreach ($cgblog_cats as $cat) {
            $data = DataMapper::PrepareCategoryData($cat);
            
            // Insert category (parent_id will be updated in second pass)
            $query = 'INSERT INTO ' . $category_table . ' (name, description, parent_id, position) VALUES (?, ?, ?, ?)';
            $this->db->Execute($query, array(
                $data['name'],
                $data['description'],
                null, // Set to null initially
                $data['position']
            ));
            
            $new_id = $this->db->Insert_ID();
            $category_map[$cat['id']] = $new_id;
            $this->results['categories']++;
        }
        
        // Second pass: update parent_id references with new IDs
        foreach ($cgblog_cats as $cat) {
            if ($cat['parent_id'] && isset($category_map[$cat['parent_id']]) && isset($category_map[$cat['id']])) {
                $new_id = $category_map[$cat['id']];
                $new_parent_id = $category_map[$cat['parent_id']];
                $query = 'UPDATE ' . $category_table . ' SET parent_id = ? WHERE category_id = ?';
                $this->db->Execute($query, array($new_parent_id, $new_id));
            }
        }
        
        // Store category map for article relationships
        $this->category_map = $category_map;
        $this->Log('Migrated ' . $this->results['categories'] . ' categories', false);
    }
    
    /**
     * Create default field definitions for Content and Summary
     */
    public function CreateDefaultFieldDefs()
    {
        $fielddef_table = $this->table_prefix . 'fielddef';
        
        // Check if Content field already exists
        $content_check = $this->db->GetOne('SELECT fielddef_id FROM ' . $fielddef_table . ' WHERE alias = ?', array('content'));
        if (!$content_check) {
            $query = 'INSERT INTO ' . $fielddef_table . ' (name, alias, help, type, position, required, template, extra) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
            $this->db->Execute($query, array(
                'Content',
                'content',
                '',
                'TextArea',
                1,
                0,
                '{$fielddef.name}: {$fielddef.value}',
                null
            ));
            $content_id = $this->db->Insert_ID();
            $this->fielddef_map['content'] = $content_id;
            $this->results['fielddefs']++;
        } else {
            $this->fielddef_map['content'] = $content_check;
        }
        
        // Check if Summary field already exists
        $summary_check = $this->db->GetOne('SELECT fielddef_id FROM ' . $fielddef_table . ' WHERE alias = ?', array('summary'));
        if (!$summary_check) {
            $query = 'INSERT INTO ' . $fielddef_table . ' (name, alias, help, type, position, required, template, extra) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
            $this->db->Execute($query, array(
                'Summary',
                'summary',
                '',
                'TextArea',
                2,
                0,
                '{$fielddef.name}: {$fielddef.value}',
                null
            ));
            $summary_id = $this->db->Insert_ID();
            $this->fielddef_map['summary'] = $summary_id;
            $this->results['fielddefs']++;
        } else {
            $this->fielddef_map['summary'] = $summary_check;
        }
    }
    
    /**
     * Migrate field definitions
     */
    public function MigrateFieldDefs()
    {
        $this->Log('Migrating field definitions...');
        $cgblog_fields = DataMapper::GetCGBlogFieldDefs($this->db);
        $fielddef_table = $this->table_prefix . 'fielddef';
        
        $fielddef_map = array(); // Map old ID to new ID
        $position = 10; // Start after default fields (Content=1, Summary=2)
        
        foreach ($cgblog_fields as $field) {
            $data = DataMapper::PrepareFieldDefData($field);
            
            // Map field type correctly
            $lise_type = DataMapper::MapFieldType($field['type']);
            // Convert to LISE type format (TextInput, TextArea, etc.)
            if ($lise_type == 'text') {
                $lise_type = 'TextInput';
            } elseif ($lise_type == 'textarea') {
                $lise_type = 'TextArea';
            } elseif ($lise_type == 'checkbox') {
                $lise_type = 'Checkbox';
            } elseif ($lise_type == 'file') {
                $lise_type = 'File';
            } elseif ($lise_type == 'image') {
                $lise_type = 'Image';
            }
            
            // Insert field definition with all required columns
            $query = 'INSERT INTO ' . $fielddef_table . ' (name, alias, help, type, position, required, template, extra) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
            $this->db->Execute($query, array(
                isset($field['name']) ? $field['name'] : $data['alias'],
                $data['alias'],
                isset($field['help']) ? $field['help'] : '',
                $lise_type,
                $position++,
                0, // required
                '{$fielddef.name}: {$fielddef.value}', // template
                null // extra
            ));
            
            $new_id = $this->db->Insert_ID();
            $fielddef_map[$field['id']] = $new_id;
            $this->results['fielddefs']++;
        }
        
        // Merge with default fielddefs
        $this->fielddef_map = array_merge($this->fielddef_map, $fielddef_map);
        $this->Log('Migrated ' . count($fielddef_map) . ' field definitions', false);
    }
    
    /**
     * Migrate articles
     */
    public function MigrateArticles($migrate_categories, $category_map)
    {
        $this->Log('Migrating articles...');
        $cgblog_articles = DataMapper::GetCGBlogArticles($this->db);
        $item_table = $this->table_prefix . 'item';
        $item_category_table = $this->table_prefix . 'item_categories';
        $fieldval_table = $this->table_prefix . 'fieldval';
        
        $article_map = array(); // Map old ID to new ID
        $position = 0;
        
        foreach ($cgblog_articles as $article) {
            $data = DataMapper::PrepareArticleData($article, $this->db);
            
            // Insert article - use correct column names and exclude data/summary
            $query = 'INSERT INTO ' . $item_table . ' (title, url, alias, position, active, create_time, modified_time) VALUES (?, ?, ?, ?, ?, ?, ?)';
            $this->db->Execute($query, array(
                $data['title'],
                $data['url'],
                $data['alias'],
                $position++,
                $data['active'],
                $data['create_date'], // Still use create_date from DataMapper, but insert into create_time
                $data['modified_date'] // Still use modified_date from DataMapper, but insert into modified_time
            ));
            
            $new_id = $this->db->Insert_ID();
            $article_map[$article['cgblog_id']] = $new_id;
            $this->results['articles']++;
            
            // Store content and summary as field values
            if (isset($this->fielddef_map['content']) && !empty($data['data'])) {
                $query = 'INSERT INTO ' . $fieldval_table . ' (item_id, fielddef_id, value_index, value) VALUES (?, ?, ?, ?)';
                $this->db->Execute($query, array(
                    $new_id,
                    $this->fielddef_map['content'],
                    0,
                    $data['data']
                ));
                $this->results['fieldvals']++;
            }
            
            if (isset($this->fielddef_map['summary']) && !empty($data['summary'])) {
                $query = 'INSERT INTO ' . $fieldval_table . ' (item_id, fielddef_id, value_index, value) VALUES (?, ?, ?, ?)';
                $this->db->Execute($query, array(
                    $new_id,
                    $this->fielddef_map['summary'],
                    0,
                    $data['summary']
                ));
                $this->results['fieldvals']++;
            }
            
            // Migrate category relationships
            if ($migrate_categories && !empty($category_map)) {
                $categories = DataMapper::GetCGBlogArticleCategories($this->db, $article['cgblog_id']);
                foreach ($categories as $old_cat_id) {
                    if (isset($category_map[$old_cat_id])) {
                        $new_cat_id = $category_map[$old_cat_id];
                        $query = 'INSERT INTO ' . $item_category_table . ' (item_id, category_id) VALUES (?, ?)';
                        $this->db->Execute($query, array($new_id, $new_cat_id));
                    }
                }
            }
        }
        
        // Store article map for field values
        $this->article_map = $article_map;
        $this->Log('Migrated ' . $this->results['articles'] . ' articles', false);
    }
    
    /**
     * Migrate field values
     */
    public function MigrateFieldVals()
    {
        if (empty($this->article_map) || empty($this->fielddef_map)) {
            return;
        }
        
        $this->Log('Migrating field values...');
        $fieldval_table = $this->table_prefix . 'fieldval';
        
        foreach ($this->article_map as $old_article_id => $new_article_id) {
            $fieldvals = DataMapper::GetCGBlogFieldVals($this->db, $old_article_id);
            
            foreach ($fieldvals as $fieldval) {
                // Check if fielddef was migrated from CGBlog (by ID)
                if (!isset($this->fielddef_map[$fieldval['fielddef_id']])) {
                    continue; // Skip if fielddef wasn't migrated
                }
                
                $new_fielddef_id = $this->fielddef_map[$fieldval['fielddef_id']];
                
                $query = 'INSERT INTO ' . $fieldval_table . ' (item_id, fielddef_id, value_index, value) VALUES (?, ?, ?, ?)';
                $this->db->Execute($query, array(
                    $new_article_id,
                    $new_fielddef_id,
                    0,
                    $fieldval['value']
                ));
                
                $this->results['fieldvals']++;
            }
        }
        
        $this->Log('Migrated field values', false);
    }
    
    /**
     * Get category map
     */
    public function GetCategoryMap()
    {
        return $this->category_map;
    }
    
    /**
     * Get fielddef map
     */
    public function GetFielddefMap()
    {
        return $this->fielddef_map;
    }
    
    /**
     * Get article map
     */
    public function GetArticleMap()
    {
        return $this->article_map;
    }
    
    /**
     * Log message
     */
    private function Log($message, $audit = true)
    {
        if ($this->log_callback) {
            call_user_func($this->log_callback, $message, $audit);
        }
    }
}

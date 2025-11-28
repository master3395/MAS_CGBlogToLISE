<?php
/**
 * Data Mapper Class
 * Maps CGBlog data structures to LISE format
 */

if (!defined('CMS_VERSION')) exit;

class DataMapper
{
    /**
     * Map CGBlog field type to LISE field type
     */
    public static function MapFieldType($cgblog_type)
    {
        $mapping = array(
            'textbox' => 'text',
            'textarea' => 'textarea',
            'checkbox' => 'checkbox',
            'file' => 'file',
            'image' => 'image',
            'file_select' => 'file_select',
            'image_select' => 'image_select'
        );
        
        return isset($mapping[$cgblog_type]) ? $mapping[$cgblog_type] : 'text';
    }
    
    /**
     * Map article status to active flag
     */
    public static function MapStatusToActive($status)
    {
        return ($status == 'published') ? 1 : 0;
    }
    
    /**
     * Get CGBlog articles
     */
    public static function GetCGBlogArticles($db)
    {
        $query = 'SELECT * FROM ' . cms_db_prefix() . 'module_cgblog ORDER BY cgblog_id';
        return $db->GetArray($query);
    }
    
    /**
     * Get CGBlog categories
     */
    public static function GetCGBlogCategories($db)
    {
        $query = 'SELECT * FROM ' . cms_db_prefix() . 'module_cgblog_categories ORDER BY hierarchy, item_order';
        return $db->GetArray($query);
    }
    
    /**
     * Get CGBlog field definitions
     */
    public static function GetCGBlogFieldDefs($db)
    {
        $query = 'SELECT * FROM ' . cms_db_prefix() . 'module_cgblog_fielddefs ORDER BY item_order';
        return $db->GetArray($query);
    }
    
    /**
     * Get CGBlog field values for an article
     */
    public static function GetCGBlogFieldVals($db, $article_id)
    {
        $query = 'SELECT fv.*, fd.name, fd.type 
                  FROM ' . cms_db_prefix() . 'module_cgblog_fieldvals fv
                  LEFT JOIN ' . cms_db_prefix() . 'module_cgblog_fielddefs fd ON fv.fielddef_id = fd.id
                  WHERE fv.cgblog_id = ?';
        return $db->GetArray($query, array($article_id));
    }
    
    /**
     * Get CGBlog article categories
     */
    public static function GetCGBlogArticleCategories($db, $article_id)
    {
        $query = 'SELECT category_id FROM ' . cms_db_prefix() . 'module_cgblog_blog_categories WHERE blog_id = ?';
        $result = $db->GetArray($query, array($article_id));
        $categories = array();
        if ($result) {
            foreach ($result as $row) {
                $categories[] = $row['category_id'];
            }
        }
        return $categories;
    }
    
    /**
     * Prepare article data for LISE
     */
    public static function PrepareArticleData($cgblog_article, $db)
    {
        $data = array(
            'title' => $cgblog_article['cgblog_title'],
            'data' => $cgblog_article['cgblog_data'],
            'summary' => isset($cgblog_article['summary']) ? $cgblog_article['summary'] : '',
            'create_date' => $cgblog_article['cgblog_date'],
            'modified_date' => isset($cgblog_article['modified_date']) ? $cgblog_article['modified_date'] : $cgblog_article['cgblog_date'],
            'active' => self::MapStatusToActive($cgblog_article['status']),
            'url' => isset($cgblog_article['url']) ? $cgblog_article['url'] : '',
            'author' => isset($cgblog_article['author']) ? $cgblog_article['author'] : '',
            'position' => 0 // Will be set during migration
        );
        
        // Generate alias from title if URL is empty
        if (empty($data['url'])) {
            $data['alias'] = munge_string_to_url($data['title']);
        } else {
            $data['alias'] = munge_string_to_url($data['url']);
        }
        
        return $data;
    }
    
    /**
     * Prepare category data for LISE
     */
    public static function PrepareCategoryData($cgblog_category)
    {
        return array(
            'name' => $cgblog_category['name'],
            'description' => isset($cgblog_category['description']) ? $cgblog_category['description'] : '',
            'parent_id' => isset($cgblog_category['parent_id']) ? (int)$cgblog_category['parent_id'] : null,
            'position' => isset($cgblog_category['item_order']) ? (int)$cgblog_category['item_order'] : 0
        );
    }
    
    /**
     * Prepare field definition data for LISE
     */
    public static function PrepareFieldDefData($cgblog_fielddef)
    {
        return array(
            'alias' => $cgblog_fielddef['name'],
            'type' => self::MapFieldType($cgblog_fielddef['type']),
            'position' => isset($cgblog_fielddef['item_order']) ? (int)$cgblog_fielddef['item_order'] : 0,
            'public' => isset($cgblog_fielddef['public']) ? (int)$cgblog_fielddef['public'] : 1
        );
    }
}


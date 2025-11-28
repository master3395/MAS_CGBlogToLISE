<?php
/**
 * Migration Tab Function
 * Main migration interface
 */

// Note: This file is included from action.defaultadmin.php, so cmsms() should already be available

// Function to load classes only when needed
function load_migration_classes($module) {
    static $classes_loaded = false;
    if ($classes_loaded) {
        return true;
    }
    
    try {
        $module_path = $module->GetModulePath();
        if (!is_dir($module_path . '/lib')) {
            throw new Exception('Library directory not found: ' . $module_path . '/lib');
        }
        
        $validator_file = $module_path . '/lib/class.MigrationValidator.php';
        $mapper_file = $module_path . '/lib/class.DataMapper.php';
        $engine_file = $module_path . '/lib/class.MigrationEngine.php';
        
        if (!file_exists($validator_file)) {
            throw new Exception('Validator class not found: ' . $validator_file);
        }
        if (!file_exists($mapper_file)) {
            throw new Exception('DataMapper class not found: ' . $mapper_file);
        }
        if (!file_exists($engine_file)) {
            throw new Exception('MigrationEngine class not found: ' . $engine_file);
        }
        
        require_once($validator_file);
        require_once($mapper_file);
        require_once($engine_file);
        $classes_loaded = true;
        return true;
    } catch (Exception $e) {
        echo '<div class="pageoverflow"><p class="pagetext" style="color: red;">Error loading classes: ' . htmlspecialchars($e->getMessage()) . '</p></div>';
        return false;
    } catch (Error $e) {
        echo '<div class="pageoverflow"><p class="pagetext" style="color: red;">Fatal Error loading classes: ' . htmlspecialchars($e->getMessage()) . '</p></div>';
        return false;
    }
}

// Handle preview request
if (isset($params['preview'])) {
    if (!load_migration_classes($this)) {
        return;
    }
    try {
        $validator = new MigrationValidator($this);
        $validator->ValidatePrerequisites();
        $errors = $validator->GetErrors();
        
        if (!empty($errors)) {
            echo $this->ShowErrors($errors);
            $this->Audit(0, $this->Lang('friendlyname'), 'Migration preview failed: ' . implode(', ', $errors));
            return;
        }
        
        $stats = $this->GetCGBlogStats();
        
        // Log preview to admin log
        $preview_msg = sprintf(
            'Migration preview: %d articles, %d categories, %d field definitions, %d field values ready for migration',
            $stats['articles'],
            $stats['categories'],
            $stats['fielddefs'],
            $stats['fieldvals']
        );
        $this->Audit(0, $this->Lang('friendlyname'), $preview_msg);
        
        echo '<h2>' . $this->Lang('preview_title') . '</h2>';
        echo '<p>' . $this->Lang('preview_description') . '</p>';
        echo '<ul>';
        echo '<li>' . $this->Lang('preview_articles_count', $stats['articles']) . '</li>';
        echo '<li>' . $this->Lang('preview_categories_count', $stats['categories']) . '</li>';
        echo '<li>' . $this->Lang('preview_fielddefs_count', $stats['fielddefs']) . '</li>';
        echo '<li>' . $this->Lang('preview_fieldvals_count', $stats['fieldvals']) . '</li>';
        echo '</ul>';
        
        // Create proper Cancel link back to migration tab (clears preview parameter)
        // Use CreateLink directly - it will include all necessary session tokens
        echo '<div class="pageoverflow">';
        echo '<p class="pageinput">';
        echo $this->CreateLink($id, 'defaultadmin', $returnid, $this->Lang('migrate_cancel'), array('activetab' => 'migrate'), '', '', '', '', '', '', '', '', '', '', 'class="cms_buttonlink"');
        echo '</p>';
        echo '</div>';
        return;
    } catch (Exception $e) {
        echo '<div class="pageoverflow"><p class="pagetext" style="color: red;">Error: ' . htmlspecialchars($e->getMessage()) . '</p></div>';
        return;
    }
}

// Handle migration execution
if (isset($params['migrate'])) {
    if (!load_migration_classes($this)) {
        return;
    }
    try {
        $engine = new MigrationEngine($this);
        $engine->SetParameters($params);
        
        $result = $engine->Execute();
        
        if ($result) {
            $results = $engine->GetResults();
            $warnings = $engine->GetWarnings();
            
            echo '<h2>' . $this->Lang('results_title') . '</h2>';
            echo '<ul>';
            echo '<li>' . $this->Lang('results_articles_migrated', $results['articles']) . '</li>';
            echo '<li>' . $this->Lang('results_categories_migrated', $results['categories']) . '</li>';
            echo '<li>' . $this->Lang('results_fielddefs_migrated', $results['fielddefs']) . '</li>';
            echo '<li>' . $this->Lang('results_fieldvals_migrated', $results['fieldvals']) . '</li>';
            echo '</ul>';
            
            if (!empty($warnings)) {
                echo '<h3>' . $this->Lang('results_warnings', count($warnings)) . '</h3>';
                echo '<ul>';
                foreach ($warnings as $warning) {
                    echo '<li>' . htmlspecialchars($warning) . '</li>';
                }
                echo '</ul>';
            }
            
            // Audit log is already done by MigrationEngine
            $this->Redirect($id, 'defaultadmin', $returnid, array('activetab' => 'migrate', 'msg' => 'migration_completed'));
            return;
        } else {
            $errors = $engine->GetErrors();
            echo $this->ShowErrors($errors);
            // Audit log is already done by MigrationEngine
        }
        
        echo '<p>' . $this->CreateLink($id, 'defaultadmin', $returnid, $this->Lang('migrate_cancel'), array('activetab' => 'migrate')) . '</p>';
        return;
    } catch (Exception $e) {
        echo '<div class="pageoverflow"><p class="pagetext" style="color: red;">Error during migration: ' . htmlspecialchars($e->getMessage()) . '</p></div>';
        echo '<p>' . $this->CreateLink($id, 'defaultadmin', $returnid, $this->Lang('migrate_cancel'), array('activetab' => 'migrate')) . '</p>';
        return;
    }
}

// Main migration form - DO NOT load classes here, only show the form
// Get stats without loading classes
try {
    $stats = $this->GetCGBlogStats();
} catch (Exception $e) {
    $stats = array('articles' => 0, 'categories' => 0, 'fielddefs' => 0, 'fieldvals' => 0);
} catch (Error $e) {
    $stats = array('articles' => 0, 'categories' => 0, 'fielddefs' => 0, 'fieldvals' => 0);
}

try {
    $lise_instances = $this->GetLISEInstances();
} catch (Exception $e) {
    $lise_instances = array();
} catch (Error $e) {
    $lise_instances = array();
}

// Default values
$instance_name = isset($params['instance_name']) ? $params['instance_name'] : '';
$instance_friendlyname = isset($params['instance_friendlyname']) ? $params['instance_friendlyname'] : '';
$use_existing = isset($params['use_existing']) ? (int)$params['use_existing'] : 0;
$existing_instance_id = isset($params['existing_instance_id']) ? (int)$params['existing_instance_id'] : 0;
$migrate_articles = isset($params['migrate_articles']) ? (int)$params['migrate_articles'] : 1;
$migrate_categories = isset($params['migrate_categories']) ? (int)$params['migrate_categories'] : 1;
$migrate_fielddefs = isset($params['migrate_fielddefs']) ? (int)$params['migrate_fielddefs'] : 1;
$migrate_fieldvals = isset($params['migrate_fieldvals']) ? (int)$params['migrate_fieldvals'] : 1;

// Statistics
echo '<h3>' . $this->Lang('statistics_title') . '</h3>';
echo '<ul>';
echo '<li>' . $this->Lang('statistics_articles') . ': ' . $stats['articles'] . '</li>';
echo '<li>' . $this->Lang('statistics_categories') . ': ' . $stats['categories'] . '</li>';
echo '<li>' . $this->Lang('statistics_fielddefs') . ': ' . $stats['fielddefs'] . '</li>';
echo '<li>' . $this->Lang('statistics_fieldvals') . ': ' . $stats['fieldvals'] . '</li>';
echo '</ul>';

// Migration form
echo $this->CreateFormStart($id, 'defaultadmin', $returnid, 'post', '', false, '', array('activetab' => 'migrate'));

// Instance selection
echo '<h3>' . $this->Lang('instance_selection') . '</h3>';

echo '<div class="pageoverflow">';
echo '<p class="pagetext">' . $this->Lang('instance_create_new') . ':</p>';
echo '<p class="pageinput">';
echo $this->CreateInputRadio($id, 'use_existing', 0, $use_existing == 0, 'onclick="toggleInstanceOptions()"');
echo '</p>';
echo '</div>';

echo '<div class="pageoverflow">';
echo '<p class="pagetext">' . $this->Lang('instance_use_existing') . ':</p>';
echo '<p class="pageinput">';
echo $this->CreateInputRadio($id, 'use_existing', 1, $use_existing == 1, 'onclick="toggleInstanceOptions()"');
echo '</p>';
echo '</div>';

// New instance options
echo '<div id="new_instance_options" style="display: ' . ($use_existing == 0 ? 'block' : 'none') . ';">';
echo '<div class="pageoverflow">';
echo '<p class="pagetext">' . $this->Lang('instance_name') . ' <strong>(optional)</strong>:</p>';
echo '<p class="pageinput">';
echo $this->CreateInputText($id, 'instance_name', $instance_name, 40, 255, '', 'id="instance_name_field" placeholder="Leave empty for auto-generated name"');
echo '<br><em>' . $this->Lang('instance_name_help') . '</em>';
echo '</p>';
echo '</div>';

echo '<div class="pageoverflow">';
echo '<p class="pagetext">' . $this->Lang('instance_friendlyname') . ' <strong>(optional)</strong>:</p>';
echo '<p class="pageinput">';
echo $this->CreateInputText($id, 'instance_friendlyname', $instance_friendlyname, 40, 255, '', 'id="instance_friendlyname_field" placeholder="Leave empty for auto-generated name"');
echo '<br><em>' . $this->Lang('instance_friendlyname_help') . '</em>';
echo '</p>';
echo '</div>';
echo '</div>';

// Existing instance options
echo '<div id="existing_instance_options" style="display: ' . ($use_existing == 1 ? 'block' : 'none') . ';">';
echo '<div class="pageoverflow">';
echo '<p class="pagetext">' . $this->Lang('instance_select') . ':</p>';
echo '<p class="pageinput">';
if (empty($lise_instances)) {
    echo '<p>' . $this->Lang('instance_none') . '</p>';
} else {
    $options = array();
    foreach ($lise_instances as $inst) {
        $display_name = $inst->module_name;
        if (method_exists($inst, 'GetFriendlyName')) {
            $display_name = $inst->GetFriendlyName();
        }
        $options[$inst->module_id] = $display_name;
    }
    echo $this->CreateInputDropdown($id, 'existing_instance_id', $options, -1, $existing_instance_id);
    echo '<br><em>' . $this->Lang('instance_select_help') . '</em>';
}
echo '</p>';
echo '</div>';
echo '</div>';

// Data selection
echo '<h3>' . $this->Lang('data_selection') . '</h3>';

echo '<div class="pageoverflow">';
echo '<p class="pagetext">' . $this->Lang('data_migrate_articles') . ':</p>';
echo '<p class="pageinput">';
echo $this->CreateInputCheckbox($id, 'migrate_articles', 1, $migrate_articles);
echo '</p>';
echo '</div>';

echo '<div class="pageoverflow">';
echo '<p class="pagetext">' . $this->Lang('data_migrate_categories') . ':</p>';
echo '<p class="pageinput">';
echo $this->CreateInputCheckbox($id, 'migrate_categories', 1, $migrate_categories);
echo '</p>';
echo '</div>';

echo '<div class="pageoverflow">';
echo '<p class="pagetext">' . $this->Lang('data_migrate_fielddefs') . ':</p>';
echo '<p class="pageinput">';
echo $this->CreateInputCheckbox($id, 'migrate_fielddefs', 1, $migrate_fielddefs);
echo '</p>';
echo '</div>';

echo '<div class="pageoverflow">';
echo '<p class="pagetext">' . $this->Lang('data_migrate_fieldvals') . ':</p>';
echo '<p class="pageinput">';
echo $this->CreateInputCheckbox($id, 'migrate_fieldvals', 1, $migrate_fieldvals);
echo '</p>';
echo '</div>';

// Action buttons
echo '<div class="pageoverflow">';
echo '<p class="pagetext">&nbsp;</p>';
echo '<p class="pageinput">';
echo $this->CreateInputSubmit($id, 'preview', $this->Lang('migrate_preview'));
echo ' ';
echo $this->CreateInputSubmit($id, 'migrate', $this->Lang('migrate_button'));
echo '</p>';
echo '</div>';

// Add confirmation JavaScript
echo '<script type="text/javascript">
document.addEventListener("DOMContentLoaded", function() {
    var migrateBtn = document.querySelector("input[name=\'m1_migrate\']");
    if (migrateBtn) {
        migrateBtn.addEventListener("click", function(e) {
            if (!confirm("' . addslashes($this->Lang('migrate_confirm')) . '")) {
                e.preventDefault();
                return false;
            }
        });
    }
});
</script>';

echo $this->CreateFormEnd();

// JavaScript for toggling instance options
echo '<script type="text/javascript">
function toggleInstanceOptions() {
    var useExisting = document.querySelector("input[name=\'m1_use_existing\'][value=\'1\']").checked;
    document.getElementById("new_instance_options").style.display = useExisting ? "none" : "block";
    document.getElementById("existing_instance_options").style.display = useExisting ? "block" : "none";
}
</script>';

?>

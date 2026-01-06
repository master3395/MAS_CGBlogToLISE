<?php
/**
 * English Language File for MAS_CGBlogToLISE Module
 */

// Module info
$lang['friendlyname'] = 'MAS CGBlog to LISE Migration';
$lang['moddescription'] = 'Migrates data from CGBlog module to LISE instances. Supports creating new LISE instances or copying to existing ones.';
$lang['postinstall'] = 'MAS CGBlog to LISE Migration has been installed successfully!';
$lang['postuninstall'] = 'MAS CGBlog to LISE Migration has been uninstalled.';
$lang['really_uninstall'] = 'Are you sure you want to uninstall MAS CGBlog to LISE Migration?';
$lang['installed'] = 'Module version %s installed successfully.';
$lang['upgraded'] = 'Module upgraded to version %s.';
$lang['uninstalled'] = 'Module uninstalled.';

// Permissions
$lang['accessdenied'] = 'Access denied. Please check your permissions.';
$lang['permission_use'] = 'Use MAS CGBlog to LISE Migration';

// Tabs
$lang['tab_migrate'] = 'Migration';
$lang['tab_settings'] = 'Settings';
$lang['tab_donations'] = 'Donations';
$lang['tab_adminsettings'] = 'Admin Settings';
$lang['configuration'] = 'Configuration';

// Settings
$lang['prompt_friendlyname'] = 'Module Friendly Name';
$lang['info_friendlyname'] = 'This specifies the string that will be used identify this module in the admin navigation.  If no value is specified, then the default value will be used.  You may have to clear the CMSMS cache after changing this value.';
$lang['show_donations'] = 'Show Donations Tab';
$lang['hidedonationssubmit'] = 'Hide donations tab';
$lang['donations_tab_hidden'] = 'Donations tab has been hidden. You can show it again in Admin Settings.';
$lang['donationstext'] = 'A lot of time and effort has been put into creating this module. Please consider a small donation (5€ for instance, or what you can spare) using the PayPal-button below, especially if you use this module in a commercial context. If you donate more than 30€ you can have a link to your company on this page, if you wish to. Send me an email about what you would like shown and I will put it in for the next version. Thank you!';
$lang['sponsors'] = 'Current sponsors, thank you for your support!';
$lang['showdonationstab'] = 'Show donations tab';
$lang['submit'] = 'Submit';
$lang['msg_prefs_saved'] = 'Preferences Saved';
$lang['settingsupdated'] = 'Settings updated successfully';
$lang['prefsupdated'] = 'Preferences updated';

// Migration Interface
$lang['migration_title'] = 'CGBlog to LISE Migration';
$lang['migration_description'] = 'This tool migrates data from CGBlog module to LISE instances.';
$lang['migration_prerequisites'] = 'Prerequisites';
$lang['migration_prerequisites_desc'] = 'Before starting migration, ensure:';
$lang['migration_prerequisites_cgblog'] = 'CGBlog module is installed';
$lang['migration_prerequisites_lise'] = 'LISE module is installed';
$lang['migration_prerequisites_data'] = 'CGBlog contains data to migrate';

// Statistics
$lang['statistics_title'] = 'CGBlog Statistics';
$lang['statistics_articles'] = 'Articles';
$lang['statistics_categories'] = 'Categories';
$lang['statistics_fielddefs'] = 'Custom Fields';
$lang['statistics_fieldvals'] = 'Field Values';

// Instance Selection
$lang['instance_selection'] = 'LISE Instance Selection';
$lang['instance_create_new'] = 'Create New LISE Instance';
$lang['instance_use_existing'] = 'Use Existing LISE Instance';
$lang['instance_name'] = 'Instance Name';
$lang['instance_name_help'] = 'Enter a name for the new LISE instance (alphanumeric only, no spaces). If left empty, a default name will be generated automatically.';
$lang['instance_friendlyname'] = 'Friendly Name';
$lang['instance_friendlyname_help'] = 'Enter a friendly display name for the instance. If left empty, a default name will be generated automatically.';
$lang['instance_select'] = 'Select Existing Instance';
$lang['instance_select_help'] = 'Choose an existing LISE instance to migrate data into.';
$lang['instance_none'] = 'No LISE instances available';

// Data Selection
$lang['data_selection'] = 'Data to Migrate';
$lang['data_migrate_articles'] = 'Migrate Articles';
$lang['data_migrate_categories'] = 'Migrate Categories';
$lang['data_migrate_fielddefs'] = 'Migrate Custom Fields';
$lang['data_migrate_fieldvals'] = 'Migrate Field Values';

// Preview
$lang['preview_title'] = 'Migration Preview';
$lang['preview_description'] = 'Review the data that will be migrated:';
$lang['preview_articles_count'] = 'Articles to migrate: %s';
$lang['preview_categories_count'] = 'Categories to migrate: %s';
$lang['preview_fielddefs_count'] = 'Field definitions to migrate: %s';
$lang['preview_fieldvals_count'] = 'Field values to migrate: %s';

// Migration Actions
$lang['migrate_button'] = 'Start Migration';
$lang['migrate_preview'] = 'Preview Migration';
$lang['migrate_cancel'] = 'Cancel';
$lang['migrate_confirm'] = 'Are you sure you want to start the migration? This action cannot be undone.';

// Migration Status
$lang['migration_started'] = 'Migration started...';
$lang['migration_in_progress'] = 'Migration in progress...';
$lang['migration_completed'] = 'Migration completed successfully!';
$lang['migration_failed'] = 'Migration failed. Please check the logs for details.';
$lang['migration_partial'] = 'Migration completed with warnings. Some data may not have been migrated.';

// Migration Steps
$lang['step_validation'] = 'Validating prerequisites...';
$lang['step_instance'] = 'Creating LISE instance...';
$lang['step_categories'] = 'Migrating categories...';
$lang['step_fielddefs'] = 'Migrating field definitions...';
$lang['step_articles'] = 'Migrating articles...';
$lang['step_fieldvals'] = 'Migrating field values...';
$lang['step_relationships'] = 'Migrating relationships...';
$lang['step_verification'] = 'Verifying migration...';

// Results
$lang['results_title'] = 'Migration Results';
$lang['results_articles_migrated'] = 'Articles migrated: %s';
$lang['results_categories_migrated'] = 'Categories migrated: %s';
$lang['results_fielddefs_migrated'] = 'Field definitions migrated: %s';
$lang['results_fieldvals_migrated'] = 'Field values migrated: %s';
$lang['results_errors'] = 'Errors: %s';
$lang['results_warnings'] = 'Warnings: %s';

// Errors
$lang['error_cgblog_not_installed'] = 'CGBlog module is not installed.';
$lang['error_lise_not_installed'] = 'LISE module is not installed.';
$lang['error_no_data'] = 'No CGBlog data found to migrate.';
$lang['error_instance_exists'] = 'LISE instance with this name already exists.';
$lang['error_instance_invalid'] = 'Invalid instance name. Use only alphanumeric characters.';
$lang['error_instance_not_found'] = 'Selected LISE instance not found.';
$lang['error_migration_failed'] = 'Migration failed: %s';
$lang['error_database'] = 'Database error: %s';
$lang['error_permission'] = 'Insufficient permissions to perform migration.';

// Warnings
$lang['warning_no_categories'] = 'No categories found in CGBlog.';
$lang['warning_no_fielddefs'] = 'No custom fields found in CGBlog.';
$lang['warning_no_articles'] = 'No articles found in CGBlog.';

// Help
$lang['help'] = '<div class="mas-migration-help">
<h3>MAS CGBlog to LISE Migration Module</h3>

<p><strong>MAS CGBlog to LISE Migration</strong> is a tool for migrating data from the CGBlog module to LISE instances in CMS Made Simple.</p>

<h4>Features</h4>
<ul>
    <li><strong>Create New Instances:</strong> Automatically create new LISE instances from CGBlog data</li>
    <li><strong>Use Existing Instances:</strong> Copy data to existing LISE instances</li>
    <li><strong>Selective Migration:</strong> Choose which data to migrate (articles, categories, custom fields)</li>
    <li><strong>Preview:</strong> Preview data before migration</li>
    <li><strong>Comprehensive Mapping:</strong> Automatically maps CGBlog data structures to LISE format</li>
    <li><strong>Error Handling:</strong> Detailed error reporting and logging</li>
</ul>

<h4>Getting Started</h4>
<ol>
    <li><strong>Prerequisites:</strong> Ensure both CGBlog and LISE modules are installed</li>
    <li><strong>Access Migration:</strong> Go to Extensions → MAS CGBlog to LISE Migration</li>
    <li><strong>Choose Instance:</strong> Create new or select existing LISE instance</li>
    <li><strong>Select Data:</strong> Choose which data types to migrate</li>
    <li><strong>Preview:</strong> Review what will be migrated</li>
    <li><strong>Migrate:</strong> Start the migration process</li>
</ol>

<h4>Data Mapping</h4>
<ul>
    <li><strong>Articles:</strong> CGBlog articles → LISE items</li>
    <li><strong>Categories:</strong> CGBlog categories → LISE categories (hierarchy preserved)</li>
    <li><strong>Custom Fields:</strong> CGBlog field definitions → LISE field definitions</li>
    <li><strong>Field Values:</strong> CGBlog field values → LISE item field values</li>
</ul>

<h4>Important Notes</h4>
<ul>
    <li>Migration cannot be undone automatically. Backup your data before migration.</li>
    <li>Field types are automatically mapped from CGBlog to LISE format.</li>
    <li>Category hierarchies are preserved during migration.</li>
    <li>Article URLs and metadata are preserved.</li>
</ul>
</div>';

// Changelog
$lang['changelog'] = '<ul>
<li><strong>Version 1.0.1, 06.01.26, master3395</strong>: Bug fixes and improvements
    <ul>
        <li>Fixed database column names (create_time/modified_time instead of create_date/modified_date)</li>
        <li>Fixed content storage - article content and summaries now correctly stored in fieldval table</li>
        <li>Added automatic creation of Content and Summary field definitions</li>
        <li>Fixed field definition inserts to use correct LISE schema (name, alias, help, type, position, required, template, extra)</li>
        <li>Removed duplicate statistics display in migration interface</li>
        <li>Added visible labels for radio buttons in instance selection</li>
        <li>Split MigrationEngine.php into smaller modules to comply with 500 line limit</li>
        <li>Added Start Migration button to preview section</li>
        <li>Fixed callback error for logging functionality</li>
    </ul>
</li>
<li><strong>Version 1.0.0, 2025, master3395</strong>: Initial release
    <ul>
        <li>Migrate articles from CGBlog to LISE</li>
        <li>Migrate categories with hierarchy preservation</li>
        <li>Migrate custom field definitions and values</li>
        <li>Create new LISE instances or use existing ones</li>
        <li>Preview migration data before executing</li>
        <li>Comprehensive error handling and logging</li>
    </ul>
</li>
</ul>';

// Audit log messages
$lang['audit_preview_migration'] = 'Previewed migration: %s articles, %s categories, %s field definitions, %s field values';
$lang['audit_start_migration'] = 'Started migration to LISE instance: %s';
$lang['audit_complete_migration'] = 'Completed migration: %s articles, %s categories, %s field definitions migrated';
$lang['audit_failed_migration'] = 'Migration failed: %s';

?>

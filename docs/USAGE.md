# Usage Guide

Complete guide for using the MAS CGBlog to LISE Migration module.

## Getting Started

### Accessing the Module

1. Log into your CMSMS admin panel
2. Navigate to **Extensions → MAS CGBlog to LISE Migration**
3. You'll see the Migration tab with prerequisites check

### Understanding the Interface

The module interface consists of three main tabs:

- **Migration**: Main migration interface
- **Admin Settings**: Module configuration
- **Donations**: Support information

## Prerequisites Check

Before starting a migration, the module checks:

- ✓ CGBlog module is installed
- ✓ LISE module is installed  
- ✓ CGBlog contains data to migrate

All prerequisites must be met before proceeding.

## Migration Options

### Option 1: Create New LISE Instance

Create a brand new LISE instance with your CGBlog data.

**Steps:**

1. Select **"Create New LISE Instance"**
2. **Instance Name** (optional):
   - Enter an alphanumeric name (no spaces)
   - Leave empty for auto-generation
   - Format: `CGBlogMigratedYYYYMMDDHHMMSS`
3. **Friendly Name** (optional):
   - Enter a display name
   - Leave empty for auto-generation
   - Format: `CGBlog Migrated - YYYY-MM-DD HH:MM:SS`
4. Select data types to migrate
5. Click **"Preview Migration"** or **"Start Migration"**

**Auto-Generation:**
- If instance name is empty, generates: `CGBlogMigrated20251128004648`
- If friendly name is empty, generates: `CGBlog Migrated - 2025-11-28 00:46:48`
- Automatically handles naming conflicts by appending numbers

### Option 2: Use Existing LISE Instance

Copy data to an existing LISE instance.

**Steps:**

1. Select **"Use Existing LISE Instance"**
2. Choose an instance from the dropdown
3. Select data types to migrate
4. Click **"Preview Migration"** or **"Start Migration"**

**Important:** This will add data to the existing instance. Ensure you want to merge data.

## Data Selection

Choose what to migrate:

- ☑ **Migrate Articles**: Migrates all CGBlog articles to LISE items
- ☑ **Migrate Categories**: Migrates categories with hierarchy
- ☑ **Migrate Custom Fields**: Migrates field definitions
- ☑ **Migrate Field Values**: Migrates field values for articles

You can select any combination of these options.

## Preview Migration

Always preview before migrating:

1. Click **"Preview Migration"** button
2. Review the statistics:
   - Articles to migrate: X
   - Categories to migrate: X
   - Field definitions to migrate: X
   - Field values to migrate: X
3. Click **"Cancel"** to go back and adjust settings
4. If satisfied, return and click **"Start Migration"**

## Starting Migration

1. Verify your selections
2. Click **"Start Migration"** button
3. Confirm the action (JavaScript confirmation)
4. Wait for migration to complete
5. Review results

### Migration Process

The migration follows these steps:

1. **Validation**: Checks prerequisites and validates input
2. **Instance Creation** (if new): Creates LISE instance
3. **Categories**: Migrates categories first (needed for relationships)
4. **Field Definitions**: Migrates custom field definitions
5. **Articles**: Migrates articles with category relationships
6. **Field Values**: Migrates custom field values
7. **Verification**: Confirms migration success

### Transaction Safety

- All migrations use database transactions
- On error, all changes are rolled back
- Original data remains unchanged

## Migration Results

After migration, you'll see:

- **Articles migrated**: Count of migrated articles
- **Categories migrated**: Count of migrated categories
- **Field definitions migrated**: Count of field definitions
- **Field values migrated**: Count of field values
- **Warnings** (if any): Non-critical issues encountered

### Checking Results

1. Review the results page after migration
2. Check CMSMS Admin Log for detailed logs
3. Verify data in your LISE instance
4. Check module log files if needed

## Data Mapping

Understanding how data is mapped:

### Articles → Items

| CGBlog Field | LISE Field |
|--------------|------------|
| `cgblog_title` | `title` |
| `cgblog_data` | `data` |
| `summary` | `summary` |
| `cgblog_date` | `create_date` |
| `status` | `active` (published=1, draft=0) |
| `url` | `url` |
| `author` | `author` |

### Categories

- Category hierarchy is preserved
- Parent-child relationships maintained
- Category order is preserved

### Custom Fields

- Field types are automatically mapped
- Field definitions are migrated first
- Field values are linked to articles

## Best Practices

### Before Migration

1. **Backup your database** - Essential safety measure
2. Test on development environment first
3. Verify CGBlog data is correct
4. Check available disk space

### During Migration

1. Use Preview feature before migrating
2. Don't close browser during migration
3. Monitor the process
4. Note any warnings

### After Migration

1. Verify migrated data
2. Check all relationships
3. Test frontend display
4. Review logs for any issues

## Troubleshooting

### Migration Fails

1. Check CMSMS Admin Log for errors
2. Verify database permissions
3. Check disk space
4. Review module log files

### Data Missing

1. Verify source data exists in CGBlog
2. Check migration results
3. Review warnings
4. Check category/article relationships

### Instance Creation Fails

1. Verify LISE module is working
2. Check for naming conflicts
3. Verify permissions
4. Check disk space

## Settings

### Admin Settings Tab

Configure module behavior:

- **Module Friendly Name**: Customize name in navigation
- **Show Donations Tab**: Toggle donations tab visibility

### Logging

All migrations are logged to:
- CMSMS Admin Log (Site Admin → Admin Log)
- Module log files (`modules/MAS_CGBlogToLISE/logs/`)

## Tips

- Start with a small test migration
- Use preview before large migrations
- Keep backups before each migration
- Document your migration settings
- Review logs regularly

## Advanced Usage

### Migrating Multiple Instances

You can create multiple LISE instances from the same CGBlog data:

1. First migration: Create instance "Blog2024"
2. Second migration: Create instance "BlogArchive"
3. Use different instance names each time

### Selective Data Migration

Migrate specific data types:

- Only categories: Uncheck articles and fields
- Only articles: Uncheck categories and fields
- Custom fields only: Uncheck articles and categories

This allows for flexible data organization.


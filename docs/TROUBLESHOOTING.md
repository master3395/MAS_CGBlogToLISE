# Troubleshooting Guide

Common issues and solutions for the MAS CGBlog to LISE Migration module.

## Quick Diagnosis

### Check Prerequisites First

Before troubleshooting, verify:

- [ ] CMS Made Simple 2.2.0 or higher
- [ ] PHP 7.4 to 8.6
- [ ] CGBlog module installed and active
- [ ] LISE module installed and active
- [ ] Both modules have data/content

## Common Issues

### Module Installation Issues

#### Module doesn't appear after upload

**Symptoms:**
- Uploaded via Module Manager but module doesn't show in list
- Module folder exists but isn't recognized

**Solutions:**

1. **Clear CMSMS Cache**
   - Go to **Site Admin → Global Settings → Clear Cache**
   - Refresh the module list

2. **Check File Permissions**
   ```bash
   chmod -R 755 modules/MAS_CGBlogToLISE/
   chmod 644 modules/MAS_CGBlogToLISE/*.php
   ```

3. **Verify Folder Structure**
   - Ensure folder is named exactly `MAS_CGBlogToLISE`
   - Check that `MAS_CGBlogToLISE.module.php` exists in root

4. **Check PHP Error Logs**
   - Review PHP error logs for syntax errors
   - Check CMSMS error logs

#### Installation fails

**Symptoms:**
- Installation process stops or errors
- Error messages during installation

**Solutions:**

1. **Check PHP Version**
   ```bash
   php -v
   ```
   Must be PHP 7.4 to 8.6

2. **Verify CMSMS Version**
   - Go to **Site Admin → Global Settings**
   - Check version (must be 2.2.0+)

3. **Check Database Permissions**
   - Verify database user has CREATE/ALTER permissions
   - Check database connection settings

4. **Review Error Logs**
   - Check CMSMS Admin Log
   - Review PHP error logs
   - Check module log files

### Prerequisites Issues

#### "CGBlog module is not installed"

**Symptoms:**
- Module shows CGBlog as not installed
- Cannot proceed with migration

**Solutions:**

1. **Verify CGBlog Installation**
   - Go to **Extensions → Module Manager**
   - Check if CGBlog is listed and installed
   - If not installed, install it first

2. **Check Module Activation**
   - Ensure CGBlog is activated
   - Check for any CGBlog errors

3. **Verify Module Files**
   - Check that CGBlog files exist in `modules/CGBlog/`
   - Verify CGBlog database tables exist

#### "LISE module is not installed"

**Symptoms:**
- Module shows LISE as not installed
- Cannot create LISE instances

**Solutions:**

1. **Verify LISE Installation**
   - Go to **Extensions → Module Manager**
   - Check if LISE is listed and installed
   - If not installed, install it first

2. **Check Module Activation**
   - Ensure LISE is activated
   - Verify LISE is working properly

3. **Verify Module Files**
   - Check that LISE files exist in `modules/LISE/`
   - Verify LISE database tables exist

### Migration Issues

#### "No CGBlog data found to migrate"

**Symptoms:**
- Module shows no articles or categories
- Preview shows zero items

**Solutions:**

1. **Verify CGBlog Has Data**
   - Check CGBlog module for articles
   - Verify categories exist
   - Check that data is published/active

2. **Check Database Connection**
   - Verify CMSMS can access CGBlog tables
   - Check database permissions

3. **Review Module Logs**
   - Check `modules/MAS_CGBlogToLISE/logs/` for errors
   - Review CMSMS Admin Log

#### Migration fails or stops

**Symptoms:**
- Migration starts but doesn't complete
- Error messages during migration
- Partial data migration

**Solutions:**

1. **Check Database Transactions**
   - Migration uses transactions
   - Check if transaction was rolled back
   - Review error messages

2. **Verify Disk Space**
   ```bash
   df -h
   ```
   Ensure sufficient disk space

3. **Check PHP Memory Limit**
   - Increase `memory_limit` in php.ini if needed
   - Large migrations may need more memory

4. **Review Error Logs**
   - Check CMSMS Admin Log for detailed errors
   - Review module log files
   - Check PHP error logs

5. **Database Lock Issues**
   - Ensure no other processes are using database
   - Check for long-running queries

#### "LISE instance with this name already exists"

**Symptoms:**
- Cannot create new instance
- Name conflict error

**Solutions:**

1. **Choose Different Name**
   - Enter a unique instance name
   - Use auto-generation (leave empty)

2. **Use Existing Instance**
   - Select "Use Existing LISE Instance"
   - Choose from dropdown

3. **Check Existing Instances**
   - Go to LISE module
   - Review existing instance names
   - Delete unused instances if needed

### Data Issues

#### Missing data after migration

**Symptoms:**
- Some articles didn't migrate
- Categories missing
- Field values not migrated

**Solutions:**

1. **Check Migration Results**
   - Review migration summary
   - Note any warnings or errors

2. **Verify Source Data**
   - Check CGBlog for missing items
   - Verify data is active/published

3. **Review Migration Logs**
   - Check what was actually migrated
   - Look for skipped items

4. **Check Data Selection**
   - Verify correct checkboxes were selected
   - Ensure all data types were chosen

#### Incorrect data mapping

**Symptoms:**
- Field values don't match
- Categories in wrong place
- Relationships broken

**Solutions:**

1. **Review Data Mapping**
   - Check [Data Mapping Guide](DATA_MAPPING.md)
   - Verify field type conversions

2. **Check Migration Order**
   - Categories should migrate first
   - Field definitions before values
   - Review migration logs

3. **Verify Source Data**
   - Check CGBlog data structure
   - Verify field types

### Performance Issues

#### Migration is very slow

**Symptoms:**
- Migration takes a long time
- Browser times out

**Solutions:**

1. **Increase PHP Timeout**
   ```php
   set_time_limit(0); // In php.ini or script
   ```

2. **Migrate in Batches**
   - Migrate categories first
   - Then articles
   - Then fields separately

3. **Optimize Database**
   - Run database optimization
   - Check for slow queries

4. **Check Server Resources**
   - Monitor CPU and memory usage
   - Check database performance

### Logging Issues

#### No logs generated

**Symptoms:**
- No log files created
- Admin log empty

**Solutions:**

1. **Check Log Directory Permissions**
   ```bash
   chmod 755 modules/MAS_CGBlogToLISE/logs/
   chown newst3922:newst3922 modules/MAS_CGBlogToLISE/logs/
   ```

2. **Verify Log Directory Exists**
   - Ensure `logs/` directory exists
   - Check it's writable

3. **Check CMSMS Logging**
   - Verify CMSMS Admin Log is enabled
   - Check log file permissions

## Getting Help

### Before Asking for Help

1. **Check Documentation**
   - Review [Usage Guide](USAGE.md)
   - Read [Installation Guide](INSTALLATION.md)
   - Check [Data Mapping Guide](DATA_MAPPING.md)

2. **Review Logs**
   - Check CMSMS Admin Log
   - Review module log files
   - Check PHP error logs

3. **Gather Information**
   - CMSMS version
   - PHP version
   - Module versions
   - Error messages
   - Log excerpts

### Support Channels

- **GitHub Issues**: [Open an issue](https://github.com/master3395/MAS_CGBlogToLISE/issues)
- **Email Support**: info@newstargeted.com
- **Documentation**: Check `docs/` folder

### Reporting Issues

When reporting issues, include:

1. **Environment Information**
   - CMSMS version
   - PHP version
   - Server OS
   - Module versions

2. **Error Details**
   - Exact error messages
   - When error occurs
   - Steps to reproduce

3. **Log Information**
   - Relevant log excerpts
   - Error stack traces
   - Migration statistics

4. **Screenshots**
   - Error screenshots
   - Configuration screenshots
   - Migration interface screenshots

## Prevention Tips

### Before Migration

- ✅ Always backup database first
- ✅ Test on development environment
- ✅ Verify prerequisites are met
- ✅ Check available disk space
- ✅ Review source data structure

### During Migration

- ✅ Use preview feature first
- ✅ Don't close browser during migration
- ✅ Monitor the process
- ✅ Note any warnings

### After Migration

- ✅ Verify migrated data
- ✅ Check all relationships
- ✅ Test frontend display
- ✅ Review logs for issues
- ✅ Keep backup until verified

## Advanced Troubleshooting

### Database Issues

If experiencing database-related issues:

1. **Check Database Connection**
   ```php
   // Test connection
   $db = cmsms()->GetDb();
   $result = $db->Execute("SELECT 1");
   ```

2. **Verify Table Structure**
   - Check CGBlog tables exist
   - Verify LISE tables exist
   - Check table permissions

3. **Review SQL Queries**
   - Check migration logs for SQL errors
   - Verify query syntax
   - Check for constraint violations

### Module Conflicts

If experiencing conflicts with other modules:

1. **Check Module Load Order**
   - Verify module dependencies
   - Check module initialization

2. **Review Module Hooks**
   - Check for hook conflicts
   - Verify event handlers

3. **Test in Isolation**
   - Disable other modules temporarily
   - Test migration alone

## Recovery Procedures

### Rollback Migration

If migration fails:

1. **Automatic Rollback**
   - Module uses transactions
   - Failed migrations auto-rollback
   - Original data unchanged

2. **Manual Cleanup**
   - Delete failed LISE instance
   - Remove partial data
   - Restore from backup if needed

### Restore from Backup

If data corruption occurs:

1. **Database Restore**
   - Restore from backup
   - Verify data integrity
   - Re-run migration if needed

2. **Selective Restore**
   - Restore specific tables
   - Preserve other data
   - Re-migrate affected data

## Additional Resources

- [Usage Guide](USAGE.md) - Complete usage instructions
- [Installation Guide](INSTALLATION.md) - Installation details
- [Data Mapping Guide](DATA_MAPPING.md) - Data mapping information
- [GitHub Repository](https://github.com/master3395/MAS_CGBlogToLISE) - Source code and issues


# Installation Guide

This guide provides detailed instructions for installing the MAS CGBlog to LISE Migration module.

## Prerequisites

Before installing the module, ensure you have:

- **CMS Made Simple 2.2.0** or higher
- **PHP 7.4** to **8.6**
- **CGBlog module** installed and configured
- **LISE module** installed and configured
- Administrative access to your CMSMS installation

## Installation Methods

### Method 1: Module Manager (Recommended)

This is the easiest method and recommended for most users.

1. **Download the XML file**
   - Go to the [Releases page](https://github.com/master3395/MAS_CGBlogToLISE/releases)
   - Download `MAS_CGBlogToLISE-1.0.0.xml`

2. **Upload via Module Manager**
   - Log into your CMSMS admin panel
   - Navigate to **Extensions → Module Manager**
   - Click **"Upload Module"**
   - Select the downloaded XML file
   - Click **"Upload"**

3. **Install the module**
   - After upload, the module should appear in the module list
   - Click **"Install"** next to MAS CGBlog to LISE Migration
   - Follow the installation prompts

### Method 2: Manual Installation

Use this method if you prefer manual installation or if Module Manager upload fails.

1. **Download the module ZIP**
   - Go to the [Releases page](https://github.com/master3395/MAS_CGBlogToLISE/releases)
   - Download `MAS_CGBlogToLISE-1.0.0.zip`

2. **Extract to modules directory**
   - Extract the ZIP file
   - Upload the `MAS_CGBlogToLISE` folder to your CMSMS `modules/` directory
   - Ensure the folder structure is: `modules/MAS_CGBlogToLISE/`

3. **Set correct permissions**
   ```bash
   chown -R newst3922:newst3922 modules/MAS_CGBlogToLISE/
   chmod -R 755 modules/MAS_CGBlogToLISE/
   chmod 644 modules/MAS_CGBlogToLISE/*.php
   ```

4. **Install via Module Manager**
   - Navigate to **Extensions → Module Manager**
   - Find "MAS CGBlog to LISE Migration" in the list
   - Click **"Install"**

## Post-Installation

After installation:

1. **Verify installation**
   - Go to **Extensions → MAS CGBlog to LISE Migration**
   - You should see the migration interface

2. **Check prerequisites**
   - The module will display if CGBlog and LISE modules are installed
   - Verify both modules show as "Installed"

3. **Set permissions** (if needed)
   - The module requires "Modify Site Preferences" permission
   - Admin users typically have this by default

## Verification

To verify the installation:

1. Check that the module appears in **Extensions** menu
2. Access the module and verify prerequisites are met
3. Check the CMSMS Admin Log for installation confirmation

## Troubleshooting Installation

### Module doesn't appear after upload

- Clear CMSMS cache: **Site Admin → Global Settings → Clear Cache**
- Check file permissions on the module directory
- Verify PHP error logs for any issues

### Installation fails

- Check PHP version (requires 7.4+)
- Verify CMSMS version (requires 2.2.0+)
- Check file permissions
- Review CMSMS error logs

### Module shows errors

- Ensure CGBlog and LISE modules are installed first
- Check that all files uploaded correctly
- Verify database permissions

## Uninstallation

To uninstall the module:

1. Navigate to **Extensions → Module Manager**
2. Find "MAS CGBlog to LISE Migration"
3. Click **"Uninstall"**
4. Confirm uninstallation

**Note**: Uninstallation does not remove log files. If you want to completely remove the module, manually delete the `MAS_CGBlogToLISE` folder after uninstallation.

## Next Steps

After successful installation:

1. Read the [Usage Guide](USAGE.md)
2. Review the module settings
3. Perform a test migration on a development environment


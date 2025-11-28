# Download Instructions

This document provides download links and instructions for the MAS CGBlog to LISE Migration module.

## Download Links

### Module XML File (Recommended for Module Manager)

**File**: `MAS_CGBlogToLISE-1.0.0.xml`

**Direct Download**: [MAS_CGBlogToLISE-1.0.0.xml](../MAS_CGBlogToLISE-1.0.0.xml)

**GitHub Release**: [Releases Page](https://github.com/master3395/MAS_CGBlogToLISE/releases)

**Usage**: Upload this file via CMSMS Module Manager (Extensions → Module Manager → Upload Module)

### Complete Module ZIP (For Manual Installation)

**File**: `MAS_CGBlogToLISE-1.0.0.zip`

**GitHub Release**: [Releases Page](https://github.com/master3395/MAS_CGBlogToLISE/releases)

**Usage**: Extract to your CMSMS `modules/` directory

## Installation Methods

### Method 1: Module Manager (Easiest)

1. Download `MAS_CGBlogToLISE-1.0.0.xml`
2. Go to **Extensions → Module Manager → Upload Module**
3. Select the XML file and upload
4. Click **Install** when it appears in the module list

### Method 2: Manual Installation

1. Download `MAS_CGBlogToLISE-1.0.0.zip`
2. Extract the ZIP file
3. Upload the `MAS_CGBlogToLISE` folder to `modules/` directory
4. Set permissions (if needed):
   ```bash
   chown -R newst3922:newst3922 modules/MAS_CGBlogToLISE/
   chmod -R 755 modules/MAS_CGBlogToLISE/
   ```
5. Go to **Extensions → Module Manager** and install

## Version Information

**Current Version**: 1.0.0  
**Release Date**: 2025-11-28  
**CMSMS Compatibility**: 2.2.0+  
**PHP Compatibility**: 7.4 to 8.6

## Verification

After downloading:

1. Check file size matches expected size
2. Verify file integrity (no corruption)
3. For ZIP: Extract and verify folder structure
4. For XML: Check it's valid XML format

## File Locations

### XML File Location

The XML file is located in the module root:
- Module root: `modules/MAS_CGBlogToLISE/MAS_CGBlogToLISE-1.0.0.xml`
- Download directly from GitHub repository root

### ZIP File Location

The ZIP file is created from the module directory and excludes:
- Log files (`logs/*.log`)
- Git files (`.git/*`)
- Temporary files

## GitHub Repository

**Repository**: https://github.com/master3395/MAS_CGBlogToLISE

**Releases**: https://github.com/master3395/MAS_CGBlogToLISE/releases

## Troubleshooting Downloads

### XML File Won't Upload

- Verify file size is correct
- Check file permissions
- Try downloading again
- Clear browser cache

### ZIP File Won't Extract

- Verify file downloaded completely
- Check file size matches expected
- Try downloading again
- Use different extraction tool

## Support

For download issues:
- Check GitHub repository for latest version
- Verify you have correct permissions
- Contact: info@newstargeted.com


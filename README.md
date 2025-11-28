# MAS CGBlog to LISE Migration Module

A powerful CMS Made Simple module for migrating data from the CGBlog module to LISE instances.

[![CMS Made Simple](https://img.shields.io/badge/CMSMS-2.2.0+-green.svg)](https://www.cmsmadesimple.org/)
[![PHP Version](https://img.shields.io/badge/PHP-7.4--8.6-blue.svg)](https://www.php.net/)
[![License](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](LICENSE)

## Overview

**MAS CGBlog to LISE Migration** simplifies the process of migrating your blog data from CGBlog to LISE (List Item Search Engine) instances in CMS Made Simple. Whether you're upgrading your content management system or consolidating your data, this module provides a seamless migration experience with comprehensive data mapping, preview capabilities, and detailed logging.

## Features

- ✨ **Create New Instances**: Automatically create new LISE instances from CGBlog data
- 📋 **Use Existing Instances**: Copy data to existing LISE instances
- 🎯 **Selective Migration**: Choose which data to migrate (articles, categories, custom fields)
- 👁️ **Preview**: Preview data before migration to ensure accuracy
- 🔄 **Comprehensive Mapping**: Automatically maps CGBlog data structures to LISE format
- 🛡️ **Error Handling**: Detailed error reporting and logging to CMSMS Admin Log
- 🔒 **Transaction Support**: Rollback on failure for data safety
- 📊 **Statistics**: View migration statistics and results

## Requirements

- CMS Made Simple 2.2.0 or higher
- PHP 7.4 to 8.6
- CGBlog module installed and configured
- LISE module installed and configured

## Installation

### Method 1: Using Module Manager (Recommended)

1. Download the latest release from the [Releases](https://github.com/master3395/MAS_CGBlogToLISE/releases) page
2. Upload the `MAS_CGBlogToLISE-1.0.0.xml` file via **Extensions → Module Manager → Upload Module**
3. The module will be automatically installed and configured

### Method 2: Manual Installation

1. Download the module ZIP file from [Releases](https://github.com/master3395/MAS_CGBlogToLISE/releases)
2. Extract the contents to your CMSMS `modules/` directory
3. Ensure the folder is named `MAS_CGBlogToLISE`
4. Go to **Extensions → Module Manager** and install the module

### Download Links

- **Module XML File**: [MAS_CGBlogToLISE-1.0.0.xml](MAS_CGBlogToLISE-1.0.0.xml) - Direct download for Module Manager upload
- **Complete Module ZIP**: Available in the [Releases](https://github.com/master3395/MAS_CGBlogToLISE/releases) page - For manual installation

**Note**: The XML file is also included in the ZIP file, but is available as a standalone download for easy Module Manager upload.

## Quick Start

1. **Prerequisites**: Ensure both CGBlog and LISE modules are installed and configured
2. **Access Module**: Navigate to **Extensions → MAS CGBlog to LISE Migration**
3. **Choose Instance**: Select to create a new LISE instance or use an existing one
4. **Select Data**: Choose which data types to migrate (articles, categories, custom fields)
5. **Preview**: Review what will be migrated using the preview feature
6. **Migrate**: Start the migration process

## Data Mapping

The module automatically maps data structures between CGBlog and LISE:

| CGBlog | LISE |
|--------|------|
| Articles | Items |
| Categories | Categories (hierarchy preserved) |
| Custom Fields | Field Definitions |
| Field Values | Item Field Values |

### Field Type Mapping

- `textbox` → `text`
- `textarea` → `textarea`
- `checkbox` → `checkbox`
- `file` → `file`
- `image` → `image`
- `file_select` → `file_select`
- `image_select` → `image_select`

## Usage

### Creating a New LISE Instance

1. Select **"Create New LISE Instance"**
2. Optionally enter an instance name (auto-generated if left empty)
3. Optionally enter a friendly name (auto-generated if left empty)
4. Select data types to migrate
5. Click **"Preview Migration"** to review
6. Click **"Start Migration"** to execute

### Using an Existing LISE Instance

1. Select **"Use Existing LISE Instance"**
2. Choose an instance from the dropdown
3. Select data types to migrate
4. Click **"Preview Migration"** to review
5. Click **"Start Migration"** to execute

### Instance Name Auto-Generation

If you leave the instance name field empty, the module will automatically generate a unique name using the format:
- Format: `CGBlogMigratedYYYYMMDDHHMMSS`
- Example: `CGBlogMigrated20251128004648`

If a conflict occurs, a number will be appended automatically.

## Migration Process

1. **Validation**: Validates prerequisites and data integrity
2. **Instance Creation**: Creates new LISE instance (if applicable)
3. **Category Migration**: Migrates categories with hierarchy preservation
4. **Field Definition Migration**: Migrates custom field definitions
5. **Article Migration**: Migrates articles/items with relationships
6. **Field Value Migration**: Migrates custom field values
7. **Verification**: Verifies migration success and generates report

All steps are logged to the CMSMS Admin Log for auditing purposes.

## Logging

The module provides comprehensive logging:

- **CMSMS Admin Log**: All migration activities are logged to **Site Admin → Admin Log**
- **Module Log Files**: Detailed logs are stored in `modules/MAS_CGBlogToLISE/logs/`

Log entries include:
- Migration start/completion
- Number of items migrated
- Errors and warnings
- Instance creation details

## Settings

The module includes configurable settings:

- **Module Friendly Name**: Customize the module name in admin navigation
- **Donations Tab**: Show/hide the donations tab

Access settings via **Extensions → MAS CGBlog to LISE Migration → Admin Settings**

## Important Notes

⚠️ **Before Migration**:
- **Backup your database** - Migration cannot be undone automatically
- Verify CGBlog and LISE modules are properly installed
- Test on a development environment first if possible

✅ **During Migration**:
- Category hierarchies are preserved
- Article URLs and metadata are maintained
- Field types are automatically mapped
- All relationships are maintained

## Troubleshooting

### Common Issues

**"CGBlog module is not installed"**
- Ensure CGBlog module is installed and activated

**"LISE module is not installed"**
- Ensure LISE module is installed and activated

**"No CGBlog data found to migrate"**
- Verify your CGBlog module contains articles or categories

**"LISE instance with this name already exists"**
- Choose a different instance name or use an existing instance

### Getting Help

- Check the [Documentation](docs/) folder for detailed guides
- Review logs in `modules/MAS_CGBlogToLISE/logs/`
- Check CMSMS Admin Log for error details

## Contributing

Contributions are welcome! Please read our [Contributing Guidelines](CONTRIBUTING.md) before submitting pull requests.

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for a detailed list of changes.

### Version 1.0.0 (2025-11-28)

- Initial release
- Migrate articles from CGBlog to LISE
- Migrate categories with hierarchy preservation
- Migrate custom field definitions and values
- Create new LISE instances or use existing ones
- Preview migration data before executing
- Comprehensive error handling and logging
- Auto-generation of instance names
- CMSMS Admin Log integration

## License

This module is licensed under the [GPL v3 License](LICENSE).

## Author

**master3395**
- Email: info@newstargeted.com
- Website: [News Targeted](https://newstargeted.com)

## Support

For issues, feature requests, or questions:
- Open an issue on [GitHub](https://github.com/master3395/MAS_CGBlogToLISE/issues)
- Contact: info@newstargeted.com

## Acknowledgments

Built for the CMS Made Simple community. Thank you for using MAS CGBlog to LISE Migration!

---

**Made with ❤️ for CMS Made Simple**


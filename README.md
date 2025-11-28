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

## Quick Start

1. **Install the module** - See [Installation Guide](docs/INSTALLATION.md)
2. **Access Module** - Navigate to **Extensions → MAS CGBlog to LISE Migration**
3. **Choose Instance** - Select to create a new LISE instance or use an existing one
4. **Select Data** - Choose which data types to migrate
5. **Preview** - Review what will be migrated
6. **Migrate** - Start the migration process

For detailed instructions, see the [Usage Guide](docs/USAGE.md).

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

For detailed installation instructions, see the [Installation Guide](docs/INSTALLATION.md).

## Documentation

Comprehensive documentation is available in the `docs/` folder:

- **[Installation Guide](docs/INSTALLATION.md)** - Detailed installation instructions
- **[Usage Guide](docs/USAGE.md)** - Complete usage documentation
- **[Data Mapping Guide](docs/DATA_MAPPING.md)** - How data is mapped from CGBlog to LISE
- **[Troubleshooting Guide](docs/TROUBLESHOOTING.md)** - Common issues and solutions
- **[Download Instructions](docs/DOWNLOAD.md)** - Download links and instructions

## Data Mapping

The module automatically maps data structures between CGBlog and LISE:

| CGBlog | LISE |
|--------|------|
| Articles | Items |
| Categories | Categories (hierarchy preserved) |
| Custom Fields | Field Definitions |
| Field Values | Item Field Values |

For detailed mapping information, see the [Data Mapping Guide](docs/DATA_MAPPING.md).

## Important Notes

### Before Migration

⚠️ **Important**:

- **Backup your database** - Migration cannot be undone automatically
- Verify CGBlog and LISE modules are properly installed
- Test on a development environment first if possible

### During Migration

✅ **What's Preserved**:

- Category hierarchies are preserved
- Article URLs and metadata are maintained
- Field types are automatically mapped
- All relationships are maintained

## Troubleshooting

For common issues and solutions, see the [Troubleshooting Guide](docs/TROUBLESHOOTING.md).

### Quick Help

- **"CGBlog module is not installed"** - Ensure CGBlog module is installed and activated
- **"LISE module is not installed"** - Ensure LISE module is installed and activated
- **"No CGBlog data found to migrate"** - Verify your CGBlog module contains articles or categories
- **"LISE instance with this name already exists"** - Choose a different instance name or use an existing instance

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

### master3395

- Email: [info@newstargeted.com](mailto:info@newstargeted.com)
- Website: [News Targeted](https://newstargeted.com)

## Support

For issues, feature requests, or questions:

- Open an issue on [GitHub](https://github.com/master3395/MAS_CGBlogToLISE/issues)
- Contact: [info@newstargeted.com](mailto:info@newstargeted.com)
- Check the [Documentation](docs/) folder for detailed guides

## Acknowledgments

### CMS Made Simple Community

Built for the CMS Made Simple community. Thank you for using MAS CGBlog to LISE Migration!

---

### Made with ❤️ for CMS Made Simple

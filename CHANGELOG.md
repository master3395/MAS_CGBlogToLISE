# Changelog

All notable changes to the MAS CGBlog to LISE Migration module will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2025-11-28

### Added

- Initial release of MAS CGBlog to LISE Migration module
- Migration of articles from CGBlog to LISE instances
- Migration of categories with hierarchy preservation
- Migration of custom field definitions and values
- Support for creating new LISE instances
- Support for using existing LISE instances
- Preview functionality to review migration data before executing
- Comprehensive error handling and logging
- Auto-generation of instance names when not provided
- Auto-generation of friendly names when not provided
- Automatic conflict resolution for instance names
- CMSMS Admin Log integration for all migration activities
- Module log files for detailed debugging
- Transaction support for data safety (rollback on failure)
- Statistics display showing CGBlog data counts
- Migration results with detailed counts
- Warning system for non-critical issues
- Admin Settings tab for module configuration
- Donations tab for supporting the module
- Help system integrated with CMSMS help framework
- Permission system (requires "Modify Site Preferences")
- Prerequisites validation before migration
- Data integrity checks
- Field type mapping from CGBlog to LISE format
- Category relationship preservation
- Article metadata preservation
- URL and alias preservation

### Security

- Input validation and sanitization
- SQL injection prevention (prepared statements)
- Permission checks for all actions
- Secure file operations

### Technical Details

- Compatible with CMSMS 2.2.0+
- Compatible with PHP 7.4 to 8.6
- Lazy loading enabled for better performance
- Module files organized (under 500 lines per file)
- Comprehensive error handling with try-catch blocks
- Logging to both file and CMSMS Admin Log

---

## Version History

### Version 1.0.0 (2025-11-28)

**Initial Release**

Features:
- Migrate articles from CGBlog to LISE
- Migrate categories with hierarchy preservation
- Migrate custom field definitions and values
- Create new LISE instances or use existing ones
- Preview migration data before executing
- Comprehensive error handling and logging
- Auto-generation of instance names
- CMSMS Admin Log integration

---

**Note**: For detailed migration logs, check:
- CMSMS Admin Log: Site Admin → Admin Log
- Module Logs: `modules/MAS_CGBlogToLISE/logs/`


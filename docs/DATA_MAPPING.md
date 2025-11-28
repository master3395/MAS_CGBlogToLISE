# Data Mapping Guide

This document explains how data is mapped from CGBlog to LISE during migration.

## Overview

The MAS CGBlog to LISE Migration module automatically maps data structures between CGBlog and LISE formats, preserving relationships, hierarchies, and field types.

## Core Data Mapping

| CGBlog Structure | LISE Structure | Notes |
|------------------|----------------|-------|
| Articles | Items | All article data is migrated to LISE items |
| Categories | Categories | Hierarchy and relationships preserved |
| Custom Fields | Field Definitions | Field types are automatically converted |
| Field Values | Item Field Values | Linked to migrated items |

## Article to Item Mapping

### Standard Fields

| CGBlog Field | LISE Field | Transformation |
|--------------|------------|----------------|
| `cgblog_title` | `title` | Direct copy |
| `cgblog_data` | `data` | Direct copy (content) |
| `summary` | `summary` | Direct copy |
| `cgblog_date` | `create_date` | Date format preserved |
| `status` | `active` | `published` → `1`, `draft` → `0` |
| `url` | `url` | URL structure maintained |
| `author` | `author` | Author information preserved |
| `modified_date` | `modified_date` | Modification timestamp |
| `start_time` | `start_time` | Publication start time |
| `end_time` | `end_time` | Publication end time |

### Metadata Preservation

- Article IDs are preserved where possible
- SEO metadata (meta description, keywords) is maintained
- Publication dates and times are preserved
- Author information is maintained

## Category Mapping

### Hierarchy Preservation

Categories maintain their parent-child relationships:

```
CGBlog Structure:
├── Technology (parent)
│   ├── Software (child)
│   └── Hardware (child)
└── News (parent)
    └── Breaking News (child)

LISE Structure (preserved):
├── Technology (parent)
│   ├── Software (child)
│   └── Hardware (child)
└── News (parent)
    └── Breaking News (child)
```

### Category Properties

| CGBlog Property | LISE Property | Notes |
|-----------------|---------------|-------|
| Category name | Category name | Direct copy |
| Parent category | Parent category | Relationship maintained |
| Category order | Category order | Ordering preserved |
| Category description | Category description | If available |

## Custom Field Type Mapping

The module automatically converts CGBlog field types to compatible LISE field types:

| CGBlog Field Type | LISE Field Type | Conversion Notes |
|-------------------|-----------------|-------------------|
| `textbox` | `text` | Single-line text input |
| `textarea` | `textarea` | Multi-line text input |
| `checkbox` | `checkbox` | Boolean checkbox |
| `file` | `file` | File upload field |
| `image` | `image` | Image upload field |
| `file_select` | `file_select` | File selection dropdown |
| `image_select` | `image_select` | Image selection dropdown |
| `dropdown` | `dropdown` | Dropdown selection |
| `radio` | `radio` | Radio button group |
| `multiselect` | `multiselect` | Multiple selection |

### Field Definition Mapping

| CGBlog Field Definition | LISE Field Definition | Notes |
|-------------------------|----------------------|-------|
| Field name | Field name | Direct copy |
| Field type | Field type | Converted per mapping table |
| Field label | Field label | Display label preserved |
| Default value | Default value | Default values maintained |
| Required flag | Required flag | Validation rules preserved |
| Help text | Help text | User guidance text |

## Field Value Mapping

### Value Preservation

- Field values are linked to their corresponding items
- Data types are preserved (text, numbers, dates)
- File and image references are maintained
- Multi-value fields are properly handled

### Value Linking

```
CGBlog Article → LISE Item
├── Article ID: 123
│   ├── Field: "Author Bio" → Value: "John Doe"
│   ├── Field: "Featured Image" → Value: "/uploads/image.jpg"
│   └── Field: "Tags" → Value: ["tech", "news"]
```

## Relationship Preservation

### Article-Category Relationships

- Articles maintain their category assignments
- Multiple category assignments are preserved
- Category hierarchies are respected

### Article-Field Relationships

- Custom field values remain linked to articles
- Field definitions are created before values
- All relationships are validated during migration

## Data Integrity

### Validation During Migration

1. **Field Type Validation**: Ensures field types are compatible
2. **Relationship Validation**: Verifies all relationships exist
3. **Data Type Validation**: Confirms data types match field definitions
4. **Reference Validation**: Checks file/image references are valid

### Error Handling

- Invalid field types are logged as warnings
- Missing relationships are reported
- Data type mismatches are handled gracefully
- Missing file references are noted

## Special Cases

### Date and Time Fields

- Dates are preserved in their original format
- Time zones are maintained
- Date ranges (start/end times) are preserved

### File and Image Fields

- File paths are preserved
- File references are maintained
- Missing files are logged but don't stop migration

### Multi-Value Fields

- Arrays are properly converted
- Multiple selections are preserved
- Comma-separated values are handled

## Migration Order

To maintain data integrity, migration follows this order:

1. **Categories** (first) - Required for article relationships
2. **Field Definitions** (second) - Required for field values
3. **Articles/Items** (third) - Links to categories
4. **Field Values** (last) - Links to items and field definitions

This order ensures all dependencies exist before they're referenced.

## Post-Migration Verification

After migration, verify:

- [ ] All articles migrated successfully
- [ ] Category hierarchies are correct
- [ ] Field definitions match original types
- [ ] Field values are linked correctly
- [ ] Relationships are preserved
- [ ] No data loss occurred

## Custom Mapping

For advanced users, the module's mapping can be extended by modifying the `DataMapper` class in `lib/class.DataMapper.php`.

## Examples

### Example 1: Simple Article Migration

**CGBlog Article:**
- Title: "Getting Started with CMSMS"
- Content: "This is a guide..."
- Category: "Tutorials"
- Author: "John Doe"
- Published: Yes

**LISE Item (after migration):**
- Title: "Getting Started with CMSMS"
- Data: "This is a guide..."
- Category: "Tutorials"
- Author: "John Doe"
- Active: 1

### Example 2: Article with Custom Fields

**CGBlog Article:**
- Title: "Product Review"
- Custom Field: "Rating" (dropdown) = "5 stars"
- Custom Field: "Reviewer" (textbox) = "Jane Smith"

**LISE Item (after migration):**
- Title: "Product Review"
- Field: "Rating" (dropdown) = "5 stars"
- Field: "Reviewer" (text) = "Jane Smith"

## Troubleshooting Mapping Issues

### Field Type Mismatches

If a field type cannot be mapped:
- Check the field type in CGBlog
- Verify it's in the mapping table
- Review migration logs for warnings

### Missing Relationships

If relationships are missing:
- Verify categories were migrated first
- Check field definitions were created
- Review migration order in logs

### Data Loss

If data appears to be lost:
- Check migration logs for errors
- Verify source data exists
- Review field mappings
- Check for validation errors

## Additional Resources

- [Usage Guide](USAGE.md) - How to use the migration module
- [Installation Guide](INSTALLATION.md) - Installation instructions
- [Troubleshooting Guide](TROUBLESHOOTING.md) - Common issues and solutions


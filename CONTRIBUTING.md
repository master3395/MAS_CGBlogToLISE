# Contributing to MAS CGBlog to LISE Migration

Thank you for your interest in contributing to MAS CGBlog to LISE Migration! This document provides guidelines and instructions for contributing.

## Code of Conduct

- Be respectful and considerate
- Welcome newcomers and help them learn
- Focus on what is best for the community
- Show empathy towards other community members

## How to Contribute

### Reporting Bugs

Before creating a bug report:

1. Check if the issue has already been reported
2. Test on the latest version
3. Check the documentation

When creating a bug report:

- Use a clear and descriptive title
- Provide detailed steps to reproduce
- Include environment information (CMSMS version, PHP version, etc.)
- Include error messages and logs
- Add screenshots if applicable

### Suggesting Features

Feature suggestions are welcome! When suggesting:

- Check if the feature has been suggested before
- Explain the use case
- Describe how it would work
- Consider backward compatibility

### Pull Requests

1. **Fork the repository**
2. **Create a feature branch**: `git checkout -b feature/amazing-feature`
3. **Make your changes**
4. **Follow coding standards** (see below)
5. **Test thoroughly**
6. **Update documentation**
7. **Commit with clear messages**
8. **Push to your fork**
9. **Create a Pull Request**

## Coding Standards

### PHP Code

- PHP 7.4 to 8.6 compatible
- Follow PSR-12 coding style
- Use meaningful variable and function names
- Add comments for complex logic
- Maximum 500 lines per file (split into modules if needed)

### File Organization

- Keep files under 500 lines
- Split large files into modules
- Store modules in `lib/` or appropriate subdirectories
- Use clear, descriptive file names

### Error Handling

- Always use try-catch blocks
- Log errors appropriately
- Never expose sensitive information in errors
- Provide user-friendly error messages

### Security

- Validate all user input
- Use prepared statements for database queries
- Never hardcode credentials
- Store sensitive data in config.php (following project rules)
- Sanitize output

### Database

- Use CMSMS database abstraction layer
- Use transactions for multi-step operations
- Always use prepared statements
- Handle errors gracefully

## Development Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/master3395/MAS_CGBlogToLISE.git
   ```

2. **Set up development environment**
   - CMSMS 2.2.0+ installation
   - PHP 7.4 to 8.6
   - CGBlog module installed
   - LISE module installed

3. **Create a development branch**
   ```bash
   git checkout -b dev/your-feature-name
   ```

4. **Make your changes**

5. **Test thoroughly**

## Testing

Before submitting a PR:

- [ ] Test on CMSMS 2.2.0+
- [ ] Test with PHP 7.4 and 8.6
- [ ] Test migration with sample data
- [ ] Verify error handling
- [ ] Check admin log entries
- [ ] Test with new and existing instances
- [ ] Verify no PHP errors or warnings
- [ ] Check browser console for errors

## Documentation

When adding features:

- Update README.md if needed
- Update relevant docs/ files
- Update help.inc if applicable
- Update language files if adding new strings
- Add code comments for complex logic

## Commit Messages

Use clear, descriptive commit messages:

```
Add feature: Auto-generate instance names

- Implement automatic instance name generation
- Handle naming conflicts
- Add validation
```

Format:
- Start with action verb (Add, Fix, Update, Remove)
- Keep under 50 characters for title
- Add details in body if needed

## Pull Request Process

1. **Update documentation** as needed
2. **Add tests** if applicable
3. **Ensure all tests pass**
4. **Follow the PR template**
5. **Request review**

## Module Structure

```
MAS_CGBlogToLISE/
├── action.defaultadmin.php    # Main admin interface
├── function.admin_migratetab.php  # Migration tab content
├── function.admin_settings.php    # Settings tab
├── function.donations.php         # Donations tab
├── lib/
│   ├── class.MigrationEngine.php  # Core migration logic
│   ├── class.DataMapper.php       # Data mapping utilities
│   └── class.MigrationValidator.php # Validation logic
├── lang/
│   └── en_US.php                  # Language strings
├── templates/
│   └── donations.tpl              # Donations template
└── docs/                          # Documentation
```

## Questions?

If you have questions:

- Open an issue for discussion
- Check existing issues and PRs
- Review the documentation

Thank you for contributing! 🎉


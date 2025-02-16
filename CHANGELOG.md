# Auto External Link Modifier - Changelog

## [1.1.1] - 2024-02-16

### Security Improvements
- Added nonce verification for forms
  - Settings form protection
  - AJAX request validation
- Enhanced input sanitization
  - Domain input validation
  - REL attribute sanitization
- Improved error handling and logging
  - Detailed error messages
  - WordPress debug integration
  - Custom error handlers

### Performance Optimizations
- Added caching system for processed content
  - Content-based cache keys
  - Automatic cache invalidation
  - Smart cache expiration
- Optimized DOM processing
  - Reduced memory usage
  - Faster link processing
  - Better UTF-8 handling
- Error recovery mechanisms
  - Graceful fallback options
  - Self-healing processes
  - Clean error reporting

### Documentation
- Converted all documentation to English
- Added security best practices
- Added performance guidelines
- Updated installation instructions
- Enhanced code comments
- Improved inline documentation

## [1.1.0] - 2024-02-16

### Added Features
- Widget and block content support
  - Link processing in widget text
  - Link processing in Gutenberg block content
  - Dynamic widget updates
- Custom domain list management
  - Add/remove domains from admin panel
  - Automatic domain format validation
  - Real-time validation
- Advanced settings page
  - Modern and user-friendly interface
  - Enhanced experience with CSS and JavaScript
  - Visual feedback

### Improvements
- Performance optimizations
  - Enhanced DOM processing
  - Skip unnecessary operations
  - Minified CSS/JS
- Added error handling system
  - Detailed error logs
  - WordPress debug mode integration
  - Error recovery
- Updated language files
  - Added new strings
  - Updated Turkish translations
  - Improved string contexts

### Changes
- Renewed admin panel interface
- Updated REL attribute descriptions
- Optimized settings page layout
- Enhanced user experience

## [1.0.0] - 2024-02-16

### Initial Release
- External link processing in WordPress posts and pages
- REL attribute management
  - noopener
  - noreferrer
  - nofollow
  - sponsored
  - ugc
- Official domain protection
  - Global government domains
  - Global education domains
  - Military domains
  - Country specific domains
- WordPress admin panel integration
- Turkish language support

### Core Features
- Automatic link detection
- Target attribute addition
- Domain exclusion system
- Settings management
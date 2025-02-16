# Auto External Link Modifier

A WordPress plugin that adds custom 'rel' attributes and 'target="_blank"' to external links in posts and pages. It modifies all links except internal links and official institution websites.

## ✅ Features

1. Advanced Domain Management:
   - ✅ Wildcard domain support (*.example.com)
   - ✅ Regex pattern matching
   - ✅ Bulk domain operations
   - ✅ Domain import/export
   - ✅ Pattern validation

2. Link Processing:
   - ✅ Process WordPress post and page content
   - ✅ Process widget and block content
   - ✅ Add target="_blank" to external links
   - ✅ Automatically exclude internal links
   - ✅ Exclude official institution websites

3. Security Features:
   - ✅ Nonce verification for forms
   - ✅ Input sanitization
   - ✅ XSS protection
   - ✅ Secure domain validation
   - ✅ Error logging and monitoring

4. Performance Features:
   - ✅ Content caching system
   - ✅ Optimized DOM processing
   - ✅ Smart cache invalidation
   - ✅ Error recovery mechanisms
   - ✅ Skip unnecessary processing

5. REL Attributes Management:
   - ✅ Easy management from WordPress admin panel
   - ✅ Customizable REL attributes:
     - `noopener` - Prevents the new page from accessing window.opener
     - `noreferrer` - Prevents passing referrer information
     - `nofollow` - Tells search engines not to follow this link
     - `sponsored` - Marks links as paid/sponsored content
     - `ugc` - Marks links as user-generated content

6. Domain Management:
   - ✅ Custom domain patterns
   - ✅ Official institution domains:
     - Global government domains (.gov, .gov.uk, .gov.au)
     - Global education domains (.edu, .ac.uk, .edu.au)
     - Military domains (.mil)
     - Country specific domains (gov.tr, edu.tr, gov.de, edu.fr)

7. Admin Interface:
   - ✅ Modern and user-friendly design
   - ✅ Real-time domain validation
   - ✅ Visual feedback
   - ✅ Quick settings access
   - ✅ Error reporting

8. Environment Integration:
   - ✅ WordPress settings API
   - ✅ Plugin action links
   - ✅ Admin notices
   - ✅ Multi-language support
   - ✅ Debug mode integration

## 📋 Planned Features

1. Link Analytics:
   - [ ] Click tracking
   - [ ] Link status monitoring
   - [ ] Traffic statistics
   - [ ] Report generation

2. Page Builder Integration:
   - [ ] Elementor support
   - [ ] WPBakery support
   - [ ] Divi Builder support
   - [ ] Gutenberg blocks

3. Advanced Management:
   - [ ] Link preview
   - [ ] Batch processing
   - [ ] Scheduled processing
   - [ ] API endpoints

## Requirements

- WordPress 5.0+
- PHP 7.4+

## Installation

1. Download the plugin zip file
2. Go to WordPress admin panel > Plugins > Add New
3. Click "Upload Plugin" and select the downloaded zip file
4. Click "Install Now" and then "Activate"
5. Go to Settings > External Links to configure

## Usage

1. Go to Settings > External Links
2. Select REL attributes
3. Add custom domains or patterns:
   - Simple domains: example.com
   - Wildcard: *.example.com
   - Regex: ^sub[0-9]+\.example\.com$
4. Import/Export domain lists
5. Save changes

## Best Practices

1. Domain Management:
   - Use wildcards for multiple subdomains
   - Keep patterns simple and specific
   - Regularly review domain list
   - Use bulk operations for large lists

2. Performance:
   - Clear cache after major changes
   - Use domain patterns efficiently
   - Monitor server resources
   - Review error logs

3. Security:
   - Validate imported domains
   - Check pattern syntax
   - Monitor excluded domains
   - Keep plugin updated

## License

GPL v2 or later

## Developer

[Hakan Tayfur](https://htayfur.com)

## Version History

### 1.2.1
- Added wildcard domain support
- Added regex pattern matching
- Added bulk domain operations
- Enhanced domain validation
- Improved pattern matching

### 1.2.0
- Added system requirements check
- Added environment validation
- Added quick settings access
- Enhanced admin interface
- Improved error handling

### 1.1.1
- Added comprehensive security features
- Implemented performance optimizations
- Updated documentation and translations

### 1.1.0 
- Added widget content support
- Added custom domain list feature
- Added advanced settings page
- Added admin panel CSS/JS

### 1.0.0 
- Initial release
- External link processing
- Customizable REL attributes
- Official domain support

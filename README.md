# Auto External Link Modifier

A WordPress plugin that adds custom 'rel' attributes and 'target="_blank"' to external links in posts and pages. It modifies all links except internal links and official institution websites.

## ✅ Features

1. System Requirements Check:
   - ✅ PHP version compatibility check
   - ✅ WordPress version verification
   - ✅ Dependency validation
   - ✅ Server configuration check
   - ✅ Environment validation

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
   - ✅ Custom domain list with validation
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

1. Advanced Domain Management:
   - [ ] Wildcard domain support
   - [ ] Regex pattern matching
   - [ ] Bulk domain import/export
   - [ ] Domain categories

2. Link Analytics:
   - [ ] Click tracking
   - [ ] Link status monitoring
   - [ ] Traffic statistics
   - [ ] Report generation

3. Page Builder Integration:
   - [ ] Elementor support
   - [ ] WPBakery support
   - [ ] Divi Builder support
   - [ ] Gutenberg blocks

4. Advanced Management:
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
3. Optionally add custom domain list
4. Save changes
5. The plugin will automatically process links in your content

## Best Practices

1. Security:
   - Regularly update the plugin
   - Review excluded domains
   - Monitor error logs

2. Performance:
   - Clear cache after major changes
   - Use domain wildcards for better performance
   - Monitor server resources

3. Content:
   - Review modified links periodically
   - Test external links
   - Update domain exclusions as needed

## License

GPL v2 or later

## Developer

[Hakan Tayfur](https://htayfur.com)

## Version History

### 1.2.0
- Added system requirements check
- Added quick settings access
- Added environment validation
- Added admin notices
- Enhanced error handling

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

# Auto External Link Modifier

A WordPress plugin that adds custom 'rel' attributes and 'target="_blank"' to external links in posts and pages. It modifies all links except internal links and official institution websites.

## ✅ Features

1. Link Processing:
   - ✅ Process WordPress post and page content
   - ✅ Process widget and block content
   - ✅ Add target="_blank" to external links
   - ✅ Automatically exclude internal links
   - ✅ Exclude official institution websites

2. Security Features:
   - ✅ Nonce verification for forms
   - ✅ Input sanitization
   - ✅ XSS protection
   - ✅ Secure domain validation
   - ✅ Error logging and monitoring

3. Performance Features:
   - ✅ Content caching system
   - ✅ Optimized DOM processing
   - ✅ Smart cache invalidation
   - ✅ Error recovery mechanisms
   - ✅ Skip unnecessary processing

4. REL Attributes Management:
   - ✅ Easy management from WordPress admin panel
   - ✅ Customizable REL attributes:
     - `noopener` - Prevents the new page from accessing window.opener
     - `noreferrer` - Prevents passing referrer information
     - `nofollow` - Tells search engines not to follow this link
     - `sponsored` - Marks links as paid/sponsored content
     - `ugc` - Marks links as user-generated content

5. Domain Management:
   - ✅ Custom domain list with validation
   - ✅ Official institution domains:
     - Global government domains (.gov, .gov.uk, .gov.au)
     - Global education domains (.edu, .ac.uk, .edu.au)
     - Military domains (.mil)
     - Country specific domains (gov.tr, edu.tr, gov.de, edu.fr)

6. Admin Interface:
   - ✅ Modern and user-friendly design
   - ✅ Real-time domain validation
   - ✅ Visual feedback
   - ✅ Helpful descriptions
   - ✅ Error reporting

7. Multi-language Support:
   - ✅ English interface
   - ✅ Turkish translations
   - ✅ Translatable strings

## 📋 Planned Features

1. Additional Security:
   - [ ] Role-based permissions
   - [ ] API authentication
   - [ ] Activity logging

2. Enhanced Performance:
   - [ ] Batch processing
   - [ ] Background processing
   - [ ] Network optimization

3. New Features:
   - [ ] Custom shortcode support
   - [ ] Page builder integrations
   - [ ] Link analytics
   - [ ] Bulk operations

4. Developer Tools:
   - [ ] API documentation
   - [ ] Hook documentation
   - [ ] Unit tests
   - [ ] E2E tests

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

### 1.1.1
- Added comprehensive security features
  - Nonce verification
  - Input sanitization
  - Error handling
- Implemented performance optimizations
  - Content caching
  - DOM processing improvements
  - Smart cache invalidation
- Updated documentation and translations

### 1.1.0 
- Added widget content support
- Added custom domain list feature
- Added advanced settings page
- Added admin panel CSS/JS
- Performance improvements
- Enhanced error handling

### 1.0.0 
- Initial release
- External link processing
- Customizable REL attributes
- Official domain support
- Turkish language support

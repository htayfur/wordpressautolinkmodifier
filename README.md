# Auto External Link Modifier

A WordPress plugin that adds custom 'rel' attributes and 'target="_blank"' to external links in posts and pages. It modifies all links except internal links and official institution websites.

## ✅ Features

1. Link Processing:
   - ✅ Process WordPress post and page content
   - ✅ Process widget and block content
   - ✅ Add target="_blank" to external links
   - ✅ Automatically exclude internal links
   - ✅ Exclude official institution websites

2. REL Attributes Management:
   - ✅ Easy management from WordPress admin panel
   - ✅ Customizable REL attributes:
     - `noopener` - Prevents the new page from accessing window.opener
     - `noreferrer` - Prevents passing referrer information
     - `nofollow` - Tells search engines not to follow this link
     - `sponsored` - Marks links as paid/sponsored content
     - `ugc` - Marks links as user-generated content

3. Custom Domain Management:
   - ✅ Add custom domain list
   - ✅ Automatic domain format validation
   - ✅ One domain per line input
   - ✅ Easy domain management

4. Official Domain Control:
   - ✅ Global government domains (.gov, .gov.uk, .gov.au)
   - ✅ Global education domains (.edu, .ac.uk, .edu.au)
   - ✅ Military domains (.mil)
   - ✅ Country specific domains (gov.tr, edu.tr, gov.de, edu.fr)

5. Performance Improvements:
   - ✅ DOM processing optimization
   - ✅ Error catching and logging
   - ✅ Skip unnecessary processing
   - ✅ Minified CSS/JS

6. Advanced Settings:
   - ✅ Modern and user-friendly interface
   - ✅ Real-time domain validation
   - ✅ Visual feedback
   - ✅ Helpful descriptions

7. Multi-language Support:
   - ✅ Turkish language files
   - ✅ Feature description translations
   - ✅ Admin panel translations

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

## License

GPL v2 or later

## Developer

[Hakan Tayfur](https://htayfur.com)

## Version History

### 1.1.1
- Converted documentation to English
- Improved documentation structure
- Updated version information

### 1.1.0 
- Added widget content support
- Added custom domain list feature
- Added advanced settings page
- Added admin panel CSS/JS
- Performance improvements
- Enhanced error handling

### 1.0.0 
- Initial release
- External link processing in WordPress posts and pages
- Customizable REL attributes
- Global and local official domain support
- Turkish language support

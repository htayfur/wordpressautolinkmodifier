# Auto External Link Modifier - Changelog

## [2.0.2] - 2024-02-17

### Added
- Multi-language Support:
  - Chinese (zh_CN) - Simplified Chinese
  - Arabic (ar) - Right-to-left support
  - Spanish (es_ES) - Spanish
  - Portuguese (pt_BR) - Brazilian Portuguese
  - French (fr_FR) - French
  - German (de_DE) - German
  - Japanese (ja) - Japanese
  - Russian (ru_RU) - Russian
  - Italian (it_IT) - Italian
  - Dutch (nl_NL) - Dutch
  - Korean (ko_KR) - Korean
  - Polish (pl_PL) - Polish
  - Hindi (hi_IN) - Hindi
  - Turkish (tr_TR) - Turkish

### Added Files
- Translation System:
  - Added POT template file
  - Created PO files for all languages
  - PHP-based MO file generator
  - Language-specific character support
  - RTL language support
  - Unicode handling

## [2.0.1] - 2024-02-17

### Fixed
- Added function polyfills for compatibility
- Fixed action/filter arguments
- Enhanced error handling
- Improved plugin initialization
- Added compatibility checker

### Added
- Polyfill system for missing WordPress functions
- Plugin loader class for better organization
- Compatibility checking system
- Enhanced error messages
- Proper argument handling

## [2.0.0] - 2024-02-17

### Major Changes
- Added Page Builder integrations
  - Elementor integration
  - WPBakery Page Builder integration
  - Divi Builder integration
  - Gutenberg blocks support

### New Features
- Custom Smart Link components for each builder:
  - Elementor Widget
  - WPBakery Shortcode
  - Divi Module
  - Gutenberg Block
- Advanced styling options
- Visual builder support
- Dark mode compatibility
- Responsive design improvements

### Builder-Specific Features
#### Elementor
- Smart Link widget with live preview
- Custom control panel
- Dynamic content support
- Template integration

#### WPBakery
- Custom shortcode with parameters
- Visual composer integration
- Backend editor support
- Template system integration

#### Divi
- Custom module with settings
- Visual builder compatibility
- Dynamic content support
- Template integration

#### Gutenberg
- Custom block with inspector controls
- Rich text support
- Block patterns support
- Full-site editing ready

### Technical Improvements
- Modular builder architecture
- Enhanced performance
- Better error handling
- Improved code organization

### Documentation
- Added builder integration guides
- Updated installation instructions
- Added developer documentation
- Enhanced code comments

## [1.0.0] - 2024-02-16

### Initial Release
- Basic link processing
- External link detection
- Domain exclusion system
- Security features:
  - XSS protection
  - Input validation
  - Token verification
- Performance optimizations:
  - Smart caching
  - Resource management
  - Memory optimization
- Core features:
  - Custom rel attributes
  - Target attribute management
  - Domain validation
  - Error handling
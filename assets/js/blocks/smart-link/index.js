import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import {
    useBlockProps,
    RichText,
    BlockControls,
    AlignmentToolbar,
    InspectorControls,
} from '@wordpress/block-editor';
import {
    PanelBody,
    TextControl,
    ToggleControl,
    FontSizePicker,
} from '@wordpress/components';

// Register the block
registerBlockType('aelm/smart-link', {
    edit: ({ attributes, setAttributes }) => {
        const {
            content,
            url,
            opensInNewTab,
            excludeFromProcessing,
            alignment,
            fontSize,
        } = attributes;

        // Block props with styles
        const blockProps = useBlockProps({
            className: `has-text-align-${alignment}`,
            style: {
                fontSize: fontSize,
            },
        });

        return (
            <>
                <BlockControls>
                    <AlignmentToolbar
                        value={alignment}
                        onChange={(newAlignment) => setAttributes({ alignment: newAlignment })}
                    />
                </BlockControls>

                <InspectorControls>
                    <PanelBody title={__('Link Settings', 'auto-external-link-modifier')}>
                        <TextControl
                            label={__('URL', 'auto-external-link-modifier')}
                            value={url}
                            onChange={(newUrl) => setAttributes({ url: newUrl })}
                            help={__('Enter the destination URL for the link.', 'auto-external-link-modifier')}
                        />
                        <ToggleControl
                            label={__('Open in New Tab', 'auto-external-link-modifier')}
                            checked={opensInNewTab}
                            onChange={(value) => setAttributes({ opensInNewTab: value })}
                        />
                        <ToggleControl
                            label={__('Exclude from Processing', 'auto-external-link-modifier')}
                            checked={excludeFromProcessing}
                            onChange={(value) => setAttributes({ excludeFromProcessing: value })}
                            help={__('Exclude this link from automatic modifications.', 'auto-external-link-modifier')}
                        />
                        <FontSizePicker
                            value={fontSize}
                            onChange={(newFontSize) => setAttributes({ fontSize: newFontSize })}
                        />
                    </PanelBody>
                </InspectorControls>

                <div {...blockProps}>
                    <RichText
                        tagName="a"
                        href={url}
                        value={content}
                        onChange={(newContent) => setAttributes({ content: newContent })}
                        placeholder={__('Add link text...', 'auto-external-link-modifier')}
                        className={`aelm-link ${excludeFromProcessing ? 'aelm-excluded' : ''}`}
                        target={opensInNewTab ? '_blank' : null}
                        rel={opensInNewTab ? 'noopener noreferrer' : null}
                    />
                </div>
            </>
        );
    },

    save: ({ attributes }) => {
        const {
            content,
            url,
            opensInNewTab,
            alignment,
            fontSize,
        } = attributes;

        const blockProps = useBlockProps.save({
            className: `has-text-align-${alignment}`,
            style: {
                fontSize: fontSize,
            },
        });

        const linkProps = {
            href: url,
            className: 'aelm-link',
        };

        if (opensInNewTab) {
            linkProps.target = '_blank';
            linkProps.rel = 'noopener noreferrer';
        }

        return (
            <div {...blockProps}>
                <a {...linkProps}>
                    <RichText.Content value={content} />
                </a>
            </div>
        );
    },
});
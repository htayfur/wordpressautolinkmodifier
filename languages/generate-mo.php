<?php
/**
 * PO to MO converter
 * 
 * Usage: php generate-mo.php
 */

class POToMO {
    private $po_content;
    private $messages = [];
    private $headers = [];

    public function __construct($po_file) {
        $this->po_content = file_get_contents($po_file);
        $this->parse();
    }

    private function parse() {
        $lines = explode("\n", $this->po_content);
        $current_msg = null;
        $in_msgstr = false;

        foreach ($lines as $line) {
            $line = trim($line);
            
            // Skip empty lines and comments
            if (empty($line) || $line[0] === '#') {
                continue;
            }

            if (strpos($line, 'msgid "') === 0) {
                $current_msg = [
                    'msgid' => $this->extractString($line),
                    'msgstr' => ''
                ];
                $in_msgstr = false;
            } elseif (strpos($line, 'msgstr "') === 0) {
                $in_msgstr = true;
                if ($current_msg) {
                    $current_msg['msgstr'] = $this->extractString($line);
                }
            } elseif ($line[0] === '"' && $current_msg) {
                $str = $this->extractString($line);
                if ($in_msgstr) {
                    $current_msg['msgstr'] .= $str;
                } else {
                    $current_msg['msgid'] .= $str;
                }
            }

            if ($current_msg && !empty($current_msg['msgid'])) {
                if (empty($current_msg['msgid'])) {
                    // This is the header
                    $this->headers = $current_msg['msgstr'];
                } else {
                    $this->messages[] = $current_msg;
                }
                $current_msg = null;
            }
        }
    }

    private function extractString($line) {
        return substr($line, strpos($line, '"') + 1, -1);
    }

    public function generateMO($mo_file) {
        // MO file format header
        $header = pack('L*',
            0x950412de,    // Magic number
            0,             // File format revision
            count($this->messages), // Number of strings
            28,           // Offset of table with original strings
            28 + count($this->messages) * 8, // Offset of table with translation strings
            0,            // Size of hashing table
            28 + count($this->messages) * 16 // Offset of hashing table
        );

        $originals = '';
        $translations = '';
        $o_offset = 28 + count($this->messages) * 16;
        $t_offset = $o_offset;

        foreach ($this->messages as $message) {
            $o_length = strlen($message['msgid']);
            $t_length = strlen($message['msgstr']);
            
            $originals .= pack('L*', $o_length, $o_offset);
            $translations .= pack('L*', $t_length, $t_offset);
            
            $o_offset += $o_length + 1;
            $t_offset += $t_length + 1;
        }

        $data = $header . $originals . $translations;

        foreach ($this->messages as $message) {
            $data .= $message['msgid'] . "\0";
        }

        foreach ($this->messages as $message) {
            $data .= $message['msgstr'] . "\0";
        }

        return file_put_contents($mo_file, $data);
    }
}

// Get all PO files in current directory
$po_files = glob("*.po");

foreach ($po_files as $po_file) {
    $mo_file = substr($po_file, 0, -3) . ".mo";
    echo "Converting $po_file to $mo_file...\n";
    
    $converter = new POToMO($po_file);
    if ($converter->generateMO($mo_file)) {
        echo "Successfully created $mo_file\n";
    } else {
        echo "Failed to create $mo_file\n";
    }
}

echo "Done!\n";
<?php
// TODO: Доделать

// Initialize an empty array to store pairs of positions and names
$pairs = [];

// Iterate over all script properties
foreach ($scriptProperties as $key => $value) {
    // Check if the key starts with 'value' and its index is odd
    if (preg_match('/^value(\d+)$/', $key, $matches)) {
        $index = (int)$matches[1];
        if ($index % 2 == 1) {
            // Add the position and corresponding name to the pairs array
            $pairs[] = [
                'position' => $value,
                'names' => isset($scriptProperties['value' . ($index + 1)]) ? $scriptProperties['value' . ($index + 1)] : ''
            ];
        }
    }
}

// Generate the HTML output
$output = '<dl class="creators">' . PHP_EOL;

foreach ($pairs as $pair) {
    $output .= '  <div class="creators__card">' . PHP_EOL;
    $output .= '    <dt class="creators__position">' . htmlspecialchars($pair['position'], ENT_QUOTES, 'UTF-8') . '</dt>' . PHP_EOL;
    
    // Split the names by comma and generate multiple <dd> elements if necessary
    $names = explode(',', $pair['names']);
    foreach ($names as $name) {
        $output .= '    <dd class="creators__names">' . htmlspecialchars(trim($name), ENT_QUOTES, 'UTF-8') . '</dd>' . PHP_EOL;
    }
    
    $output .= '  </div>' . PHP_EOL;
}

$output .= '</dl>' . PHP_EOL;

return $output;
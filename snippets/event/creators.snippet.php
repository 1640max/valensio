<?php
$creators = $modx->getOption('creators', $scriptProperties, '');
$creators = trim($creators);
$lines = explode("\n", $creators);

$output = '<dl class="creators">'.PHP_EOL;
$currentPosition = '';

foreach ($lines as $line) {
    $line = trim($line);
    if (empty($line)) {
        continue;
    }
    
    if (str_starts_with($line, '-') === false) {
        if (!empty($currentPosition)) {
            $output .= '  </div>'.PHP_EOL;
        }
        $currentPosition = $line;
        $output .= '  <div class="creators__card">'.PHP_EOL;
        $output .= '    <dt class="creators__position">' . $currentPosition . '</dt>'.PHP_EOL;
    } else {
        $line = trim($line, '- \n\r\t\v\x00');
        $output .= '    <dd class="creators__name">' . $line . '</dd>'.PHP_EOL;
    }
}

if (!empty($currentPosition)) {
    $output .= '  </div>'.PHP_EOL;
}

$output .= '</dl>'.PHP_EOL;

return $output;
<?php
$output = '';

if (!empty($scriptProperties['strings'])) {
    $strings = $scriptProperties['strings'];
    $strings = trim($strings);
    $strings = explode("\n", $strings);
    $strings = array_map('trim', $strings);
    $strings = array_filter($strings);
    $count = count($strings);

    if ($count % 2 === 0) {
        $output .= '<dl class="creators">';

        for ($i = 0; $i < $count; $i += 2) {
            $position = $strings[$i];
            $names = $strings[$i + 1];

            $output .= '
            <div class="creators__card">
              <dt class="creators__position">' . $position . '</dt>
              <dd class="creators__names">' . $names . '</dd>
            </div>';
        }

        $output .= '</dl>';
    }
}

return $output;

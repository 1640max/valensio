<?php
// TODO: Получать значение CTName не через аргумент, а прям отсюда

$isThemeDefault = !$CTName;

$output = $isThemeDefault ? '' : 'color-theme_name_'.$CTName;

switch ($_COOKIE["color-mode"]) {
  case 'darkAndColors':
    if (!$isThemeDefault) break;
  case 'dark':
    $output .= ' color-theme_name_dark';
    break;
  
  case  null:
  case 'light':
  default:
    break;
}

return $output;
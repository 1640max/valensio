<?php
if (!$tags) {
     return;
 }

if ($class) {
    $class = ' '.$class;
}
 
 return 
     '<div class="tag'.$class.'">'.
     str_replace(';', '</div><div class="tag'.$class.'">', $tags).
     '</div>';
<?php
if (!$tags) {
     return;
 }

if ($class) {
    $class = ' '.$class;
}
 
 return 
     '<li class="tag'.$class.'">'.
     str_replace(';', '</li><li class="tag'.$class.'">', $tags).
     '</li>';
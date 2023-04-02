<?php
// Конвертируем строку в булеан
  $shorten = filter_var($_GET['shorten'], FILTER_VALIDATE_BOOLEAN);

  $links = $modx->runSnippet('utm-setter', array(
    'link' => $_GET['link'],
    'utmDate' => $_GET['utmDate'],
    'shorten' => $shorten
  ));
  
// echo '<br /><br /><pre>';
// print_r($links);
// echo '</pre>';
  
return '<pre>'.print_r($links, true).'</pre>';
<?php

$name = $modx->runSnippet('sql-query', array(
  'query' => 'SELECT type, name FROM repertoire WHERE alias = "'.$alias.'";'
));
$name = $name[0]["type"].' '.$name[0]["name"];

$output = '<div class="gallery drag-to-scroll">'.PHP_EOL;

  for ($i = 1; ; $i++) {
    if (file_exists('assets/media/repertoire/'.$alias.'/'.$i.'.jpg')) {
      $output .= '<img class="gallery__photo" src="media/repertoire/'.$alias.'/'.$i.'.jpg" alt="'.$name.'" loading="lazy">'.PHP_EOL;
    }
    else {
      // Если файл не существует, то заканчиваем цикл.
      // Но если ни одного файла не существует, то возвращаем пустоту
      if ($i == 1) return;
      else break;
    }
  }

  $output .= '</div>'.PHP_EOL;

  return $output;
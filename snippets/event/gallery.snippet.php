<?php
$output = '<div class="gallery drag-to-scroll">';

  for ($i = 1; ; $i++) {
    if (file_exists('assets/media/repertoire/'.$alias.'/'.$i.'.jpg')) {
      // TODO: Добавить название спектакля в alt
      $output .= '<img class="gallery__photo" src="media/repertoire/'.$alias.'/'.$i.'.jpg" alt="Спектакль">';
    }
    else {
      // Если файл не существует, то заканчиваем цикл.
      // Но если ни одного файла не существует, то возвращаем пустоту
      if ($i == 1) return;
      else break;
    }
  }

  $output .= '</div>';

  return $output;
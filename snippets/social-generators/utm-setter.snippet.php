<?php
// На вход поступает чистая ссылка ($link), дата поста ($utmDate) и булеан, запрашивающий сокращение ссылок ($shorten)
if (!isset($link) or !isset($utmDate)) return;

// Прикручиваем utm-метки
$outputLinks['vk'] = $link.'?utm_source=vk_page&utm_term='.$utmDate;
$outputLinks['tg'] = $link.'?utm_source=tg_channel&utm_term='.$utmDate;
$outputLinks['fb'] = $link.'?utm_source=fb_page&utm_term='.$utmDate;

// Если был запрос на сокращение ссылок, то сокращаем их
if ($shorten) {
  
  // Если ссылка уже была сокращена, то yourls выдаст уже готовое сокращение. Но с ошибкой 400.
  // file_get_contents из-за этого откажется работать.
  // Чтобы он работал, нужен вот этот код.
  // Источник: https://stackoverflow.com/questions/11475657/ignoring-errors-in-file-get-contents-http-wrapper/11479968#11479968
  $context = stream_context_create(array(
    'http' => array('ignore_errors' => true),
  ));
  
  foreach ($outputLinks as &$outputLink) {
    $encodedLink = urlencode($outputLink);
    $outputLink = file_get_contents(
      'https://valensio.com/s/yourls-api.php?signature=0ea592b05a&action=shorturl&format=simple&url='.$encodedLink,
      false,
      $context // Та самая штука, чтобы file_get_contents работал, даже в случае ошибки
    );
  }
  
}

// Добавляем текст вместо ссылки для инстаграма
$outputLinks['inst'] = 'по ссылке в шапке профиля.';

return $outputLinks;
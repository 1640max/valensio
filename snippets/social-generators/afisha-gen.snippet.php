<?php
// Скрипт генерирует посты для соцсетей с афишей на месяц

// Принимаем на вход номер месяца (month), дату для utm-метки (utmDate) и маркер сокращения ссылок (shorten)
$month = (int)$_GET['month'];
$utmDate = $_GET['utmDate'];

// Валидация
if ($month == 0 or !preg_match('/^\d{4}-\d\d-\d\d$/', $utmDate)) return;

// Конвертируем строку в булеан
$shorten = filter_var($_GET['shorten'], FILTER_VALIDATE_BOOLEAN);


$events = $modx->runSnippet('sql-query', array(
  'query' =>
  ' SELECT upcoming.date, repertoire.emoji, repertoire.name, repertoire.description, repertoire.age, repertoire.action, repertoire.link, repertoire.alias
    FROM repertoire
    JOIN (
      SELECT alias, date, action
      FROM tickets
      WHERE MONTH(date) = '.$month.' AND NOT hidden
    ) upcoming
    ON repertoire.alias = upcoming.alias
    ORDER BY upcoming.date;'
));



$russianMonths = array('','января','февраля','марта','апреля','мая', 'июня','июля','августа','сентября','октября','ноября','декабря');
$postTemplate = '';
$links = [];



// Генерируем шаблон поста. Потом на его основе сделаем посты для каждой соцсети.
// phNum — сокр. placeholderNumber
$phNum = 0;
foreach ($events as $event) {
  
  // Пишем дату. <b> чисто по приколу
  $formattedDate = strtotime($event['date']);
  $postTemplate .= '<b>'.date("d ", $formattedDate).$russianMonths[date('n', $formattedDate)].date(', H:i', $formattedDate).'</b><br>';
  
  // В названии мероприятия заменяем кавычки «» на „“.
  // Потому что название будет уже обрамлено кавычками-ёлочками.
  $event['name'] = str_replace(array("«", "»"),
                               array("„", "“"),
                               $event['name']);

  // Получаем остальное
  $postTemplate .= $event['emoji'].' «'.$event['name'].'»<br>'.
                   $event['description'].' '.$event['age'].'+';
  
  // Обрабатываем ссылку.
  // Если link равно NULL, то ссылка не создаётся — ничего не делаем.
  // Если link — пустая строка, то делаем из неё ссылку по умолчанию: с помощью alias.
  // Иначе в link уже забита готовая ссылка, используем её.
  if (!is_null($event['link'])) {
    
    // Ссылка будет. Пишем подводку к ней (например «Купить билеты: ») и плейсхолдер типа ++1++
    $postTemplate .= '<br>'.$event['action'].': ++'.$phNum.'++';
    
    // Саму ссылку запоминаем. Потом добавим к ней utm-метки и вставим на место плейсхолдера
    // Но сначала её надо создать
    if (empty($event['link'])) {
      $links[$phNum] = 'https://valensio.com/'.$event['alias'];
    } else {
      $links[$phNum] = $event['link'];
    }
    
    // Повышаем номер плейсхолдера
    $phNum++;
  }
  
  // Отступ после абзаца
  $postTemplate .= '<br><br>';
}

// Хэштег в конце
$postTemplate .= '#театралехандро';



// Заполняем вот эту матрицу ссылками.
// Первый ключ — номер плейсхолдера в шаблоне (целое число)
// Второй ключ — ссылка для конкретой соцсети ('vk', 'tg', 'fb', 'inst')
$allLinks = [];

foreach ($links as $phNum => $link)
{
  $allLinks[$phNum] = $modx->runSnippet('utm-setter',array(
    'link' => $link,
    'utmDate' => $utmDate,
    'shorten' => $shorten
  ));
}



// Ура, генерим готовые посты для соцсетей. Ключ — соцсеть, значение — пост
$posts = [];

// Проходимся циклом по матрице $allLinks.
// При этом идём мы в транспонированном порядке:
// сначала по соцсетям (по второй координате), а потом по номерам плейсхолдеров (по первой координате).
// Для нас важны только ключи, значения не важны.
foreach ($allLinks[0] as $social => $nomatter) {
  
  // Копируем шаблон в переменную с постом
  $posts[$social] = substr($postTemplate, 0);
  
  // Проходимся по шаблону и заменяем плейсхолдеры на ссылки
  // phNum — сокр. placeholderNumber
  foreach ($allLinks as $phNum => $nomatter) {
    $posts[$social] = str_replace('++'.$phNum.'++', $allLinks[$phNum][$social], $posts[$social]);
  }
}



// Под звуки фанфар выводим полученные посты
$output = '';
foreach ($posts as $social => $post) {
  $output .= '<h2>'.$social.'</h2>'.$post;
}

// echo '<br /><br /><pre>';
// print_r($posts);
// echo '</pre>';

// echo $postTemplate;

return $output;
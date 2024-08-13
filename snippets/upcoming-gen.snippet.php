<?php
/*   Запрос upcomingCards возвращает сжатую табличку с предстоящими событиями.
     alias не повторяется. Даты и ссылки на виджеты объединены в одну ячейку и разделены через ";".
     При этом прошедшие события удалены, а предстоящие отсортированы по дате.
     Ну и мы этот запрос джойним с табличкой repertoire */
     
  $upcomings = $modx->runSnippet('sql-query', array(
    'query' =>
      'SELECT *
      FROM repertoire
      JOIN ( 
        SELECT
          alias,
          GROUP_CONCAT(date   ORDER BY tickets.date ASC SEPARATOR ";") AS dates,
          GROUP_CONCAT(widget ORDER BY tickets.date ASC SEPARATOR ";") AS widgets,
          COUNT(*) AS count
        FROM tickets
        WHERE tickets.date > cast(now() as date) AND NOT hidden
        GROUP BY alias
        ORDER BY dates
        ) upcomingCards
      ON repertoire.alias = upcomingCards.alias'
  ));

  $russianMonths = array('','января','февраля','марта','апреля','мая', 'июня','июля','августа','сентября','октября','ноября','декабря');
  
  foreach ($upcomings as &$upcoming) {
    
    // link по умолчанию
    if (!$upcoming['link']) $upcoming['link'] = '/'.$upcoming['alias'];
    // cover по умолчанию
    if (!$upcoming['cover']) $upcoming['cover'] = 'media/repertoire/'.$upcoming['alias'].'/cover.jpg';
    
    // Разделяем полученные даты
    $dates = explode(";", $upcoming["dates"]);
    $widgets = explode(";", $upcoming["widgets"]);

    for ($i = 0; $i < $upcoming['count']; $i++) {

      // Сохраняем dateRaw для тега time
      $dateRaw = $dates[$i];

      // Форматируем даты
      $formattedDate = strtotime($dates[$i]);
      $dates[$i] = date("d ", $formattedDate).$russianMonths[date('n', $formattedDate)].date(" H:i", $formattedDate);

      // Формируем код тегов с датами и ссылками на виджет
      // Не круто делать это в сниппете, но делать ради этого отдельные чанки и сниппеты — ещё хуже
      
      // Если ссылки на виджет нет, то и в HTML ссылку не делаем
      if (!$widgets[$i]) {
        $upcoming["tags-workaround"] .= '<time class="tag" datetime="'.$dateRaw.'">'.$dates[$i].'</time>';
      }
      else { 
        $upcoming["tags-workaround"] .=
        '<a class="tag" href="'.$widgets[$i].'" target="_blank">'.
          '<time datetime="'.$dateRaw.'">'.$dates[$i].'</time></a>';
      }
    }
    $output .= $modx->getChunk('upcoming-card', $upcoming);
  }
  return $output;
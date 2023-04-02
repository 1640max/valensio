<?php
$events = $modx->runSnippet('sql-query',array(
    // 'query' => 'SELECT * FROM repertoire ORDER BY sort DESC'
    'query' =>
      '# https://stackoverflow.com/a/28046502/9282831
      # Сортируем репертуар, ставя на первое место ближайшие события
      SELECT repertoire.*
      FROM repertoire
      LEFT JOIN (
        # https://stackoverflow.com/a/28090544/9282831
        # Ищем ближайшие даты для каждого спектакля
        WITH upcoming
        AS (
          # Фильтруем прошедшие и скрытые события
          SELECT alias, date
          FROM   tickets
          WHERE  date > cast(now() as date) AND NOT hidden
        )
        SELECT    o.alias, o.date
        FROM      upcoming o
        LEFT JOIN upcoming b
        ON        o.alias = b.alias AND o.date > b.date
        WHERE     b.date is NULL
        ORDER BY  o.date
      ) nearest
      ON repertoire.alias = nearest.alias
      WHERE NOT repertoire.hidden
      ORDER BY
        ISNULL(nearest.date),
        nearest.date,
        repertoire.sort DESC;'
  ));
  
  foreach ($events as &$event) {

    /* Значения по умолчанию */
    // link по умолчанию
    // switch ($event['link']) {
    //   case '':
    //     echo 123;
    //   case NULL:
    //     echo 456;
    // }
    
    // Если link равно NULL, то ссылка не создаётся — ничего не делаем.
    // Если link — пустая строка, то делаем из неё ссылку по умолчанию и добавляем href.
    // Иначе в link уже забита готовая ссылка — просто добавляем href
    // switch ($event["link"]) {
    //   case '':
    //     $event['link'] = '/'.$event['alias'];
    //   case NULL:
    //     $event['link'] = 'href="'.$event['link'].'"';
    // }

    if (!is_null($event["link"])) {
      if (empty($event["link"])) {
        $event['link'] = '/'.$event['alias'];
      }
      $event['hrefed-link'] = 'href="'.$event['link'].'"';
    }

    // if (!is_null($event['link']) && !$event['link']) {
    // }
    
    // cover по умолчанию
    if (!$event['cover']) $event['cover'] = 'media/repertoire/'.$event['alias'].'/cover.jpg';

    $output .= $modx->getChunk('repertoire-card', $event);
  }
  
  return $output;
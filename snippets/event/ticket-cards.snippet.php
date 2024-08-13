<?php
$events = $modx->runSnippet('sql-query', array(
    'query' => 'SELECT * FROM tickets WHERE date > cast(now() as date) AND NOT hidden AND alias = "'.$alias.'";'
  ));

  if (!$events) {
    return '<div class="ticket-cards"><div class="ticket-card__date h3">Билетов нет :(</div></div>';
  }

  $russianMonths = array('','января','февраля','марта','апреля','мая', 'июня','июля','августа','сентября','октября','ноября','декабря');
  $russianDayOfWeek = array('','пн','вт','ср','чт','пт', 'сб','вс');

  foreach ($events as $event) {
    $event["date-raw"] = $event["date"];
    $date = strtotime($event["date"]);
    $event["date"] = date("d ", $date).$russianMonths[date('n', $date)].', '.$russianDayOfWeek[date('N', $date)];
    $event["time"] = date("H:i", $date);
    
    // Генерируем код для кнопки. Если нет ссылки или надписи на кнопке, то не генерируем.
    if ($event["widget"] && $event["action"]) {
      $event["button-workaround"] = '<a class="ticket-card__button button" href="'.$event["widget"].'" target="_blank">'.$event["action"].'</a>';
    }

    $output .= $modx->getChunk('ticket-card', $event);
  }

return '<div class="ticket-cards">'.$output.'</div>';
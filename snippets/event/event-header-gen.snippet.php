<?php
/* TODO: Что, если билетов нет */
  $event = $modx->runSnippet('sql-query', array(
    'query' => 'SELECT * FROM repertoire WHERE alias = "'.$alias.'";'
  ));

  $event = $event[0];

  // cover по умолчанию
  if (!$event['cover']) $event['cover'] = 'media/repertoire/'.$event['alias'].'/cover.jpg';

  return $modx->getChunk('event-header', $event);
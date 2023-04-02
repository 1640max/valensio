<?php
// TODO: Сделать сортировку по menuindex
  // $query = $modx->newQuery('modResource');
  // $query->sortby('menuindex', 'asc');
  $castMembers = $modx->getCollection('modResource', array('parent' => 15));

  $output = '';
  foreach ($castMembers as $castMember) {

    // Между именем и фамилией ставим перенос строки.
    // Для этого заменяем первый пробел на <br>
    $name = $castMember->get('pagetitle');
    $spacePosition = strpos($name, ' ');
    if ($pos !== false)
      $name = substr_replace($name, '<br>', $spacePosition, 1);

    $output .= $modx->getChunk('cast-member-card', array(
      'alias' => $castMember->get('alias'),
      'name'  => $name,
      'id'    => $castMember->get('id')
    ));
  }
  
  return $output;
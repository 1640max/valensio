<?php
/* Не знаю почему, но иногда вылетает ошибка из-за пустого аргумента в $modx->query() */
if (!$query) {
    return;
}

// https://stackoverflow.com/a/12476917/9282831

//$modx->query("SET NAMES utf8mb4");

$response = $modx->query($query);
$rows = array();
if ($response) {
    // loop through the result set and inspect one row at a time
    while ($row = $response->fetch(PDO::FETCH_ASSOC, PDO::NULL_NATURAL)) {
        array_push($rows, $row);
    }
}

// echo '<br /><br /><pre>';
// print_r($rows);
// echo '</pre>';

return $rows;
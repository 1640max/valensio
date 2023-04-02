<?php
$alias = $modx->resource->get('alias');
  if ($alias == "index")
    return;
  else
    return $alias."/";
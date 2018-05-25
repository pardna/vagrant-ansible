<?php
require __DIR__ . '/prod.php';
$app['debug'] = true;
$app['log.level'] = Monolog\Logger::ERROR;

$app['rules.pardna.minimumSlots'] = 3;
$app['rules.pardna.maximumSlots'] = 12;
$app['rules.pardna.minimumAmount'] = 5;
$app['rules.pardna.maximumAmount'] = 500;

$app['rules.charges.default'] = array(
  1 => 6,
  2 => 5.5,
  3 => 5.0,
  4 => 4.5,
  5 => 4.0,
  6 => 3.5,
  7 => 3.0,
  8 => 2.5,
  9 => 2.0,
  10 => 1.5,
  11 => 1.0,
  12 => 0
);

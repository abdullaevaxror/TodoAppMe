<?php


$redis = new Redis();
$redis->connect('redis', 6379);

$redis->set('name', 'test');

echo $redis->get('name');


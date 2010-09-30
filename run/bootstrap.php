<?php

defined('PATH')
    || define('PATH', realpath(dirname(__FILE__) . '/..'));

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(PATH . '/src/library'),
    get_include_path(),
)));

require 'Phorrst/Cache/Memcache.php';

$memcache = new \Memcache();
$memcache->connect('localhost');

$cache = new \Phorrst\Cache\Memcache($memcache);
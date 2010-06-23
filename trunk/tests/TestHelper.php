<?php

require_once 'PHPUnit/Framework.php';

set_include_path(
    dirname(__FILE__)."/../library". PATH_SEPARATOR . get_include_path()
);

if (defined('ZEND_LIBRARY') && strlen('ZEND_LIBRARY') > 0) {
    set_include_path(ZEND_LIBRARY . PATH_SEPARATOR . get_include_path());
}

require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();
$loader->registerNamespace('My');
<?php

use Brisum\Lib\ObjectManager;

$objectManager = ObjectManager::getInstance();
$objectManager->create('Brisum\Wordpress\PostPanel\Panel');
$objectManager->create('Brisum\Wordpress\PostPanel\Panel\TaxonomyEdit');

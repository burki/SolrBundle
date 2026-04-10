<?php

require_once __DIR__ . '/../vendor/autoload.php';

require  __DIR__ . '/../vendor/doctrine/mongodb-odm/src/Mapping/Annotations/Document.php';

if (method_exists(\Doctrine\Common\Annotations\AnnotationRegistry::class, 'registerLoader')) {
    \Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');
}

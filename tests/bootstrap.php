<?php

declare(strict_types=1);

use PHPStan\Testing\PHPStanTestCase;

require_once __DIR__ . '/../vendor/autoload.php';

if (method_exists(Doctrine\Common\Annotations\AnnotationRegistry::class, 'registerLoader')) {
    // for old doctrine annotations
    // to avoid "Doctrine\Common\Annotations\AnnotationException: [Semantical Error] The annotation "@Doctrine\ORM\Mapping\Embeddable"
    //            in class Otobank\PHPStan\Doctrine\Rules\Asset\EmbeddedEntity does not exist, or could not be auto-loaded."
    \Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');
}

PHPStanTestCase::getContainer();

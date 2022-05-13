<?php

declare(strict_types=1);

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\Persistence\Mapping\Driver\MappingDriverChain;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\DoctrineProvider;

$config = new Configuration();
$config->setProxyDir(__DIR__);
$config->setProxyNamespace('PHPstan\Doctrine\OrmProxies');
$config->setMetadataCacheImpl(new DoctrineProvider(new ArrayAdapter()));

$metadataDriver = new MappingDriverChain();

$metadataDriver->addDriver(new AnnotationDriver(
    new AnnotationReader(),
    [__DIR__]
), 'Otobank\\PHPStan\\Doctrine\\Rules\\Asset');

$config->setMetadataDriverImpl($metadataDriver);

// @ suppress ORM 's PHP deprecated calls -- "continue" targeting switch is equivalent to "break". Did you mean to use "continue 2"?
return @EntityManager::create(
    [
        'driver' => 'pdo_sqlite',
        'memory' => true,
    ],
    $config
);

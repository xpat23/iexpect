#!/usr/bin/env php
<?php
declare(strict_types=1);

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Xpat\ApiTest\Attribute\Test;
use Xpat\ApiTest\FileSystem\ClassesByPath;
use Xpat\ApiTest\FileSystem\MethodsWithAttribute;
use Xpat\ApiTest\FileSystem\MethodsWithName;
use Xpat\ApiTest\FileSystem\PublicMethodsOfClasses;
use Xpat\ApiTest\FileSystem\SubClassesOf;
use Xpat\ApiTest\Output\ResultsOutput;
use Xpat\ApiTest\Test\ApiTestMethods;
use Xpat\ApiTest\Test\TestCase;
use Xpat\ApiTest\Test\TestingResults;

require_once __DIR__ . '/vendor/autoload.php';

$directory = __DIR__;

$container = new ContainerBuilder();
$loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/config'));
$loader->load('services.yaml');

$arg = $argv[1] ?? $directory;

$method = explode('::', $arg)[1] ?? '';
$path = explode('::', $arg)[0] ?? $directory;

(
new ResultsOutput(
    new TestingResults(
        new ApiTestMethods(
            new MethodsWithAttribute(
                new MethodsWithName(
                    new PublicMethodsOfClasses(
                        new SubClassesOf(
                            new ClassesByPath($path),
                            TestCase::class
                        ),
                    ),
                    $method
                ),
                Test::class
            )
        ),
        $container
    )
)
)->print();


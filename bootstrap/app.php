<?php
require_once __DIR__ . '/bootstrap.php';

use EventFarm\RestforceDemo\SiteTemplateGenerator;
use EventFarm\RestforceDemo\Twig\TemplateNamespace;
use EventFarm\RestforceDemo\Twig\TwigTemplateGenerator;
use Moust\Silex\Provider\CacheServiceProvider;
use Silex\Application;

$app = new Application();
$app['debug'] = true;

$app->register(new CacheServiceProvider(), array(
    'cache.options' => array(
        'driver' => 'file',
        'cache_dir' => __DIR__ . '/../cache'
    )
));

$twigTemplateGenerator = TwigTemplateGenerator::createFromTemplateNamespace(
    new TemplateNamespace(__DIR__ . '/../templates', 'site')
);

$blogGenerator = new SiteTemplateGenerator(
    $twigTemplateGenerator->getTwigEnvironment()
);

return $app;

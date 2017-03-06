<?php
date_default_timezone_set('America/Los_Angeles');

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

use Symfony\Component\HttpFoundation\Request;
Request::enableHttpMethodParameterOverride();

$app->register(new Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/../views/'
]);


//*********************************
// ROUTES
//*********************************

$app->get('/', function() use ($app) {
    return $app['twig']->render('home.html.twig');
});

return $app;
?>

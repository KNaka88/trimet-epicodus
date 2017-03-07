<?php
date_default_timezone_set('America/Los_Angeles');

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../api_keys.php';

require_once __DIR__ . '/../src/Location.php';

$app = new Silex\Application();
$app['debug'] = true;

$server = 'mysql:host=localhost:8889;dbname=trimet';
$username = 'root';
$password = 'root';
$DB = new PDO($server, $username, $password);

use Symfony\Component\HttpFoundation\Request;
Request::enableHttpMethodParameterOverride();

$app->register(new Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/../views/'
]);

//*********************************
// ROUTES
//*********************************

$app->get('/', function() use ($app, $google_api) {

    $locations = Location::getAll();
    $time = date('h:ia');
    return $app['twig']->render('home.html.twig', [
        'locations' => $locations, 'time' => $time,
        'google_api' => $google_api
    ]);
});

$app->post('/trimet', function() use ($app, $trimet_api) {
    $start_location = Location::find($_POST['start-point-id']);
    $end_location = Location::find($_POST['end-point-id']);

    $date = '3-6-2017';
    $time = '4:12%20PM'; // time + AM or PM
    $arr  = 'D';  // D: departure time, A: Arrival time, nothing: current time

    $request_url =
    "https://developer.trimet.org/ws/V1/trips/tripplanner/" .
    "maxIntineraries/3/format/xml/fromCoord/{$start_location->getLongitude()},{$start_location->getLatitude()}/toCoord/{$end_location->getLongitude()},{$end_location->getLatitude()}/date/{$date}/time/{$time}/arr/{$arr}/min/T/walk/0.50/mode/T/appId/{$trimet_api}";
    return $app->redirect($request_url);
});

return $app;
?>

<?php
date_default_timezone_set('America/Los_Angeles');

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../api_keys.php';

require_once __DIR__ . '/../src/Location.php';
require_once __DIR__ . '/../src/Itinerary.php';
require_once __DIR__ . '/../src/Leg.php';

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

$app->get('/show_results', function() use ($app, $google_api) {
    $itineraries = Itinerary::getAll();
    return $app['twig']->render('results.html.twig', [
        'itineraries' => $itineraries, 'locations'=> Location::getAll(), 'google_api' => $google_api
    ]);
});

$app->post('/trimet', function() use ($app, $trimet_api) {
    $start_location = Location::find($_POST['start-point-id']);
    $end_location = Location::find($_POST['end-point-id']);
    $this_lat = $_POST['this_lat'];
    $this_lng = $_POST['this_lng'];
    Itinerary::deleteAll();
    Leg::deleteAll();
    $date = '3-7-2017';
    $time = '4:12%20PM'; // time + AM or PM
    $arr  = 'D';  // D: departure time, A: Arrival time, nothing: current time

    $request_url =
    "https://developer.trimet.org/ws/V1/trips/tripplanner/" .
    "maxIntineraries/3/format/xml/fromCoord/{$start_location->getLongitude()},{$start_location->getLatitude()}/toCoord/{$end_location->getLongitude()},{$end_location->getLatitude()}/date/{$date}/time/{$time}/arr/{$arr}/min/T/walk/0.50/mode/T/appId/{$trimet_api}";

    parseTrimetResults($request_url);

    

    // return "Stop";
    return $app->redirect('/show_results');
});

//*********************************
// Helper functions
//*********************************
function parseTrimetResults($request_url)
{

    $xml=simplexml_load_file($request_url) or die("Error: Cannot create object");

    //itineraries
    $itineraries = $xml->itineraries;
    $itineraries_count = $itineraries->attributes()->count;

    for($i=0; $i<$itineraries_count; $i++){
        $itinerary = $itineraries->itinerary[$i];
        $time_distance = $itinerary->{"time-distance"};
        $distance = $time_distance->distance; //11.26
        $start_time = DateTime::createFromFormat('n/j/y g:i A', $time_distance->date . ' ' . $time_distance->startTime);
        $end_time = DateTime::createFromFormat('n/j/y g:i A', $time_distance->date . ' ' . $time_distance->endTime);
        $new_itinerary = new Itinerary($distance, $start_time->format('Y-m-d H:i:s'), $end_time->format('Y-m-d H:i:s'));
        $new_itinerary->save();
        $itinerary_id = $new_itinerary->getId();

        // legs
        $legs = $itinerary->leg;
        $legs_count = count($legs);

        for($j=0; $j<$legs_count; $j++){
            $mode = $legs[$j]->attributes()->mode;
            $order = $legs[$j]->attributes()->order;

            $leg_start_time = null;
            $leg_end_time = null;

            if(!empty($legs[$j]->{"time-distance"}->startTime)){
                $leg_start_time =
                DateTime::createFromFormat('n/j/y g:i A', $time_distance->date . ' ' . $legs[$j]->{"time-distance"}->startTime)->format('Y-m-d H:i:s');

                $leg_end_time =
                DateTime::createFromFormat('n/j/y g:i A', $time_distance->date . ' ' . $legs[$j]->{"time-distance"}->endTime)->format('Y-m-d H:i:s');
                $leg_distance = $legs[$j]->{"time-distance"}->distance;
                $route_number = $legs[$j]->route->number;
                $route_name = $legs[$j]->route->name;
            }



            $to_location = new Location($to->pos->lat, $to->pos->long, $to->description);
            $to_location->save();
            $from_location = new Location($from->pos->lat, $from->pos->long, $from->description);
            $from_location->save();
            $new_leg = new Leg($mode, $leg_distance, $from_location->getId(), $to_location->getId(), $itinerary_id, $j, $leg_start_time, $leg_end_time, $order, $route_number, $route_name);

            $new_leg->save();

        }
    }
}

return $app;
?>

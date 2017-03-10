<?php
date_default_timezone_set('America/Los_Angeles');

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../api_keys.php';
require_once __DIR__ . '/../src/Location.php';
require_once __DIR__ . '/../src/Itinerary.php';
require_once __DIR__ . '/../src/Leg.php';

$app = new Silex\Application();
$app['debug'] = true;
// use Symfony\Component\Debug\Debug;
//   Debug::enable();

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
    $time = date('g:i');
    return $app['twig']->render('home.html.twig', [
        'locations' => $locations, 'time' => $time,
        'google_api' => $google_api
    ]);
});

$app->get('/show_results', function() use ($app) {
    $itineraries = Itinerary::getAll();
    $itinerary = $itineraries[0];
    $legs = $itinerary->getLegs();
    $leg_count = count($legs);

    $from_array = [];
    foreach($legs as $leg) {
        $leg_from_id = $leg->getFromId();
        $from_location = Location::Find($leg_from_id);
        array_push($from_array, $from_location);
    }

    $to_array = [];
    foreach($legs as $leg) {
        $leg_to_id = $leg->getToId();
        $to_location = Location::Find($leg_to_id);
        array_push($to_array, $to_location);
    }


    $locations = Location::getAll();

    return $app['twig']->render('results.html.twig', [
        'legs'=> $legs, 'itineraries' => $itineraries, 'locations'=> Location::getAll(), "from_array" => $from_array, "to_array" => $to_array
    ]);

});


$app->post('/trimet', function() use ($app, $trimet_api) {


    $start_location_value = $_POST['start-point-id'];
    if($start_location_value == "current"){
        $start_lat = $_POST['cur_lat'];
        $start_lng = $_POST['cur_lng'];
    }else{
        $start_location = Location::find($start_location_value);
        $start_lat = $start_location->getLatitude();
        $start_lng = $start_location->getLongitude();
    }
    $end_point_value = $_POST['end-point-id'];

    if($end_point_value == "pinned"){
        $dest_lat = $_POST['dest_lat'];
        $dest_lng = $_POST['dest_lng'];
    }else{
        $end_location = Location::find($end_point_value);
        $dest_lat = $end_location->getLatitude();
        $dest_lng = $end_location->getLongitude();
    }
    Itinerary::deleteAll();
    Leg::deleteAll();
    $date_time_strings = explode('T', $_POST['date-time']);
    $date_obj = DateTime::createFromFormat('Y-m-d H:i', $date_time_strings[0] . ' ' . $date_time_strings[1]);
    $date = $date_obj->format('n-j-Y');
    $time = "{$date_obj->format('g:i')}%20{$date_obj->format('A')}"; // time + AM or PM
    $arr  = $_POST['time-priority'];  // D: departure time, A: Arrival time, nothing: current time

    // Setting Destination (On process)
    $request_url =
    "https://developer.trimet.org/ws/V1/trips/tripplanner/" .
    "maxIntineraries/3/format/xml/fromCoord/{$start_lng},{$start_lat}/toCoord/{$dest_lng},{$dest_lat}/date/{$date}/time/{$time}/arr/{$arr}/min/T/walk/0.50/mode/A/appId/{$trimet_api}";


    parseTrimetResults($request_url);
    return $app->redirect('/show_results');
    // return $app->redirect($request_url);
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


            if(!empty($legs[$j]->{"time-distance"}->startTime)){
                $leg_start_time =
                DateTime::createFromFormat('n/j/y g:i A', $time_distance->date . ' ' . $legs[$j]->{"time-distance"}->startTime)->format('Y-m-d H:i:s');

                $leg_end_time =
                DateTime::createFromFormat('n/j/y g:i A', $time_distance->date . ' ' . $legs[$j]->{"time-distance"}->endTime)->format('Y-m-d H:i:s');
                $leg_distance = $legs[$j]->{"time-distance"}->distance;
                $route_number = $legs[$j]->route->number;
                $route_name = $legs[$j]->route->name;
                $leg_stop_sequence = $legs[$j]->to->stopSequence;

            }

            $from = $legs[$j]->from;
            $to = $legs[$j]->to;

            $from_location = new Location($from->pos->lat, $from->pos->lon, $from->description, 100);
            $from_location->save();

            $to_location = new Location($to->pos->lat, $to->pos->lon, $to->description, 100);
            $to_location->save();

            if(!empty($legs[$j]->{"time-distance"}->startTime)){
              $new_leg = new Leg($mode, $leg_distance, $from_location->getId(), $to_location->getId(), $itinerary_id, $j, $leg_start_time, $leg_end_time, $order, $route_number, $route_name, $leg_stop_sequence);
              $new_leg->save();
            }else{
              $new_leg = new Leg($mode, $leg_distance, $from_location->getId(), $to_location->getId(), $itinerary_id, $j, $order);
              $new_leg->save();
          }
        }
    }
}

return $app;
?>

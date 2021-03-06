<?php
/**
 * Created by PhpStorm.
 * User: Marios
 * Date: 5/30/2016
 * Time: 7:42 PM
 */

//Get all active tickets


include('../DB_Functions.php');

$db = new DB_Functions();

$place_id=1;


$tickets = $db->get_tickets($place_id);

$counter = 1;
$previous_serve_time;
$new_average_time=0;

foreach ($tickets as $ticket) {
//	echo $ticket['id'];

	if ($ticket['number'] == 1) {

		$minutes_to_add = mt_rand(2, 8);
		echo "Minutes to Add: " . $minutes_to_add . "</br>";

		echo $ticket['estimated_time'];
		$time = new DateTime($ticket['estimated_time']);
		$time->add(new DateInterval('PT' . intval($minutes_to_add) . 'M'));
		$served_time = $time->format('d-m-Y H:i');
		$resposns = $db->serve_ticket($ticket['id'], $served_time);
		$ticket['time_served'] = $served_time;
		echo empty($resposns) ? "ERROR" : "";
		$previous_serve_time = $served_time;

	} else {

		$minutes_to_add = RandomWeightedNumber();

		echo "Minutes to Add: " . $minutes_to_add . "</br>";

		$time = new DateTime($previous_serve_time);

		$time->add(new DateInterval('PT' . intval($minutes_to_add) . 'M'));

		$served_time = $time->format('d-m-Y H:i');
		//		echo $served_time;

		$resposns = $db->serve_ticket($ticket['id'], $served_time);

		echo empty($resposns) ? "ERROR" : "";

		$previous_serve_time = $served_time;

	}
	$new_average_time=$new_average_time+$minutes_to_add;
	$counter++;
}

$new_average_time= $new_average_time/$counter;
$new_average_time= round($new_average_time,2);
echo " \n NEW AVERAGE TIME : ".$new_average_time." \n";
$ok = $db->setAverageTime($new_average_time,$place_id);
echo $ok;




function RandomWeightedNumber()
{

	$weight = mt_rand(0, 10);
	if ($weight == 1) {
		return mt_rand(11, 15);
	} elseif ($weight == 2 || $weight == 3 || $weight == 4) {
		return mt_rand(8, 10);
	} elseif ( $weight == 5 || $weight == 6) {
		return mt_rand(1, 3);
	} else {
		return mt_rand(4, 7);
	}
}

?>


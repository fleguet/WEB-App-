<?php
session_start();
include("template.php");
$bdd = new PDO('mysql:host=localhost;dbname=airline;charset=utf8', 'root', 'root');

######################## Set template ########################
$template = new Template('./');
$template->set_filenames(array(
  'body' => 'flight.html'
));

######################## Delete flight ########################
if (isset($_GET['action'])){
  if ($_GET['action'] == 'delete'){
    $number = $_GET['number'];
    $result = $bdd->query("DELETE FROM flight
                           WHERE number = '{$number}'");
    if ($result == true){
      $_SESSION['is_deleted'] = 1;
    }
    else {
      $_SESSION['is_deleted'] = 0;
    }
    $template->assign_vars(array('message_status' => $_SESSION['is_deleted'] == 1?
                                 'The flight has been succesfully deleted':
                                 'This flight cannot be deleted'));
    unset($_SESSION['is_deleted']);
  }
}

######################## Add flight ########################
if (isset($_POST['number']) &&
    isset($_POST['start_date']) &&
    isset($_POST['end_date']) &&
    isset($_POST['departure_time']) &&
    isset($_POST['arrival_time']) &&
    isset($_POST['airport_code_departure']) &&
    isset($_POST['airport_code_arrival']) &&
    isset($_POST['aircraft_registration_number'])
    )
{
  $number = $_POST['number'];
  $start_date = $_POST['start_date'];
  $end_date = $_POST['end_date'];
  $departure_time = $_POST['departure_time'];
  $arrival_time = $_POST['arrival_time'];
  $airport_code_departure = $_POST['airport_code_departure'];
  $airport_code_arrival = $_POST['airport_code_arrival'];
  $aircraft_registration_number = $_POST['aircraft_registration_number'];
  $result = $bdd->query("INSERT INTO flight
                         VALUES ('{$number}',
                                 '{$start_date}',
                                 '{$end_date}',
                                 '{$departure_time}',
                                 '{$arrival_time}',
                                 '{$airport_code_departure}',
                                 '{$airport_code_arrival}',
                                 '{$aircraft_registration_number}'
                                )");

  if ($result == true){
    $_SESSION['is_added'] = 1;
  }
  else {
    $_SESSION['is_added'] = 0;
  }
  $template->assign_vars(array('message_status' => $_SESSION['is_added'] == 1?
                               'The flight has been succesfully added':
                               'The flight cannot be added'));
  unset($_SESSION['is_added']);

}

######################## Query for form ########################
$result = $bdd->query("SELECT * FROM airport");
while ($row = $result->fetch())
{
 $template->assign_block_vars('airport_select',array(
   'code' => $row['code'],
   'name' => $row['name']
 ));
}

$result = $bdd->query("SELECT * FROM aircraft");
while ($row = $result->fetch())
{
 $template->assign_block_vars('aircraft_select',array(
   'registration_number' => $row['registration_number'],
   'type' => $row['type']
 ));
}

######################## Read the table flight and show it ########################
$result = $bdd->query('SELECT
                       flight.number,
                       start_date,
                       end_date,
                       departure_time,
                       arrival_time,
                       view_departure.name AS airport_departure_name,
                       view_arrival.name AS airport_arrival_name,
                       aircraft.type AS aircraft_type
                       FROM flight
                       LEFT JOIN (SELECT * FROM airport) AS view_departure ON flight.airport_code_departure = view_departure.code
                       LEFT JOIN (SELECT * FROM airport) AS view_arrival ON flight.airport_code_arrival = view_arrival.code
                       LEFT JOIN aircraft ON flight.aircraft_registration_number = aircraft.registration_number
                       ');

while ($row = $result->fetch())
{
  $template->assign_block_vars('flight',array(
    'number' => $row['number'],
    'start_date' => $row['start_date'],
    'end_date' => $row['end_date'],
    'departure_time' => $row['departure_time'],
    'arrival_time' => $row['arrival_time'],
    'airport_departure_name' => $row['airport_departure_name'],
    'airport_arrival_name' => $row['airport_arrival_name'],
    'aircraft_type' => $row['aircraft_type'],
  ));
}

######################## Parse to html ########################
$template->pparse('body');
?>

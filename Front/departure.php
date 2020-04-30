<?php
session_start();
include("template.php");
$bdd = new PDO('mysql:host=localhost;dbname=airline;charset=utf8', 'root', 'root');

######################## Set template ########################
$template = new Template('./');
$template->set_filenames(array(
  'body' => 'departure.html'
));

######################## Delete departure ########################
if (isset($_GET['action'])){
  if ($_GET['action'] == 'delete'){
    $flight_number = $_GET['flight_number'];
    $result = $bdd->query("DELETE FROM departure
                           WHERE flight_number = '{$flight_number}'");
    if ($result == true){
      $_SESSION['is_deleted'] = 1;
    }
    else {
      $_SESSION['is_deleted'] = 0;
    }
    $template->assign_vars(array('message_status' => $_SESSION['is_deleted'] == 1?
                                 'The departure has been succesfully deleted':
                                 'The departure cannot be deleted'));
    unset($_SESSION['is_deleted']);
  }
}

######################## Add departure ########################
if (isset($_POST['flight_number']) &&
    isset($_POST['date']) &&
    isset($_POST['pilot_ssn_1']) &&
    isset($_POST['pilot_ssn_2']) &&
    isset($_POST['flight_crew_ssn_1']) &&
    isset($_POST['flight_crew_ssn_2']) &&
    isset($_POST['number_seats'])
    )
{
  $flight_number = $_POST['flight_number'];
  $date = $_POST['date'];
  $pilot_ssn_1 = $_POST['pilot_ssn_1'];
  $pilot_ssn_2 = $_POST['pilot_ssn_2'];
  $flight_crew_ssn_1 = $_POST['flight_crew_ssn_1'];
  $flight_crew_ssn_2 = $_POST['flight_crew_ssn_2'];
  $number_of_free_seats = $_POST['numbe_seats']; #free seats = total seat par defaut (sans réservations)
  $number_seats = $_POST['number_seats'];
  $result = $bdd->query("INSERT INTO departure
                         VALUES ('{$flight_number }',
                                 '{$date}',
                                 '{$pilot_ssn_1}',
                                 '{$pilot_ssn_2}',
                                 '{$flight_crew_ssn_1}',
                                 '{$flight_crew_ssn_2}',
                                 '{$number_seats}',
                                 '{$number_seats}'
                                )");

  if ($result == true){
    $_SESSION['is_added'] = 1;
  }
  else {
    $_SESSION['is_added'] = 0;
  }
  $template->assign_vars(array('message_status' => $_SESSION['is_added'] == 1?
                               'The departure has been succesfully added':
                               'The departure cannot be added'));
  unset($_SESSION['is_added']);

}

######################## Query for select ########################
$result = $bdd->query("SELECT employee.ssn, first_name, last_name
                       FROM employee
                       JOIN pilot on employee.ssn = pilot.ssn");

while ($row = $result->fetch())
{
 $template->assign_block_vars('pilot_select',array(
   'ssn' => $row['ssn'],
   'first_name' => $row['first_name'],
   'last_name' => $row['last_name']
 ));
}

$result = $bdd->query("SELECT employee.ssn, first_name, last_name
                       FROM employee
                       JOIN flight_crew on employee.ssn = flight_crew.ssn");

while ($row = $result->fetch())
{
$template->assign_block_vars('flight_crew_select',array(
  'ssn' => $row['ssn'],
  'first_name' => $row['first_name'],
  'last_name' => $row['last_name']
));
}

$result = $bdd->query("SELECT number
                       FROM flight
                       ORDER BY number");
while ($row = $result->fetch())
{
 $template->assign_block_vars('flight_select',array(
   'number' => $row['number']
 ));
}

######################## Read the table departure and show it ########################
$result = $bdd->query(' SELECT
                        flight_number,
                        date,
                        view_pilot_1.first_name AS pilot_1_first_name,
                        view_pilot_1.last_name AS pilot_1_last_name,
                        view_pilot_2.first_name AS pilot_2_first_name,
                        view_pilot_2.last_name AS pilot_2_last_name,
                        view_flight_crew_1.first_name AS flight_crew_1_first_name,
                        view_flight_crew_1.last_name AS flight_crew_1_last_name,
                        view_flight_crew_2.first_name AS flight_crew_2_first_name,
                        view_flight_crew_2.last_name AS flight_crew_2_last_name,
                        number_of_free_seats,
                        number_seats

                        FROM departure

                        LEFT JOIN (
                        SELECT * FROM employee
                        ) AS view_pilot_1 ON departure.pilot_ssn_1 = view_pilot_1.ssn

                        LEFT JOIN (
                        SELECT * FROM employee
                        ) AS view_pilot_2 ON departure.pilot_ssn_2 = view_pilot_2.ssn

                        LEFT JOIN (
                        SELECT * FROM employee
                        ) AS view_flight_crew_1 ON departure.flight_crew_ssn_1 = view_flight_crew_1.ssn

                        LEFT JOIN (
                        SELECT * FROM employee
                        ) AS view_flight_crew_2 ON departure.flight_crew_ssn_2 = view_flight_crew_2.ssn
                        ');

while ($row = $result->fetch())
{
  $template->assign_block_vars('departure',array(
    'flight_number' => $row['flight_number'],
    'date' => $row['date'],
    'pilot_1' => $row['pilot_1_first_name'] . ' ' . $row['pilot_1_last_name'],
    'pilot_2' => $row['pilot_2_first_name'] . ' ' . $row['pilot_2_last_name'],
    'flight_crew_1' => $row['flight_crew_1_first_name'] . ' ' . $row['flight_crew_1_last_name'],
    'flight_crew_2' => $row['flight_crew_2_first_name'] . ' ' .  $row['flight_crew_2_last_name'],
    'number_of_free_seats' => $row['number_of_free_seats'],
    'number_seats' => $row['number_seats']
  ));
}

######################## Parse to html ########################
$template->pparse('body');
?>

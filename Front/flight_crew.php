<?php
session_start();
include("template.php");
$bdd = new PDO('mysql:host=localhost;dbname=airline;charset=utf8', 'root', 'root');

######################## Set template ########################
$template = new Template('./');
$template->set_filenames(array(
  'body' => 'flight_crew.html'
));

######################## Delete Flight Crew Member ########################
if (isset($_GET['action'])){
  if ($_GET['action'] == 'delete'){
    $ssn = $_GET['ssn'];
    $result = $bdd->query("DELETE FROM flight_crew
                           WHERE ssn = {$ssn}");
    if ($result == true){
      $_SESSION['is_deleted'] = 1;
    }
    else {
      $_SESSION['is_deleted'] = 0;
    }
    $template->assign_vars(array('message_status' => $_SESSION['is_deleted'] == 1?
                                 'The flight crew member has been succesfully deleted':
                                 'The flight crew member cannot be deleted'));
    unset($_SESSION['is_deleted']);
  }
}

######################## Add Flight Crew Member ########################
if (isset($_POST['ssn']) &&
    isset($_POST['position']) &&
    isset($_POST['number_of_flight_hours'])
    )
{
  $ssn = $_POST['ssn'];
  $position = $_POST['position'];
  $number_of_flight_hours = $_POST['number_of_flight_hours'];
  $result = $bdd->query("INSERT INTO flight_crew
                         VALUES ('{$ssn}', '{$position}', '{$number_of_flight_hours}')");

  if ($result == true){
    $_SESSION['is_added'] = 1;
  }
  else {
    $_SESSION['is_added'] = 0;
  }
  $template->assign_vars(array('message_status' => $_SESSION['is_added'] == 1?
                               'The flight crew member has been succesfully added':
                               'The flight crew member cannot be added'));
  unset($_SESSION['is_added']);

}

######################## Query to get the list of employees for the select ########################
$result = $bdd->query("SELECT employee.ssn as ssn,
                              employee.first_name as first_name,
                              employee.last_name as last_name
                       FROM employee
                       LEFT JOIN pilot ON employee.ssn = pilot.ssn
                       LEFT JOIN flight_crew ON employee.ssn = flight_crew.ssn
                       WHERE pilot.ssn IS NULL
                       AND flight_crew.ssn IS NULL");

while ($row = $result->fetch())
{
 $template->assign_block_vars('flight_crew_select',array(
   'ssn' => $row['ssn'],
   'first_name' => $row['first_name'],
   'last_name' => $row['last_name']
 ));
}

######################## Read the table flight_crew and show it ########################
$result = $bdd->query('SELECT flight_crew.ssn as ssn,
                              employee.first_name as first_name,
                              employee.last_name as last_name,
                              flight_crew.position as position,
                              flight_crew.number_of_flight_hours as number_of_flight_hours
                         FROM flight_crew
                         JOIN employee ON employee.ssn = flight_crew.ssn
                         ORDER BY flight_crew.number_of_flight_hours DESC');

while ($row = $result->fetch())
{
  $template->assign_block_vars('flight_crew',array(
    'ssn' => $row['ssn'],
    'first_name' => $row['first_name'],
    'last_name' => $row['last_name'],
    'position' => $row['position'],
    'number_of_flight_hours' => $row['number_of_flight_hours']
  ));
}

######################## Parse to html ########################
$template->pparse('body');
?>

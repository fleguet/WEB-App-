<?php
session_start();
include("template.php");
$bdd = new PDO('mysql:host=localhost;dbname=airline;charset=utf8', 'user', 'user');

$template = new Template('./');
$template->set_filenames(array(
  'body' => 'user_booking.html'
));


$result = $bdd->query(' SELECT
                        flight_number,
                        date,
                        
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


######################## Query for select ########################
$result = $bdd->query("SELECT flight_number
                       FROM departure
                       ORDER BY flight_number");

while ($row = $result->fetch())
{
 $template->assign_block_vars('departure_flight_number_select',array(
   'departure_flight_number' => $row['flight_number']
 ));
}

/**
$result = $bdd->query("SELECT date
                       FROM departure
                       ORDER BY date ");
                       #WHERE flight_number=departure_flight_number_select");


while ($row = $result->fetch())
{
 $template->assign_block_vars('departure_date_select',array(
   'departure_date' => $row['departure_date']
 ));
}
**/

$result = $bdd->query("SELECT id, first_name, last_name
                       FROM client
                       ORDER BY id");

while ($row = $result->fetch())
{
 $template->assign_block_vars('client_select',array(
   'client_id' => $row['id'],
   'first_name' => $row['first_name'],
   'last_name' => $row['last_name']
 ));
}

######################## Add Order ########################
if (
    isset($_POST['departure_flight_number']) &&
    isset($_POST['departure_date']) &&
    isset($_POST['client_id'])
    )

{
  $order_date =  date('Y-m-d') ;
  $price = 100; #choisi arbitrairement
  $departure_flight_number = $_POST['departure_flight_number'];
  $departure_date = $_POST['departure_date'];
  $client_id = $_POST['client_id'];
  $result = $bdd->query("INSERT INTO `order` (order_date, price, departure_flight_number, departure_date, client_id)
                         VALUES ('{$order_date}', '{$price}', '{$departure_flight_number}', '{$departure_date}', '{$client_id}')");
  

 
  if ($result == true){
    $_SESSION['is_added'] = 1;
  }
  else {
    $_SESSION['is_added'] = 0;
  }
  $template->assign_vars(array('message_status' => $_SESSION['is_added'] == 1?
                               'The reservation has been succesfully made':
                               'The reservation cannot be made'));
  unset($_SESSION['is_added']);
}

# Refresh the page to actualize the free seats 
  #echo "<meta http-equiv='refresh' content='0';URL=".$_SERVER['user_booking'].".php?refresh=0'>";

$template->pparse('body');
?>
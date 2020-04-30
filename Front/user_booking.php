<?php
session_start();
include("template.php");
$bdd = new PDO('mysql:host=localhost;dbname=airline;charset=utf8', 'user', 'user');

$template = new Template('./');
$template->set_filenames(array(
  'body' => 'user_booking.html'
));

#############################################################

$result = $bdd->query(' SELECT
                        flight_number,
                        date,
                        number_of_free_seats,
                        number_seats
                        FROM departure');
while ($row = $result->fetch())
{
  $template->assign_block_vars('departure',array(
    'flight_number' => $row['flight_number'],
    'date' => $row['date'],
    'number_of_free_seats' => $row['number_of_free_seats']
  ));
}



#############################################################
#Select a flight number
$result = $bdd->query("SELECT flight_number, date
                       FROM departure
                       ");
while ($row = $result->fetch())
{
 $template->assign_block_vars('departure_flight_number_select',array(
   'departure_flight_number' => $row['flight_number'],
   'departure_date' => $row['date']
  ));
}

#Select a departure date 
$result = $bdd->query("SELECT date
                       FROM departure
                       ");
while ($row = $result->fetch())
{
 $template->assign_block_vars('date_select',array(
   'departure_date' => $row['date']
  ));
}


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

$template->pparse('body');
?>
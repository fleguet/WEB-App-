<?php
session_start();
include("template.php");
$bdd = new PDO('mysql:host=localhost;dbname=airline;charset=utf8', 'root', 'root');

######################## Set template ########################
$template = new Template('./');
$template->set_filenames(array(
  'body' => 'order.html'
));

######################## Delete Order ########################
if (isset($_GET['action'])){
  if ($_GET['action'] == 'delete'){
    $id = $_GET['id'];
    $result = $bdd->query("DELETE FROM `order`
                           WHERE id = '{$id}'");
    if ($result == true){
      $_SESSION['is_deleted'] = 1;
    }
    else {
      $_SESSION['is_deleted'] = 0;
    }
    $template->assign_vars(array('message_status' => $_SESSION['is_deleted'] == 1?
                                 'The order has been succesfully deleted':
                                 'The order cannot be deleted'));
    unset($_SESSION['is_deleted']);
  }
}

######################## Add Order ########################
if (isset($_POST['departure_flight_number']) &&
    isset($_POST['departure_date']) &&
    isset($_POST['client_id'])
    )
{
  $order_date = date('Y-m-d') ;
  $price = 100;
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
                               'The order has been succesfully added':
                               'The order cannot be added'));
  unset($_SESSION['is_added']);

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

######################## Read the table order and show it ########################
$result = $bdd->query('SELECT
                       `order`.id,
                       order_date,
                       price,
                       departure_flight_number,
                       departure_date,
                       client.first_name AS client_first_name,
                       client.last_name AS client_last_name
                       FROM `order`
                       LEFT JOIN client on client.id = `order`.client_id');

while ($row = $result->fetch())
{
  $template->assign_block_vars('order',array(
    'id' => $row['id'],
    'order_date' => $row['order_date'],
    'price' => $row['price'],
    'departure_flight_number' => $row['departure_flight_number'],
    'departure_date' => $row['departure_date'],
    'client_first_name' => $row['client_first_name'],
    'client_last_name' => $row['client_last_name']
  ));
}

######################## Parse to html ########################
$template->pparse('body');
?>
